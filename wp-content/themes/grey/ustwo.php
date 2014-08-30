<?php

function event_sort($a, $b) {
  	return strcmp($b["date"], $a["date"]);
}

// Create a custom class to wrap our stuff in
class Ustwo {
  
  // Returns a string of html links for the current post
  public function current_category_links() {
    $links = array();
    foreach((get_the_category()) as $category) {
      if ($category->cat_name == "Home") { continue; }
      $link = get_category_link($category->cat_ID);
      array_push($links, $category->cat_name);
    }
    return join(", ", $links);
  }
  
  public function current_category() {
    $categories = get_the_category();
    if(isset($categories[0]))
    {
    	$ret = $categories[0]->slug;
    }
    return $ret;
  }
  
  //return current case category
  public function current_case_category($id = false) {
  	if((int)$id)
		return $this->get_the_case_category($id);
	else
	{
		$terms = get_terms('case_category');
		return $terms;
	}
  }
  
  //return current case role
  public function current_case_role($id = false) {
  	if((int)$id)
		return $this->get_the_case_role($id);
	else
	{
		$terms = get_terms('case_role');
		return $terms;
	}
  }
  
  // Get case category
function get_the_case_category( $id = false ) {
	global $post;

	$id = (int) $id;
	if ( !$id )
		$id = (int) $post->ID;

	$categories = get_object_term_cache( $id, 'case_category' );
	if ( false === $categories ) {
		$categories = wp_get_object_terms( $id, 'case_category' );
		wp_cache_add($id, $categories, 'category_relationships');
	}

	if ( !empty( $categories ) )
		usort( $categories, '_usort_terms_by_name' );
	else
		$categories = array();

	foreach ( (array) array_keys( $categories ) as $key ) {
		_make_cat_compat( $categories[$key] );
	}

	return $categories;
}

  // Get case role
function get_the_case_role( $id = false ) {
	global $post;

	$id = (int) $id;
	if ( !$id )
		$id = (int) $post->ID;

	$categories = get_object_term_cache( $id, 'case_role' );
	if ( false === $categories ) {
		$categories = wp_get_object_terms( $id, 'case_role' );
		wp_cache_add($id, $categories, 'category_relationships');
	}

	if ( !empty( $categories ) )
		usort( $categories, '_usort_terms_by_name' );
	else
		$categories = array();

	foreach ( (array) array_keys( $categories ) as $key ) {
		_make_cat_compat( $categories[$key] );
	}

	return $categories;
}

  // Returns vacancies as an array of dictionaries
  public function vacancies( $locationId = false ) {
    $rows = array();

	$vacanciesMeta = array();
	$vacanciesMeta['job_title']		= array( 'Job Title', 'input' );
	$vacanciesMeta['description']	= array( 'Description', 'tinymce' );
	$vacanciesMeta['excerpt']		= array( 'Excerpt', 'textarea' );
	$vacanciesMeta['job_level']		= array( 'Level', 'input' );
	$vacanciesMeta['job_salary']	= array( 'Salary', 'input' );
	$vacanciesMeta['job_benefits']	= array( 'Benefits', 'input' );
	$vacanciesMeta['job_holidays']	= array( 'Holidays', 'input' );
	$vacanciesMeta['job_closing_date'] = array( 'Closing Date For Applications', 'input' );
    
    $loop = new WP_Query(array('post_type' => 'vacancy', 'post_count' => 100, 'posts_per_page'=>100, 'location'=> 'London'));
    while ($loop->have_posts()) {
      $loop->the_post();
	  $post_date = strtotime(get_the_date("Y-m-d H:i:s")); 
	  $today_date = date("Y-m-d H:i:s");
	  if( date('Y-m-d',strtotime('+'.NEWDATE.' day', $post_date)) >= $today_date)
	  	$isNew = 1;
	  else
	  	$isNew = 0;
      $title = the_title('', '', false);
      $location = wp_get_post_terms($loop->post->ID, "vacancy_location");
	  if ( $locationId && $locationId != $location[0]->term_id ) {
		  continue;
	  }

	  foreach ( $vacanciesMeta as $id => $properties )
	  {
		  $value = get_post_custom_values( "vacancy_$id" );
		  $row[$id] = $value[0];
	  }
      
      $row["is_new"] = $isNew;
      $row["title"] = $title;
      $row["location"] = $location[0]->name;
	  $row["permalink"] = get_permalink();
      
      array_push($rows, $row);
    }
    
    return $rows;
  }

