<?php

require_once __DIR__.'/_config.php';

$jsonFile =  DATA_DIR.'/single-blog.json';

$blog = new SingleBlog(array(
	'templateUrl' => 'http://'.$_SERVER['SERVER_NAME']
));

$data = json_decode(file_get_contents($jsonFile), true);

$data['urls'] = $blog->getUrls();

echo $blog->render('single-blog', $data);

?>