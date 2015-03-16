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
  'isCS1' => get_the_ID() == 8,
  'isCS2' => get_the_ID() == 11,
  'isCS3' => get_the_ID() == 10,
  'page_strategy_text' => get_post_custom_values("page_strategy_text")[0],
  'page_objective_text' => get_post_custom_values("page_objective_text")[0],
  'page_results_image_url' => get_post_custom_values("page_results_image_url")[0],
  
  'page_slide1_image_url' => get_post_custom_values("page_slide1_image_url")[0],
  'page_slide1_title' => get_post_custom_values("page_slide1_title")[0],
  'page_slide1_subtitle' => get_post_custom_values("page_slide1_subtitle")[0],


  'page_slide2_image_url' => get_post_custom_values("page_slide2_image_url")[0],
  'page_slide2_title' => get_post_custom_values("page_slide2_title")[0],
  'page_slide2_subtitle' => get_post_custom_values("page_slide2_subtitle")[0],

  
  'page_slide3_image_url' => get_post_custom_values("page_slide3_image_url")[0],
  'page_slide3_title' => get_post_custom_values("page_slide3_title")[0],
  'page_slide3_subtitle' => get_post_custom_values("page_slide3_subtitle")[0],

  
  'page_slide4_image_url' => get_post_custom_values("page_slide4_image_url")[0],
  'page_slide4_title' => get_post_custom_values("page_slide4_title")[0],
  'page_slide4_subtitle' => get_post_custom_values("page_slide4_subtitle")[0],

  
	'urls' => $page->getUrls(),
);


file_put_contents(DATA_DIR.'/case-study.json', json_encode($data));

echo $page->render('case-study', $data);

?>
