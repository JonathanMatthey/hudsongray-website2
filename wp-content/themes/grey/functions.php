<?

//define how many days vacancy is new;
define("NEWDATE", "5");

//cache directory
define("CACHEDIR", "cache/");

//how many posts (projekt) in portfolio
define("POSTS_PER_PAGE", "9");

/*


// VACANCIES
add_action( 'init', 'create_vacancies' );
function create_vacancies() {
  register_post_type( 'vacancy',
    array(
      'labels' => array(
        'name' => __( 'Vacancies' ),
        'singular_name' => __( 'vacancy' )
      ),
      'public' => true,
      "supports" => array("title", "revisions")
    )
  );
}
*/

global $vacanciesMeta;
$vacanciesMeta = array();
$vacanciesMeta['job_title']   = array( 'Job Title', 'input' );
$vacanciesMeta['description'] = array( 'Description', 'tinymce' );
$vacanciesMeta['excerpt']   = array( 'Excerpt', 'textarea' );
$vacanciesMeta['job_level']   = array( 'Level', 'input' );
$vacanciesMeta['job_salary']  = array( 'Salary', 'input' );
$vacanciesMeta['job_benefits']  = array( 'Benefits', 'input' );
$vacanciesMeta['job_holidays']  = array( 'Holidays', 'input' );
$vacanciesMeta['job_closing_date'] = array( 'Closing Date For Applications', 'input' );
$vacanciesMeta['experience']  = array( 'Experience &amp; Skills', 'tinymce' );
$vacanciesMeta['project'] = array( 'Project &amp; Team Management', 'tinymce' );
$vacanciesMeta['tools'] = array( 'The tools we use', 'tinymce' );
$vacanciesMeta['character'] = array( 'Character requirements', 'tinymce' );
$vacanciesMeta['qualifications']  = array( 'Qualifications', 'tinymce' );


global $pressMeta;
$pressMeta = array();
$pressMeta['pdf'] = array( 'External URL', 'input' );
$pressMeta['image'] = array( 'Image', 'input' );

global $postMeta;
$postMeta = array();
$postMeta['excerpt_title']  = array( 'Title Excerpt', 'input', 'high' );
$postMeta['thumbnail']  = array( 'Thumbnail   ( 100 x 100 )', 'input', 'high' );
$postMeta['homepage_thumbnail'] = array( 'Homepage Thumbnail   ( 285 x 180 )', 'input', 'high' );

global $blogMeta;
$blogMeta = array();


/*

// FEATURES
add_action( 'init', 'create_features' );
function create_features() {
  register_post_type( 'feature',
    array(
      'labels' => array(
        'name' => __( 'Home Slider' ),
        'singular_name' => __( 'feature' )
      ),
      'public' => true,
      "supports" => array("title", "excerpt")
    )
  );
}

// CASE STUDIES
add_action( 'init', 'create_casestudies' );
function create_casestudies() {
     register_taxonomy( 'case_category', 'case_study', array( 'show_in_nav_menus'=>true, 'show_ui'=>true, 'public'=>true, 'show_tagcloud' => true, 'hierarchical' => true, 'label' => 'Platforms', 'query_var' => true, 'rewrite' => true ) );
     register_taxonomy( 'case_role', 'case_study', array( 'show_in_nav_menus'=>true, 'show_ui'=>true, 'public'=>true, 'show_tagcloud' => true, 'hierarchical' => true, 'label' => 'Roles', 'query_var' => true, 'rewrite' => true ) );
  register_post_type( 'case_study',
    array(
      'labels' => array(
        'name' => __( 'Case Studies' ),
        'singular_name' => __( 'case study' )
      ),
      'public' => true,
      'taxonomies' => array('cat', 'case_category'),
      "supports" => array("title", "excerpt", "editor")
    )
  );
}


//PRESS
add_action( 'init', 'create_press' );
function create_press() {
  register_post_type( 'press',
    array(
      'labels' => array(
        'name' => __( 'Press & Events' ),
        'singular_name' => __( 'press' )
      ),
      'public' => true,
      "supports" => array("title", "excerpt")
    )
  );
}

//BLOG
add_action( 'init', 'create_blog' );
function create_blog() {
  register_post_type( 'blog',
    array(
      'labels' => array(
        'name' => __( 'Blog' ),
        'singular_name' => __( 'blog' )
      ),
      'public' => true,
      "supports" => array("title", "editor", "author", "excerpt", "trackbacks", "comments", "revisions", "thumbnail"),
    'taxonomies' => array('blog_taxonomy', 'blog_category')
    )
  );
}
*/

