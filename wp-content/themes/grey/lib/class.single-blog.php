<?php

use Handlebars\Handlebars;

class SingleBlog extends Page {
	public function __construct($options = false) {
		parent::__construct($options);

		$this->postType = 'blog';
	}

	public function getPost() {
		$result = parent::getPost();

		$result['layout'] = array(
			'categoryClass' => strtolower($result['category'])
		);

		return $result;
	}

	public function getAdjacentPost($isPrev = true) {
		$result = array();

		$post = get_adjacent_post(false, '', $isPrev);

		if (!empty($post)) {
			$result = $this->getPostInfo($post);
			$result['layout'] = array(
				'categoryClass' => strtolower($result['category'])
			);

			$result['exists'] = true;
		} else {
			$result['exists'] = false;
		}

		return $result;
	} 

	public function getNextPost() {
		return $this->getAdjacentPost(false);

	}

	public function getPreviousPost() {
		return $this->getAdjacentPost(true);

	}
}

?>