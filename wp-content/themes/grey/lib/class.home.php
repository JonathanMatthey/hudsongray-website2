<?php

use Handlebars\Handlebars;

class Home extends Page {
	public function __construct($options = false) {
		parent::__construct($options);

		$this->postType = 'blog';
	}

	public function getLatestBlogPost() {
		global $wp_query;
		$result;

		$criteria = array(
			'post_type' => $this->postType,
			'posts_per_page' => 1,
		);

		$wp_query = new WP_Query($criteria);	

		while ($wp_query->have_posts()) {
			$result = $this->getPost();
			break;
		}

		return $result;
	}

	public function getTwitterFeed() {
		$encodedFeed = get_option('twitter-feed');
		$result = json_decode($encodedFeed);

		return $result;
	}
}

?>