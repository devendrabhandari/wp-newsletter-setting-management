<?php
/*
Plugin Name: Newsletter Management
Description: Manage Newsletter Setting.
Version: 2.0
License: GPL
Author: Devendra Singh Bhandari
Author URI: http://www.linkedin.com/in/devendrasinghbhandari
*/

// Add new submenu of 'Settings' in the admin dashboard
add_action( 'admin_menu', 'showNewsletterSettingMenu' );
function showNewsletterSettingMenu()
{
	add_options_page('Newsletter Setting', 'Newsletter', 'manage_options', 'newsletter-setting', 'newsletterSetting');

	// Call register settings function
	add_action( 'admin_init', 'registerNewsletterSetting' );
}

// Register newsletter setting
function registerNewsletterSetting()
{
	register_setting( 'newsletter_setting_options_group', 'newsletter_box1' ); 
	register_setting( 'newsletter_setting_options_group', 'newsletter_box2' ); 
	register_setting( 'newsletter_setting_options_group', 'newsletter_box3' ); 
} 

function newsletterSetting()
{
	if ( !current_user_can( 'administrator' ) )	
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );

	// Get list of posts
	$args_posts = array(
	'posts_per_page'   => -1,
	'offset'           => 0,
	'category'         => '',
	'orderby'          => 'title',
	'order'            => 'ASC',
	'include'          => '',
	'exclude'          => '',
	'post_type'        => 'post',
	'post_mime_type'   => '',
	'post_parent'      => '',
	'post_status'      => 'publish',
	'suppress_filters' => true );
	$posts = get_posts( $args_posts );

	global $blogObj;

	// Create connection manually with different database
	$blogObj = new wpdb('XXXX' , 'XXXX' , 'XXXX' , 'XXXX');

	$query = "
	SELECT wp_posts.ID, wp_posts.post_title
	FROM wp_posts 
	WHERE 1=1 
	AND wp_posts.post_type = 'post'
	AND ((wp_posts.post_status = 'publish')) 
	ORDER BY wp_posts.post_title ASC
	";
	$posts = $blogObj->get_results($query);
	?>
	<div class="wrap">
		<h2>Newsletter Settings</h2>
		<form method="post" action="options.php">
			<?php settings_fields( 'newsletter_setting_options_group' ); ?>
			<?php do_settings_sections( 'newsletter_setting_options_group' ); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Box 1</th>
					<td>
						<?php $post_ids = get_option('newsletter_box1'); ?>
						<select name="newsletter_box1[]" class="regular-text" style="height:400px;" multiple>
						<?php
						foreach($posts as $post) {
							if (in_array($post->ID, $post_ids))
								echo '<option value="'.$post->ID.'" selected>'.$post->post_title.'</option>';
							else
								echo '<option value="'.$post->ID.'">'.$post->post_title.'</option>';
						}
						?>
						</select>
						<p class="description">Select single/multiple blog post from multiple selection dropdown box.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Box 2</th>
					<td>
						<?php $post_ids = get_option('newsletter_box2'); ?>
						<select name="newsletter_box2[]" class="regular-text" style="height:400px;" multiple>
						<?php
						foreach($posts as $post) {
							if (in_array($post->ID, $post_ids))
								echo '<option value="'.$post->ID.'" selected>'.$post->post_title.'</option>';
							else
								echo '<option value="'.$post->ID.'">'.$post->post_title.'</option>';
						}
						?>
						</select>
						<p class="description">Select single/multiple blog post from multiple selection dropdown box.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Webinars</th>
					<td>
						<?php $total_webinars = get_option('newsletter_box3'); ?>
						<select name="newsletter_box3" class="regular-text">
							<option value="1" <?php if($total_webinars==1) echo "selected"; ?>>1</option>
							<option value="2" <?php if($total_webinars==2) echo "selected"; ?>>2</option>
							<option value="3" <?php if($total_webinars==3) echo "selected"; ?>>3</option>
							<option value="4" <?php if($total_webinars==4) echo "selected"; ?>>4</option>
							<option value="5" <?php if($total_webinars==5) echo "selected"; ?>>5</option>
							<option value="6" <?php if($total_webinars==6) echo "selected"; ?>>6</option>
							<option value="7" <?php if($total_webinars==7) echo "selected"; ?>>7</option>
							<option value="8" <?php if($total_webinars==8) echo "selected"; ?>>8</option>
							<option value="9" <?php if($total_webinars==9) echo "selected"; ?>>9</option>
							<option value="10" <?php if($total_webinars==10) echo "selected"; ?>>10</option>
						</select>
						<p class="description">Select number of upcoming webinars to show.</p>
					</td>					
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
