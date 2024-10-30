<?php
/**
 * Options Plugin
 * Make configutarion
*/

if ( !class_exists('ilen_seo_make') ) {

class ilen_seo_make{

        public $parameter       = array();
        public $options         = array();
        public $components      = array();
 
    function __construct(){

        if( is_admin()){

            if(  isset($_GET["page"]) && $_GET["page"] == 'bubble-seo' ){
                add_action('admin_footer',  array( &$this , 'getMetaTagsTest'));    
            }
            //self::getMetaTagsTest();
            self::configuration_plugin();
        }else{
            self::parameters();
        }

        global $ilen_seo,$if_utils;
        // get option plugin ;)
        $ilen_seo = $if_utils->IF_get_option( $this->parameter['name_option'] );

    }

    function getHeaderPlugin(){
        //code 

        return array(            'id'             =>'bubble_seo_id',
                                 'id_menu'        =>'bubble-seo',
                                 'name'           =>'Bubble SEO',
                                 'name_long'      =>'Bubble SEO',
                                 'name_option'    =>'bubble_seo',
                                 'name_plugin_url'=>'bubble-seo',
                                 'descripcion'    =>'',
                                 'version'        =>'3.50.27',
                                 'url'            =>'',
                                 'logo'           =>'<i class="fa fa-line-chart" style="padding: 13px 11px!important;color: #9B9B9B;"></i>', // or image .jpg,png
                                 'logo_text'      =>'', // alt of image
                                 'slogan'         =>'', // powered by <a href="">iLenTheme</a>
                                 'url_framework'  =>plugins_url()."/bubble-seo/assets/ilenframework",
                                 'theme_imagen'   =>plugins_url()."/bubble-seo/assets/images",
                                 'languages'      =>plugins_url()."/bubble-seo/assets/languages",
                                 'twitter'        => '',
                                 'wp_review'      => 'http://wordpress.org/support/view/plugin-reviews/bubble-seo?filter=5',
                                 'wp_support'     => 'http://support.ilentheme.com/forums/forum/plugins/bubble-seo/',
                                 'link_donate'    => 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5ZHACVPGAD6DA',
                                 'type'           =>'plugin',
                                 'method'         =>'free',
                                 'themeadmin'     =>'fresh',
                                 'scripts_admin'  =>array( 'page'        => array('bubble-seo'  => array('jquery_ui_reset','enhancing_code')),
                                                           'post_type'   => array('page'        => array('jquery_ui_reset'),
                                                                                  'post'       => array('jquery_ui_reset')), 
                                                         )
                    );
    }

    function getOptionsPlugin(){

    global ${'tabs_plugin_' . $this->parameter['name_option']};
    ${'tabs_plugin_' . $this->parameter['name_option']} = array();
    ${'tabs_plugin_' . $this->parameter['name_option']}['tab04']=array('id'=>'tab04','name'=>'Preview','icon'=>'<i class="fa fa-eye"></i>','width'=>'130'); 
    ${'tabs_plugin_' . $this->parameter['name_option']}['tab01']=array('id'=>'tab01','name'=>'Formats','icon'=>'<i class="fa fa-circle-o"></i>','width'=>'130'); 
    ${'tabs_plugin_' . $this->parameter['name_option']}['tab02']=array('id'=>'tab02','name'=>'Meta','icon'=>'<i class="fa fa-pencil"></i>','width'=>'130'); // ,'fix'=>1
    ${'tabs_plugin_' . $this->parameter['name_option']}['tab03']=array('id'=>'tab03','name'=>'Social SEO','icon'=>'<i class="fa fa-users"></i>','width'=>'130'); 
    ${'tabs_plugin_' . $this->parameter['name_option']}['tab05']=array('id'=>'tab05','name'=>'More','icon'=>'<i class="fa fa-plus"></i>','width'=>'130'); 
 

    return array('a'=>array(                'title'      => __('Basic',$this->parameter['name_option']), 
                                            'title_large'=> __('Basic',$this->parameter['name_option']), 
                                            'description'=> '', 
                                            'icon'       => 'fa fa-circle-o',
                                            'tab'        => 'tab01',

                                            'options'    => array(  
                                                                     
                                                                    array(  'title' =>__('Homepage Title',$this->parameter['name_option']),
                                                                            'help'  =>__('',$this->parameter['name_option']),
                                                                            'type'  =>'text',
                                                                            'value' =>'{blog} | {description}',
                                                                            'id'    =>$this->parameter['name_option'].'_'.'home_title',
                                                                            'name'  =>$this->parameter['name_option'].'_'.'home_title',
                                                                            'class' =>'',
                                                                            'row'   =>array('a','b')),

                                                                    array(  'title' =>__('Post Title Format',$this->parameter['name_option']),
                                                                            'help'  =>__('',$this->parameter['name_option']),
                                                                            'type'  =>'text',
                                                                            'value' =>'{post} | {blog}',
                                                                            'id'    =>$this->parameter['name_option'].'_'.'post_title_format',
                                                                            'name'  =>$this->parameter['name_option'].'_'.'post_title_format',
                                                                            'class' =>'',
                                                                            'row'   =>array('a','b')),

                                                                    array(  'title' =>__('Category Title Format',$this->parameter['name_option']),
                                                                            'help'  =>__('',$this->parameter['name_option']),
                                                                            'type'  =>'text',
                                                                            'value' =>'{category} | {blog}',
                                                                            'id'    =>$this->parameter['name_option'].'_'.'category_title_format',
                                                                            'name'  =>$this->parameter['name_option'].'_'.'category_title_format',
                                                                            'class' =>'',
                                                                            'row'   =>array('a','b')),

                                                                    array(  'title' =>__('Tag Title Format',$this->parameter['name_option']),
                                                                            'help'  =>__('',$this->parameter['name_option']),
                                                                            'type'  =>'text',
                                                                            'value' =>'{tag} | {blog}',
                                                                            'id'    =>$this->parameter['name_option'].'_'.'tag_title_format',
                                                                            'name'  =>$this->parameter['name_option'].'_'.'tag_title_format',
                                                                            'class' =>'',
                                                                            'row'   =>array('a','b')),

                                                                    array(  'title' =>__('Day Archive Title Format',$this->parameter['name_option']),
                                                                            'help'  =>__('',$this->parameter['name_option']),
                                                                            'type'  =>'text',
                                                                            'value' =>'Archives for {month} {day}, {year} | {blog}',
                                                                            'id'    =>$this->parameter['name_option'].'_'.'day_title_format',
                                                                            'name'  =>$this->parameter['name_option'].'_'.'day_title_format',
                                                                            'class' =>'',
                                                                            'row'   =>array('a','b')),

                                                                    array(  'title' =>__('Month Archive Title Format',$this->parameter['name_option']),
                                                                            'help'  =>__('',$this->parameter['name_option']),
                                                                            'type'  =>'text',
                                                                            'value' =>'Archives for {month} {year} | {blog}',
                                                                            'id'    =>$this->parameter['name_option'].'_'.'month_title_format',
                                                                            'name'  =>$this->parameter['name_option'].'_'.'month_title_format',
                                                                            'class' =>'',
                                                                            'row'   =>array('a','b')),

                                                                    array(  'title' =>__('Year Archive Title Format',$this->parameter['name_option']),
                                                                            'help'  =>__('',$this->parameter['name_option']),
                                                                            'type'  =>'text',
                                                                            'value' =>'Archives for {year} | {blog}',
                                                                            'id'    =>$this->parameter['name_option'].'_'.'year_title_format',
                                                                            'name'  =>$this->parameter['name_option'].'_'.'year_title_format',
                                                                            'class' =>'',
                                                                            'row'   =>array('a','b')),

                                                                    array(  'title' =>__('Author Archive Title Format',$this->parameter['name_option']),
                                                                            'help'  =>__('',$this->parameter['name_option']),
                                                                            'type'  =>'text',
                                                                            'value' =>'Posts by {author} | {blog}',
                                                                            'id'    =>$this->parameter['name_option'].'_'.'author_title_format',
                                                                            'name'  =>$this->parameter['name_option'].'_'.'author_title_format',
                                                                            'class' =>'',
                                                                            'row'   =>array('a','b')),

                                                                    array(  'title' =>__('Search Title Format',$this->parameter['name_option']),
                                                                            'help'  =>__('',$this->parameter['name_option']),
                                                                            'type'  =>'text',
                                                                            'value' =>'Search Results for {query} | {blog}',
                                                                            'id'    =>$this->parameter['name_option'].'_'.'search_title_format',
                                                                            'name'  =>$this->parameter['name_option'].'_'.'search_title_format',
                                                                            'class' =>'',
                                                                            'row'   =>array('a','b')),

                                                                    array(  'title' =>__('404 Title Format',$this->parameter['name_option']),
                                                                            'help'  =>__('',$this->parameter['name_option']),
                                                                            'type'  =>'text',
                                                                            'value' =>'404 Not Found | {blog}',
                                                                            'id'    =>$this->parameter['name_option'].'_'.'no_404_title_format',
                                                                            'name'  =>$this->parameter['name_option'].'_'.'no_404_title_format',
                                                                            'class' =>'',
                                                                            'row'   =>array('a','b')),

                                                                    array(  'title' =>__('Pagination Title Format',$this->parameter['name_option']),
                                                                            'help'  =>__('',$this->parameter['name_option']),
                                                                            'type'  =>'text',
                                                                            'value' =>'{blog} - Page {num}',
                                                                            'id'    =>$this->parameter['name_option'].'_'.'pagination_title_format',
                                                                            'name'  =>$this->parameter['name_option'].'_'.'pagination_title_format',
                                                                            'class' =>'',
                                                                            'row'   =>array('a','b')),

                                                                    ),
                ),
                'b'=>array(                'title'      => __('Home Meta',$this->parameter['name_option']), 
                                           'title_large'=> __('',$this->parameter['name_option']), 
                                           'description'=> '',  
                                           'icon'       => '',
                                           'tab'        => 'tab02',

                                            'options'    => array( 

                                                                    array(  'title' =>__('Meta description',$this->parameter['name_option']),
                                                                        'help'  =>__('Write about your website in general. The optimum would be 155 Characters',$this->parameter['name_option']),
                                                                        'type'  =>'textarea',
                                                                        'value' =>'',
                                                                        'id'    =>$this->parameter['name_option'].'_'.'meta_description',
                                                                        'name'  =>$this->parameter['name_option'].'_'.'meta_description',
                                                                        'class' =>'',
                                                                        'maxlength'=> 155,
                                                                        'row'   =>array('a','b')),

                                                                    array(  'title' =>__('Meta keyword',$this->parameter['name_option']),
                                                                        'help'  =>__('For what? This is already <a href="http://googlewebmastercentral.blogspot.com/2009/09/google-does-not-use-keywords-meta-tag.html" target="_blank" >obsolete by google since 2009</a>. We recommend that you do not put anything here that because the google search engine may penalize you.',$this->parameter['name_option']),
                                                                        'type'  =>'textarea',
                                                                        'value' =>'',
                                                                        'id'    =>$this->parameter['name_option'].'_'.'meta_keywork',
                                                                        'name'  =>$this->parameter['name_option'].'_'.'meta_keywork',
                                                                        'class' =>'',
                                                                        'row'   =>array('a','b')),

                                                                    
                                            ),
                ),
                'c'=>array(                'title'      => __('Other',$this->parameter['name_option']), 
                                           'title_large'=> __('',$this->parameter['name_option']), 
                                           'description'=> '',  
                                           'icon'       => '',
                                           'tab'        => 'tab02',

                                            'options'    => array( 


                                                            array(  'title' =>__('Remove Canonical Links?',$this->parameter['name_option']),
                                                                            'help'  =>__('Example: <br /><code>&lt;link href="http://mywebsite.com/my-content-page/" rel="canonical" /></code>',$this->parameter['name_option']),
                                                                            'type'  =>'checkbox',
                                                                            'value' =>'0',
                                                                            'value_check'=>1, 
                                                                            'id'    =>$this->parameter['name_option']. '_'. 'remove_link_canonical',
                                                                            'name'  =>$this->parameter['name_option']. '_'. 'remove_link_canonical',
                                                                            'class' =>'',  
                                                                            'row'   =>array('a','b')),

                                                            array(  'title' =>__('Remove Wordpress generator?',$this->parameter['name_option']),
                                                                            'help'  =>__('Remove meta link: <br /><code>&lt;meta name="generator" content="WordPress 4.x" /></code>',$this->parameter['name_option']),
                                                                            'type'  =>'checkbox',
                                                                            'value' =>'0',
                                                                            'value_check'=>1, 
                                                                            'id'    =>$this->parameter['name_option']. '_'. 'remove_link_wp_generator',
                                                                            'name'  =>$this->parameter['name_option']. '_'. 'remove_link_wp_generator',
                                                                            'class' =>'',  
                                                                            'row'   =>array('a','b')),

                                                            array(  'title' =>__('Use tags as keywork in post',$this->parameter['name_option']),
                                                                            'help'  =>__('With this you can convert your post how tags <code>Meta Keyword</code> tags <a href="http://googlewebmastercentral.blogspot.com/2009/09/google-does-not-use-keywords-meta-tag.html" target="_blank" >(not recommended)</a>',$this->parameter['name_option']),
                                                                            'type'  =>'checkbox',
                                                                            'value' =>'0',
                                                                            'value_check'=>1, 
                                                                            'id'    =>$this->parameter['name_option']. '_'. 'tag_keyword',
                                                                            'name'  =>$this->parameter['name_option']. '_'. 'tag_keyword',
                                                                            'class' =>'',  
                                                                            'row'   =>array('a','b')),


                                                            )
                                        ), 
                'd'=>array(                'title'      => __('Twitter',$this->parameter['name_option']), 
                                           'title_large'=> __('',$this->parameter['name_option']), 
                                           'description'=> '',  
                                           'icon'       => '',
                                           'tab'        => 'tab03',

                                            'options'    => array( 


                                                            array(  'title' =>__('Twitter account page',$this->parameter['name_option']),
                                                                            'help'  =>__("Put only the user without the @<br /><br />
&lt;meta name='twitter:card' content='summary'><br />
&lt;meta name='twitter:site' content='@xxxx'><br />
&lt;meta name='twitter:title' content='xxxx'><br />
&lt;meta name='twitter:description' content='xxxx'>",$this->parameter['name_option']),
                                                                            'type'  =>'text',
                                                                            'value' =>'',
                                                                            'id'    =>$this->parameter['name_option'].'_'.'twitter_user',
                                                                            'name'  =>$this->parameter['name_option'].'_'.'twitter_user',
                                                                            'class' =>'',
                                                                            'row'   =>array('a','b')),


                                                            )
                                        ), 
                'e'=>array(                'title'      => __('Open Graph data (facebook)',$this->parameter['name_option']), 
                                           'title_large'=> __('',$this->parameter['name_option']), 
                                           'description'=> '',  
                                           'icon'       => '',
                                           'tab'        => 'tab03',

                                            'options'    => array( 


                                                            array(  'title' =>__('Active',$this->parameter['name_option']),
                                                                    'help'  =>__("&lt;meta property='og:title' content='xxxx' /><br />
&lt;meta property='og:description' content='xxxx' /><br />
&lt;meta property='og:image' content='xxxx' /><br />
&lt;meta property='og:url' content='xxxx' /><br />
&lt;meta property='og:type' content='article' /><br />
&lt;meta property='og:site_name' content='xxxx' /><br />
&lt;meta property='og:locale' content='xxxx' />",$this->parameter['name_option']),
                                                                            'type'  =>'checkbox',
                                                                            'value' =>'1',
                                                                            'value_check'=>1, 
                                                                            'id'    =>$this->parameter['name_option']. '_'. 'facebook_open_graph',
                                                                            'name'  =>$this->parameter['name_option']. '_'. 'facebook_open_graph',
                                                                            'class' =>'',  
                                                                            'row'   =>array('a','b')),
                                                                            
                                                            array(  'title' =>__('Open Graph Tag?',$this->parameter['name_option']),
                                                                    'help'  =>__("Each Tag it would add <br />&lt;meta property='article:tag' content='Tag1' /><br />&lt;meta property='article:tag' content='Tag2' />",$this->parameter['name_option']),
                                                                            'type'  =>'checkbox',
                                                                            'value' =>'0',
                                                                            'value_check'=>1, 
                                                                            'id'    =>$this->parameter['name_option']. '_'. 'facebook_open_graph_tag',
                                                                            'name'  =>$this->parameter['name_option']. '_'. 'facebook_open_graph_tag',
                                                                            'class' =>'',  
                                                                            'row'   =>array('a','b')),


                                                            )
                                        ), 
                 'f'=>array(                'title'      => __('Google+',$this->parameter['name_option']), 
                                           'title_large'=> __('',$this->parameter['name_option']), 
                                           'description'=> '',  
                                           'icon'       => '',
                                           'tab'        => 'tab03',

                                            'options'    => array( 
 
                                                            array(  'title' =>__('',$this->parameter['name_option']),
                                                                    'help'  =>__('',$this->parameter['name_option']),
                                                                    'type'  =>'html',
                                                                    'value' =>'',
                                                                    'id'    =>$this->parameter['name_option'].'_'.'html1',
                                                                    'name'  =>$this->parameter['name_option'].'_'.'html1',
                                                                    'class' =>'',
                                                                    'html2' =>"<p style='text-align:center'><img src='".$this->parameter['theme_imagen']."/google-authorship-rel-publisher.png' /><br />
                                                                            Insert your url google+, you can get it <a href='https://support.google.com/plus/answer/2676340' target='_blank'>here</a></p>",
                                                                    'row'   =>array('a','c')),

                                                            array(  'title' =>__('Google+ Publisher Markup',$this->parameter['name_option']),
                                                                    'help'  =>__('Example: https://plus.google.com/b/117461235542473303070/',$this->parameter['name_option']),
                                                                    'type'  =>'text',
                                                                    'value' =>'',
                                                                    'id'    =>$this->parameter['name_option'].'_'.'google_publisher',
                                                                    'name'  =>$this->parameter['name_option'].'_'.'google_publisher',
                                                                    'class' =>'',
                                                                    'row'   =>array('a','b')),

                                                            )

 
                                        ),

                'g'=>array(                'title'      => __('So your SEO code will look in the browser',$this->parameter['name_option']), 
                                           'title_large'=> __('',$this->parameter['name_option']), 
                                           'description'=> '',  
                                           'icon'       => '',
                                           'tab'        => 'tab04',

                                            'options'    => array( 
 
                                                            array(  'title' =>__('',$this->parameter['name_option']),
                                                                    'help'  =>__('',$this->parameter['name_option']),
                                                                    'type'  =>'component_enhancing_code',
                                                                    'value' =>'',
                                                                    'mini_callback' => 'editor_bubble_seo_preview.setValue( jQuery("#ilen_seo_preview").html() );',
                                                                    'id'    =>$this->parameter['name_option'].'_'.'preview',
                                                                    'name'  =>$this->parameter['name_option'].'_'.'preview',
                                                                    'class' =>'',
                                                                    'row'   =>array('a','c')),


                                                            /*array(  'title' =>__('Recommendation',$this->parameter['name_option']),
                                                                    'help'  =>__('',$this->parameter['name_option']),
                                                                    'type'  =>'html',
                                                                    'value' =>'',
                                                                    'id'    =>$this->parameter['name_option'].'_'.'recommendation',
                                                                    'name'  =>$this->parameter['name_option'].'_'.'recommendation',
                                                                    'class' =>'',
                                                                    'html2' => '<strong style="font-size:15px;font-weight:bold;">Recommendation (optional)</strong><br />Go to your theme folder, find the <code>header.php</code> file and in that file look for the function <code>&lt;?php wp_head(); ?></code>, Cut it and place it just below the <code>&lt;title></code> tag. The SEO will be much faster for search engines ;)',
                                                                    'row'   =>array('a','c')),*/
  
  

                                                            )

 
                                        ),
                'h'=>array(                'title'      => __('Other options',$this->parameter['name_option']), 
                                           'title_large'=> __('',$this->parameter['name_option']), 
                                           'description'=> '',  
                                           'icon'       => '',
                                           'tab'        => 'tab05',

                                            'options'    => array( 
 
                                                            array(  'title' =>__('Image home (logo)',$this->parameter['name_option']),
                                                                    'help'  =>__('The image to be displayed on social networks if you share your main url for code SEO',$this->parameter['name_option']),
                                                                    'type'  =>'upload',
                                                                    'value' =>$this->parameter['theme_imagen'].'/default.png',
                                                                    'id'    =>$this->parameter['name_option'].'_'.'home_image',
                                                                    'name'  =>$this->parameter['name_option'].'_'.'home_image',
                                                                    'class' =>'',
                                                                    'row'   =>array('a','b')),

                                                            array(  'title' =>__('Default image post',$this->parameter['name_option']),
                                                                    'help'  =>__('Default image in case there is no image in the post  for code SEO.',$this->parameter['name_option']),
                                                                    'type'  =>'upload',
                                                                    'value' =>$this->parameter['theme_imagen'].'/default.png',
                                                                    'id'    =>$this->parameter['name_option'].'_'.'default_image',
                                                                    'name'  =>$this->parameter['name_option'].'_'.'default_image',
                                                                    'class' =>'',
                                                                    'row'   =>array('a','b')),
  

                                                            )

 
                                        ),
                
                            'last_update'=>time(),


                             );
        
    }








  function getMetaTagsTest(){

    global $ilen_seo,$if_utils;

    $featured_args = array(
       'posts_per_page'      => 1,
       'orderby'             => 'rand',
       'ignore_sticky_posts' => 1,
       'post_type' => array('post')
       //'post__in' => array(61)
    );
     
    // The Featured Posts query.
    $posta = new WP_Query($featured_args);
     
    if ( $posta->have_posts() ) {
        while ( $posta->have_posts() ) {
            $posta->the_post();
            $post = $posta->post;
            // Reset the post data
            wp_reset_postdata();
            break;
        }
        
    }


    $meta_keyword        = null;
    $meta_description    = null;
    $tags_to_metakeyword = null;
    $meta_facebook       = null;
    $meta_twitter        = null;
    $meta_google         = null;

    // Get image post for social network
    $image_post = null;
    $image_post = ($if_utils->IF_get_image('medium',null,$post->ID));   
 
    if( $image_post['src'] == null || !$image_post['src'] ){
        if( isset($ilen_seo->default_image) && $ilen_seo->default_image ){
            $image_post = $ilen_seo->default_image;
        }
    }else{
        $image_post = $image_post['src'];
    }


    if( (isset($ilen_seo->meta_keywork) && $ilen_seo->meta_keywork) || (   isset($ilen_seo->tag_keyword) && $ilen_seo->tag_keyword ) ){

      if( isset($ilen_seo->tag_keyword) && $ilen_seo->tag_keyword ){

        $t = wp_get_post_tags($post->ID);
        if( $t ){
          $tags = array();
          foreach ($t as $tag) {
            $tags[] = $tag->name;
          }

          $tags_to_metakeyword = implode(",",$tags);
        }

        $meta_keyword = "<meta name='keywords' content='{$tags_to_metakeyword}' />\n";

      }else{

        $meta_keyword = "<meta name='keywords' content='$ilen_seo->meta_keywork' />\n";

      }

      

    }

   

      $meta_description = $ilen_seo->meta_description;


      $excert  = strip_shortcodes(strip_tags(trim( $post->post_content  )));
      $excert1 = preg_replace('/\s\s+/', ' ', $excert);  
      $excert2 = $if_utils->IF_removeShortCode( $excert1 );
      $content = substr(trim( $excert2 ),0,155)."...";
      $meta_description = $content;
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
                    $tags_string .="\n<meta property='article:tag' content='$tt' />";
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
 

        $meta_facebook = "<!-- open graph data -->
<meta property='og:title' content='".get_the_title()."' />
<meta property='og:description' content='$meta_description' />
<meta property='og:url' content='".get_permalink()."' />
<meta property='og:type' content='website' />
<meta property='og:locale' content='".get_locale()."' />
<meta property='article:section' content='$categories_string' />$tags_string
<meta property='og:site_name' content='".get_bloginfo( 'name' )."' />
<meta property='og:image' content='$image_post' />\n";
 }

      if( isset( $ilen_seo->twitter_user ) && $ilen_seo->twitter_user ){
      $meta_twitter= "<!-- twitter card data -->
<meta name='twitter:card' content='summary' />
<meta name='twitter:site' content='@$ilen_seo->twitter_user' />
<meta name='twitter:title' content='".get_the_title()."' />
<meta name='twitter:description' content='$meta_description' />
<meta name='twitter:image' content='$image_post' />\n";
      }

 

    if( $meta_description ){

      $meta_description = "<meta name='description' content='$meta_description' />\n";

    }

    if( isset($ilen_seo->google_publisher) && $ilen_seo->google_publisher ){

        $meta_google = "<!-- google publisher -->\n<link href='$ilen_seo->google_publisher' rel=publisher />";

    }

    $meta_title = "<title>".self::getFormatTitleTester( get_the_title() )."</title>\n";

    $seo_hidden =  "<!-- pure seo -->\n".$meta_title.$meta_description.$meta_keyword.$meta_facebook.$meta_twitter.$meta_google."<!-- pure seo -->";
    echo "<div id='ilen_seo_preview' style='display:none'>$seo_hidden</div>
    <style type='text/css'>
      .CodeMirror {
        border: 1px solid #eee;
        height: auto;
      }
      .CodeMirror-scroll {
        overflow-y: hidden;
        overflow-x: auto;
      }
    </style>";

  }



  function getFormatTitleTester( $title = ''){

    global $ilen_seo, $authordata, $post;

    $title_format = null;
    $blog         = get_bloginfo('name');
    $post         = "";
    $category     = "";
    $tag          = "";
    $day          = null;
    $monthnum     = null;
    $year         = null;
    $author       = "";
    $query        = "";
    $num          = "";
 
    $title_format = $ilen_seo->post_title_format;
 
    $variables = array(
        '{blog}'                   => $blog
        , '{post}'                 => $title
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







 

    function parameters(){

        $this->parameter = self::getHeaderPlugin();
    }

    function myoptions_build(){
        
        $this->options = self::getOptionsPlugin();

        return $this->options;
        
    }

    function use_components(){
        //code 
        
        $this->components = array('enhancing_code');

    }

    function configuration_plugin(){
        // set parameter 
        self::parameters();

        // my configuration 
        self::myoptions_build();

        // my component to use
        self::use_components();
    }

}
}


?>