  // Returns features as an array of dictionaries
  public function features() {
    $features = array();

    $loop = new WP_Query(array( 'post_type' => 'feature'));
    while ($loop->have_posts()) {
      $loop->the_post();
      $title = the_title('', '', false);
      $image = get_post_custom_values("feature_banner_image");
      $link = get_post_custom_values("feature_url");
      $excerpt = get_the_excerpt();

      $feature["title"] = $title;
      $feature["image"] = $image[0];
      $feature["url"] = $link[0];
      $feature["excerpt"] = $excerpt;

      array_push($features, $feature);
    }

    return $features;
  }

  // Returns blog posts as an array of dictionaries
  public function blogposts( $category = false, $author = false ) {
    $posts = array();

	$criteria = array();
	$criteria['post_type'] = 'blog';

	if ( $category ) {
		$criteria['blog_category'] = $category;
	}

	if ( $author ) {
		$criteria['author'] = $author;
	}

    $loop = new WP_Query( $criteria );
    while ($loop->have_posts()) {
      $loop->the_post();
      $title = the_title('', '', false);
      $excerpt = get_the_excerpt();
	  $date = get_the_time( 'j F Y' );
	  $link = get_permalink();

	  $post = array();
	  $post['id'] = get_the_ID();
	  $post['title'] = $title;
	  $post['excerpt'] = $excerpt;
	  $post['date'] = $date;
	  $post['link'] = $link;

	  $posts[$post['id']] = $post;
    }

    return $posts;
  }
  
  
  // Returns features as an array of dictionaries
  public function events() {
    $events = array();
    
    $loop = new WP_Query(array( 'post_type' => 'event'));
    while ($loop->have_posts()) {
      $loop->the_post();
      $title = the_title('', '', false);
      $location = get_post_custom_values("event_location");
      $date = get_post_custom_values("event_date");
      $time = get_post_custom_values("event_time");
      $url = get_post_custom_values("event_url");
      
      $event["title"] = $title;
      $event["location"] = $location[0];
      $event["date"] = $date[0];
      $event["time"] = $time[0];
      $event["url"] = $url[0];
      
      array_push($events, $event);
    }
    
    usort($events, "event_sort");
    
    return $events;
  }
 

  // Returns case studies as an array of dictionaries
  public function casestudies($orderby = null,$filtr = null) {
    $casestudies = array();
    if($orderby == null)
    {
    $query = array('orderby'=>'date', 'order'=>'DESC', 'post_type' => 'case_study', 'showposts' => POSTS_PER_PAGE, 'paged' => (get_query_var('paged')) ? get_query_var('paged') : 1);
    }
    else
    {
    	if($orderby == 'date')
    	   $query = array('order'=>'DESC','orderby'=>'date', 'post_type' => 'case_study', 'showposts' => -1);
    	if($orderby == 'clients')
    	   $query = array('meta_key'=>'casestudies_client', 'order'=>'ASC','orderby'=>'meta_value', 'post_type' => 'case_study', 'showposts' => -1);
    	if($orderby == 'projects')
    	   $query = array('order'=>'ASC','orderby'=>'title', 'post_type' => 'case_study', 'showposts' => -1);
    }
    query_posts($query);
    if($filtr != null)
    	$categories = split(",", $filtr);
    while (have_posts()) {
      the_post();
      $id = get_the_ID();
      $is_select = false;
      if($filtr == null || $filtr == "all")
      {
      	$is_select = true;
      }
      else
      {
	      	foreach($this->current_case_category($id) as $item)
	      	{
		      	foreach($categories as $category)
		      	{
		      		if($item->slug == $category)
		      		{
		      			$is_select = true;
		      			break;
		      		}
		      	}
		      	if($is_select)
		      		break;
	      	}
      }
      if($is_select)
      {
	      $title = the_title('', '', false);
	      $excerpt = get_the_excerpt();
	      
	      $link = get_permalink();
	      $casestudy["date"] = get_the_date("j F Y");
	      $casestudy["title"] = $title;
	      $casestudy["excerpt"] = $excerpt;
	      $casestudy["link"] = $link;
	      
	      $url = get_post_custom_values("casestudies_url");
	      $casestudy["url"] = $url[0];
	      
	      $client = get_post_custom_values("casestudies_client");
	      $casestudy["client"] = $client[0];
	      $casestudy["id"] = $id;
	      array_push($casestudies, $casestudy);
      }
    }
    return $casestudies;
  }
  
