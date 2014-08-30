<?php

require_once __DIR__.'/_config.php';

$jsonFile =  DATA_DIR.'/home.json';

$page = new Home(array(
	'templateUrl' => 'http://'.$_SERVER['SERVER_NAME']
));

$data = json_decode(file_get_contents($jsonFile), true);

$data['urls'] = $page->getUrls();

//print_r($data); die();

echo $page->render('home', $data);

?>