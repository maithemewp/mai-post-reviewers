<?php

/**
 * Get a the "Reviewed by:" text.
 *
 * @since   0.1.0
 *
 * @return  string|HTML
 */
function maipr_get_post_reviewers_text() {
	$text = apply_filters( 'maipr_post_reviewers_text', __( 'Reviewed by:', 'mai-post-reviewers' ) );
	return $text . '&nbsp;';
}

/**
 * Get a post's reviewer images.
 *
 * @since   0.1.0
 *
 * @return  string|HTML
 */
function maipr_get_post_reviewer_images( $terms ) {
	// Images.
	$html = '';
	foreach ( $terms as $term ) {
		$image_id   = get_term_meta( $term->term_id, 'banner_id', true );
		if ( $image_id ) {
			$image = wp_get_attachment_image( $image_id, 'tiny' );
			if ( $image ) {
				$html .= sprintf( '<a class="reviewer-image" href="%s">%s</a>', get_term_link( $term, 'reviewer' ), $image );
			}
		}
	}
	return $html;
}

/**
 * Get a post's reviewer names.
 *
 * @since   0.1.0
 *
 * @return  string|HTML
 */
function maipr_get_post_reviewer_names( $terms ) {
	$html = '';
	foreach ( $terms as $term ) {
		$html .= sprintf( '<a class="reviewer-name" href="%s">%s</a>,&nbsp;', get_term_link( $term, 'reviewer' ), $term->name );
	}
	return rtrim( $html, ',&nbsp;' );
}

/**
 * Get a post's reviewers.
 * Must be used in the loop.
 *
 * @since   0.1.0
 *
 * @return  bool|WP_Terms
 */
function maipr_get_post_reviewer_terms() {

	// Setup cache.
	static $terms_cache = null;

	// If not an error, return the cache.
	if ( null !== $terms_cache ) {
		return $terms_cache;
	}

	// Get the reviewers.
	$terms = get_the_terms( get_the_ID(), 'reviewer' );

	// If we have reviewers.
	if ( $terms || ! is_wp_error( $terms ) ) {
		// Set them.
		$reviewers = $terms;
	}
	// No reviewers.
	else {
		$reviewers = false;
	}

	// Push value into cache.
	$terms_cache = $reviewers;

	// Return them.
	return $reviewers;
}
