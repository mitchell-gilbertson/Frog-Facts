<?php
function enqueue_styles_and_scripts()
{
	//js
	wp_enqueue_script('jquery', '/wp-includes/js/jquery/jquery.min.js', null, null, true);
	wp_enqueue_script('lazy_load', get_template_directory_uri() . '/assets/js/lazy-load.js', array('jquery'), null, true);
	wp_enqueue_style('lazy_load', get_template_directory_uri() . '/assets/css/lazy-load.css');
	wp_enqueue_style('main_css', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'enqueue_styles_and_scripts');

//pagination
function my_repeater_show_more() {
	if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'my_repeater_field_nonce')) {exit;}// validate the nonce
	if (!isset($_POST['post_id'])) {return;}// make sure we have the other values
	ob_start();// use an object buffer to capture the html output alternately you could create a varaible like $html and add the content to this string, but I find object buffers make the code easier to work with
	// variables
	$post_id = $_POST['post_id'];
	$count = 1;
	$show = 2; // how many more to show
	$activePage = $_POST['page_num'] ? $_POST['page_num'] : 1;
	$start = ($activePage * $show) - $show + 1;
	$end = $start+$show;
	$activeGalleryID = $_POST['gallery_id'];
	$outerRepeater = get_field('outer_repeater', $post_id);
	$activeRepeaterRow = $outerRepeater[$activeGalleryID - 1];
	$innerRepeater = $activeRepeaterRow['inner_repeater'];
	$total = count($innerRepeater);
	$totalPages = $total / $show;
	if($innerRepeater): echo '<div class="row gallery">'; foreach($innerRepeater as $myRow):
		$image = $myRow['image'];
		if($count < $start): $count++; continue; elseif($count >= $start && $count < $end)://check to see if row belongs on page
			?>

			<div class="col-sm-4"><a href="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" title="<?php echo $image['title']; ?>"><img class="img-responsive center-block" src="<?php echo $image['sizes']['medium']; ?>" alt="<?php echo $image['alt']; ?>" title="<?php echo $image['title']; ?>" /></a></div>

			<?php
			$count++; continue;
		else: break; endif;//end row check
	endforeach;//end foreach innerRepeater
	echo '</div>';//close .row.gallery
	echo '<div class="row"><div class="col-xs-12"><ul>';//add pagination list
	for ($i = 1; $i < $totalPages + 1; $i++):
		?>

		<li class="<?php echo $i == $activePage ? 'pagination-link active' : 'pagination-link'; ?>"><a href="javascript: my_repeater_show_more(<?php echo $i .','. $activeGalleryID; ?>);"><?php echo $i; ?></a></li>

		<?php
	endfor;
	echo '</ul></div></div>';//close pagination list
endif;//end if innerRepeater
$content = ob_get_clean();
echo json_encode(array('content' => $content, 'gallery_id' => $activeGalleryID));//output our values as a json encoded array
exit;
}//end function my_repeater_show_more
add_action('wp_ajax_my_repeater_show_more', 'my_repeater_show_more');// add action for logged in users
add_action('wp_ajax_nopriv_my_repeater_show_more', 'my_repeater_show_more');// add action for non logged in users


function lazy_image_shortcode($atts) {
	shortcode_atts(array('img' => '',), $atts);
	$image = unserialize($atts['img']);
	$paddingPercent = ($image['height'] / $image['width']) * 100;
	ob_start(); ?>
	<div class="col lazy-item lazy-hidden">
		<div class="lazy-container" style="padding-bottom: <?php echo $paddingPercent; ?>%;" data-img="<?php echo $image['url']; ?>">
			<img class="img-responsive lazy-placeholder" src="<?php echo $image['sizes']['medium']; ?>" />
		</div>
	</div>
	<?php return ob_get_clean();
}
add_shortcode( 'lazyimg', 'lazy_image_shortcode' );


function my_custom_post_types() {
	register_post_type( 'facts',
		array(
			'labels' => array(
				'name' => __( 'Facts' ),
				'singular_name' => __( 'Fact' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'facts'),
			'show_in_rest' => true,
			'menu_icon' => 'dashicons-id-alt',
			'taxonomies' => array('category')
		)
	);
	register_post_type( 'copypastas',
		array(
			'labels' => array(
				'name' => __( 'Copypastas' ),
				'singular_name' => __( 'Copypasta' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'copypastas'),
			'show_in_rest' => true,
			'menu_icon' => 'dashicons-id-alt',
			'taxonomies' => array('category')
		)
	);
}
// Hooking up our function to theme setup
add_action( 'init', 'my_custom_post_types' );
