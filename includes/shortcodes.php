<?php


add_shortcode( 'post_reviewer_images', function() {
	$reviewers = maipr_get_post_reviewer_terms();
	if ( ! $reviewers ) {
		return '';
	}
	return maipr_get_post_reviewer_images( $reviewers );
});

add_shortcode( 'post_reviewer_text', function() {
	$reviewers = maipr_get_post_reviewer_terms();
	if ( ! $reviewers ) {
		return '';
	}
	return maipr_get_post_reviewers_text();
});

add_shortcode( 'post_reviewer_names', function() {
	$reviewers = maipr_get_post_reviewer_terms();
	if ( ! $reviewers ) {
		return '';
	}
	return maipr_get_post_reviewer_names( $reviewers );
});
