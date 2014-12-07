
<?php get_header();?>

<?php wc_print_notices(); ?>

<div id="look-book" class="template template-page">	
	
	<section id="look-book-introduction" class="look-book-introduction block">	
	
		<div class="products">
			<div class="container">
				<?php  if ( is_user_logged_in() ) : ?>
				<div class='row filter'>
					<div class='col-sm-6'></div>
					<div class='col-sm-2'><a href="#">Owned</a></div>
					<div class='col-sm-2'><a href="#">Shares Available</a></div>
					<div class='col-sm-2'><a href="#">Purchase Available</a></div>
				</div>
				<?php endif; ?>
				
				<div class="row">
				<?php
				$args = array(
					'post_type' => 'dress',
					'posts_per_page' => -1,
					'post__not_in' => array(45)
				);

				$GLOBALS['LOOP'] = new WP_Query( $args );
				$GLOBALS['USER'] = wp_get_current_user();

				if ( $GLOBALS['LOOP']->have_posts() ) {

					while ( $GLOBALS['LOOP']->have_posts() ) : 
					 	get_template_part( '_partials/dress/dress', 'card' );
					endwhile;

				} else { 
					echo '<h1>There aren\'t any dresses right now!</h1>';
				} 



				?>
				</div>
			</div>
		</div>
			
								
	</section>	
	
</div>	

<?php get_footer(); ?>
