<?php

use Handlebars\Handlebars;

class Page {
	public function __construct($options = false) {

		$this->engine = new Handlebars(array(
		    'loader' => new \Handlebars\Loader\FilesystemLoader(TEMPLATE_DIR),
		    'partials_loader' => new \Handlebars\Loader\FilesystemLoader(
		        TEMPLATE_DIR,
		        array(
		            'prefix' => '_'
		        )
		    )
		));

		if ($options) {
			$this->options = $options;
		} else {
			$this->options = array(
				'templateUrl' => get_bloginfo('template_directory')
			);
		}

		$this->postType = 'post';
	}

	public function getMeta() {
		return array(
			'title' => 'hudsongray'
		);
	}

	public function getUrls() {
		return array(
			//'site' => get_site_url(),
			'site' => 'http://' . $_SERVER['HTTP_HOST'],
			'cdn'	=> 'http://cdn.hudsongray.com',
			'page'	=> $_SERVER["REDIRECT_URL"],
			'full'	=> 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER["REDIRECT_URL"],
			'theme' => $this->options['templateUrl'],
			'ajax'	=> $this->options['templateUrl'] . '/ajax',
			'css'	=> $this->options['templateUrl'] . '/css',
			'js'	=> $this->options['templateUrl'] . '/js',
			'images'	=> $this->options['templateUrl'] . '/images'
		);
	}

	protected function getPostInfo($post) {
		$id = $post->ID;

		$categories = get_the_term_list($id, 'blog_category', '', '|');
		$category = strip_tags( array_shift( explode( '|', $categories ) ) );

		$thumbnail = wp_get_attachment_url(get_post_thumbnail_id());
		if (!$thumbnail) {
			$thumbnail = $this->options['templateUrl'].'/images/index/landing_hero_01_tablet_portrait.jpg';
		}

		$result = array(
			'id' => $id,
			'title' => $post->post_title,
			'excerpt' => strip_tags($post->post_excerpt),
			'content' => apply_filters('the_content', get_the_content()),
			'date' => get_the_date("j F Y"),
			'link'	=> get_permalink($id),
			'category' => $category,
			'thumbnail'	=> $thumbnail
		);

		return $result;
	}

	public function getPost() {
		global $wp_query;
		global $post;

		$wp_query->the_post();

		$id = get_the_ID();

		$singlePost = $this->getPostInfo($post);

		$singlePost['author'] = array(
			'name' => get_the_author_meta('display_name'),
			'avatar' => get_avatar( get_the_author_meta('ID'), '110' ),
			'description' => get_the_author_meta('user_description')
		);

		$singlePost['comments'] = $this->getPostComments($id);
		$singlePost['social'] = $this->getSocialCounts($id, get_permalink());

		return $singlePost;
	}

	public function getSocialCounts($postId, $url) {

		$socialCounter = new SocialCounter();
		$pageUrl = str_replace($_SERVER['HTTP_HOST'], 'hudsongray.com', $url);

		$tweets = get_post_meta($postId, 'social-twitter-count');
		$facebook = get_post_meta($postId, 'social-facebook-count');
		$plus = get_post_meta($postId, 'social-plus-count');
		$total = $tweets[0] + $facebook[0] + $plus[0];

		$result = array(
			'tweets' => $tweets[0],
			'facebook' => $facebook[0],
			'plus'	=> $plus[0],
			'total'	=> $total
		);

		return $result;
	}

	public function getAllPosts() {
		global $wp_query;

		$posts = array();

		$criteria = array(
			'post_type' => $this->postType,
			'posts_per_page' => -1
		);

		$wp_query = new WP_Query($criteria);

		while ($wp_query->have_posts()) {
			$singlePost = $this->getPost();
			array_push($posts, $singlePost);
		}

		return $posts;
	}

	public function getPosts($page = 1, $searchString = false) {
		global $wp_query;

		$queryVars = $wp_query->query_vars;

		$postPerPages = $page === 1? 11 : 12;
		$offset = $page === 1? 0 : ($page - 2) * 12 + 11;

		$criteria = array(
			'post_type' => $this->postType,
			'posts_per_page' => $postPerPages,
			'paged' => $page,
			'offset'	=> $offset,
			'blog_category' => $queryVars['blog_category']
		);

		if ($searchString) {
			$criteria['s'] = $searchString;
		}

		$wp_query = new WP_Query($criteria);

		$this->postNumber = $wp_query->found_posts;

		$posts = array();

		$isFirst = ($page < 2);
		while ($wp_query->have_posts()) {
			$singlePost = $this->getPost();
			$singlePost['extraClasses'] = $isFirst? 'blog__articles__item--hero' : '';
			array_push($posts, $singlePost);

			$isFirst = false;
		}

		return $posts;
	}

	public function getPostComments($id) {
		$comments = get_comments(array(
			'post_id' => $id,
			'status' => 'approve'
		));

		$result = array(
			'items' => array(),
			'count' => count($comments)
		);

		$singleComment = array();

		foreach ($comments as $comment) {
			$singleComment['author'] = array();
			$singleComment['author']['name'] = $comment->comment_author;
			$singleComment['author']['email'] = $comment->comment_author_email;
			$singleComment['author']['avatar'] = get_avatar($comment_author_email, 110);
			$singleComment['content'] = $comment->comment_content;
			$singleComment['date'] = $comment->comment_date;

			array_push($result['items'], $singleComment);
		}

		return $result;
	}

	public function getNumberOfPosts() {
		return $this->postNumber;
	}

	public function render($template, $data) {
		return $this->engine->render( $template, $data );
	}
}

?>