add_action( 'init', 'create_blog_taxonomies' );
function create_blog_taxonomies() {
  register_taxonomy( 'blog_category', 'blog', array( 'hierarchical' => true, 'label' => 'Categories' ) );
  register_taxonomy( 'blog_taxonomy', 'blog', array( 'hierarchical' => false, 'label' => 'Blog tags' ) );
}

add_rewrite_rule( '^davide', 'index.php?pagename=blog&paged=2', 'top');

add_filter( 'rewrite_rules_array','blog_rewrite_rules' );
add_filter( 'query_vars','blog_query_vars' );
add_action( 'wp_loaded','blog_flush_rules' );



// flush_rules() if our rules are not yet included
function blog_flush_rules(){
  $rules = get_option( 'rewrite_rules' );

  if ( ! isset( $rules['blog/page/([0-9]+)'] ) ) {
    global $wp_rewrite;
      $wp_rewrite->flush_rules();
  }
}

// Adding a new rule
function blog_rewrite_rules( $rules )
{
  $newrules = array();
  $newrules['blog/page/([0-9]+)'] = 'index.php?pagename=blog&paged=$matches[1]';
  return $newrules + $rules;
}

// Adding the id var so that WP recognizes it
function blog_query_vars( $vars )
{
    array_push($vars, 'pagename');
    return $vars;
}

// EVENTS
/*
add_action( 'init', 'create_events' );
function create_events() {
  register_post_type( 'event',
    array(
      'labels' => array(
        'name' => __( 'Events' ),
        'singular_name' => __( 'event' )
      ),
      'public' => true,
      "supports" => array("title")
    )
  );
}*/

/* Custom meta boxes */
add_action("admin_init", "admin_init");
add_action("load-post.php", "edit_post_custompost");
add_action('save_post', 'save_vacancy');
add_action("save_post", "save_feature");
add_action("save_post", "save_casestudies");
add_action("save_post", "save_press");
add_action("save_post", "save_event");
add_action("save_post", "save_post");


function edit_post_custompost( $postID ) {

  // Avoid adding post types if not in edit post page
  if ( $_GET['action'] != 'edit' ) return;

  wp_enqueue_script( 'tiny_mce' );

  $postTypes = array(
    'vacancy' => 'vacanciesMeta',
    'press' => 'pressMeta',
    'post' => 'postMeta',
    'blog' => 'blogMeta'
  );

  init_tinymce_metaboxes( $postTypes );
}

