<!--/**
* Template Name: ACF Pagination Test
*/-->
<?php get_header(); if(have_posts()): while(have_posts()): the_post(); 
$banner = get_field('static_banner_image'); $mainHeading = get_field('main_heading'); $mainContent = get_field('main_content'); $featuredImage = get_field('featured_image'); ?>
<?php if($banner): echo
    '<div id="banner">
		<img src="'. $banner['url'] .'" alt="'. $banner['alt'] .'" title="'. $banner['title'] .'" class="img-responsive center-block" />
	</div>';
endif; ?> 
<main id="main">	
    <div class="container main-inner">
        <h2>Displays 2 images per page</h2>
        <?php if(have_rows('outer_repeater')): $galleryID = 0; while(have_rows('outer_repeater')): the_row(); $galleryID++; ?>
        <div class="row" id="gallery-<?php echo $galleryID; ?>">
            <h3>Gallery <?php echo $galleryID; ?></h3>
            <section class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php 
                $gallery = get_sub_field('inner_repeater'); 
                if(have_rows('inner_repeater')):
                ?>
                <div class="myGallery">
                    <?php echo '<div class="row">';
                    $count = 0; // a counter
                    $show = 2; // how many more to show
                    $activePage = isset($_POST['page_num']) ? $_POST['page_num'] : 1;
                    $start = ($activePage * $show) - $show;
                    $end = $start+$show;
                    $total = count($gallery);
                    $totalPages = ceil($total / $show);
                    while(have_rows('inner_repeater')): the_row();
                    $image = get_sub_field('image');
                    if($count < $start): $count++; continue; elseif($count >= $start && $count < $end)://check to see if row belongs on page
                    ?>
                    <div class="col-sm-4"><a href="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" title="<?php echo $image['title']; ?>"><img class="img-responsive center-block" src="<?php echo $image['sizes']['medium']; ?>" alt="<?php echo $image['alt']; ?>" title="<?php echo $image['title']; ?>" /></a></div>
                    <?php 
                    $count++; continue;
                    else: break; endif;//end row check
                    endwhile;// end inner while
                    echo '</div>';//close .row.gallery
                    echo '<div class="row"><div class="col-xs-12"><ul>';//add pagination list
                    for ($i = 1; $i < $totalPages + 1; $i++): ?>
                    <li class="<?php echo $i == $galleryID ? 'pagination-link active' : 'pagination-link'; ?>"><a href="javascript: my_repeater_show_more(<?php echo $i .','. $galleryID; ?>);"><?php echo $i; ?></a></li>
                    <?php endfor; echo '</ul></div></div>';//close pagination list ?>
                </div>
                <?php endif;//end inner if ?>
            </section>
        </div>
        <?php endwhile; endif;//end outer while/if ?>
    </div>
    <!-- The JS that will do the AJAX request -->
    <script type="text/javascript">
        var my_repeater_field_post_id = <?php echo $post->ID; ?>;
        var my_repeater_field_nonce = '<?php echo wp_create_nonce('my_repeater_field_nonce'); ?>';
        var my_repeater_ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
        var my_repeater_more = true;

        function my_repeater_show_more(pageNum, galleryID) {

            // make ajax request
            jQuery.post(
                my_repeater_ajax_url, {
                    // this is the AJAX action we set up in PHP
                    'action': 'my_repeater_show_more',
                    'post_id': my_repeater_field_post_id,
                    'nonce': my_repeater_field_nonce,
                    'page_num': pageNum,
                    'gallery_id': galleryID
                },
                function (json) {
                    // add content to container
                    // this ID must match the containter 
                    // you want to append content to
                    jQuery('#gallery-' + galleryID + ' .myGallery').html(json['content']);
                },
                'json'
            );
        }

    </script>
</main>
<?php endwhile; endif; get_footer(); ?>