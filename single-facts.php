<?php if(have_posts()): while(have_posts()): the_post();?>
<?php
$random = isset($_GET['random']) ? true : false;
//random fact
if($random):
	$responses = array();
	if(have_rows('facts')):
		while(have_rows('facts')):
			the_row();
			$fact = get_sub_field('fact');
			array_push($responses, $fact);
		endwhile;
	endif;
	$rand_key = array_rand($responses, 1);
	$message = $responses[$rand_key];
	echo $message;
//All Facts
else:
	if(have_rows('facts')):
		echo '<ol class="facts">';
		while(have_rows('facts')):
			the_row();
			$fact = get_sub_field('fact');
			echo $fact ? '<li class="fact">'. $fact .'</li>' : '';
		endwhile;
		echo '</ol>';
	endif;
endif;
?>
<?php endwhile; endif; ?>
