<?php
/**
* Plugin Name: Custom Admin Widget Titles
* Description: This plugin allows you to input nice looking and descriptive widget titles in your admin widget area.
* Version: 1.8
* Author: Geraldo Pena Perez
* License: GPLv2 or later
* Author URI: https://profiles.wordpress.org/geraldopena/
* Requires at least: 3
* Tested up to: 5.6
* Requires PHP: 5
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if(!function_exists('customadminwidgettitles2021_activationmessage')){
	function customadminwidgettitles2021_activationmessage() {
		set_transient( 'customadminwidgettitles2021_adminnotice', true, 5 );
	}
	register_activation_hook( __FILE__, 'customadminwidgettitles2021_activationmessage' );
}

if(!function_exists('customadminwidgettitles2021_welcomemessage')){
	function customadminwidgettitles2021_welcomemessage(){
		if(get_transient('customadminwidgettitles2021_adminnotice')){
			?>
			<div class="updated notice is-dismissible">
				<h3>Thank you for activating Custom Admin Widget Titles plugin!</h3>
				<p style="font-size: 1.2em;">This plugin is completely free for you to use. Just remember to show a small token of appreciation and invite me a <a href="https://www.paypal.com/donate/?business=CQS2XGFWJ5A7C&currency_code=CAD">coffee!</a>.</p>
			</div>
			<?php
		   delete_transient('customadminwidgettitles2021_adminnotice');
		}
	}
	add_action('admin_notices', 'customadminwidgettitles2021_welcomemessage');
}

if(!function_exists('customadminwidgettitles2021_enqueuejs')){
	function customadminwidgettitles2021_enqueuejs(){
		if(is_admin()){
			wp_enqueue_script('admin-jquery-functions', plugin_dir_url( __FILE__ ).'js/admin_functions.js', array('jquery'), false, true);
		}
	}
	add_action('admin_enqueue_scripts', 'customadminwidgettitles2021_enqueuejs');
}

if(!function_exists('customadminwidgettitles2021_customlink')){
	function customadminwidgettitles2021_customlink($links, $file) {
		if(strpos( $file, 'custom-admin-widget-titles.php') !== false){
			$new_links = array(
				'<a href="https://www.paypal.com/donate/?business=CQS2XGFWJ5A7C&currency_code=CAD">BUY ME A COFFEE!</a>'
				);
			$links = array_merge( $links, $new_links );
		}
		return $links;
	}
	add_filter('plugin_row_meta', 'customadminwidgettitles2021_customlink', 10, 2);
}

if(!function_exists('customadminwidgettitles2021_addwidgettitlefield')){
	function customadminwidgettitles2021_addwidgettitlefield($t,$return,$instance){
		$instance = wp_parse_args((array) $instance, array( 'widget_title' => ''));
		if(!isset($instance['widget_title'])){
			$instance['widget_title'] = '';
		}
		?>
		<p>
			<hr />
			<div style="display: table; width: 100%;">
				<label for="<?php echo $t->get_field_id('widget_title'); ?>" style="display: table-cell; width: 1%; white-space: nowrap;">
					<?php _e('Widget Title: ','digitalmasta'); ?>&nbsp;
				</label>
				<input id="<?php echo $t->get_field_id('widget_title'); ?>" type="<?php echo esc_attr('text'); ?>" name="<?php echo $t->get_field_name('widget_title'); ?>" class="DM-custom-widget-title" style="display: table-cell; width: 100%;" value="<?php echo $instance['widget_title']; ?>" />
			</div>
			<hr />
		</p>
		<?php
		$retrun = null;
    	return array($t,$return,$instance);
	}
	add_action('in_widget_form', 'customadminwidgettitles2021_addwidgettitlefield',5,3);
}

if(!function_exists('customadminwidgettitles2021_savewidgettitlefield')){
	function customadminwidgettitles2021_savewidgettitlefield($instance, $new_instance, $old_instance){
		if(!empty($new_instance['widget_title'])){
			$pre_process = strip_tags($new_instance['widget_title']);
			$res = preg_replace("/[^a-zA-Z0-9\- ]/", "", $pre_process);
			$instance['widget_title'] = $res;
		}else{
			$instance['widget_title'] = '';
		}
		return $instance;
	}
	add_filter('widget_update_callback', 'customadminwidgettitles2021_savewidgettitlefield',5,3);
}

if(!function_exists('customadminwidgettitles2021_changewidgettitle')){
	function customadminwidgettitles2021_changewidgettitle($params){
		global $wp_registered_widgets;
		$widget_id = $params[0]['widget_id'];
		$widget_obj = $wp_registered_widgets[$widget_id];
		
		$option_name = $wp_registered_widgets[$widget_id]['_callback'][0]->option_name;
		$the_key = $params[1]['number'];
		$widget_data = get_option($option_name);
		$output = $widget_data[$the_key];
		
		if(is_admin()){
			if(!empty($output['widget_title'])){
				$params[0]['widget_name'] = $output['widget_title'];
			}
		}
		return $params;
	}
	add_filter('dynamic_sidebar_params', 'customadminwidgettitles2021_changewidgettitle', 20);
}

?>