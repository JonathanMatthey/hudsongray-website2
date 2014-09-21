<?
/*
Template Name: Case Study
*/
?>

<?php

require __DIR__.'/_config.php';

$ustwo = new Ustwo();

ob_start();
wp_head();
$extraHead = ob_get_clean();

$page = new Page();
$data = array(
	'meta' => array(
		'title' => 'HudsonGray - Case Study: '
	),
  'pageId' => get_the_ID(),
  'isCS1' => get_the_ID() == 19,
  'isCS2' => get_the_ID() == 13,
  'isCS3' => get_the_ID() == 20,
	'urls' => $page->getUrls(),
);

file_put_contents(DATA_DIR.'/case-study.json', json_encode($data));

echo $page->render('case-study', $data);

?>
