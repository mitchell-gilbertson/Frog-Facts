<?php get_header(); ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title><?php echo $type; ?> fact</title>
		<style>
			.fact-section {
				position: fixed;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
				color: #fff;
				text-shadow: 2px 2px 2px rgb(0 0 0 / 30%);
				perspective: 1000px;
			}
			.fact {
				text-align: center;
				font-size: 32px;
			}
			h1.type {
				text-align: center;
				font-size: 42px;
				position: relative;
				top: 0;
				left: 0%;
				transform-style: preserve-3d;
				//animation-name: wee;
				animation-duration: 3s;
				animation-fill-mode: forwards;
				animation-iteration-count: infinite;
				animation-play-state: running;
				transition: transform ease-in-out 3s;
				animation-direction: alternate-reverse;
				animation-timing-function: ease-in-out;
			}
			.new-fact {
				text-align: center;
				margin-top: 50px;
			}
			.btn {
				border: none;
				outline: none;
				box-shadow: none;
				background: #628395;
				color: #fff;
				padding: 0.75em 1.5em;
				font-size: 22px;
				box-shadow: 4px 4px 10px 0px rgba(0,0,0,.5);
				transition: all ease .5s;
				border-radius: 4px;
				text-shadow: 1px 1px 3px rgba(0,0,0,.5);
			}
			.btn:hover {
				background: #CF995F;
				cursor: pointer;
			}
			.btn:focus {
				transform: translate(2px, 2px);
				box-shadow: 0px 0px 8px 0px rgba(0,0,0,.5);
			}
			h1 {
				text-transform: capitalize;
			}
			.frog {
				background-size: 100% auto;
				background-position: 50% 50%;
				position: fixed;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				z-index: -1;
			}
			.frog:after {
				content: '';
				position: absolute;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				background-image: linear-gradient(to bottom, rgba(0,0,0,0) 10%, rgba(0,0,0,0.5) 40%, rgba(0,0,0,0.5) 60%, rgba(0,0,0,0) 90%);
			}
			.fact-types {
				display: flex;
				justify-content: flex-end;
			}
			.fact-types select {
				padding: 6px;
				border: none;
				border-radius: 5px;
				background-color: rgba(255,255,255,.4);
				color: #252525;
				font-weight: 700;
				cursor: pointer;
			}
			.fact-types option {
				padding: 15px;
				display: block;
				color: #252525;
				font-weight: 700;
			}
			@media (max-aspect-ratio: 3/4) {
				.frog {
					background-size: auto 100%;
				}
			}

			@keyframes wee {
				from {
					opacity: 1;
					transform: rotateY(-10deg) translateZ(-100px) translateX(-50%);
				}
				to {
					opacity: 1;
					transform: rotateY(10deg) translateZ(100px) translateX(-50%);
				}
			}
			a {
				color: #fff;
			}
			.types {
				display: flex;
				flex-wrap: wrap;
				justify-content: center;
			}
			.type {
				flex: 0 1 calc(33.33% - 20px);
				margin-bottom: 30px;
			}
			.type:not(:nth-child(3n)):not(:last-child) {
				margin-right: 30px;
			}
			.type img {
				max-width: 100%;
				display: block;
				margin: 0 auto;
			}
			.type a {
				display: block;
				text-decoration: none;
			}
			.type .heading {
				text-align: center;
				background-color: #4B543B;
				color: #fff;
				margin: 0;
				padding: 10px;
			}
		</style>
	</head>
	<body>
		<?php
		$myQuery = new WP_Query(array('post_type' => 'facts', 'posts_per_page' => '-1', 'category_name' => 'uncategorized'));
		if($myQuery->have_posts()):
			echo '<div class="types">';
			while($myQuery->have_posts()):
				$myQuery->the_post();
				$pageTitle = $post->post_title;
				$pageLink = $post->post_name;
				$image = get_field('fact_image'); ?>
				<div class="type">
					<?php echo $pageLink ? '<a href="/facts/'. $pageLink .'">' : '';
					echo $image ? '<div class="image"><img class="img-responsive center-block" src="'. $image['url'] .'" alt="'. $image['alt'] .'" title="'. $image['title'] .'" /></div>' : '';
					echo $pageTitle ? '<h2 class="heading">'. $pageTitle .'</h2>' : '';
					echo $pageLink ? '</a>' : ''; ?>
				</div>
			<?php endwhile;
			wp_reset_query();
			echo '</div>';
		endif; ?>
		<?php
		$frog = get_field('frog', 80);
		echo $frog ? '<div class="frog" style="background-image: url('. $frog['url'] .');" title="frog" alt="frog"></div>' : '';
		?>
	</body>
</html>
<?php get_footer(); ?>
