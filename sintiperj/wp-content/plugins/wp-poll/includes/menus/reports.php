<?php	


/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

	$Poll_Query = new WP_Query( array (
		'post_type' => 'poll',
		'post_status' => 'publish',		
		'posts_per_page' => -1,
	) );
	
	$poll_id = isset( $_GET['p'] ) ? $_GET['p'] : '';
	
?>

<div class="wrap vb-wrap"> 

	<div id="icon-tools" class="icon32"><br></div>
	<h2>WP Poll - Reports</h2><br>
	
	<form id="wpp_report_form" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?> method="get">
		<input type="hidden" name="post_type" value="poll" />
		<input type="hidden" name="page" value="wpp_reports" />
		<select name="p">
			<?php
			echo sprintf('<option value="%d">%s</option>', '', __('Select a Poll', WPP_TEXT_DOMAIN ) );		
			
			if ( $Poll_Query->have_posts() ) : while ( $Poll_Query->have_posts() ) : $Poll_Query->the_post();
				$selected = $poll_id == get_the_ID() ? 'selected' : '';
				echo sprintf('<option value="%d" %s>%s</option>', get_the_ID(), $selected, get_the_title() );
			endwhile;wp_reset_query();endif;
			?>
		</select>
	</form>
	
	
	
	
	<?php if( !empty( $poll_id ) ): ?>
	<div id="report-<?php echo $poll_id; ?>" class="report_container" > 
		
		<h3 class="report_name"><i class="dashicons-before dashicons-chart-bar"></i> <?php echo get_the_title( $poll_id ); ?></h3>
		
		<?php do_action( 'wpp_admin_action_before_report', $poll_id ); ?>
		<?php do_action( 'wpp_admin_action_report', $poll_id ); ?>
		<?php do_action( 'wpp_admin_action_after_report', $poll_id ); ?>
		
	</div>
	<?php endif; ?>
	
</div>