<?php
/*
Plugin Name: Canonical URL Feature
Description: Adds a canonical URL field to posts and pages for better SEO.
Version: 1.0
Author: Adekunle Owolabi
*/

// Add the custom meta box for Canonical URL
function add_canonical_url_meta_box()
{
    add_meta_box('canonical_url_meta_box', 'Canonical URL', 'render_canonical_url_meta_box', 'post', 'side', 'high');
    add_meta_box('canonical_url_meta_box', 'Canonical URL', 'render_canonical_url_meta_box', 'page', 'side', 'high');
}
add_action('add_meta_boxes', 'add_canonical_url_meta_box');

// Render the content of the Canonical URL meta box
function render_canonical_url_meta_box($post)
{
    $canonical_url = get_post_meta($post->ID, '_canonical_url', true);
?>
    <p>
        <label for="canonical_url">Enter Canonical URL:</label>
        <input type="text" id="canonical_url" name="canonical_url" value="<?php echo esc_attr($canonical_url); ?>" style="width:100%;">
    </p>
<?php
}

// Save the Canonical URL when the post or page is saved
function save_canonical_url($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['canonical_url'])) {
        update_post_meta($post_id, '_canonical_url', sanitize_text_field($_POST['canonical_url']));
    } else {
        delete_post_meta($post_id, '_canonical_url');
    }
}
add_action('save_post', 'save_canonical_url');

// Add the canonical URL tag to the header if set
function add_canonical_url_tag()
{
    if (is_singular()) {
        $canonical_url = get_post_meta(get_the_ID(), '_canonical_url', true);
        if (!empty($canonical_url)) {
            echo '<link rel="canonical" href="' . esc_url($canonical_url) . '" />' . "\n";
        }
    }
}
add_action('wp_head', 'add_canonical_url_tag');
