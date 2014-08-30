<?php

require __DIR__.'/_config.php';

$ustwo = new Ustwo();

ob_start();
wp_head();
$extraHead = ob_get_clean();

$blog = new SingleBlog();

$data = array(
	'extraHead' => $extraHead,
	'urls' => $blog->getUrls(),
	'post' => $blog->getPost(),
	'previous' => $blog->getPreviousPost(),
	'next' => $blog->getNextPost()
);

file_put_contents(DATA_DIR.'/single-blog.json', json_encode($data));

echo $blog->render('single-blog', $data);
?>