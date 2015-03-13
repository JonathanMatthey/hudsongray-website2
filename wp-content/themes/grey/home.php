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
    'bodyClass' => 'homepage',
    // EDITABLE HOME CONTENT:
  'companyDescription' => "is a full service creative marketing and branding agency. We create seamless strategic marketing campaigns that leverage the power of your brand narrative and encourage audience growth in the new brand era.",
  'whatWeDo1-tagline' => "Learn about you, to develop a map for us.",
  'whatWeDo1-keywords' => "brand briefing, research, pilot viewing, script reading, product sampling, competitive analysis, trend assessment, feasibility",
  'whatWeDo2-tagline' => "Craft original concepts that capture the essence of your brand.",
  'whatWeDo2-keywords' => "corporate & visual identity, conceptual creative, graphic design and branding, 3D visualization, event design, branded content, video design and production",
  'whatWeDo3-tagline' => "Nail the details to bring your vision to life.",
  'whatWeDo3-keywords' => "budget management, venue research and site inspections, procurement and contract management, talent buying and management, geographic production planning, operations and logistics management, onsite coordination and management, post program assessment and services",
  'whatWeDo4-tagline' => "Keep your campaign streamlined and seamless with our multi-platform experience.",
  'whatWeDo4-keywords' => "events, branded environments, pop ups, mobile tours, road shows, guerrilla stunts, mobile engagement tools, websites, microsites, brand and sponsorship activations, conferences and branded collateral",
  'whatWeDo5-tagline' => "Utilize the latest in experiential and digital platforms for an integrated approach.",
  'whatWeDo5-keywords' => "interactive walls, robotics, gestural media, mobile app development, iBeacons, NFC, photo/video engagements, aurasma, augmented reality, interactive registration, live streams and web series",
  'whatWeDo6-tagline' => "Deliver a truly measurable product to ensure you hit your target.",
  'whatWeDo6-keywords' => ""
);

file_put_contents(DATA_DIR.'/home.json', json_encode($data));

echo $home->render('home', $data);

?>