function admin_init(){
  $postTypes = array(
    'vacancy' => 'vacanciesMeta',
    'press' => 'pressMeta',
    'post' => 'postMeta',
    'blog' => 'blogMeta'
  );

  foreach ( $postTypes as $postType => $meta )
  {
    global $$meta;

    foreach  ( $$meta as $id => $properties )
    {
      $label = $properties[0];
      $type = $properties[1];
      $priority = isset( $properties[2] )? $properties[2]: 'low';

      add_meta_box("{$postType}_{$id}", $label, "render_metabox_options", $postType, "normal", $priority, compact( 'id', 'label', 'type' ) );
    }
  }
  add_meta_box("page_slide1_image_url", "Slide 1 Image URL", "page_slide1_image_url_options", "page", "normal", "low");
  add_meta_box("page_slide1_title", "Slide 1 Title", "page_slide1_title_options", "page", "normal", "low");
  add_meta_box("page_slide1_subtitle", "Slide 1 Subtitle", "page_slide1_subtitle_options", "page", "normal", "low");

  add_meta_box("page_slide2_image_url", "Slide 2 Image URL", "page_slide2_image_url_options", "page", "normal", "low");
  add_meta_box("page_slide2_title", "Slide 2 Title", "page_slide2_title_options", "page", "normal", "low");
  add_meta_box("page_slide2_subtitle", "Slide 2 Subtitle", "page_slide2_subtitle_options", "page", "normal", "low");

  add_meta_box("page_slide3_image_url", "Slide 3 Image URL", "page_slide3_image_url_options", "page", "normal", "low");
  add_meta_box("page_slide3_title", "Slide 3 Title", "page_slide3_title_options", "page", "normal", "low");
  add_meta_box("page_slide3_subtitle", "Slide 3 Subtitle", "page_slide3_subtitle_options", "page", "normal", "low");

  add_meta_box("page_slide4_image_url", "Slide 4 Image URL", "page_slide4_image_url_options", "page", "normal", "low");
  add_meta_box("page_slide4_title", "Slide 4 Title", "page_slide4_title_options", "page", "normal", "low");
  add_meta_box("page_slide4_subtitle", "Slide 4 Subtitle", "page_slide4_subtitle_options", "page", "normal", "low");

  add_meta_box("page_objective_text", "Objective Text", "page_objective_text_options", "page", "normal", "low");
  add_meta_box("page_strategy_text", "Strategy Text", "page_strategy_text_options", "page", "normal", "low");
  add_meta_box("page_results_image_url", "Results Image URL", "page_results_image_url_options", "page", "normal", "low");

  add_meta_box("casestudies_url", "Image URL", "casestudies_url_options", "case_study", "normal", "low");
  add_meta_box("casestudies_client", "Client", "casestudies_client_options", "case_study", "normal", "low");
  add_meta_box("casestudies_features", "Key Features", "casestudies_features_options", "case_study", "normal", "low");
  add_meta_box("casestudies_download", "Download link", "casestudies_download_options", "case_study", "normal", "low");
  add_meta_box("casestudies_website", "Website", "casestudies_website_options", "case_study", "normal", "low");

  add_meta_box("casestudies_obj1", "Images", "casestudies_obj1_options", "case_study", "normal", "low");

  add_meta_box("feature_url", "Link URL", "feature_url_options", "feature", "normal", "low");
  add_meta_box("feature_banner_image", "Banner image", "feature_banner_image_options", "feature", "normal", "low");

  add_meta_box("event_date", "Date", "event_date_options", "event", "normal", "low");
  add_meta_box("event_time", "Time", "event_time_options", "event", "normal", "low");
  add_meta_box("event_location", "Location", "event_location_options", "event", "normal", "low");
  add_meta_box("event_url", "URL", "event_url_options", "event", "normal", "low");
  
  
}

function casestudies_url_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $url = $custom["casestudies_url"][0];
?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/mootools/1.2.4/mootools-yui-compressed.js"></script>
<script type="text/javascript" src="<?= bloginfo('template_directory'); ?>/js/ckeditor/ckeditor.js"></script>
<script>
window.addEvent('domready', function(){
  var editor = CKEDITOR.replace('ckeditor');
  $$('.mini_img').addEvent('click', function(e){
    e.stop();
    editor.openDialog('image');
    id = e.target.id.split('_');
    objectId = id[2];
  });
});
</script>
<div style="display:none;"><textarea id="ckeditor"></textarea></div>

<label>Image url:</label><br /><a id="object_link_url" class="mini_img" href="#" style="background:url(<?= bloginfo('template_directory'); ?>/js/ckeditor/skins/kama/icons.png);width:17px; height:17px;float:right;text-indent:-15000px;background-position: 0 -575px;">add</a><input id="casestudies_obj_url" name="casestudies_url" value="<?php echo $url; ?>" style="width:88%" />
<?
}

function page_slide1_image_url_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $val = $custom["page_slide1_image_url"][0];
?>
<input name="page_slide1_image_url" value="<?php echo $val; ?>" style="width:95%" />
<?
}
function page_slide1_title_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $val = $custom["page_slide1_title"][0];
?>
<input name="page_slide1_title" value="<?php echo $val; ?>" style="width:95%" />
<?
}
function page_slide1_subtitle_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $val = $custom["page_slide1_subtitle"][0];
?>
<input name="page_slide1_subtitle" value="<?php echo $val; ?>" style="width:95%" />
<?
}

function page_slide2_image_url_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $val = $custom["page_slide2_image_url"][0];
?>
<input name="page_slide2_image_url" value="<?php echo $val; ?>" style="width:95%" />
<?
}
function page_slide2_title_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $val = $custom["page_slide2_title"][0];
?>
<input name="page_slide2_title" value="<?php echo $val; ?>" style="width:95%" />
<?
}
function page_slide2_subtitle_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $val = $custom["page_slide2_subtitle"][0];
?>
<input name="page_slide2_subtitle" value="<?php echo $val; ?>" style="width:95%" />
<?
}


