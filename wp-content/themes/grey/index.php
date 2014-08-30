<? get_header(); ?>

<div class="grid_12 content-row">
	<? get_template_part( 'loop', 'index' ); ?>

	<div id="sidebar-news" class="grid_4">
	<?php include (TEMPLATEPATH . '/sidebar_news.php'); ?>
	</div>

</div>

<? get_footer(); ?>