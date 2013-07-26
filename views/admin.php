<?php
/**
 * Represents the view for the administration dashboard.
 *
 *
 * @package   Search_Keyword_Redirect
 * @author    Nick Pelton <nick@werkpress.com>
 * @license   GPL-2.0+
 * @link      http://werkpress.com/plugin
 * @copyright 2013 Nick Pelton or Werkpress
 */
?>
<div class="wrap">

	<?php // screen_icon(); ?>
	<h2><?php _e('Search Keyword Redirects'); ?></h2>

	<div class="wrap ww-keyword-redirect">
			<h3><?php _e('Overview'); ?></h3>
			<p><?php _e('This plugin Matches search queries to keywords. On a match it redirects to specific URLs.'); ?> <br><?php _e('Note, this means any match for a non case-sensative string.');?></p>
			<p><strong><?php _e('Example'); ?>:</strong> <?php _e('The plugin will redirect with these sample search queries for the keyword <span class="hl">test</span>'); ?>:</p>

			<ol>
				<li><span class="hl">test</span></li>
				<li><span class="hl">Test</span></li>
				<li>Search for <span class="hl">test</span> number 450</li>
				<li>dfsd<span class="hl">test</span>fsdfsd</li>
			</ol>

			
			<h3><?php _e('Create Redirects'); ?></h3>
			<ul>
				<li><?php _e('To create redirects, enter the keyword and the URL destination of the keyword and click save.'); ?></li>
				
			</ul>
			
		
			<h2><?php _e('Current Redirects'); ?></h2>
			<form method="post" action="">
			<div  class="redirects">
				<table class="redirect_table">
					<tr>
						<th><?php _e('Keyword'); ?></th>
						<th><?php _e('URL Destination'); ?></th>
						
					</tr>
					<tr>
						<td><small><?php _e('example'); ?>: <?php _e('Keyword'); ?> </small></td>
						<td><small><?php _e('example'); ?>: <?php echo get_option('home'); ?>/destination-url/</small></td>
						
					</tr>
					<?php echo $this->get_keyword_redirects(); ?>
					

				</table>
				<a href="#" class="add_redirect button" title="Add redirect">Add Redirect</a>
			</div>
			<br/>
			<script type="text/template" class="template" id="redirect">
					<tr>
						<td><input type="text" name="ww_keyword_redirects[request][]" value="" style="width:15em" />&nbsp;&raquo;&nbsp;</td>
						<td><input type="text" name="ww_keyword_redirects[destination][]" value="" style="width:30em;" /><!-- &nbsp;&raquo;&nbsp; --></td>
						<!-- <td><input type="text" name="ww_keyword_redirects[used][]" value="" style="width:10em;" readonly /></td> -->
						<td><a href="#" class="delete_redirect button" title="Delete Redirect"><?php _e('Delete Redirect'); ?><a/></td>
					</tr>
			</script>
			
			<p class="submit">
			<?php wp_nonce_field('ww_submit_save_form', 'ww_keyword_redirects_nonce' ); ?>
			<input type="submit" name="submit_keywords" class="button-primary" value="<?php _e('Save Changes') ?>" /> 
			<input type="button" name="cancel_changes" class="button cancel_changes" value="<?php _e('Cancel') ?>" />
			</p>
			</form>
		</div>

</div>
