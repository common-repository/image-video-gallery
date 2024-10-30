<?php

/**
 * Handle array value
 */
if(!function_exists('ivmArrayVal'))
{
    function ivmArrayVal($ar, $key)
    {
        return (isset($ar[$key]) ? $ar[$key] : false);
    }
}


/** 
 * retrieves the attachment ID from the file URL
 * */ 
if(!function_exists('ivmGetImageId'))
{
    function ivmGetImageId($image_url) {
        if(!empty($image_url))
        {
            global $wpdb;
            $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
            return $attachment[0]; 
        }
        return false;
    }
}


/**
 * Load file 
 */
if(!function_exists('ivmLodFile'))
{
    function ivmLodFile($file)
    {
        try
        {
            if(file_exists($file))
            {
                require_once($file);
                return true;
            }
        }
        catch(Exception $e)
        {
            echo 'Error : Unable to load file.';
            return false;
        }
    }
}