function page_slide3_image_url_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $val = $custom["page_slide3_image_url"][0];
?>
<input name="page_slide3_image_url" value="<?php echo $val; ?>" style="width:95%" />
<?
}
function page_slide3_title_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $val = $custom["page_slide3_title"][0];
?>
<input name="page_slide3_title" value="<?php echo $val; ?>" style="width:95%" />
<?
}
function page_slide3_subtitle_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $val = $custom["page_slide3_subtitle"][0];
?>
<input name="page_slide3_subtitle" value="<?php echo $val; ?>" style="width:95%" />
<?
}


function page_slide4_image_url_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $val = $custom["page_slide4_image_url"][0];
?>
<input name="page_slide4_image_url" value="<?php echo $val; ?>" style="width:95%" />
<?
}
function page_slide4_title_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $val = $custom["page_slide4_title"][0];
?>
<input name="page_slide4_title" value="<?php echo $val; ?>" style="width:95%" />
<?
}
function page_slide4_subtitle_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $val = $custom["page_slide4_subtitle"][0];
?>
<input name="page_slide4_subtitle" value="<?php echo $val; ?>" style="width:95%" />
<?
}



function page_strategy_text_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $val = $custom["page_strategy_text"][0];
?>
<textarea name="page_strategy_text" style="width:100%;min-height:150px;"><?php echo $val; ?></textarea> 
<?
}

function page_objective_text_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $val = $custom["page_objective_text"][0];
?>
<textarea name="page_objective_text" style="width:100%;min-height:150px;"><?php echo $val; ?></textarea> 
<?
}

function page_results_image_url_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $val = $custom["page_results_image_url"][0];
?>
<input name="page_results_image_url" value="<?php echo $val; ?>" style="width:95%" />

<?
}

function casestudies_client_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $client = $custom["casestudies_client"][0];
?>
<label>Client:</label><input name="casestudies_client" value="<?php echo $client; ?>" style="width:95%" />
<?
}

function casestudies_website_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $website = $custom["casestudies_website"][0];
?>
<label>Website:</label><input name="casestudies_website" value="<?php echo $website; ?>" style="width:95%" />
<?
}

function casestudies_download_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $download = $custom["casestudies_download"][0];
?>
<label>Download:</label><input name="casestudies_download" value="<?php echo $download; ?>" style="width:95%" />
<?
}

function casestudies_features_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $features = $custom["casestudies_features"][0];
?>
<label>Key features:<br /></label><textarea name="casestudies_features" style="width:100%;min-height:150px;"><?php echo $features; ?></textarea> <!--  <input name="casestudies_download" value="" size="80" /> -->
<?
}

function casestudies_obj1_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $obj[1] = $custom["casestudies_obj1"][0];
  $obj[2] = $custom["casestudies_obj2"][0];
  $obj[3] = $custom["casestudies_obj3"][0];
  $obj[4] = $custom["casestudies_obj4"][0];
  $obj[5] = $custom["casestudies_obj5"][0];
  $obj[6] = $custom["casestudies_obj6"][0];
?>
<label>Image 1:</label><br /><a id="object_link_1" class="mini_img" href="#" style="background:url(<?= bloginfo('template_directory'); ?>/js/ckeditor/skins/kama/icons.png);width:17px; height:17px;float:right;text-indent:-15000px;background-position: 0 -575px;">add</a><input id="casestudies_obj_1" name="casestudies_obj1" value="<?php echo $obj[1]; ?>" style="width:88%" /><br />
<label>Image 2:</label><br /><a id="object_link_2" class="mini_img" href="#" style="background:url(<?= bloginfo('template_directory'); ?>/js/ckeditor/skins/kama/icons.png);width:17px; height:17px;float:right;text-indent:-15000px;background-position: 0 -575px;">add</a><input id="casestudies_obj_2" name="casestudies_obj2" value="<?php echo $obj[2]; ?>" style="width:88%" /><br />
<label>Image 3:</label><br /><a id="object_link_3" class="mini_img" href="#" style="background:url(<?= bloginfo('template_directory'); ?>/js/ckeditor/skins/kama/icons.png);width:17px; height:17px;float:right;text-indent:-15000px;background-position: 0 -575px;">add</a><input id="casestudies_obj_3" name="casestudies_obj3" value="<?php echo $obj[3]; ?>" style="width:88%" /><br />
<label>Image 4:</label><br /><a id="object_link_4" class="mini_img" href="#" style="background:url(<?= bloginfo('template_directory'); ?>/js/ckeditor/skins/kama/icons.png);width:17px; height:17px;float:right;text-indent:-15000px;background-position: 0 -575px;">add</a><input id="casestudies_obj_4" name="casestudies_obj4" value="<?php echo $obj[4]; ?>" style="width:88%" /><br />
<label>Image 5:</label><br /><a id="object_link_5" class="mini_img" href="#" style="background:url(<?= bloginfo('template_directory'); ?>/js/ckeditor/skins/kama/icons.png);width:17px; height:17px;float:right;text-indent:-15000px;background-position: 0 -575px;">add</a><input id="casestudies_obj_5" name="casestudies_obj5" value="<?php echo $obj[5]; ?>" style="width:88%" /><br />
<label>Image 6:</label><br /><a id="object_link_6" class="mini_img" href="#" style="background:url(<?= bloginfo('template_directory'); ?>/js/ckeditor/skins/kama/icons.png);width:17px; height:17px;float:right;text-indent:-15000px;background-position: 0 -575px;">add</a><input id="casestudies_obj_6" name="casestudies_obj6" value="<?php echo $obj[6]; ?>" style="width:88%" /><br />
<?
}

