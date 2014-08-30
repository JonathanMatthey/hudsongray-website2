 <?php  
// Our include  
define('WP_USE_THEMES', false);  

require_once __DIR__.'/../_config.php';

require_once(THEME_DIR.'/../../../wp-load.php');

//$ustwo = new Ustwo();

ob_start();
wp_head();
$extraHead = ob_get_clean();

$page = $_GET['page'];

$blog = new Blog();
$data = array(
	'extraHead' => $extraHead,
	'urls' => $blog->getUrls(),
    'posts' => $blog->getPosts($page)
);

echo $blog->render('ajax-blog', $data);
?> 