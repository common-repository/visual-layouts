<?php
/*
Plugin Name: Visual Layouts
Plugin URI: http://wordpress.damien.co/freetile?utm_source=WordPress&utm_medium=freetile&utm_campaign=WordPress-Plugin
Description: Add visual effects to your list of posts & custom post types using Freetile. Needs a responsive theme   
Version: 1.2
Author: Damien Saunders
Author URI: http://damien.co/?utm_source=WordPress&utm_medium=freetile&utm_campaign=WordPress-Plugin
License: This plugin GPLv3.
*/

/**
 * You shouldn't be here.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * PHPUnit_test
 * Only create an instance of the plugin if it doesn't already exists in GLOBALS
 */
if( ! array_key_exists( 'visual-layouts', $GLOBALS ) ) { 
    class Visual_Layouts { 
        function __construct() { 
        } // end constructor 
    } // end class 
    // Store a reference to the plugin in GLOBALS so that our unit tests can access it 
    $GLOBALS['visual-layouts'] = new Visual_Layouts();  
} // end if  





/**
 * Globals
 */
define ("FREETILE_VERSION", "1.2");

$plugin = plugin_basename(__FILE__); 
global $dbc_option;

/**
 * dbc_get_global_options function.
 * 
 * @access public
 * @return void
 */
function dbc_get_global_options(){  
  $dbc_option  = get_option('visual_layouts_options');  
return $dbc_option;  
} 

/**
 * Plugin Updater
 */

require 'inc/plugin-update/plugin-update-checker.php';
$damien_vpl_update_checker = new PluginUpdateChecker(
 //   'http://damien.co/info.json',
 'http://dev.local/wp-content/plugins/visual-layouts/dev-info.json',
    __FILE__,
    'visual-layouts'
);


/**
 * damien_vpl_scripts_method function. 
 * Enqueue freetile.js
 * 
 * @access public
 * @return void
 */
function damien_vpl_scripts_method() {
	wp_enqueue_script('freetile', plugins_url('/js/jquery.freetile.min.js', __FILE__), array('jquery'));
}    
 
add_action('wp_enqueue_scripts', 'damien_vpl_scripts_method');

/**
 * Register with hook 'wp_enqueue_scripts', which can be used for front end CSS and JavaScript
 */
add_action( 'wp_enqueue_scripts', 'damien_vpl_add_my_stylesheet' );

/**
 * Enqueue plugin style-file
 */
function damien_vpl_add_my_stylesheet() {
    // Respects SSL, Style.css is relative to the current file
    wp_register_style( 'damien-vpl-style', plugins_url('css/custom_freetile.css', __FILE__) );
    wp_enqueue_style( 'damien-vpl-style' );
}


/**
 * damien_vpl_menu function.
 * Add Hook for Menu under Appearance
 * 
 * @access public
 * @return void
 */
add_action('admin_menu', 'damien_vpl_menu');

function damien_vpl_menu() 
{
	if(function_exists('add_menu_page')) 
	{
		add_theme_page('Visual Layouts', 'Visual Layouts', 'manage_options', dirname(__FILE__).'/dbc-freetile-options.php');
	}
}


/*
 * Add settings link on Installed Plugin page
 */
