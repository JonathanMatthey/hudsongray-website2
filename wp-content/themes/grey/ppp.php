<?
/*
Template Name: PPP
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
		'title' => 'ustwo - PPP'
	),
	'isPpp' => true,
	'urls' => $page->getUrls(),
);

file_put_contents(DATA_DIR.'/ppp.json', json_encode($data));

echo $page->render('ppp', $data);

?>
