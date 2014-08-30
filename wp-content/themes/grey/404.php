<?php

require __DIR__.'/_config.php';

ob_start();
wp_head();
$extraHead = ob_get_clean();

$page = new Page();
$data = array(
	'extraHead' => $extraHead,
	'urls' => $page->getUrls(),
	'bodyClass' => 'error404' 
);

echo $page->render('404', $data);

die();

?>