function damien_vpl_settings_link($links) { 
  $settings_link = '<a href="themes.php?page=visual-layouts/dbc-freetile-options.php">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'damien_vpl_settings_link' );


/**
 * Add shortcode function
 * usage example
 * [dbc_freetile posts=5] will show 5 posts
 * [dbc_freetile posts=-1] will show all posts
 * [dbc_freetile posts=-1 post_type=feedback] will show all posts from custom post type feedback
 * @param posts default is 10
 * @param cat default is all
 */
add_shortcode('dbc_freetile', 'damien_vpl_shortcode_handler', 10);
 
function damien_vpl_shortcode_handler($atts) {
	extract(shortcode_atts(array(
      'posts' => 10,
      'cats' => '',
      'order' => 'DESC',
      'post_type' => '',
      'extract' => true
      ), 
      $atts));
	 $damien_vpl_cats2 = $cats;
	 $damien_vpl_posttype = $post_type;
	 $damien_vpl_order = $order;
	 $damien_vpl_extract = $extract;
	 $dbc_option = dbc_get_global_options();
	?>

	<!-- Freetile for WordPress by Damien http://wordpress.damien.co/freetile  -->
	<?php 
		$vpl_style = $dbc_option["dropdown1"];
		$vpl_images = $dbc_option["dropdown2"];
		$args = (array(
		'post_type' => $damien_vpl_posttype,
		'orderby' => 'date', 
		'order' => $damien_vpl_order, 
		'cat' => $damien_vpl_cats2, 
		'numberposts' => $posts
		));
		global $post, $id, $blogid;
		
		/**
		 * my_posts
		 * 
		 * (default value: get_posts($args))
		 * 
		 * @var mixed
		 * @access public
		 */
		
		$vpl_current_site ='';
		$vpl_current_site = get_current_blog_id();
		$vpl_current_site .='_freetile_query';
		
		
		// Get any existing copy of our transient data
		if ( false === ( $vpl_query = get_transient( $vpl_current_site ) ) ) {
			// It wasn't there, so regenerate the data and save the transient
			$vpl_query = get_posts($args);;
			set_transient( $vpl_current_site, $vpl_query );
     }


		
		
		
        // depending on the option in the database to include featured images				
		switch ($vpl_images) {
		case 'Image Only'; // try this with a photoblog or custom post type
		$vpl_return='<div id="freetile">';			
		foreach( $vpl_query as $post ) :	setup_postdata($post);
		$vpl_return .= '<div class="fbox'.$vpl_style.'"><a href="'.get_permalink().'">'.get_the_post_thumbnail($id, 'thumbnail', array('class' => 'dsimage')).'</a></div>';
		endforeach;
		$vpl_return.='</div>';
		// return the output
		return $vpl_return;
		break;
		
		case 'Image with Text'; // the default option
		$vpl_return='<div id="freetile">';			
		foreach( $vpl_query as $post ) :	setup_postdata($post);
		$vpl_return .= '<div class="fbox'.$vpl_style.'"><a href="'.get_permalink().'">'.get_the_post_thumbnail($id, 'thumbnail', array('class' => 'dsimage')).'<div class="ftext">'.get_the_title().'</a></div></div>';
		endforeach;
		$vpl_return.='</div>';
		// return the output
		return $vpl_return;
		break;
		
		case 'Text Only'; // No Image
		$vpl_return='<div id="freetile">';			
		foreach( $vpl_query as $post ) :	setup_postdata($post);
		$vpl_return .= '<div class="fbox'.$vpl_style.'"><a href="'.get_permalink().'"><div class="ftext">'.get_the_title().'</a></div></div>';
		endforeach;
		$vpl_return.='</div>';
		// return the output
		return $vpl_return;
		break;
		
		}	
	}

/**
 * Include Freetile_myfile in Footer
 */

add_action('wp_footer','damien_vpl_set_style', 1);

function damien_vpl_set_style() {
		include("inc/freetile_myfile.js"); 
		}


/**
 * Add shortcode function for larger images. Try this with a portfolio
 * usage example 
 * [dbc_freetile_image posts=5] will show 5 posts
 * [dbc_freetile_image posts=-1] will show all posts
 * [dbc_freetile_image posts=-1 post_type=feedback] will show all posts from custom post type feedback
 * @param posts default is 10
 * @param cat default is all
 */

add_shortcode('dbc_freetile_image', 'damien_vpl_image_shortcode_handler');
 
/**
 * damien_vpl_image_shortcode_handler function.
 * 
 * @access public
 * @param mixed $atts
 * @return void
 */
function damien_vpl_image_shortcode_handler($atts) {
	extract(shortcode_atts(array(
      'posts' => 10,
      'cats' => '',
      'order' => 'DESC',
      'post_type' => '',
      'image' => ''
      ), 
      $atts));
	 $damien_vpl_cats2 = $cats;
	 $damien_vpl_posttype = $post_type;
	 $damien_vpl_order = $order;
	 $dbc_option = dbc_get_global_options();
	?>

	<!-- Visual Layouts by Damien http://wordpress.damien.co/freetile  -->
	<?php 
		$vpl_style = $dbc_option["dropdown1"];
		// $vpl_images = $dbc_option["dropdown2"];
		$args = (array(
		'post_type' => $damien_vpl_posttype,
		'orderby' => 'date', 
		'order' => $damien_vpl_order, 
		'cat' => $damien_vpl_cats2, 
		'numberposts' => $posts
		));
		global $post;
		$vpl_query = get_posts($args);
		
		
        // depending on the option in the database to include featured images				
		$vpl_return='<div id="freetile">';			
		foreach( $vpl_query as $post ) :	setup_postdata($post);
		$vpl_return .= '<div class="fbox'.$vpl_style.'"><a href="'.get_permalink().'">'.get_the_post_thumbnail($id, 'medium', array('class' => 'dsimage')).'</a></div>';
		endforeach;
		$vpl_return.='</div>';
		// return the output
		return $vpl_return;
		}




/**
 * Add the default settings to the database
 */
add_action('admin_init', 'damien_vpl_set_default_options');

function damien_vpl_set_default_options() {
	if (get_option('visual_layouts_options') === false){
		$new_options['dropdown1'] = "Yellow";
		$new_options['dropdown2'] = "Image with Text";
		$new_options['version'] = FREETILE_VERSION;
		$new_options['update'] = "Yes";
		$new_options['usage'] = "No";
		$new_options['excerpt'] = "on";
		add_option('visual_layouts_options', $new_options);
		add_option('damien_style', "Freetile");
	}
	
}


/**
 *  Register the settings and such
 */
add_action('admin_init', 'vpl_plugin_admin_init');

function vpl_plugin_admin_init(){
register_setting( 'vpl_plugin_options', 'visual_layouts_options');
add_settings_section('vpl_plugin_main', __('Plugin Settings', 'dbcfreetile'), 'plugin_section_text', 'dbc_freetile');
//add_settings_field('vpl_plugin_text_string', 'Plugin Text Input', 'plugin_setting_string', 'dbc_freetile', 'vpl_plugin_main');
add_settings_field('vpl_drop_down1', __('Colour', 'dbcfreetile'), 'vpl_setting_dropdown_fn', 'dbc_freetile', 'vpl_plugin_main');
add_settings_field('vpl_drop_down2', __('Include Featured Image', 'dbcfreetile'), 'vpl_setting_thumbnails_fn', 'dbc_freetile', 'vpl_plugin_main');
add_settings_field('vpl_drop_down4', __('Plugin Update Notifier', 'dbcfreetile'), 'vpl_setting_update_fn', 'dbc_freetile', 'vpl_plugin_main');
}

/**
 * plugin_section_text function.
 * 
 * @access public
 * @return void
 */
function plugin_section_text() {
echo '<div class="inside"><p>This plugin is free for you to use. Because I paid for a licence, you don\'t need one to use this.</p>';
}


/**
 * vpl_setting_dropdown_fn function.
 * CSS Style Dropdown
 *
 * @access public
 * @return void
 */
function vpl_setting_dropdown_fn() {
	$options = get_option('visual_layouts_options');
	$items = array("Red", "Green", "Blue", "Orange", "White", "Violet", "Yellow");
	echo "<select id='vpl_drop_down1' name='visual_layouts_options[dropdown1]'>";
	foreach($items as $item) {
		$selected = ($options['dropdown1']==$item) ? 'selected="selected"' : '';
		echo "<option value='$item' $selected>$item</option>";
	}
	echo "</select>";
}


/**
 * plugin_setting_string function.
 * Redundant Text Input - reserved for Registration Key
 *
 * @access public
 * @return void
 */
function plugin_setting_string() {
$options = get_option('visual_layouts_options');
echo "<input id='vpl_plugin_text_string' name='visual_layouts_options[text_string]' size='40' type='text' value='{$options['text_string']}' />";
}

/**
 * Thumbnails Dropdown
 */
	function vpl_setting_thumbnails_fn() {
	$options = get_option('visual_layouts_options');
	$items = array(__("Image with Text", 'dbc_freetile'), __("Image Only", 'dbc_freetile'), __("Text Only", 'dbc_freetile'));
	echo "<select id='vpl_drop_down2' name='visual_layouts_options[dropdown2]'>";
	foreach($items as $item) {
		$selected = ($options['dropdown2']==$item) ? 'selected="selected"' : '';
		echo "<option value='$item' $selected>$item</option>";
	}
	echo "</select>";
}


function vpl_setting_chk1_fn() {
	$options = get_option('visual_layouts_options');
	if($options['excerpt']) { $checked = ' checked="checked" '; }
	echo "<input ".$checked." id='plugin_chk1' name='visual_layouts_options[excerpt]' type='checkbox' />";
}


 
/**
 *  Display the Options on the Plugin page
 */

function vpl_plugin_options_page() {
?>
<form action="options.php" method="post">
<?php settings_fields('vpl_plugin_options');
	do_settings_sections('dbc_freetile'); ?>
<input class="button-primary" name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
</form>


<?php
}

// Add specific CSS class by filter

function my_class_names($classes) {
	// add 'class-name' to the $classes array
	$classes[] = 'freetile';
	// return the $classes array
	return $classes;
}

add_filter('body_class','my_class_names');



/*
 * Uninstall function
 */	
function damien_vpl_uninstall()
{	
	delete_option('visual_layouts_options');
	delete_option('damien_style');
}
register_deactivation_hook(__FILE__, 'damien_vpl_uninstall');
?>