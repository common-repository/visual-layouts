<?php
// Damien's Admin Panel

/**
 * You shouldn't be here.
 */
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * dbc_option
 * 
 * (default value: dbc_get_global_options())
 * 
 * @var mixed
 * @access public
 */
$dbc_option = dbc_get_global_options();  
?>
<div class="wrap">

<div id="icon-options-general" class="icon32"></div>
<h2>Visual Layouts</h2>
<div class="metabox-holder has-right-sidebar">

	<div class="inner-sidebar">
		
		<div class="postbox">
			<h3><span>Thanks from Damien</span></h3>
			<div class="inside">
			<p>Thanks for installing this. 
			<br /><a target="_blank" href="http://damien.co/?utm_source=WordPress&utm_medium=visual-layouts-Installed&utm_campaign=Visual-Layouts">Damien</a></p> 
			<p>Please add yourself to <a target="_blank" href="http://wordpress.damien.co/wordpress-mailing-list/?utm_source=WordPress&utm_medium=visual-layouts-Installed&utm_campaign=Visual-Layouts">my mailing list</a> to be the first to hear about updates for this plugin.</p>
			<p>Let me and your friends know you installed this:</p>
			<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://wordpress.damien.co/freetile" data-counturl="http://wordpress.damien.co/freetile" data-count="horizontal" data-via="damiensaunders">Tweet</a><script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>	
			</div>
		</div>
		<div class="postbox">
			<h3><span>Help & Support</span></h3>
			<div class="inside">
			<ul>
			<li><a target="_blank" href="http://wordpress.damien.co/freetile/?utm_source=WordPress&utm_medium=visual-layouts-Installed&utm_campaign=Visual-Layouts">Help & FAQ's</a></li>
			<li><a target="_blank" href="http://wordpress.damien.co/?utm_source=WordPress&utm_medium=visual-layouts-Installed&utm_campaign=Visual-Layouts">More WordPress Tips & Ideas</a></li>
			</ul>
			</div>
		</div>
		<div class="postbox">
			<h3><span>Plugin Suggestions</span></h3>
			<div class="inside">
			<p>Here's another plugin of mine that I think you'll need.</p>
			<ul>
			<li><a target="_blank" href="http://wordpress.damien.co/dbc-backup-2/?utm_source=WordPress&utm_medium=visual-layouts-Installed&utm_campaign=Visual-Layouts">DBC Backup 2</a> - in the Top 10 Backup Plugins on WordPress. Secure and easy backup for your WordPress SQL database. Automated schedule and delete older backups.</li>
			</ul>
			</div>
		</div>			
		
		<!-- ... more boxes ... -->
	
	</div> <!-- .inner-sidebar -->

	<div id="post-body">
		<div id="post-body-content">
		
		<div class="postbox">
		<h3><span><?php _e('Get Started with Visual Layouts', 'dbc_freetile'); ?></span></h3>
		<div class="inside">
		<p>I've made this free plugin as easy to use as possible. There is no code to change or files to move. So please enjoy.</p>
		<ol>
		<li>Add a <a target="_blank" href="post-new.php?post_type=page">new Page</a></li>
		<li>Add the shortcode [dbc_freetile]</li>
		<li>Remember to give your page a name</li>
		<li>Publish the page</li>
		<li>View your page with freetile</li>
		</ol>
		</div> <!-- .inside -->
		</div>
		<div class="postbox">

		
		<?php vpl_plugin_options_page(); ?>
		
		
		</div> <!-- .inside -->
		</div>
		<div class="postbox">
		<h3><span><?php _e('Shortcode Options', 'dbc_freetile'); ?></span></h3>
		<div class="inside">
		<p>You can configure the number of posts to show. Here are a couple of examples</p>
		<ul>
		<li> [dbc_freetile posts=5] will show 5 posts</li>
		<li> [dbc_freetile posts=-1] will show all posts</li>
		<li> [dbc_freetile posts=-1 post_type=feedback] will show all posts from custom post type feedback</li>
		<li> [dbc_freetile order=DESC] defaults to most recent posts first but you can change this to ASC to go with oldest.
		
		</ul>
		</div> <!-- .inside -->
		</div>
		
		</div> <!-- #post-body-content -->
	</div> <!-- #post-body -->

</div> <!-- .metabox-holder -->

</div> <!-- .wrap -->