jQuery(document).ready(function ()
{
    var varArr = [];

    if (screen.width <= 1000)
    {
        var wid = (screen.width * 0.9);
        var ht = Math.round(56.38 / 100 * wid);
        jQuery('iframe.vid_iframe').attr('width', (wid) + 'px');
        jQuery('iframe.vid_iframe').attr('height', (ht) + 'px');
    }

    var _id = video = plyr = '';
    jQuery('.videoTrigger').click(function ()
    {
        _id = '#' + jQuery(this).attr('data-id');
        plyr = _id + " iframe";
        jQuery('.popBox').show().fadeIn();
        jQuery(_id).fadeIn();
        video = jQuery(plyr).attr('src');
        jQuery(plyr).attr('src', video + "/?autoplay=1");
    });

    jQuery('.ivg_gallery_section').on('click', '.ivg_gallery_image', function () {
        varArr.rel = jQuery(this).attr('rel');
        varArr.galleryItems = [];
        var ikA = 0;


        jQuery(document).find('a[rel="' + varArr.rel + '"]').each(function () {
            varArr.galleryItems[ikA] = jQuery(this);
            ikA++;
        });

        return showPopUp(jQuery(this));
    });


    jQuery('body').on('click', '.ivg_next', function () {
        alert('tset')
        for (var i = 0; i < (varArr.galleryItems).length; i++)
        {
            if (jQuery(varArr.galleryItems[i]).hasClass('ivg.iframe'))
            {
                console.log(i)
            }
        }
    });
    

    jQuery('.popBox, .closePop').click(function ()
    {
        jQuery('.popBox').fadeOut();
        var videoURL = jQuery(plyr).prop('src');
        videoURL = videoURL.replace("/?autoplay=1", "");
        jQuery(plyr).prop('src', '');
        jQuery(plyr).prop('src', videoURL);
        jQuery('.pop_section').fadeOut();
    });
});


function showPopUp(eleObj)
{
    var outPut = '';
    var dataSrc = jQuery(eleObj).attr('data-src');
    if (jQuery(eleObj).hasClass('ivg.iframe'))
    {
        outPut = '<iframe name="ivg_" class="ivg_frameBox" src="' + dataSrc + '"></iframe>'
    } else
    {
        outPut = '<img alt="ivg_imageFrame" class="ivg_frameBox" src="' + dataSrc + '"/>';
    }

    outPut = '<div class="ivg_popbox" id="ivg_popbox">' +
            '<div class="ivg_popbox_wrap">' +
            '<div class="ivg_popbox_wrapin">' +
            outPut +
            '</div>' +
            '<div class="ivg_popbox_nav">' +
            '<span class="ivg_prev">Prev</span>' +
            '<span class="ivg_next">Next</span>' +
            '</div>' +
            '</div>' +
            '</div>';
    jQuery('body').append(outPut).fadeIn(1500);
}