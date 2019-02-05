<?php

/**
 * Add inline CSS.
 * Way late cause Engine changes stylesheet to 999.
 *
 * @since   0.1.0
 *
 * @return  void
 */
add_action( 'wp_enqueue_scripts', function() {
	// Bail if not auto displaying reviewers.
	if ( ! maipr_display_post_reviewers() ) {
		return;
	}
	$suffix = maipr_get_suffix();
	$css    = file_get_contents( MAI_POST_REVIEWERS_PLUGIN_DIR . "assets/css/style{$suffix}.css" );
	wp_add_inline_style( maipr_get_handle(), $css );
}, 1000 );

/**
 * Add post reviewers to entry meta.
 *
 * @since   0.1.0
 *
 * @return  string
 */
add_action( 'genesis_before', function() {
	// Bail if not auto displaying reviewers.
	if ( ! maipr_display_post_reviewers() ) {
		return;
	}
	add_filter( 'genesis_post_info', function( $post_info ) {
		$post_info .= '[post_reviewers]';
		return $post_info;
	});
});

/**
 * Disable the banner area on reviewer archive pages.
 *
 * @since   0.1.0
 *
 * @return  void
 */
add_action( 'genesis_before', function() {
	if ( ! is_tax( 'reviewer' ) ) {
		return;
	}
	// Disable the banner area.
	remove_action( 'genesis_after_header', 'mai_do_banner_area', 20 );
});

/**
 * Add reviewer image to reviewer archive pages.
 *
 * @since   0.1.0
 *
 * @return  void
 */
add_action( 'genesis_before_loop', 'maipr_do_reviewer_archive_image' );
function maipr_do_reviewer_archive_image() {

	$image_id = get_term_meta( get_queried_object_id(), 'banner_id', true );

	if ( ! $image_id ) {
		return;
	}

	printf( '<p class="reviewer-image">%s</p>', wp_get_attachment_image( $image_id, maipr_get_post_reviewer_image_size() ) );
}