  //case study pagination init
  //init $prev and $next
  public function caseStudyPaginationInit($currentId) {
  	$rows = $this->casestudies("date");
	$this->_current = null;
	$this->_next = null;
	$this->_prev = null;
	$this->_pagenavi = array();
	$this->_currentInPagenavi;
	$counter = 1;
	foreach($rows as $item)
	{
		$this->_pagenavi[$counter] = $item;
		if($this->_current != null && !$this->_next)
		{
			$this->_next = $item;	
		}
		if($currentId == $item['id'])
		{
			$this->_currentInPagenavi = $counter;
			$this->_current = $item;
		}
		if(!$this->_current)
		{
			$this->_prev = $item;
		}
		$counter++;
	}
  }
  
  //create case study thumbnail
  public function createCaseStudyThumbnail($url)
  {
  	include_once(TEMPLATEPATH.'/image.php');
  	$path = pathinfo($url);
  	$urlMedium = $path['dirname'].'/'.$path['filename'].'_thm_598.'.$path['extension'];
  	resizeImg($urlMedium);
	$url = $path['dirname'].'/'.$path['filename'].'_thm_88_88.'.$path['extension'];
	resizeImg($url);
  	return $url;
  }
  
  //create Case_study navigation
  public function createCaseStudyNavigation()
  {
  	$paged = $this->_currentInPagenavi;
  	$size = sizeof($this->_pagenavi);
  	echo '<div class="wp-pagenavi">';
	for($i = $paged-3; $i < $paged; $i++)
	{
		if($i > 0)
			echo '<a href="'.$this->_pagenavi[$i]['link'].'" class="page">'.$i.'</a>';
	}
	echo '<span class="current">'.$paged.'</span>';
	for($i = $paged+1; ($i <= $paged+3 && $i < $size + 1); $i++)
	{
		echo '<a href="'.$this->_pagenavi[$i]['link'].'" class="page">'.$i.'</a>';
	}
	echo '</div></div><!-- #nav-below -->';
  }
  
  //get case_study prev
  public function getCaseStudyPrev()
  {
  	return $this->_prev;
  }
  
  //get case_study next
  public function getCaseStudyNext()
  {
  	return $this->_next;
  }
  
  //created facebook link 
  public function createFacebookIconLink()
  {
  	if(is_single()) 
	{
		if (have_posts())
		{
		   while (have_posts()) 
		   {
		   	the_post();
		   	$url = get_post_custom_values("posts_objtitle");
		   	if(stristr($url[0], 'youtube.com'))
		   	{
		   		$url = split('&', $url[0]);
		   		$url = split('/', $url[0]);
		   		echo '<link rel="image_src" href="http://i3.ytimg.com/vi/'.$url[sizeof($url)-1].'/hqdefault.jpg" />';
		   	}
		   	else if(stristr($url[0], 'vimeo'))
		 	{
		 		$url = split('/', $url[0]);
				$hash = unserialize(file_get_contents('http://vimeo.com/api/v2/video/'. $url[sizeof($url)-1] .'.php'));
				echo '<link rel="image_src" href="'.$hash[0]["thumbnail_large"].'" />';
		 	}
		   	else if(strlen($url[0]))
		   		echo '<link rel="image_src" href="'.$url[0].'" />';
		   }
		}
	}
  }
  
  //render main post object
 public function renderMainPostObject()
 {
 	$url = get_post_custom_values("posts_objtitle");

 	if(stristr($url[0], 'youtube.com'))
 	{
 		echo '<p class="title"><object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="592" height="355" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0">'
 				.'<param name="allowFullScreen" value="true" />'
 				.'<param name="allowScriptAccess" value="always" />'
 				.'<param name="src" value="'.$url[0].'" />'
 				.'<param name="allowfullscreen" value="true" />'
 				.'<embed type="application/x-shockwave-flash" width="592" height="355" src="'.$url[0].'" allowscriptaccess="always" allowfullscreen="true">'
 				.'</embed>'
 			.'</object></p>';
 		
 	}
 	else if(stristr($url[0], 'vimeo'))
 	{
 		echo '<p class="title"><iframe src="'.$url[0].'" width="592" height="355" frameborder="0">'
 		.'</iframe></p>';
 	}
 	else if(strlen($url[0]))
 	{
		echo '<p class="title"><img src="'.$url[0].'" alt="'.$url[0].'" style="width: 100%; height: auto;" /></p>';
 	}
 }
  
