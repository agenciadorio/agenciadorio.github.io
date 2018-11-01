<?php
/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

	$poll_meta_options = get_post_meta( $post->ID, 'poll_meta_options', true );
	$poll_deadline = get_post_meta( $post->ID, 'poll_deadline', true );
	$poll_meta_multiple = get_post_meta( $post->ID, 'poll_meta_multiple', true );
	$poll_meta_new_option = get_post_meta( $post->ID, 'poll_meta_new_option', true );

	if( empty( $poll_meta_options ) ) { $poll_meta_options = array( time() => '' ); }
	if( empty( $poll_meta_multiple ) ) { $poll_meta_multiple = 'no'; }
	if( empty( $poll_meta_new_option ) ) { $poll_meta_new_option = 'no'; }
	
	// echo '<pre>'; print_r( $poll_meta_options ); echo '</pre>';

?>
<div class="poll_meta_box">
	
<a class="button button-orange poll_view_link" href="<?php echo get_the_permalink(); ?>" target="_blank">View Poll</a>
		
	<div class="section-box">
		<p class="section-title">Poll Title</p>
		<div class="section-box">
			<input class="poll_title" type="text" name="post_title" placeholder="Poll title" value="<?php echo get_the_title(); ?>" />
		</div>
	</div>
	
	<div class="section-box">
		<p class="section-title">Poll Options</p>
		<div class="section-box">
			<div class="button add_new_option">Add New</div>
			<ul class="option-box poll_option_container">
			<?php foreach( $poll_meta_options as $option_id => $option_value ) { ?>
				<li class="poll_option_single <?php $option_id; ?>">
					<span>Option Value</span>
					<input type="text" name="poll_meta_options[<?php echo $option_id; ?>]" value="<?php echo $option_value; ?>"/>
					<div class="poll_option_remove" status=0><i class="fa fa-times" aria-hidden="true"></i></div>
					<div class="poll_option_single_sorter"><i class="fa fa-sort" aria-hidden="true"></i></div>
				</li>
			<?php } ?>
			</ul>
		</div>
	</div>
	
	<div class="section-box">
		<p class="section-title">Poll Content</p>
		<div class="section-box">
			<?php
			wp_editor( $post->post_content,'poll_content_editor',
				$settings = array(
					'textarea_name' => 'content',
					'textarea_rows' => 10,
				)
			);
			?>
		</div>
	</div>
	
	
	<div class="section-box">
		
		<p class="section-title">Poll Settings</p>
		
		<div class="section-box">
			<div class="section-box-label">Poll Shortcode | Using this you can place this Poll anywhere you want.</div>
			<input id="wp_poll_shortcode" type="text" value="[poll id=<?php echo get_the_ID(); ?>]" disabled="disabled" style="background:#ddd;" />
			<span class="button" onclick="copyToClipboard('#wp_poll_shortcode')">Copy it</span>
		</div>
		
		<div class="section-box">
			<div class="section-box-label">Poll Deadline | Leave this empty if No Deadline</div>
			<input type="text" name="poll_deadline" placeholder="<?php echo date('d-m-Y'); ?>" id="poll_deadline" value="<?php echo $poll_deadline; ?>" />
		</div>
		
		<div class="section-box">
			<div class="section-box-label">Allow Multiple Polling | Default is No - Not Allow</div>
			<select name="poll_meta_multiple">
				<option <?php if( $poll_meta_multiple == 'yes' ) echo 'selected'; ?> value="yes">Yes - Allow</option>
				<option <?php if( $poll_meta_multiple == 'no' ) echo 'selected'; ?> value="no">No - Not Allow</option>
			</select>
		</div>
		
		<div class="section-box">
			<div class="section-box-label">Allow Visitors to add New Option | Default is No - Not Allow</div>
			<select name="poll_meta_new_option">
				<option <?php if( $poll_meta_new_option == 'yes' ) echo 'selected'; ?> value="yes">Yes - Allow</option>
				<option <?php if( $poll_meta_new_option == 'no' ) echo 'selected'; ?> value="no">No - Not Allow</option>
			</select>
		</div>
		
		
	</div>
	
	
	
	<div class="section-box">
		<p class="section-title">Poll Statistics</p>
		<div class="section-box">
			<a class="button" href="edit.php?post_type=poll&page=wpp_reports&p=<?php echo $post->ID; ?>">Get Results</a>
		</div>
	</div>
	
</div>
	















