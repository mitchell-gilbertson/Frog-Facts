<?php if(have_posts()): while(have_posts()): the_post(); get_header();
$banner = get_field('banner_image'); $mainHeading = get_field('main_heading'); $mainContent = get_field('main_content');  ?>
<?php echo $banner ? '<div id="banner" class="banner"><div class="banner-inner"><img src="'. $banner['url'] .'" alt="'. $banner['alt'] .'" title="'. $banner['title'] .'" class="img-responsive center-block" /></div></div>' : ''; ?>
<div id="main-section" class="main-section">
  <div class="container main-inner">
	<?php echo $mainHeading ? '<h1>'. $mainHeading .'</h1>' : ''; ?>
	<?php echo $mainContent ? $mainContent : ''; ?>
	<?php the_content(); ?>
  </div>
</div>
<?php get_footer(); endwhile; endif; ?>
