<?php

/*
  Plugin Name: Image Video Gallery
  Plugin URI: https://wordpress.org/plugins/image-video-gallery
  Description: Add image and video gallery meta fields.
  Author: BlueSuiter
  Version: 1.11.17
  Author URI: https://scriptrecipes.blogspot.in/
 */

/* Stop direct access of the file*/
if (!defined('ABSPATH'))
{
    die();
}

/**
 * Load Helper functions
 */
if(file_exists(dirname(__FILE__) . '/helper/helper.php'))
{
    require_once(dirname(__FILE__) . '/helper/helper.php');
}

/**
 * Load Required Files
 */
if (!class_exists('ImageVideoMetaController') && !class_exists('ImageVideoMetaTemplate'))
{
    if(ivmLodFile(dirname(__FILE__) . "/controller/image-video-meta-controller.php"))
    {
        $objImageVideoMetaController = new ImageVideoMetaController();
        add_action('add_meta_boxes', [$objImageVideoMetaController, 'ivm_addImageVideoMetaField']);
        add_action('save_post', [$objImageVideoMetaController, 'ivm_saveImageVideoMetaFields'], 1, 2);    
    }
    
    if(ivmLodFile(dirname(__FILE__) . '/templates/image-video-meta-template.php'))
    {
        $objImageVideoMetaTemplate = new ImageVideoMetaTemplate();
        add_shortcode('bsImageGallery', [$objImageVideoMetaTemplate, 'ivmGalleryTemplate']);
        add_action('wp_footer', [$objImageVideoMetaTemplate, 'ivmColorboxScript']);      
    }
}


/**
 * Handle Gallery Shortcode For Content
 */
function applyGalleryFilter()
{
    $gallery_shortcode = '[bsImageGallery id="' . intval($post->ID) . '"]';
}
apply_filters('the_content', 'applyGalleryFilter');


/**
 * Enqueue required script/style
 */
function enqueueFancyBox()
{
    global $wp_scripts;
    wp_enqueue_style('ivg_gallery-css', plugin_dir_url(__FILE__) . 'assets/css/ivg_style.css', false, '0.11.17', 'all');
    wp_enqueue_style('colorbox-css', plugin_dir_url(__FILE__) . 'assets/css/colorbox.min.css', false, '', 'all');
    wp_enqueue_script('colorbox-js', plugin_dir_url(__FILE__) . 'assets/js/jquery.colorbox-min.js', array('jquery'), '1.6.4', false);
}
add_filter('wp_enqueue_scripts', 'enqueueFancyBox');
