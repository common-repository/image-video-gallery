<?php


class ImageVideoMetaTemplate {

    private $selString, $relGallery = '';

    public function ivmGalleryTemplate($atts)
    {
        $this->ivmShowVideo($atts);
    }

    /**
     * SHORT CODE Handler
     **/
    private function ivmShowVideo($atts)
    {
        ob_start();

        $subStr = substr(str_shuffle('ABCDEFGHIJKMLNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 7);
        $postID = isset($atts['id']) ? $atts['id'] : '';
        $relString = isset($atts['rel']) ? $atts['rel'] : $subStr;
        $bs_gallery_column = get_post_meta($postID, '_bs_gallery_column', true);

        $imgLink = ivmArrayVal($bs_gallery_column, 'gallery_image_file');
        $imgId = ivmArrayVal($bs_gallery_column, 'gallery_image_id');
        $imgVideo = ivmArrayVal($bs_gallery_column, 'gallery_image_video');
        $imgText = ivmArrayVal($bs_gallery_column, 'gallery_image_text');
        $imgActive = ivmArrayVal($bs_gallery_column, 'gallery_image_active');
        $ik = 0;
        $iframe = '';

        $count = max(count($imgLink), count($imgVideo), count($imgText));
        ?>
        <div class="ivg_gallery_section">
            <?php
            for ($ika = 0; $ika < $count; $ika++)
            {
                $img = isset($imgLink[$ika]) ? esc_url($imgLink[$ika]) : '';
                $iId = isset($imgId[$ika]) ? esc_url($imgId[$ika]) : '';
                $vid = isset($imgVideo[$ika]) ? esc_url($imgVideo[$ika]) : '';
                $txt = isset($imgText[$ika]) ? esc_html($imgText[$ika]) : '';
                $act = isset($imgActive[$ika]) ? $imgActive[$ika] : '';

                if ($act == '1')
                {
                    ?>
                    <div class="ivg_gallery_itm">
                        <?php
                        if ($vid)
                        {
                            $link = $vid . '?rel=0&amp;wmode=transparent';
                            $iframe = 'iframe';
                        }
                        else
                        {
                            $link = $img;
                            $iframe = '';                            
                        }
                        ?>                        
                        <a class="<?php echo $subStr ?> <?php echo $iframe ?> ivg_gallery_image" href="<?php echo $link ?>">
                            <img class="ivg_frnt_img" width="100%" src="<?php echo $img; ?>" alt="<?php echo $txt; ?>"/>
                        </a>
                        <span class="ivg_gallery_titl" style="display:none;"><?php echo $txt; ?></span>
                    </div>
                    <?php
                    ++$ik;
                    if ($ik == 3)
                    {
                        $ik = 0;
                    }
                }
            }
            ?>
        </div>
        <?php
        $this->selString .= '.'.$subStr;
        $this->relGallery = $relString;
        return ob_get_flush();
    }


    public function ivmColorboxScript()
    {
        ?>
        <script>
            jQuery(function($){
                var colorBox = $("<?php echo $this->selString ?>");
                colorBox.colorbox({rel:'<?php echo $this->relGallery ?>',
                    transition:"none", width:"75%", height:"80%", 
                    onLoad: function(){ 
                        if(jQuery(this).hasClass('iframe'))
                        {
                            return colorBox.colorbox({iframe:true});
                        } 
                        return colorBox.colorbox({iframe:false});
                    }
                });
            });
            function iframeCheck()
            {
                var $element = $.colorbox.element();
                if(jQuery(this).hasClass('iframe'))
                {
                    return true;
                }
                return false;
            }
        </script>
        <?php
    }

    /*     * *************************************************************************** */
    /*     * *************************************************************************************** */
    function getCats($id, $taxonomy)
    {
        $cat_args = array('parent' => $id, 'number' => 10, 'hide_empty' => false);
        $termParent = get_terms($taxonomy, $cat_args);
        return $termParent;
    }


    function clean($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $test = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        return $test;
    }
}