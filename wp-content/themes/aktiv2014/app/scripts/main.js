'use strict';
/*global moment, jQuery, document */
var program_endpoint = 'https://studentersamfundet.no/api/events/get_upcoming/';
var query_params = {
    //meta_key: '_neuf_events_starttime',
    //meta_value: moment().add('days', 1).format('X'),
    //meta_compare: '<='
    count: 10
};
var entrypoint = '.program-list';

function format_posts(posts) {
    var html = '';
    var i=0;
    //var stylesheet_dir = jQuery('meta[name=x-stylesheet-directory]').attr('content');

    if(posts.length === 0) {
        return '';
    }

    html += '<thead><tr class="fadein"><th width="53%">Arrangement</th><th>Sted</th><th>Tid</th></tr></thead>';
    for(i=0; i< posts.length; i++) {
        var post = posts[i];
        html += '<tr class="fadein">';
        //if(post.thumbnail_images) {
        //    html += '<img src="'+ post.thumbnail_images['four-column-promo'].url +'" /><br/>';
        //} else {
        //    html += '<img src="'+ stylesheet_dir +'/img/placeholder.gif" /><br/>';
        //}
        html += '<td class="entry-title"><a href="'+ post.url +'">'+ post.title +'</a></td>';
        html += '<td class="entry-venue">'+ post.custom_fields._neuf_events_venue +'</td>';
        html += '<td class="entry-starttime">'+ moment.unix(post.custom_fields._neuf_events_starttime).utc().format('DD. MMMM YYYY, HH.mm') +'</td></tr>';
    }

    return html;
}

jQuery(document).ready(function() {
    moment.lang('nb');

    var $ = jQuery;
    $.getJSON(
        program_endpoint+'?callback=?',
        query_params,
        function(data) {
            if(data && data.events) {
                //console.log(data);
                // render template
                var html = format_posts(data.events);
                $(entrypoint).html(html);
            }
        }
    );

    /* Menu toggle */
    $('#menu [data-toggle-menu]').on('click', function(e) {
        e.preventDefault();
        // add open css class
        var menu = $('#menu .main-menu, #menu .user-menu-wrap, #menu .user-menu');
        menu.toggleClass('visible');
    });

    $('[data-toggle-introduction]').on('click', function() {
        $(this).parent().fadeOut(600);
        $.cookie('introduction-dismissed', '1');
    });
    if( $.cookie('introduction-dismissed') !== '1' ) {
        $('.introduction').show();
        $('.introduction').addClass('fadein');
    }


});
