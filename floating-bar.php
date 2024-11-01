<?php
/*
Plugin Name: Webloggerz WP Floating Footer Bar
Plugin URI: http://webloggerz.com/
Description: This plugin creates a floating footer bar to display random posts from selected categories or via HTML code. Also included social media links.
Version: v1.0
Author: Ansh Gupta
Author URI: http://webloggerz.com/
License: GPLv2
*/

/*  Copyright 2013  Ansh Gupta 
	You need written confirmation by Anshit Gupta before using or modifying
	the code in any of your project.

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if (!defined('MYPLUGIN_THEME_DIR'))
    define('MYPLUGIN_THEME_DIR', ABSPATH . 'wp-content/themes/' . get_template());

if (!defined('MYPLUGIN_PLUGIN_NAME'))
    define('MYPLUGIN_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));
	
if (!defined('MYPLUGIN_PLUGIN_DIR'))
    define('MYPLUGIN_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . MYPLUGIN_PLUGIN_NAME);
	
if (!defined('MYPLUGIN_PLUGIN_URL'))
    define('MYPLUGIN_PLUGIN_URL', WP_PLUGIN_URL . '/' . MYPLUGIN_PLUGIN_NAME);
	
// create custom plugin settings menu
add_action('admin_menu', 'wwffb_floatingbar_create_menu');
$wwffb_flb_settings = get_option('wwffb_flb_settings');

function wwffb_floatingbar_create_menu() {

	//create new top-level menu
	add_menu_page('Floating Bar', 'Floating Bar', 'administrator', __FILE__, 'wwffb_floatingbar_settings_page',plugins_url('/images/icon.png', __FILE__));

	//call register settings function
	add_action( 'admin_init', 'wwffb_register_mysettings' );
	
	
}


function wwffb_register_mysettings() {
	//register our settings	
	register_setting( 'wwffb_flb_settings_group', 'wwffb_flb_settings' );
}

function wwffb_floatingbar_settings_page() {
?>
<div class="wrap">
<h2>Floating Bar</h2>

<form method="post" action="options.php">
    <?php settings_fields('wwffb_flb_settings_group'); ?>
    <?php global $wwffb_flb_settings; ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Method</th>
        <td>
        
        <?php
		
			$automatic = ($wwffb_flb_settings['mode'] =='automatic' || !$wwffb_flb_settings['mode']) ? 'checked' : ''; 
			$manual = ($wwffb_flb_settings['mode'] =='manual') ? 'checked' : '';
		
		?>
        
        <input class="mode" id = "automatic"  type="radio" name="wwffb_flb_settings[mode]" value="automatic" <?php echo $automatic; ?> > <label for="automatic">Automatic </label>
        <input class="mode" id = "manual" type="radio" name="wwffb_flb_settings[mode]" value="manual" <?php echo $manual; ?> > <label for="manual">Manual</label>
        
        </td>
        </tr>
        
        <tr valign="top" class="html-wrapper">
        <th scope="row">Enter HTML</th>
        <td>
        
        <textarea rows="4" cols="50"  name="wwffb_flb_settings[html]"><?php echo $wwffb_flb_settings['html']; ?></textarea> 
        </td>
        </tr>   
        
        <tr valign="top" class="cat-wrapper">
        <th scope="row">Enter Category ID</th>
        <td><input type="text" name="wwffb_flb_settings[cat]" value="<?php echo $wwffb_flb_settings['cat']; ?>" /></td>
        </tr>              
        
        <tr valign="top">
        	<td colspan="2"><strong>Social Links</strong></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Facebook URL</th>
        <td>
            <textarea rows="4" cols="50"  name="wwffb_flb_settings[facebook]"><?php echo $wwffb_flb_settings['facebook']; ?></textarea> 
            <br />( Example: https://www.facebook.com/webloggerz )
        </td>
        </tr>
        <tr valign="top">
        <th scope="row">Twitter Username</th>
        <td>
            <textarea rows="4" cols="50"  name="wwffb_flb_settings[twitter]"><?php echo $wwffb_flb_settings['twitter']; ?></textarea>
            <br /> ( Example: webloggerz )
        </td>
        </tr>        
        
        <tr valign="top">
        <th scope="row">Google+ Page URL</th>
        <td>
            <textarea rows="4" cols="50"  name="wwffb_flb_settings[google]"><?php echo $wwffb_flb_settings['google']; ?></textarea>
            <br /> ( Example: https://plus.google.com/100302322511288585262 )
        </td>
        </tr>
    </table>
    <input type="submit" class="button-primary" value="<?php _e('Save Settings') ?>" />
    <?php //submit_button(); ?>

</form>
</div>
<?php } 


function wwffb_floating_bar($args = array(), $content = null) {
	global $wwffb_flb_settings; 
	?>
    <div class="fixedbar" >
    <div class="floatingbox">
      <ul id="tips">
        <li style="float: left;">
	
    <?php
    if($wwffb_flb_settings['mode'] =='automatic'){ 
		$args = array(
		'post_type' => 'post',
		'cat'  => $wwffb_flb_settings['cat'],
		'posts_per_page' => 1,
		'orderby' => 'rand'				
		);
		

	
		$myPosts = new WP_Query();
		$myPosts->query($args);
		
		while ($myPosts->have_posts()) : $myPosts->the_post(); ?>
			<a href='<?php the_permalink() ?>' title='<?php the_title(); ?>'><?php the_title(); ?></a>
		<?php endwhile; 
		
		//Reset Query
		wp_reset_query();
	}
	else{
		echo htmlspecialchars_decode($wwffb_flb_settings['html']); 
	}
	 ?>
    </li>
    
    
    <?php if(!empty($wwffb_flb_settings['google'])) :?>
    <li>
  
    

   
<!-- Place this tag in your head or just before your close body tag. -->
<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>

<!-- Place this tag where you want the +1 button to render. -->
<div class="g-follow" data-annotation="bubble" data-height="20" data-href="<?php echo $wwffb_flb_settings['google']; ?>"></div>    

    </li> 
    <?php endif; ?>
    
    <?php if(!empty($wwffb_flb_settings['twitter'])) :?>
	<li>
    
    
        <a href="https://twitter.com/<?php echo $wwffb_flb_settings['twitter']; ?>" class="twitter-follow-button" data-show-count="false" data-lang="en" data-show-screen-name="false">Follow</a>

    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
    
    
    </li><?php endif; ?>
    
	<?php if(!empty($wwffb_flb_settings['facebook'])) :?>
    <li>

    <div class="fb-like" data-href="<?php echo $wwffb_flb_settings['facebook']; ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>
    
    </li>
 	<?php endif; ?>

      </ul>
    </div>
 </div>
    <?php 
	if(!empty($wwffb_flb_settings['facebook'])) : ?>
	
	    <div id="fb-root"></div>
	<script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    
    <?php
	endif;
}


function wwffb_admin_inline_js(){ ?>


<?php global $wwffb_flb_settings; ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('.mode').on("change", function() {
		var val = $(this).val();
		if(val == "manual"){
			$('.html-wrapper').show();
			//$('.cat-wrapper input').val('');
			$('.cat-wrapper').hide();
		}
		else if(val == "automatic"){
			//$('.html-wrapper input').val('');
			$('.cat-wrapper').show();
			$('.html-wrapper').hide();	
		}
	});
	<?php 
	if($wwffb_flb_settings['mode'] !='manual'){ ?>
		$('.cat-wrapper').show();
		$('.html-wrapper').hide();
	<?php }
	else{ ?>
		$('.cat-wrapper').hide();
		$('.html-wrapper').show();
	<?php }?>
	
});
</script>
<?php }
add_action( 'admin_print_scripts', 'wwffb_admin_inline_js', 100 );
add_action( 'wp_head', 'wwffb_flb_style' );

function prefix_on_deactivate() {
       delete_option('wwffb_flb_settings');
}

register_deactivation_hook(__FILE__, 'prefix_on_deactivate');

function wwffb_flb_style() { 
wp_enqueue_script( 'jquery');	
?>
	
<style type="text/css" media="screen">

</style>
	
<?php }
add_action( 'wp_footer', 'wwffb_floating_bar' );

add_action('wp_enqueue_scripts', 'wwffb_myplugin_styles');

function wwffb_myplugin_styles() {
    $handle = 'myplugin-css';
    $src = MYPLUGIN_PLUGIN_URL . '/style.css';

    wp_register_style($handle, $src);
    wp_enqueue_style($handle);
}