<?php 
/**
 * Components: List Categories
 * @package ilentheme
 * 
 */



if ( !class_exists('ilenframework_component_list_category') ) {

class ilenframework_component_list_category{

	var $modo = '';
	var $first_element_block    = false;
	var $total_taxonomies_fetch = 0;
	var $count_taxonomies_fetch = 0;

	function __construct(){

		// add scripts & styles
		//add_action('admin_enqueue_scripts', array( &$this,'load_script_and_styles') );

	}

	function IF_getCategories( $args = array(), $text_first_element ){

		global $IF;
		
		$array_categories['-1'] = __( $text_first_element ,$IF->parameter['name_option'] );

		$categories = get_categories( $args );

		if( $categories ){

			foreach ($categories as $key => $value) {

				$array_categories[$value->cat_ID] = $value->name;

			}

		}

		return $array_categories;


		
	}


	function display_tags( $id_name , $seleted_array = '', $lang_category = 'Tags'){

		$args = array(
		  'public'   => true,
		  '_builtin' => false 
		); 
		$output     = ''; // or objects
		$output_js     = ''; // js
		$operator   = 'and'; // 'and' or 'or'

		$taxonomies_array[] = array('name'=>'tag','label'=>$lang_category);
		$taxonomies = get_taxonomies( $args, $output, $operator );
		if ( $taxonomies ) {

			$option_taxonomy = $taxonomy;
			foreach ( $taxonomies  as $taxonomy ){
				if( !is_taxonomy_hierarchical(  $taxonomy->name  ) ){
					$taxonomies_array[] = array('name'=>$taxonomy->name,'label'=>$taxonomy->label);
				}
			}

		}

		$i = 0;
		foreach ( $taxonomies_array  as $taxonomy_v ){
			$output .= "<span style='padding: 4px 8px; margin-bottom: 10px; display: inline-block; border-bottom: 2px solid #D2D2D2; color: #8E8E8E;'>".$taxonomy_v->label."</span>";
			$output .= "<input type='text' id='input_tax_tag_$i' class='input_tax_tag' value='$seleted_array' /> ";
			$output_js .= "$('#input_tax_tag_$i').tagEditor({ beforeTagSave: function(field, editor, tags, tag, val) { return val + '|$taxonomy_v->label'; }, });";
		}
		$output.="<script> jQUery(document).ready(function(){ $output_js }); </script>";
	}


	function display( $id_name , $seleted_array = array(), $text_first_element="All", $taxonomy = 'category', $lang_category = 'Category', $modo = '' ){		

		//code
		if( !is_array( $seleted_array ) ){
			$seleted_array = array();
		}

		$hash = substr(md5($id_name), 0, 8);

		/*
		$list_categories = $this->IF_getCategories(array(), $text_first_element);

		$style_check = "";
		if( in_array("-1", $seleted_array ) ){

			$style_check = "style='display:none'";

		}*/


		/*foreach ($list_categories as $key2 => $value2): ?>

			<div class="row_checkbox_list <?php if( $key2 != "-1"){ echo "checked_hidden"; } ?>" <?php if( $key2 != "-1"){ echo $style_check; } ?>>
				<input  type="checkbox" <?php if( in_array( $key2  ,  $seleted_array ) ){ echo " checked='checked' ";} ?> name="<?php echo $id_name ?>[]" id="<?php echo $id_name."_". $key2 ?>" value="<?php echo $key2; ?>" class="<?php if($key2=="-1"){ echo "check_all"; } ?>" />
				<label for="<?php echo $id_name."_". $key2 ?>"><span class="ui"></span></label>
				&nbsp;<?php echo  $value2; ?> 
				<div class="help"></div>
			</div>

		<?php endforeach; */

		$this->modo = $modo;
		$category_for_taxomonies    = false;
		$only_category_from_options = false;
		if( $taxonomy == 'category' ){
			$only_category_from_options = true;
			echo $this->IF_full_taxonomies_tree( $taxonomy , 0 , $id_name, $text_first_element, $seleted_array );
		}elseif( $taxonomy == 'all' ){
			$args = array(
			  'public'   => true,
			  '_builtin' => false 
			); 
			$output     = ''; // or objects
			$operator   = 'and'; // 'and' or 'or'

			$taxonomies_array[] = array('name'=>'category','label'=>$lang_category);

			$taxonomies = get_taxonomies( $args, $output, $operator );
			if ( $taxonomies ) {
				//var_dump($taxonomies);
				//$taxonomies = array_diff($taxonomies,array('catetory'));
				$option_taxonomy = $taxonomy;
				foreach ( $taxonomies  as $taxonomy ){
					if( is_taxonomy_hierarchical(  $taxonomy->name  ) ){
						$taxonomies_array[] = array('name'=>$taxonomy->name,'label'=>$taxonomy->label);
					}
				}
			}
			$this->total_taxonomies_fetch = count($taxonomies_array); // for closed 'div'

			foreach ($taxonomies_array as $taxonomies_array_key => $taxonomies_array_value) {
				$this->count_taxonomies_fetch++;
				echo $this->IF_full_taxonomies_tree( $taxonomies_array_value['name'] , 0 , $id_name , $taxonomies_array_value['label'] , $seleted_array , '' , false , $option_taxonomy, $hash );	
			}

			if( $this->modo == 'expand' && $this->count_taxonomies_fetch == $this->total_taxonomies_fetch  ){// for closed 'div'
				echo "</div>";
			}


		}elseif( $taxonomy ) {
			echo $this->IF_full_taxonomies_tree( $taxonomy , 0 , $id_name, $text_first_element, $seleted_array  );
		}

		if(  $this->modo == 'expand' && $this->first_element_block == true ){
			echo "<script>
jQuery(document).ready(function( $ ){
	if( $('.row_checkbox_list_expand_collap_$hash').length>0 ){
		$('.row_checkbox_list_expand_collap_$hash').on('click',function(){
			$(this).toggleClass('row_checkbox_list_expand_collap_press');
			$(this).next('div').toggleClass('row_checkbox_list_expand_collap_active');
		});
	}
});
</script>
<style>
.row_checkbox_list_expand_collap_$hash:before{content:'+'!important;width: 10px;display: inline-block;}
.row_checkbox_list_expand_collap_press:before{content:'-'!important;width: 10px;display: inline-block;}
.row_checkbox_list_expand_collap_normal{display:none;}
.row_checkbox_list_expand_collap_active{display:block!important;}
</style>
";
		}


		$this->first_element_block    = false;
		$this->total_taxonomies_fetch = 0;
		$this->count_taxonomies_fetch = 0;
		
		
	}


/**
* Gets all taxonomies include 'Category'
*
* @link https://stackoverflow.com/questions/18401236/custom-category-tree-in-wordpress#answer-28069283
* @return string html
*/
	function IF_full_taxonomies_tree($TermName='', $termID, $id_name , $text_first_element, $seleted_array, $separator='', $parent_shown=true, $option_taxonomy=null, $id_hash = ''  ){
		global $only_category_from_options;
		$output = '';
		$style_check   = "";
		if( in_array("-1", $seleted_array ) && $TermName == 'category' && $only_category_from_options == true ){
			$style_check = "style='display:none'";
		}

		$args    = 'hierarchical=1&taxonomy='.$TermName.'&hide_empty=0&orderby=id&parent=';
		$checked = '';
		if ($parent_shown) {
			//$term         = get_term($termID , $TermName); 
			//$output       =	$separator.$term->name.'('.$term->term_id.')<br/>';
			if( in_array( -1 ,  $seleted_array ) ){ $checked = " checked='checked' ";}
			$output ='<div class="row_checkbox_list">'.$separator.'
				<input '.$checked.' type="checkbox"  name="'. $id_name .'[]" id="'. $id_name."_" . -1 .'" value="' . -1 . '" class="check_all" />
				<label for="' . $id_name . "_" . -1 . '"><span class="ui"></span></label>
				&nbsp;' . $text_first_element . '
				<div class="help"></div>
			</div>';
			$parent_shown = false;
		}elseif( $option_taxonomy == 'all' ){
			$term = null;
			if( $termID != 0 ){
				$term = get_term($termID , $TermName); 	
			}

			//$output       =	$separator.$term->name.'('.$term->term_id.')<br/>';

			if( isset($term->term_id) && in_array( $term->term_id  ,  $seleted_array ) ){ $checked = " checked='checked' ";}
			$text_collapse = '';
			if(  $this->modo == 'expand' && $this->first_element_block == true && !$separator ){ $output .= '</div>'; }
			$output .= $separator."<div style='padding: 4px 8px; margin-bottom: 10px; border-bottom: 1px solid #D2D2D2; color: #8E8E8E;' class='row_checkbox_list_expand_collap_$id_hash'>".$text_collapse.' '.$text_first_element."</div>";
			$this->first_element_block = true;
			if( !$separator &&  $this->modo == 'expand' ){ $output .= '<div class="row_checkbox_list_expand_collap_normal">'; }
			$option_taxonomy = "taxonomies_various";
		}

		$separator .= '<span style="display:inline-block;width:38px;"></span>';  
		$terms     	= get_terms($TermName, $args . $termID);

		if(count($terms)>0){
			foreach ($terms as $term) {
				//$selected = ($cat->term_id=="22") ? " selected": "";
				//$output .=  '<option value="'.$category->term_id.'" '.$selected .'>'.$separator.$category->cat_name.'</option>';
				//$output .=  $separator.$term->name.'('.$term->term_id.')<br/>';
				//var_dump($term);exit;
				//var_dump($term->name.'|'.$TermName);

				$id_input = ($option_taxonomy != 'taxonomies_various' && $TermName == 'category'  && $only_category_from_options == true) ? $term->term_id : $term->slug.'|'.$TermName.'|'.$term->term_id;

				$checked = "";
				//var_dump(($option_taxonomy != 'taxonomies_various' && $TermName == 'category' && $only_category_from_options == true) &&  in_array( $term->term_id  ,  $seleted_array ));
				//var_dump($term->slug.'|'.$term->name.'|'.$term->term_id  ,  $seleted_array);
				//exit;
				if(  ($option_taxonomy != 'taxonomies_various' && $TermName == 'category' && $only_category_from_options == true) &&  in_array( $term->term_id  ,  $seleted_array ) ){
					$checked = " checked='checked' ";
				}elseif( in_array( $term->slug.'|'.$TermName.'|'.$term->term_id  ,  $seleted_array ) ){
					$checked = " checked='checked' ";
				}
				//if( in_array( $term->term_id  ,  $seleted_array ) ){ $checked = " checked='checked' ";}
				$output .='<div class="row_checkbox_list checked_hidden " '.$style_check.'>'.$separator.'
				<input '.$checked.' type="checkbox"  name="'. $id_name .'[]" id="'. $id_name."_" . $term->term_id .'" value="' . $id_input . '" />
				<label for="' . $id_name . "_" . $term->term_id . '"><span class="ui"></span></label>
				&nbsp;' . $term->name . '
				<div class="help"></div>
			</div>';
				$output .=  $this->IF_full_taxonomies_tree($TermName, $term->term_id, $id_name, $text_first_element, $seleted_array, $separator, $parent_shown,  $option_taxonomy);
			}
		}

		return $output;
	}

	// =SCRIPT & STYLES---------------------------------------------
	/*function load_script_and_styles(){
		//code 
		global $IF;
		if( is_admin()  && isset($_GET["page"]) == $IF->parameter['id_menu'] ){

			wp_enqueue_script('ilenframework-script-admin-list-category', $IF->parameter['url_framework'] . '/assets/js/list_category.js', array( 'jquery' ), '', true );

		}
	}*/






 

} // class

} // if


global $IF_COMPONENT;

$IF_COMPONENT['component_list_category'] = new ilenframework_component_list_category;





?>