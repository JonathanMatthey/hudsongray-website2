<?php

use Handlebars\Handlebars;

class Blog extends Page {
	public function __construct($options = false) {
		parent::__construct($options);

		$this->postType = 'blog';
	}

	public function getMeta() {
		$meta = parent::getMeta();
		$meta['title'] .= ' - blog';

		return $meta;
	}

	public function getPost() {
		$result = parent::getPost();

		$result['layout'] = array(
			'categoryClass' => strtolower($result['category'])
		);

		return $result;
	}

	public function getCategory() {
		global $wp_query;
		$category = $wp_query->query_vars['blog_category'];

		$result = strlen($category) ? $category : 'all';

		return $result;
	}
}

?>