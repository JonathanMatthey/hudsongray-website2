<?
/*
Template Name: Home
*/
?>

<?php

require __DIR__.'/_config.php';

$ustwo = new Ustwo();

ob_start();
wp_head();
$extraHead = ob_get_clean();

$home = new Home();
$data = array(
	'meta' => $home->getMeta(),
	'page' => 'home',
	'extraHead' => $extraHead,
	'dependencies' => array(
		'js' => array(
			),
		'css' => array()),
	'urls' => $home->getUrls(),
    'blogPost' => $home->getLatestBlogPost(),
    'twitterFeed' => $home->getTwitterFeed(),
    'bodyClass' => 'homepage'
);

file_put_contents(DATA_DIR.'/home.json', json_encode($data));

echo $home->render('home', $data);

?>
