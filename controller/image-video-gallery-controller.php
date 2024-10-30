<?php

class ImageVideoMetaController
{
    public function ivm_addImageVideoMetaField()
    {
        $postType = ['post', 'page'];
        add_meta_box('imageVideoMetaField', 'Image Video Meta Field', [$this, 'ivm_imageVideoMetaField'], $postType, 'normal', 'default');
    }


    public function ivm_imageVideoMetaField()
    {
        global $post;
        $ika = 0;
        $postID = get_the_ID();

        $bs_gallery_column = get_post_meta($postID, '_bs_gallery_column', true);

        $imgLink = ivmArrayVal($bs_gallery_column, 'gallery_image_file');
        $imgId = ivmArrayVal($bs_gallery_column, 'gallery_image_id');
        $imgVideo = ivmArrayVal($bs_gallery_column, 'gallery_image_video');
        $imgText = ivmArrayVal($bs_gallery_column, 'gallery_image_text');
        $imgActive = ivmArrayVal($bs_gallery_column, 'gallery_image_active');
        ?>

        <?php
        /* / Noncename needed to verify where the data originated */
        echo '<input type="hidden" name="servicemeta_noncename" id="servicemeta_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
        $strFile = get_post_meta($post->ID, $key = 'gallery_image_file', true);
        $media_file = get_post_meta($post->ID, $key = '_wp_attached_file', true);
        if (!empty($media_file))
        {
            $strFile = $media_file;
        }
        ?>

        <div id="meta-box">
            <p>
                <label><strong>Gallery ID::</strong></label>
                <input type="text" name="sliderId" value="[bsImageGallery id='<?php echo get_the_ID(); ?>']" readonly/>
                <button class="upload_image_button button" type="button">Upload Image</button>
            </p>
            <table class="gallery_container" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Video</th>
                        <th>Title</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i < max(count($imgLink), count($imgVideo), count($imgText)); $i++)
                    {
                        $img = isset($imgLink[$i]) ? esc_url($imgLink[$i]) : false;
                        $iId = isset($imgId[$i]) ? sanitize_key($imgId[$i]) : ivmGetImageId($img);
                        $vid = isset($imgVideo[$i]) ? esc_url($imgVideo[$i]) : false;
                        $txt = isset($imgText[$i]) ? esc_html($imgText[$i]) : false;
                        $act = isset($imgActive[$i]) ? $imgActive[$i] : false;

                        ?>
                        <tr class="gallery_item">
                            <td class="imageCol">
                                <img src="<?php echo $img; ?>" alt="No Image" class="prevImage"/>
                                <input id="gallery_image_file<?php echo $i; ?>" class="gallery_image_file" type="hidden" name="gallery_image_file[]" value="<?php echo $img; ?>" />
                                <input type="hidden" class="gallery_image_id<?php echo $i; ?>" name="gallery_image_id[]" value="<?php echo $iId; ?>" />
                            </td> 
                            <td>
                                <input type="text" name="gallery_image_video[]" class="gallery_image_video" id="gallery_image_video<?php echo $i; ?>" placeholder="Video-Link" value="<?php echo $vid; ?>" />
                            </td>
                            <td>
                                <input type="text" name="gallery_image_text[]" class="gallery_image_text" id="gallery_image_text<?php echo $i; ?>" placeholder="Title" value="<?php echo $txt; ?>"/>
                            </td>
                            <td style="text-align:center;">
                                <input name="gallery_image_active[<?php echo $i; ?>]" class="gallery_image_active" id="gallery_image_active<?php echo $i; ?>" value="1" <?php echo ($act == '1' ? 'checked' : '') ?> type="checkbox"/>
                                <i class="dashicons dashicons-trash trashIt"></i>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
        wp_enqueue_script('imageVideoMetaJs', plugin_dir_url(__DIR__) . '/assets/js/script.js', 'jquery');
        wp_enqueue_style('imageVideoMetaStyle', plugin_dir_url(__DIR__) . '/assets/css/style.css');
    }


    //
    /* /Saving the file */

    public function ivm_saveImageVideoMetaFields($post_id, $post)
    {
        /* / verify this came from the our screen and with proper authorization, */
        /* / because save_post can be triggered at other times */
        if (!wp_verify_nonce(ivmArrayVal($_POST, 'servicemeta_noncename'), plugin_basename(__FILE__)))
        {
            return $post->ID;
        }

        /* / Is the user allowed to edit the post? */
        if (!current_user_can('edit_post', $post->ID))
        {
            return $post->ID;
        }

        /* / We need to find and save the data */
        /* / We'll put it into an array to make it easier to loop though. */
        $gallery_data['gallery_image_file'] = array_values(array_filter(ivmArrayVal($_POST, 'gallery_image_file')));
        $gallery_data['gallery_image_id'] = array_values(array_filter(ivmArrayVal($_POST, 'gallery_image_id')));
        $gallery_data['gallery_image_video'] = array_values(array_filter(ivmArrayVal($_POST, 'gallery_image_video')));
        $gallery_data['gallery_image_text'] = array_values(array_filter(ivmArrayVal($_POST, 'gallery_image_text')));
        $gallery_data['gallery_image_active'] = array_values(ivmArrayVal($_POST, 'gallery_image_active'));

        /* / Add values of $gallery_data as custom fields */
        if ($post->post_type == 'revision')
        {
            return;
        }

        $key = '_bs_gallery_column';
        $value = $gallery_data;

        if (get_post_meta($post->ID, $key, FALSE))
        {
            /* / If the custom field already has a value it will update */
            update_post_meta($post->ID, $key, $value);
        }
        else
        {
            /* / If the custom field doesn't have a value it will add */
            add_post_meta($post->ID, $key, $value);
        }

        if (!$value)
        {
            delete_post_meta($post->ID, $key); // Delete if blank value
        }
    }
}
