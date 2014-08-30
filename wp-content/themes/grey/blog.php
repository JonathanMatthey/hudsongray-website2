<?
/*
Template Name: Blog
*/
?>

<?php

require __DIR__.'/_config.php';

$ustwo = new Ustwo();

ob_start();
wp_head();
$extraHead = ob_get_clean();


$searchString = false;
if (strlen($_POST['search']) > 0) {
	$searchString = $_POST['search'];
}

$blog = new Blog();
$data = array(
	'isBlog' => true,
	'meta' => $blog->getMeta(),
	'extraHead' => $extraHead,
	'urls' => $blog->getUrls(),
	'category' => $blog->getCategory(),
    'posts' => array(
    	'items' =>$blog->getPosts(1, $searchString),
    	'number' => $blog->getNumberOfPosts()
    ),
    'searchString' => $searchString
);

file_put_contents(DATA_DIR.'/blog.json', json_encode($data));

echo $blog->render('blog', $data);

?>