function press_pdf_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $pdf = $custom["press_pdf"][0];
?>
<label>PDF URL:</label><input name="press_pdf" value="<?php echo $pdf; ?>" style="width:95%" />
<?
}

function init_tinymce_metaboxes( $postTypes )
{
  $ids = array();

  foreach ( $postTypes as $type => $meta )
  {
    global $$meta;

    foreach ( $$meta as $id => $properties )
    {
      if ( $properties[1] == 'tinymce' )
      {
        $ids[] = 'customEditor-'.$type.'_'.$id;
      }
    }
  }

  if (function_exists('wp_tiny_mce'))
    wp_tiny_mce(false, array(
      'mode' => 'exact',
      'elements' => implode( ', ', $ids ),
      'height' => 200,
      'plugins' => 'inlinepopups,wpdialogs,wplink,media,wpeditimage,wpgallery,paste,tabfocus',
      'forced_root_block' => false,
      'force_br_newlines' => true,
      'force_p_newlines' => false,
      'convert_newlines_to_brs' => true
  ));
}

function render_metabox_options( $post, $properties )
{
  static $multiMceLoaded = false;

  extract( $properties['args'] );

  $name = $properties['id'];

  $custom = get_post_custom($post->ID);
  $value = $custom[$name][0];

  switch ( $type )
  {
    case 'input':
    default:
?>
<input name="<? echo $name; ?>" value="<?php echo $value; ?>" style="width:95%" />
<?
    break;

    case 'tinymce':
      $id = "customEditor-$name";
?>

<div class="customEditor"><textarea id="<? echo $id; ?>" name="<? echo $name; ?>" style="width:95%"><?php echo $value; ?></textarea></div>
<?
    break;

    case 'textarea':
?>
<textarea name="<? echo $name; ?>" style="width:95%"><?php echo $value; ?></textarea>
<?
    break;
  }
}

function event_location_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $val = $custom["event_location"][0];
?>
<input name="event_location" value="<?php echo $val; ?>" style="width:95%" />
<?
}

function event_date_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $val = $custom["event_date"][0];
?>
<input name="event_date" value="<?php echo $val; ?>" style="width:95%" />
<?
}

function event_time_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $val = $custom["event_time"][0];
?>
<input name="event_time" value="<?php echo $val; ?>" style="width:95%" />
<?
}

function event_url_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $val = $custom["event_url"][0];
?>
<input name="event_url" value="<?php echo $val; ?>" style="width:95%" />
<?
}

function savePostType( $postType, $meta )
        {
  global $post;

  foreach ( $meta as $id => $properties )
  {
    $fieldName = "{$postType}_{$id}";
    update_post_meta($post->ID, $fieldName, $_POST[$fieldName]);
  }
}

