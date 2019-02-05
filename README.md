# Mai Post Reviewers

**Requires Genesis. Intended to use with [Mai Theme](https://maitheme.com/) but will mostly work without it.**

Add reviewers to posts. Includes shortcodes and helper functions to use with genesis_post_info.

Registers a new taxonomy called "Reviewers" where you can choose one or more reviewers for a given post.


## Display
Setup your reviewers via Dashboard > Posts > Reviewers. Make sure to add a reviewer image.

Add/edit any post and check off any reviewers for that post and they will automatically display on the post.

**Default Layout**

![alt text](/assets/images/default.png "Mai Post Reviewers default layout")

**Custom Layout and styles (via genesis_post_info filter)**

![alt text](/assets/images/custom.png "Mai Post Reviewers custom layout")

## Filters
```
/**
 * Filter to disable auto display of post reviewers.
 *
 * @param   bool  Wheter or not to display post reviewers if a post has them.
 *
 * @return  bool
 */
$display = apply_filters( 'maipr_display_post_reviewers', $display );
```
```
/**
 * Filter to change the default 'Reviewed by:' text.
 *
 * @param   string  The text.
 *
 * @return  string  The modified text.
 */
$text = apply_filters( 'maipr_post_reviewer_text', __( 'Reviewed by:', 'mai-post-reviewers' ) );
```
```
/**
 * Filter the image size to use for post reviewer.
 *
 * @param   string  The image size.
 *
 * @return  string  The new image size.
 */
$size = apply_filters( 'maipr_post_reviewer_image_size', 'tiny' );
```

## Shortcodes
```
[post_reviewer_images]
```
Display all post reviewer images.

```
[post_reviewer_text]
```
Display the "Reviewed by:" text.

```
[post_reviewer_names]
```
Display all post reviewers names.
