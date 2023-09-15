<?php /* Template Name: Fact Test */ if(have_posts()): while(have_posts()): the_post();?>
<?php
$type = isset($_GET['type']) ? $_GET['type'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : false;
$stream = isset($_GET['stream']) ? $_GET['stream'] : false;
$type = $type == 'null' ? 'random' : $type;
$id = $id == 'null' ? 'random' : $id;
if(substr($type, 0, 2) == 'f-'){
	$type = substr($type, 2);
}
$responses = array();
if($type == 'random' || $type == '' || $type == 'f-'):
	$myQuery = new WP_Query(array('post_type' => 'facts', 'posts_per_page' => '-1', 'nopaging' => true));
	if($myQuery->have_posts()):
		while($myQuery->have_posts()): $myQuery->the_post();
			$categories = get_the_category();
			$cat = $categories[0]->cat_name;
			if ($cat) {
				if ($stream != $cat) {
					continue;
				}
			}
			if(have_rows('facts')):
				while(have_rows('facts')): the_row();
					$fact = get_sub_field('fact');
					$factTitle = get_the_title();
					$factData = array('type' => $factTitle, 'fact' => $fact);
					array_push($responses, $factData);
				endwhile;
			endif;
		endwhile; wp_reset_postdata();
	endif;
	$rand_key = array_rand($responses, 1);
	$theFact = $responses[$rand_key];
	$message = $theFact['type'] . ' Fact: ' . $theFact['fact'];
 else:
	$postId = url_to_postid('/facts/'. $type);
	if(isset($_GET['id']) && $_GET['id'] != '') {
		$facts = get_field('facts', $postId);
		$factId = intval($_GET['id']) - 1;
		array_push($responses, $facts[$factId]['fact']);
	} else {
		if(have_rows('facts', $postId)):
			while(have_rows('facts', $postId)): the_row();
				$fact = get_sub_field('fact');
				array_push($responses, $fact);
			endwhile;
		endif;
	}
	$rand_key = array_rand($responses, 1);
	$message = $responses[$rand_key];
endif;
$api = false;
if(isset($_GET['api'])) {
	$api = true;
}
if($api) {
	echo $message;
} else {
	?>
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
			</style>
		</head>
		<body>
			<?php $myQuery = new WP_Query(array('post_type' => 'facts', 'posts_per_page' => '-1', 'nopaging' => true, 'orderby' => 'title', 'order' => 'ASC'));
			if($myQuery->have_posts()): ?>
			<div class="fact-types">
				<select name="" id="fact-select" onchange="updateUrl(this)">
					<option value="" disabled selected>Change Fact Type</option>
					<?php while($myQuery->have_posts()): $myQuery->the_post(); $title = get_the_title();
						echo '<option value="'. $title .'">'. $title .'</option>';
					endwhile; wp_reset_postdata(); ?>
				</select>
			</div>
			<?php endif; ?>
			<div id="fact-section" class="fact-section">
				<?php
				if ($type == 'random' || !$type) {
					echo $theFact['type'] ? '<h1 class="type">'. $theFact['type'] .' Fact</h1>' : '';
					echo $theFact['fact'] ? '<div class="fact">'. $theFact['fact'] .'</div>' : '';
				} else {
					echo $type ? '<h1 class="type">'. $type .' Fact</h1>' : '';
					echo $message ? '<div class="fact">'. $message .'</div>' : '';
				}
				?>
				<div class="new-fact">
					<button class="btn" type="button" value="Reload Page" onClick="window.location.reload(true)">New Fact</button>
				</div>
			</div>
			<?php
			$frog = get_field('frog', 80);
			echo $frog ? '<div class="frog" style="background-image: url('. $frog['url'] .');" title="frog" alt="frog"></div>' : '';
			?>
			<script>
			function updateUrl(val) {
				const queryString = window.location.search;
				const urlParams = new URLSearchParams(queryString);
				if(urlParams.get('type')) {
					location.search = location.search.replace(/type=[^&$]*/i, 'type=' + val.value);
				} else {
					location.search = location.search + '&type=' + val.value;
				}
			}
			</script>
		</body>
	</html>
	<?php
}
?>
<?php endwhile; endif; ?>