function save_vacancy()
{
  global $vacanciesMeta;

  // http://wordpress.stackexchange.com/questions/37967/custom-field-being-erased-after-autosave
    // Stop WP from clearing custom fields on autosave,
    // and also during ajax requests (e.g. quick edit) and bulk edits.
    if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || (defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']))
        return;


  savePostType( 'vacancy', $vacanciesMeta );
}

// Custom feature stuff
function feature_url_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $val = $custom["feature_url"][0];
?>
<label>Link URL:</label><input name="feature_url" value="<?php echo $val; ?>" style="width:95%" />
<?
}
function feature_banner_image_options(){
  global $post;
  $custom = get_post_custom($post->ID);
  $val = $custom["feature_banner_image"][0];
?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/mootools/1.2.4/mootools-yui-compressed.js"></script>
<script type="text/javascript" src="<?= bloginfo('template_directory'); ?>/js/ckeditor/ckeditor.js"></script>
<script>
window.addEvent('domready', function(){
  var editor = CKEDITOR.replace('ckeditor');
  $$('.mini_img').addEvent('click', function(e){
    e.stop();
    editor.openDialog('image');
    id = e.target.id.split('_');
    objectId = id[2];
  });
});
</script>
<div style="display:none;"><textarea id="ckeditor"></textarea></div>
<label>Banner image:</label><br /><a id="object_link_banner" class="mini_img" href="#" style="background:url(<?= bloginfo('template_directory'); ?>/js/ckeditor/skins/kama/icons.png);width:17px; height:17px;float:right;text-indent:-15000px;background-position: 0 -575px;">add</a><input id="casestudies_obj_banner" name="feature_banner_image" value="<?php echo $val; ?>" style="width:88%" />
<?
}

function save_feature(){
  global $post;
  update_post_meta($post->ID, "feature_url", $_POST["feature_url"]);
  update_post_meta($post->ID, "feature_banner_image", $_POST["feature_banner_image"]);
}


function save_casestudies(){
  global $post;
  update_post_meta($post->ID, "page_slide1_image_url", $_POST["page_slide1_image_url"]);
  update_post_meta($post->ID, "page_slide1_title", $_POST["page_slide1_title"]);
  update_post_meta($post->ID, "page_slide1_subtitle", $_POST["page_slide1_subtitle"]);
  
  update_post_meta($post->ID, "page_slide2_image_url", $_POST["page_slide2_image_url"]);
  update_post_meta($post->ID, "page_slide2_title", $_POST["page_slide2_title"]);
  update_post_meta($post->ID, "page_slide2_subtitle", $_POST["page_slide2_subtitle"]);
  
  update_post_meta($post->ID, "page_slide3_image_url", $_POST["page_slide3_image_url"]);
  update_post_meta($post->ID, "page_slide3_title", $_POST["page_slide3_title"]);
  update_post_meta($post->ID, "page_slide3_subtitle", $_POST["page_slide3_subtitle"]);
  
  update_post_meta($post->ID, "page_slide4_image_url", $_POST["page_slide4_image_url"]);
  update_post_meta($post->ID, "page_slide4_title", $_POST["page_slide4_title"]);
  update_post_meta($post->ID, "page_slide4_subtitle", $_POST["page_slide4_subtitle"]);
  
  
  update_post_meta($post->ID, "page_objective_text", $_POST["page_objective_text"]);
  update_post_meta($post->ID, "page_strategy_text", $_POST["page_strategy_text"]);
  update_post_meta($post->ID, "page_results_image_url", $_POST["page_results_image_url"]);
  
  
  
  update_post_meta($post->ID, "casestudies_url", $_POST["casestudies_url"]);
  update_post_meta($post->ID, "casestudies_client", $_POST["casestudies_client"]);
  update_post_meta($post->ID, "casestudies_website", $_POST["casestudies_website"]);
  update_post_meta($post->ID, "casestudies_features", $_POST["casestudies_features"]);
  update_post_meta($post->ID, "casestudies_download", $_POST["casestudies_download"]);

  update_post_meta($post->ID, "casestudies_obj1", $_POST["casestudies_obj1"]);
  update_post_meta($post->ID, "casestudies_obj2", $_POST["casestudies_obj2"]);
  update_post_meta($post->ID, "casestudies_obj3", $_POST["casestudies_obj3"]);
  update_post_meta($post->ID, "casestudies_obj4", $_POST["casestudies_obj4"]);
  update_post_meta($post->ID, "casestudies_obj5", $_POST["casestudies_obj5"]);
  update_post_meta($post->ID, "casestudies_obj6", $_POST["casestudies_obj6"]);
}

function save_press(){
  global $pressMeta;

  savePostType( 'press', $pressMeta );
}

