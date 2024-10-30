<?php 

class bubble_seo_metabox{

    //the vars
    var $metabox_header = null;
    var $metabox_body   = null;
    var $parameter      = null;
    var $ME             = null;

    public function __construct(){
 
        // always variables
        global $IF;
        $this->parameter  = isset($IF->parameter)?(array)$IF->parameter:null;
        $this->ME = $IF;
        
        self::_builder();

        add_action( 'admin_head',  array( &$this , '_add' ) , 5 );
        add_action( 'save_post' ,  array( &$this->ME , 'IF_save_metabox' ) , 10 , 1 );
        // ---------------


        // set custom script for metabox
        add_action( 'in_admin_footer',  array( &$this, 'script_and_style_metabox_custom') );
 

    }

 

    function _builder(){

        $this->metabox_header['main_metabox_seo'] = array(
                                                                'id'         => 'main_metabox_seo',
                                                                'title'      => 'Bubble SEO - Wordpress',
                                                                'post_type'  => '',
                                                                'context'    => 'normal',  // (normal, advanced, or side)
                                                                'priority'   => 'low', // (high, core, default, or low)
                                                                'position'   => 'vertical', // vertical or horizontal
                                                                'tabs'       => array(
                                                                                    array('id'=>'seo_tab01','name'=>'Metas','icon'=>'<i class="fa fa-code"></i>','width'=>'200'),
                                                                                )
                                                          );
 
 

        $this->metabox_body['main_metabox_seo']   = array(
                                    'a'=>array( 'title'      => __(''), 
                                                'title_large'=> __(''), 
                                                'description'=> __('Enter the meta data you want to change in this "post"'), 
                                                'tab'        => 'seo_tab01',

                                                'options'    => array(
                                                                        array(  'title' =>__('Title SEO'),
                                                                                'help'  =>__('If you leave empty will default the title of the post.'),
                                                                                'type'  =>'text',
                                                                                'value' =>'',
                                                                                'id'    =>'title_seo',
                                                                                'name'  =>'title_seo',
                                                                                'class' =>'',
                                                                                'sanitizes'=>'s',
                                                                                'row'   =>array('a','b')),
                                                                                
                                                                        array(  'title' =>__('Description'),
                                                                                'help'  =>__('If you leave empty will default the first letters of the text of the post.'),
                                                                                'type'  =>'text',
                                                                                'value' =>'',
                                                                                'id'    =>'description_seo',
                                                                                'name'  =>'description_seo',
                                                                                'class' =>'',
                                                                                'sanitizes'=>'s',
                                                                                'row'   =>array('a','b')),

                                                                        array(  'title' =>__('Keyword'),
                                                                                'help'  =>__('For what? This is already <a href="http://googlewebmastercentral.blogspot.com/2009/09/google-does-not-use-keywords-meta-tag.html" target="_blank" >obsolete by google since 2009</a>. We recommend that you do not put anything here that because the google search engine may penalize you.'),
                                                                                'type'  =>'text',
                                                                                'value' =>'',
                                                                                'id'    =>'keyword_seo',
                                                                                'name'  =>'keyword_seo',
                                                                                'class' =>'',
                                                                                'sanitizes'=>'s',
                                                                                'row'   =>array('a','b')),
                                                                    ),
                                            ),

 

                                    );
 

        $this->ME->parameter['metabox_name']   = $this->parameter['name_option']."_metabox";
        $this->ME->parameter['header_metabox'] = $this->metabox_header;
        $this->ME->parameter['body_metabox']   = $this->metabox_body;
        

    }




    function _add(){

        global $post_type,$post;
 
        if( $post_type == 'post' || $post_type == 'page' ){

            $this->ME->create_metabox( $this->metabox_header , $this->metabox_body , $this->ME->parameter['metabox_name'] , $post_type  );            

        }


    }





    function script_and_style_metabox_custom(){
 
        global $IF_CONFIG;

    }
 


}



if( is_admin() ) new bubble_seo_metabox;
?>