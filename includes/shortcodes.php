<?php

/**
 * Display all post reviewers.
 *
 * @since   0.1.0
 *
 * @return  string|HTML
 */
add_shortcode( 'post_reviewers', function() {
	return maipr_get_post_reviewers_html();
});

/**
 * Display all post reviewer images.
 *
 * @since   0.1.0
 *
 * @return  string|HTML
 */
add_shortcode( 'post_reviewer_images', function() {
	return maipr_get_post_reviewer_images();
});

/**
 * Display the post reviewers text.
 *
 * @since   0.1.0
 *
 * @return  string|HTML
 */
add_shortcode( 'post_reviewer_text', function() {
	return maipr_get_post_reviewer_text();
});

/**
 * Display all post reviewer names.
 *
 * @since   0.1.0
 *
 * @return  string|HTML
 */
add_shortcode( 'post_reviewer_names', function() {
	return maipr_get_post_reviewer_names();
});
