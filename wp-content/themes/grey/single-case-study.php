<?php

require __DIR__.'/_config.php';

$ustwo = new Ustwo();

ob_start();
wp_head();
$extraHead = ob_get_clean();

$blog = new SingleBlog();

$data = array(
);

file_put_contents(DATA_DIR.'/single-case-study.json', json_encode($data));

echo $blog->render('single-case-study', $data);
?>