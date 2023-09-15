<?php 
/*
*Template Name: podcasts 4
*/
?>

<?php get_header();?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<style>
	.lazy-container {
		background-color: #f6f6f6;
		background-size: cover;
		background-repeat: no-repeat;
		position: relative;
		overflow: hidden;
	}

	.lazy-container img {
		position: absolute;
		opacity: 0;
		top: 0;
		left: 0;
		width: 100%;
		transition: opacity .2s linear;
	}

	.lazy-container img.loaded {
		opacity: 1;
	}

	img.lazy-placeholder {
		opacity: 1;
		filter: blur(40px);
		/* this is needed so Safari keeps sharp edges */
		transform: scale(1);
	}
	.lazy-placeholder.loaded {
		opacity: 0;	
	}
	.lazy-text {position: absolute;}
	.lazy-item {position: relative;}
	#loading {display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;}
	#lazy-row {display: grid; grid-template-columns: auto; grid-gap: 20px;}
	.column-layout {display: grid; grid-template-columns: auto auto auto; text-align: center; margin-bottom: 20px;}
	.column-layout a {cursor: pointer; padding: 5px 10px; background: #252525; color: #f1f1f1;}
	.column-layout a:hover, .column-layout a:focus, .column-layout a:active, .column-layout a:selected {background: #1a266d; color: #f1f1f1;}
	#podcasts {margin: 20px 0;}
	.lazy-item .loading {display: none; position: absolute; top: 0; left: 0; bottom: 0; right: 0; z-index: 9; background: rgba(0,0,0,.6); width: 100%;}
	.lazy-item .loading img {width: 100%; height: auto; max-width: 20%; position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%);}
	.lazy-item .loading p {position: absolute; left: 50%; transform: translateX(-50%); color: #fff;}
	.lazy-item .loading p:first-of-type {bottom: 15%;}
	.lazy-item .loading p:last-of-type {top: 15%;}
	.lazy-item.loading .loading {display: block;}
	#lazy-row.col-1 {grid-template-columns: auto;}
	#lazy-row.col-2 {grid-template-columns: auto auto;}
	#lazy-row.col-3 {grid-template-columns: auto auto auto;}
	.col-1 .lazy-item .loading p {font-size: 28px;}
	.col-3 .lazy-item .loading p {font-size: 22px;}
	.col-3 .lazy-item .loading p {font-size: 14px;}

</style>
<?php if(have_rows('podcast_repeater')): ?>
<section id="podcasts">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="column-layout">
					<div class="column-layout-option">
						<a id="col-1">Single Column View</a>
					</div>
					<div class="column-layout-option">
						<a id="col-2">Two Column View</a>
					</div>
					<div class="column-layout-option">
						<a id="col-3">Three Column View</a>
					</div>
				</div>
				<div id="podcasts-inner">
					<div id="lazy-row">
						<?php
						$i = 0; while(have_rows('podcast_repeater')): the_row(); $i++;
						$lazyImage = get_sub_field('podcast_image');
						$paddingPercent = ($lazyImage['height'] / $lazyImage['width']) * 100;
						$heading = get_sub_field('heading');
						$link = get_sub_field('link');
						echo '<div class="col" id="'. $i .'">';//col
						echo $link ? '<a href="'. $link['url'] . ($link['target'] ? '" target="_blank">' : '">') : '';//a link conditional
						echo '<img class="img-responsive center-block" src="'. $lazyImage['url'] .'" />';//placeholder image
						echo $heading ? '<div class="lazy-text">'. $heading .'</div>' : '';//Heading overlay
						echo $link ? '</a>' : '';//a
						echo '</div>';//col
						endwhile;
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>
<?php endwhile; endif; ?>
<?php get_footer();?>

<script>
	$('#col-1').click(function(){
		$('#lazy-row').addClass('col-1');
		$('#lazy-row').removeClass('col-2');
		$('#lazy-row').removeClass('col-3');
		window.location.search = '?col-1';
	});
	$('#col-2').click(function(){
		$('#lazy-row').addClass('col-2');
		$('#lazy-row').removeClass('col-1');
		$('#lazy-row').removeClass('col-3');
		window.location.search = '?col-2';
	});
	$('#col-3').click(function(){
		$('#lazy-row').addClass('col-3');
		$('#lazy-row').removeClass('col-1');
		$('#lazy-row').removeClass('col-2');
		window.location.search = '?col-3';
	});
	if(window.location.search == '?col-1'){
		$('#lazy-row').addClass('col-1');
	}
	if(window.location.search == '?col-2'){
		$('#lazy-row').addClass('col-2');
	}
	if(window.location.search == '?col-3'){
		$('#lazy-row').addClass('col-3');
	}
</script>