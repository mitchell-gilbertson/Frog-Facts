<?php /* Template Name: Blog Page */ if(have_posts()): while(have_posts()): the_post(); get_header();
$banner = get_field('banner_image'); $mainHeading = get_field('main_heading'); $mainContent = get_field('main_content');
$the_query = new WP_Query(array('orderby' => 'date','order' => 'ASC', 'paged' => get_query_var('paged'))); ?>
<?php echo $banner ? '<div id="banner" class="banner"><div class="banner-inner"><img src="'. $banner['url'] .'" alt="'. $banner['alt'] .'" title="'. $banner['title'] .'" class="img-responsive center-block" /></div></div>' : ''; ?>
<div id="main-section" class="main-section">
	<div class="container main-inner">
		<div class="row">
			<section class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<?php echo $mainHeading ? '<h1>'. $mainHeading .'</h1>' : ''; ?>
				<?php echo $mainContent ? $mainContent : ''; ?>
			</section>
		</div>
	</div>
</div>
<div class="blog-section">
    <div class="blog-section-inner container">
		<div class="flex-row row-md blogs-row">
			<?php if($the_query->have_posts()): ?>
            <div class="col blogs-col">
				<div class="blogs">
	                <?php while($the_query->have_posts()): $the_query->the_post(); $image = get_field('featured_image'); $content = get_field('main_content'); $heading = get_field('main_heading'); $excerpt = getTextExcerpt($content); $link = get_permalink($post->ID); ?>
	                <div class="blog">
	                    <?php echo $link ? '<a href="'. $link .'" title="'. $heading .'">' : ''; ?>
						<div class="flex-row row-sm blog-inner">
							<?php echo $image ? '<div class="col image-col"><img class="img-responsive center-block" src="'. $image['url'] .'" alt="'. $image['alt'] .'" title="'. $image['title'] .'"  /></div>' : '';
							echo $heading || $excerpt ? '<div class="col content-col">'. ($heading ? '<div class="heading"><h2>'. $heading .'</h2></div>' : '') . ($excerpt ? '<div class="content">'. $excerpt .'</div>' : '') .'</div>' : ''; ?>
						</div>
	                    <?php echo $link ? '</a>' : ''; ?>
	                </div>
	                <?php endwhile; wp_reset_query(); ?>
				</div>
				<div class="pagination">
					<?php pagination_bar(); ?>
				</div>
            </div>
			<?php endif; ?>
            <div class="col sidebar-col">
                <div class="sidebar">
                    <?php is_active_sidebar('blog-sidebar') ? dynamic_sidebar('blog-sidebar') : ''; ?>
                </div>
            </div>
		</div>
    </div>
</div>
<?php get_footer(); endwhile; endif; ?>