function save_post(){
  // verify if this is an auto save routine.
  // If it is our form has not been submitted, so we dont want to do anything
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    return;

  global $postMeta;

  savePostType( 'post', $postMeta );
}


function save_event(){
  global $post;
  update_post_meta($post->ID, "event_location", $_POST["event_location"]);
  update_post_meta($post->ID, "event_date", $_POST["event_date"]);
  update_post_meta($post->ID, "event_time", $_POST["event_time"]);
    update_post_meta($post->ID, "event_url", $_POST["event_url"]);
}




// Set up custom taxonomies
add_action( 'init', 'build_taxonomies', 0 );
function build_taxonomies() {
    register_taxonomy(
      'vacancy_location',
      'vacancy',
      array(
        'hierarchical' => true,
        'label' => 'Location',
        'query_var' => true,
        'rewrite' => true
      )
    );
}




function ustwo_widgets_init() {
  // Area 1, located at the top of the sidebar.
  register_sidebar( array(
    'name' => __( 'Primary Widget Area', 'ustwo' ),
    'id' => 'primary-widget-area',
    'description' => __( 'The primary widget area', 'ustwo' ),
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ) );

  // Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
  register_sidebar( array(
    'name' => __( 'Secondary Widget Area', 'ustwo' ),
    'id' => 'secondary-widget-area',
    'description' => __( 'The secondary widget area', 'ustwo' ),
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ) );

  // Area 3, located in the footer. Empty by default.
  register_sidebar( array(
    'name' => __( 'First Footer Widget Area', 'ustwo' ),
    'id' => 'first-footer-widget-area',
    'description' => __( 'The first footer widget area', 'ustwo' ),
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ) );

  // Area 4, located in the footer. Empty by default.
  register_sidebar( array(
    'name' => __( 'Second Footer Widget Area', 'ustwo' ),
    'id' => 'second-footer-widget-area',
    'description' => __( 'The second footer widget area', 'ustwo' ),
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ) );

  // Area 5, located in the footer. Empty by default.
  register_sidebar( array(
    'name' => __( 'Third Footer Widget Area', 'ustwo' ),
    'id' => 'third-footer-widget-area',
    'description' => __( 'The third footer widget area', 'ustwo' ),
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ) );

  // Area 6, located in the footer. Empty by default.
  register_sidebar( array(
    'name' => __( 'Fourth Footer Widget Area', 'ustwo' ),
    'id' => 'fourth-footer-widget-area',
    'description' => __( 'The fourth footer widget area', 'ustwo' ),
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ) );
}
/** Register sidebars by running ustwo_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'ustwo_widgets_init' );


include_once('ustwo.php');

// Removes Wordpress Meta Data comment from html
if ( function_exists( 'wp_generator' ) ) {
        remove_action( 'wp_head', 'wp_generator' );
}

// auto generate meta desc for single posts
function gen_meta_desc() {
  global $post;

  if (!is_single()) {
      return;
  }

  //print_r($post);
  $meta = strip_tags($post->post_content);
  $meta = str_replace(array("\n", "\r", "\t"), ' ', $meta);
  $meta = substr($meta, 0, 125);

  echo "<meta name='description' content='$meta' />";
}

add_action('wp_head', 'gen_meta_desc');


if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name'=> 'Main',
        'id' => 'main',
        'before_widget' => '<div class="widget_box side">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
    register_sidebar(array(
        'name'=> 'Homepage',
        'id' => 'homepage',
        'before_widget' => '<div class="widget_box">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
}



add_theme_support( 'post-thumbnails', array('post', 'blog', 'vacancy', 'press') );
add_theme_support( 'thumbnail', array('post', 'blog', 'vacancy', 'press') );
add_image_size( 'homepage-thumbnail', 285, 180); // Permalink thumbnail size

// Add URL rewrite for blog categories
add_action( 'init', 'wpse26388_rewrites_init' );
function wpse26388_rewrites_init(){
/*  add_rewrite_tag('%blog_category%','([^&]+)');
  add_rewrite_rule('blog/business$', 'index.php?page_id=788&blog_category=business', 'top' );
  add_rewrite_rule('blog/culture$', 'index.php?page_id=788&blog_category=culture', 'top' );
  add_rewrite_rule('blog/design$', 'index.php?page_id=788&blog_category=design', 'top' );
  add_rewrite_rule('blog/development$', 'index.php?page_id=788&blog_category=development', 'top' );
  add_rewrite_rule('blog/process$', 'index.php?page_id=788&blog_category=process', 'top' );
  add_rewrite_rule('blog/ux$', 'index.php?page_id=788&blog_category=ux', 'top' );
  add_rewrite_rule('blog/\?s=([^/]*)/?', 'index.php?page_id=788&s=$matches[1]', 'top' );
*/
}