  // Returns press
  public function press() {
    $pressArray = array();
    $loop = new WP_Query(array( 'post_type' => 'press'));
    while ($loop->have_posts()) {
      $loop->the_post();
      $title = the_title('', '', false);
      $excerpt = get_the_excerpt();
      
      $press["date"] = get_the_date("j F Y");
      $press["title"] = $title;
      $press["excerpt"] = $excerpt;
      
      $pdf = get_post_custom_values("press_pdf");
      $image = get_post_custom_values("press_image");

      $press["pdf"] = $pdf[0];
	  $press['image'] = $image[0];
      
      array_push($pressArray, $press);
    }
    
    return $pressArray;
  }
  
  
  //Count tweeter followers
	function twitter_followers_counter($username) 
	{
		$cache_file = CACHEDIR . 'twitter_followers_counter_' . md5 ( $username );
		if (is_file ( $cache_file ) == false) 
			$cache_file_time = strtotime ( '1984-01-11 07:15' );
		else
			$cache_file_time = filemtime ( $cache_file );

		$now = strtotime ( date ( 'Y-m-d H:i:s' ) );
		$api_call = $cache_file_time;
		$difference = $now - $api_call;
		$api_time_seconds = 1800;

		if ($difference >= $api_time_seconds) 
		{
			$api_page = 'http://twitter.com/users/show/' . $username;
			$xml = file_get_contents ( $api_page );
	
			$profile = new SimpleXMLElement ( $xml );
			$count = $profile->followers_count;
			if (is_file ( $cache_file ) == true) 
				unlink ( $cache_file );
			touch ( $cache_file );
			file_put_contents ( $cache_file, strval ( $count ) );
			return strval ( $count );
		} 
		else
		{
			$count = file_get_contents ( $cache_file );
			return strval ( $count );
		}
	}
	
	function twitter_tiny_url_cache($url)
	{
		
		$cache_file = CACHEDIR . 'twitter_tyny_url_' . md5 ( $url);
		if (is_file ( $cache_file ) == false) 
			$cache_file_time = strtotime ( '1984-01-11 07:15' );
		else
			$cache_file_time = filemtime ( $cache_file );
			
		$now = strtotime ( date ( 'Y-m-d H:i:s' ) );
		$api_call = $cache_file_time;
		$difference = $now - $api_call;
		$api_time_seconds = 1800;

		if ($difference >= $api_time_seconds) 
		{
			$api_page = 'http://api.bit.ly/v3/shorten?login=ustwocouk&apiKey=R_0e9090b2d1c057be11295a9abc30faf1&longUrl='.urlencode($url).'&format=txt';
			/*$xml =  file_get_contents ( $api_page );
			$profile = new SimpleXMLElement ( $xml );
			$tinyUrl = $profile['url'];*/
			$tinyUrl = file_get_contents ( $api_page );
			
			if (is_file ( $cache_file ) == true) 
				unlink ( $cache_file );
			touch ( $cache_file );
			file_put_contents ( $cache_file,  $tinyUrl  );
			return $tinyUrl;
		} 
		else
		{
			$tinyUrl = file_get_contents ( $cache_file );
			return $tinyUrl;
		}
	} 
}

// Added by Davide

function getAttachmentIdByUrl( $url )
{
	global $wpdb;

	$posts = $wpdb->get_results
	("
	  SELECT *
	  FROM $wpdb->posts
		WHERE

		  post_type = 'attachment'
		AND
		  guid like '%{$url}'
	");

	return $posts[0]->ID;
}

function the_title_excerpt() {
	$titleExcerpt = get_post_custom_values("post_excerpt_title");

	$result = ( $titleExcerpt[0] )? $titleExcerpt[0]: get_the_title();

	echo $result;
}

function the_thumbnail() {
	$thumbnail = get_post_custom_values("post_thumbnail");

	$result = $thumbnail[0];

	echo sprintf( '<img src="%s" width="100" height="100" />', $result );
}

function the_home_thumbnail() {
	$thumbnail = get_post_custom_values("post_homepage_thumbnail");

	$result = $thumbnail[0];

	echo sprintf( '<img src="%s" width="285" height="180" />', $result );
}

function isYoutubeVideo( $url )
{
	return preg_match( '|http://www.youtube.com/v/.*|', $url );
}

function getYoutubeThumbnail( $url )
{
	return preg_replace( '|.*/(.*)|', 'http://img.youtube.com/vi/$1/0.jpg', $url );
}