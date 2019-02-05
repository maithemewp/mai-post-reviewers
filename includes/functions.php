<?php

/**
 * Whether or not to automatically display post reviewers on a given post.
 *
 * @since   0.1.0
 *
 * @return  string|HTML
 */
function maipr_display_post_reviewers() {

	$display = true;

	// Bail if not a single post or there are no reviewers.
	if ( ! ( is_singular( 'post' ) && maipr_get_post_reviewer_terms() ) ) {
		$display = false;
	}

	// Filter to disable auto display of post reviewers.
	$display = apply_filters( 'maipr_display_post_reviewers', $display );

	return $display;
}

/**
 * Get the post reviewers HTML.
 *
 * @since   0.1.0
 *
 * @return  string|HTML
 */
function maipr_get_post_reviewers_html() {
	$reviewers = maipr_get_post_reviewer_terms();
	if ( ! $reviewers ) {
		return '';
	}
	$content  = maipr_get_post_reviewer_images();
	$content .= maipr_get_post_reviewer_text();
	$content .= maipr_get_post_reviewer_names();
	return genesis_markup( array(
		'open'    => '<span %s>',
		'close'   => '</span>',
		'content' => $content,
		'context' => 'post-reviewers',
		'echo'    => false,
	) );
}

/**
 * Get a post's reviewer images.
 *
 * @since   0.1.0
 *
 * @return  string|HTML
 */
function maipr_get_post_reviewer_images() {
	$reviewers = maipr_get_post_reviewer_terms();
	if ( ! $reviewers ) {
		return '';
	}
	// Images.
	$html = '';
	foreach ( $reviewers as $term ) {
		$image_id   = get_term_meta( $term->term_id, 'banner_id', true );
		if ( $image_id ) {
			$image = wp_get_attachment_image( $image_id, maipr_get_post_reviewer_image_size() );
			if ( $image ) {
				$html .= sprintf( '<a class="reviewer-image" href="%s">%s</a>', get_term_link( $term, 'reviewer' ), $image );
			}
		}
	}
	return $html;
}

/**
 * Get a the reviewer image size.
 *
 * @since   0.1.0
 *
 * @return  string|HTML
 */
function maipr_get_post_reviewer_image_size() {
	// Filter the image size to use for post reviewer.
	return apply_filters( 'maipr_post_reviewer_image_size', 'tiny' );
}

/**
 * Get a the "Reviewed by:" text.
 *
 * @since   0.1.0
 *
 * @return  string|HTML
 */
function maipr_get_post_reviewer_text() {
	$reviewers = maipr_get_post_reviewer_terms();
	if ( ! $reviewers ) {
		return '';
	}
	// Filter to change the default 'Reviewed by:' text.
	$text = apply_filters( 'maipr_post_reviewer_text', __( 'Reviewed by:', 'mai-post-reviewers' ) );
	return sprintf( '<span class="reviewer-text">%s&nbsp;</span>', $text );
}

/**
 * Get a post's reviewer names.
 *
 * @since   0.1.0
 *
 * @return  string|HTML
 */
function maipr_get_post_reviewer_names() {
	$reviewers = maipr_get_post_reviewer_terms();
	if ( ! $reviewers ) {
		return '';
	}
	$html = '';
	foreach ( $reviewers as $term ) {
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

/**
 * Get the stylesheet handle.
 *
 * @since   0.1.0
 *
 * @return  string
 */
function maipr_get_handle() {
	if ( function_exists( 'mai_get_handle' ) ) {
		return mai_get_handle();
	}
	return ( defined( 'CHILD_THEME_NAME' ) && CHILD_THEME_NAME ) ? sanitize_title_with_dashes( CHILD_THEME_NAME ) : 'child-theme';
}

/**
 * Get script suffix.
 *
 * @since   0.1.0
 *
 * @return  string
 */
function maipr_get_suffix() {
	if ( function_exists( 'mai_get_suffix' ) ) {
		return mai_get_suffix();
	}
	$debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
	return $debug ? '' : '.min';
}
