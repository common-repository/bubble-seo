<?php
/*
Plugin Name: Bubble SEO
Plugin URI: https://wordpress.org/support/view/plugin-reviews/bubble-seo?filter=5
Description: It's time to have a good and fast SEO (Pure SEO)
Version: 3.50.27
Author: iLen
Author URI: http://support.ilentheme.com
*/
if ( !class_exists('ilen_seo') ) {

require_once 'assets/ilenframework/assets/lib/utils.php'; // get utils
require_once 'assets/functions/options.php'; // get options plugins

class ilen_seo extends ilen_seo_make{

function __construct(){

	global $if_utils;
	parent::__construct(); // configuration general

	global $ilen_seo;


	if( is_admin() ){

		add_action( 'admin_enqueue_scripts', array( &$this,'script_and_style_admin' ) );
		//register_update_hook( __FILE__ , array( &$this,'redirect_wp_' ) );


	}elseif( ! is_admin() ) {

		if( TRUE ){
			global $wp_version;
 
			if( (float)$wp_version >= 4.4 ){
				add_theme_support( "title-tag" );
				add_filter( 'pre_get_document_title', array( &$this,'getRealTitle') );
			}else{
				if ( ! has_filter( 'wp_title', array( &$this,'getRealTitle')) ){
					remove_all_filters( 'wp_title' );
					add_filter( 'wp_title', array( &$this,'getRealTitle'), 10, 3 );
				}
			}
 
			// add meta tags
			add_action('wp_head', array( &$this , 'getMetaTags'), 0 );

			// remove Wordpress generator (as options)
			if( isset($ilen_seo->remove_link_wp_generator) && $ilen_seo->remove_link_wp_generator ){
			  add_filter('the_generator', array( &$this,'wp_generator') );
			}

			// remove canonical links
			if( isset($ilen_seo->remove_link_canonical) && $ilen_seo->remove_link_canonical ){
			  remove_action('wp_head', 'rel_canonical');
			}


		}

		// add scripts & styles
		add_action( 'wp_enqueue_scripts', array( &$this,'script_and_style_front' ) );
	}

}


function wp_generator() {
	return '';
}

function redirect_wp_(){
	//exit( wp_redirect( admin_url( 'options-general.php?page=bubble-seo' ) ) );
}
 


  //  SEO
  function getRealTitle( $title ){
 
	if ( is_feed() )
	  return $title;
	
	$title = '';
	$title = self::getFormatTitle();
	return apply_filters( 'getRealTitle', $title );

  }


  function getMetaTags(){

	global $ilen_seo,$post,$authordata,$if_utils,$post_type,$meta_seo;

	$meta_keyword            = null;
	$meta_description        = null;
	$tags_to_metakeyword     = null;
	$meta_facebook           = null;
	$meta_twitter            = null;
	$meta_google             = null;
	$meta_title_custom       = null;
	$meta_keyword_custom     = null;
	$meta_description_custom = null;
	$post_type               = isset($post->ID) && $post->ID?get_post_type( $post->ID ):rand(10000,25000);
	$meta_seo 				 = get_post_meta( $post->ID, $this->parameter['name_option']."_metabox" );

	if( $post_type == "post" || $post_type == "page" ){
		if( isset($meta_seo[0]['keyword_seo']) && $meta_seo[0]['keyword_seo'] ){
			$meta_keyword_custom = $meta_seo[0]['keyword_seo'];
		}
	}

	// Get image post for social network
	$image_post = null;
	if( is_single() ){

		$image_post = ($if_utils->IF_get_image('large',null,$post->ID));	

		if( !$image_post['src'] ){
			if( isset($ilen_seo->default_image) && $ilen_seo->default_image ){
				$image_post = $ilen_seo->default_image;
			}
		}else{
			$image_post = $image_post['src'];
		}
	}elseif( is_home() || is_front_page() ){
		if( isset($ilen_seo->home_image) && $ilen_seo->home_image ){
				$image_post = $ilen_seo->home_image;
		}
	}
	
	

	if( (isset($ilen_seo->meta_keywork) && $ilen_seo->meta_keywork) || ( is_singular() &&  isset($ilen_seo->tag_keyword) && $ilen_seo->tag_keyword ) || ( is_singular() && $meta_keyword_custom ) ){

	  if( is_singular() && isset($ilen_seo->tag_keyword) && $ilen_seo->tag_keyword ){

		$t = wp_get_post_tags($post->ID);
		if( $t ){
		  $tags = array();
		  foreach ($t as $tag) {
			$tags[] = $tag->name;
		  }

		  $tags_to_metakeyword = implode(",",$tags);
		}
 		
 		if( $tags_to_metakeyword ){
			$meta_keyword_custom = ",$meta_keyword_custom";
 		}


		$meta_keyword = '
<meta name="keywords" content="'.$tags_to_metakeyword.$meta_keyword_custom.'" />';

	  }else{

	  	if( $ilen_seo->meta_keywork ){
			$meta_keyword_custom = ",$meta_keyword_custom";
 		}

		$meta_keyword = '
<meta name="keywords" content="'.$ilen_seo->meta_keywork.$meta_keyword_custom.'" />';

	  }

	  

	}

	if( get_query_var('paged') ){

	  $meta_description = "";

	}elseif( is_home() || is_front_page() ){

	  $meta_description = mb_substr($ilen_seo->meta_description,0,155,'utf-8');

	  if( isset( $ilen_seo->facebook_open_graph ) && $ilen_seo->facebook_open_graph ){

$meta_facebook = '
<!-- open Graph data -->
<meta property="og:title" content="'.get_bloginfo('name').'" />
<meta property="og:description" content="'.$meta_description.'" />
<meta property="og:url" content="'.get_bloginfo('url').'" />
<meta property="og:type" content="website" />
<meta property="og:locale" content="'.get_locale().'" />
<meta property="og:site_name" content="'.get_bloginfo( 'name' ).'" />
<meta property="og:image" content="'.$image_post.'" />
';

	  }

	  if( isset( $ilen_seo->twitter_user ) && $ilen_seo->twitter_user ){
	  $meta_twitter= '
<!-- twitter Card data -->
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@'.$ilen_seo->twitter_user.'" />
<meta name="twitter:title" content="'.get_bloginfo('name').'" />
<meta name="twitter:description" content="'.$meta_description.'" />
<meta name="twitter:image" content="'.$image_post.'" />
';
	  }
	}elseif( is_tag() ){
  
	  if( isset( $ilen_seo->facebook_open_graph ) && $ilen_seo->facebook_open_graph ){

		$tag = ucfirst(single_tag_title("", false));
		$tag_id = get_query_var('tag_id');
		$meta_facebook = '
<!-- open Graph data -->
<meta property="og:title" content="'.($tag).'" />
<meta property="og:url" content="'.get_tag_link( $tag_id ).'" />
<meta property="og:type" content="website" />
<meta property="og:locale" content="'.get_locale().'" />
<meta property="article:section" content="'.($tag).'" />
<meta property="og:site_name" content="'.get_bloginfo( 'name' ).'" />
<meta property="og:image" content="'.$image_post.'" />';

	  }

	  if( isset( $ilen_seo->twitter_user ) && $ilen_seo->twitter_user ){
	  $meta_twitter= '
<!-- twitter Card data -->
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@'.$ilen_seo->twitter_user.'" />
<meta name="twitter:title" content="'.$tag.'" />
<meta name="twitter:image" content="'.$image_post.'" />
';
	  }

	  $meta_description = "";

	}elseif( is_singular() ){

		if( $post->post_type == "forum" ){
			$excert  = strip_shortcodes(strip_tags(trim( mb_substr($ilen_seo->meta_description,0,155,'utf-8') )));
			$excert1 = preg_replace('/\s\s+/', ' ', $excert);  
			$excert2 = $if_utils->IF_removeShortCode( $excert1 );
			$content = mb_substr(trim( $excert2 ),0,155,'utf-8')."...";
			$meta_description = htmlspecialchars($content, ENT_QUOTES | 'ENT_HTML5');
		}elseif( $post->post_type == "topic" ){
			$excert  = strip_shortcodes(strip_tags(trim( bbp_get_topic_content() )));
			$excert1 = preg_replace('/\s\s+/', ' ', $excert);  
			$excert2 = $if_utils->IF_removeShortCode( $excert1 );
			$content = mb_substr(trim( $excert2 ),0,155,'utf-8')."...";
			$meta_description = htmlspecialchars($content, ENT_QUOTES | 'ENT_HTML5');
		}elseif( $post->post_type != "topic" && $post->post_type != "forum" ){
			$excert  = strip_shortcodes(strip_tags(trim( $post->post_content  )));
			$excert1 = preg_replace('/\s\s+/', ' ', $excert);  
			$excert2 = $if_utils->IF_removeShortCode( $excert1 );
			$content = mb_substr(trim( $excert2 ),0,155,'utf-8')."...";
			$meta_description = htmlspecialchars($content, ENT_QUOTES | 'ENT_HTML5');
		}



		if( isset($meta_seo[0]['title_seo']) && $meta_seo[0]['title_seo'] ){
			$meta_title_custom = $meta_seo[0]['title_seo'];
		}else{
			$meta_title_custom = get_the_title();
		}


		if( isset($meta_seo[0]['description_seo']) && $meta_seo[0]['description_seo'] ){
			$meta_description_custom = $meta_seo[0]['description_seo'];
		}else{
			$meta_description_custom = $meta_description;
		}

		$meta_description = $meta_description_custom;
		$tags_string = "";
		$categories_string = "";

		if( isset( $ilen_seo->facebook_open_graph ) && $ilen_seo->facebook_open_graph ){

		if(   isset( $ilen_seo->facebook_open_graph_tag ) && $ilen_seo->facebook_open_graph_tag   ){
			$t = wp_get_post_tags($post->ID);
			if( $t ){
			  $tags = array();
			  foreach ($t as $tag) {
				$tag_link = get_tag_link($tag->term_id);
				$tags[] = $tag->name;
			  }
			  if( is_array($tags) ){
				foreach($tags as $tt){
					$tags_string .='
<meta property="article:tag" content="$tt" />
';
				}
				$tags_string = "\n{$tags_string}\n";
			  }
			}
		}

		$c = get_the_category(); 
		$array_cat = array();
		if( $c ){

		  foreach ($c as $category) {
			$array_cat[] = $category->cat_name;
		  }

		  if( is_array( $array_cat ) ){
			$categories_string = implode(",",$array_cat);
		  }

		}

		
		$meta_facebook = '
<!-- open graph data -->
<meta property="og:title" content="'.$meta_title_custom.'" />
<meta property="og:description" content="'.$meta_description_custom.'" />
<meta property="og:url" content="'.get_permalink().'" />
<meta property="og:type" content="website" />
<meta property="og:locale" content="'.get_locale().'" />
<meta property="og:image" content="'.$image_post.'" />
<meta property="article:section" content="'.$categories_string.'" />
<meta property="og:site_name" content="'.get_bloginfo( 'name' ).'" />';

		}

		if( isset( $ilen_seo->twitter_user ) && $ilen_seo->twitter_user ){
		$meta_twitter= '
<!-- twitter card data -->
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@'.$ilen_seo->twitter_user.'" />
<meta name="twitter:title" content="'.$meta_title_custom.'" />
<meta name="twitter:description" content="'.$meta_description_custom.'" />
<meta name="twitter:image" content="'.$image_post.'" />';
		}

	}elseif( is_category() ){

	  $current_url = rtrim($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], "/");
	  $arr_current_url = explode("/", $current_url);
	  $thecategory = get_category_by_slug(end($arr_current_url));

	  //$category      = get_the_category();
	  $category_desc = $thecategory->description;
	  $category_name = $thecategory->name;
	  $category_id   = $thecategory->term_id;

	  if( isset( $ilen_seo->facebook_open_graph ) && $ilen_seo->facebook_open_graph ){

		$meta_facebook = '
<!-- open graph data -->
<meta property="og:title" content="'.($category_name).'" />
<meta property="og:description" content="'.$category_desc.'" />
<meta property="og:url" content="'.get_category_link( $category_id ).'" />
<meta property="og:type" content="website" />
<meta property="og:locale" content="'.get_locale().'" />
<meta property="article:section" content="'.($category_name).'" />
<meta property="og:site_name" content="'.get_bloginfo( 'name' ).'" />
<meta property="og:image" content="'.$image_post.'" />';

	  }

	  if( isset( $ilen_seo->twitter_user ) && $ilen_seo->twitter_user ){
	  $meta_twitter= '
<!-- twitter card data -->
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@'.$ilen_seo->twitter_user.'" />
<meta name="twitter:title" content="'.$category_name.'" />
<meta name="twitter:description" content="'.$category_desc.'" />
<meta name="twitter:image" content="'.$image_post.'" />';
	  }

	}elseif( is_search() ){

	  $meta_description = "";

	}elseif( is_day() ){

	  $meta_description = "";

	}elseif( is_month() ){

	  $meta_description = "";

	}elseif( is_year() ){

	  $meta_description = "";

	}elseif( is_author() ){

	  if( $des_aut = get_the_author_meta( 'description', $authordata->ID ) ){
		$meta_description = htmlspecialchars($des_aut, ENT_QUOTES | 'ENT_HTML5');  
	  }

	  if( isset( $ilen_seo->facebook_open_graph ) && $ilen_seo->facebook_open_graph ){

		$meta_facebook = '
<!-- open graph data -->
<meta property="og:title" content="'.($authordata->display_name).'" />
<meta property="og:description" content="'.$meta_description.'" />
<meta property="og:url" content="'.get_author_posts_url( $authordata->ID ).'" />
<meta property="og:type" content="website" />
<meta property="og:locale" content="'.get_locale().'" />
<meta property="og:site_name" content="'.get_bloginfo( 'name' ).'" />
<meta property="og:image" content="'.$image_post.'" />
';

	  }

	  if( isset( $ilen_seo->twitter_user ) && $ilen_seo->twitter_user ){

	  $meta_twitter= '<!-- twitter card data -->
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@'.$ilen_seo->twitter_user.'" />
<meta name="twitter:title" content="'.($authordata->display_name).'" />
<meta name="twitter:description" content="'.$meta_description.'" />
<meta name="twitter:image" content="'.$image_post.'" />
';
	  }
	  

	}elseif( is_404() ){

	  $meta_description = "";

	}



	if( $meta_description ){

	  $meta_description = '
<meta name="description" content="'.htmlspecialchars($meta_description, ENT_QUOTES | 'ENT_HTML5').'" />';

	}

	if( isset($ilen_seo->google_publisher) && $ilen_seo->google_publisher ){

		$meta_google = '
<!-- google publisher -->
<link href="'.$ilen_seo->google_publisher.'" rel="publisher" />
';

	}
 
echo "\n<!-- This site is optimized with the WordPress Bubble SEO  plugin v". 
$this->parameter['version'] .
"- https://wordpress.org/plugins/bubble-seo/  -->"
.$meta_description.$meta_keyword.$meta_facebook.$meta_twitter.$meta_google."<!-- /Bubble SEO -->\n\n";

  }




  function getFormatTitle(){

	global $ilen_seo, $authordata, $meta_seo,$post;

	$title_format = null;
	$blog         = get_bloginfo('name');
	$description  = get_bloginfo('description');
	$post_title   = "";
	$category     = "";
	$tag          = "";
	$day          = null;
	$monthnum     = null;
	$year         = null;
	$author       = "";
	$query        = "";
	$num          = "";



	if( get_query_var('page') || get_query_var('paged') ){

		$title_format = $ilen_seo->pagination_title_format;
		$num = get_query_var('page')?get_query_var('page'):get_query_var('paged');

	}elseif( is_home() || is_front_page() ){

		$title_format = $ilen_seo->home_title;

	}elseif( is_singular() ){

		$meta_seo = get_post_meta( $post->ID, $this->parameter['name_option']."_metabox" );

		if( isset($meta_seo[0]['title_seo']) && $meta_seo[0]['title_seo'] ){
			$post_title = $meta_seo[0]['title_seo'];
		}else{
			$post_title = get_the_title();
		}

		//$post = get_the_title();
		$title_format = $ilen_seo->post_title_format;

	}elseif( is_category() ){

		$title_format = $ilen_seo->category_title_format;

		$current_url = rtrim($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], "/");
		$arr_current_url = explode("/", $current_url);
		$thecategory = get_category_by_slug(end($arr_current_url));

		//$category      = get_the_category();
		$category = $thecategory->name;

	}elseif( is_search() ){

		$title_format = $ilen_seo->search_title_format;

	}elseif( is_tag() ){

		$tag = ucfirst(single_tag_title("", false));
		$title_format = $ilen_seo->tag_title_format;

	}elseif( is_day() ){

		$year     = get_query_var('year');
		$monthnum = get_query_var('monthnum');
		$day      = get_query_var('day');
		$title_format = $ilen_seo->day_title_format;

	}elseif( is_month() ){

		$year     = get_query_var('year');
		$monthnum = get_query_var('monthnum');
		$day      = get_query_var('day');
		$title_format = $ilen_seo->month_title_format;

	}elseif( is_year() ){

		$year     = get_query_var('year');
		$monthnum = get_query_var('monthnum');
		$day      = get_query_var('day');
		$title_format = $ilen_seo->year_title_format;

	}elseif( is_author() ){

		$author = $authordata->display_name;
		$title_format = $ilen_seo->author_title_format;

	}elseif( is_404() ){

		$title_format = $ilen_seo->no_404_title_format;

	}

	$variables = array(
		'{blog}'                   => $blog
		, '{description}'          => $description
		, '{post}'                 => $post_title
		, '{category}'             => $category
		, '{tag}'                  => $tag
		, '{month}'                => $monthnum
		, '{year}'                 => $year
		, '{day}'                  => $day
		, '{author}'               => $author
		, '{query}'                => (get_search_query())
		, '{num}'                  => $num
	); 
	
	$new_title = str_replace(array_keys($variables), array_values($variables), htmlspecialchars($title_format));
	return $new_title;

  }




  
	function script_and_style_admin(){
		if( isset($_GET["page"]) &&  $_GET["page"] == $this->parameter["id_menu"] ){
			wp_enqueue_script( 'admin-js-'.$this->parameter["name_option"], plugins_url('/assets/js/admin.js',__FILE__), array( 'jquery' ), $this->parameter['version'], true );
		}

		wp_register_style( 'admin-css-'.$this->parameter["name_option"], plugins_url('/assets/css/admin.css',__FILE__),'all',$this->parameter['version'] );
		// Enqueue styles
		wp_enqueue_style( 'admin-css-'.$this->parameter["name_option"] );

	}

	function script_and_style_front(){
		// Register styles
		//wp_register_style( 'front-css-'.$this->parameter["name_option"], plugins_url('/assets/css/style.css',__FILE__),'all',$this->parameter['version'] );
		// Enqueue styles
		//wp_enqueue_style( 'front-css-'.$this->parameter["name_option"] );
		//wp_enqueue_script( 'front-js-'.$this->parameter["name_option"], plugins_url('/assets/js/jquery.equalizer.js',__FILE__), array( 'jquery' ), '1.2.5', true );
	}

 

 
 


} // end class
} // end if

 
global $IF_CONFIG;
unset($IF_CONFIG);
$IF_CONFIG = null;
$IF_CONFIG = new ilen_seo;

require_once "assets/ilenframework/core.php";

require_once "assets/functions/metabox.php";
?>