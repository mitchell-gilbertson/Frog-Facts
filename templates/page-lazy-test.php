<?php /* Template Name: Lazy Test */

if(have_posts()) : while(have_posts()): the_post(); get_header(); ?>
<?php if(have_rows('image_repeater')): ?>
<section id="podcasts">
	<div class="container">
		<div class="row">
			<?php while(have_rows('image_repeater')): the_row(); $image = get_sub_field('image'); ?>
			<div class="col-xs-12">
				<?php $lazyShort = "[lazyimg img='". serialize($image) ."']"; echo do_shortcode($lazyShort); ?>
			</div>
			<?php endwhile; ?>
		</div>
	</div>
</section>
<?php endif; ?>
<?php get_footer(); endwhile; endif; ?>