add_filter( 'query_vars', 'wpse26388_query_vars' );
function wpse26388_query_vars( $query_vars ){
    $query_vars[] = 'blog_category';
    return $query_vars;
}

add_filter('init','flushRules');
function flushRules(){ global $wp_rewrite; $wp_rewrite->flush_rules(); }


// Add a cron task for the social counters update
//
// Add a new interval of a week
// See http://codex.wordpress.org/Plugin_API/Filter_Reference/cron_schedules
add_filter( 'cron_schedules', 'add_cron_schedule_5minutes' );
function add_cron_schedule_5minutes( $schedules ) {
    $schedules['15minutes'] = array(
        'interval' => 900,
        'display'  => __( 'Every 15 minutes' ),
    );

    return $schedules;
}

if( !wp_next_scheduled( 'social_counters_update' ) ) {
   wp_schedule_event( time(), '15minutes', 'social_counters_update' );
}

add_action('social_counters_update', 'socialCountersUpdate');


wp_clear_scheduled_hook('twitter_feed_update');
if( !wp_next_scheduled( 'twitter_feed_update' ) ) {
   wp_schedule_event( time(), '15minutes', 'twitter_feed_update' );
}

add_action('twitter_feed_update', 'twitterFeedUpdate');


/**
 * Update the count of shares on social networks of the blog posts
 *
 * After a few tests, it looks like Twitter doesn't take in account the domain name change
 * (hudsongray.co.uk -> hudsongray.com), so the two URLs stat have to be fetched separately and then added.
 * Facebook & Plus understand the change of domain and return the same stats for the different URLs,
 * so there is no need to fetch twice the same blog post.
 * @return [type] [description]
 */
function socialCountersUpdate() {
  require __DIR__.'/_config.php';

  $hosts = array(
    array(
      'name' => 'hudsongray.com',
      'services' => array('twitter', 'facebook', 'plus')
    ),
    array(
      'name' => 'www.hudsongray.co.uk',
      'services' => array('twitter')
    )
  );

  $blog = new Blog();
  $socialCounter = new SocialCounter();

  $posts = $blog->getAllPosts();

  foreach ($posts as $post) {
    $id = $post['id'];
    $link = $post['link'];

    $totalCounts = array();

    foreach ($hosts as $host) {
      $urlToCount = str_replace($_SERVER['HTTP_HOST'], $host['name'], $link);
      $counts = $socialCounter->execute($urlToCount, $host['services']);

      if ($socialCounter->hasError) {
        $errorMessage = join($socialCounter->errorMessages, "\n");
        $text = "Wordpress - Error fetching social counts for ${urlToCount}\n$errorMessage";
        mail('davide@ustwo.co.uk', 'Social counter update', $text);

        // Let's abort the update
        break;
      } else {
        foreach (array_keys($counts) as $service) {
          if (!isset($totalCounts[$service])) {
            $totalCounts[$service] = $counts[$service];
          } else {
            $totalCounts[$service] += $counts[$service];
          }
        }
      }
    }

    extract($totalCounts);

    update_post_meta($id, 'social-twitter-count', $twitter);
    update_post_meta($id, 'social-facebook-count', $facebook);
    update_post_meta($id, 'social-plus-count', $plus);

    $text = <<< EOT
Url: $link
Tweets: $twitter
Facebooks: $facebook
Plus: $plus

EOT;
      echo "<pre>$text</pre>";
  }
}

/**
 * Update the
 * @return [type] [description]
 */
function twitterFeedUpdate() {
  require __DIR__.'/_config.php';

  $twitterFetch = new TwitterFetch();
  $mentions = $twitterFetch->getLastMentions(3);
  $myTweets = $twitterFetch->getLastTweets(3);

  $tweets = array_merge($myTweets, $mentions);

  function compare($a, $b) {
    return $a['time'] < $b['time'];
  }

  usort($tweets, 'compare');

  $tweets = array_slice($tweets, 0, 3);

  update_option('twitter-feed', json_encode($tweets));

  print_r(json_decode(get_option('twitter-feed')));
}

?>
