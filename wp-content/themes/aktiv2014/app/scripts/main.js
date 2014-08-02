'use strict';
/*global moment, jQuery, document, _ */
var program_endpoint = 'https://studentersamfundet.no/api/events/get_upcoming/';
var inside_url = 'https://inside.studentersamfundet.no';
var user_search_endpoint = '/inside-api/';
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
    $('#menu .menu-toggle').on('click', function(e) {
        e.preventDefault();
        // add open css class
        var menu = $('#menu .main-menu, #menu .user-menu-wrap');
        menu.toggleClass('visible');
    });

    /* User Menu toggle */
    $('#menu a.profile-badge').on('click', function(e) {
        e.preventDefault();
        // add open css class
        var menu = $('#menu .user-menu');
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

    /* Search */
    $('.search-field').focus();
    var header_template = '<table><thead><tr><th>Navn</th><th>Brukernavn</th><th>Epost</th><th>Telefon</th><th>Medlem</th><th>Grupper</th></tr></thead>';
    $('.search-field').on('keyup keypress', function(e) {
        // No <enter>
        if (e.keyCode === 10 || e.keyCode === 13)  {
            e.preventDefault();
        }
    });
    $('.search-field').on('keyup keypress', _.debounce(function(e) {
        if(e.target.value.length > 2) {
            $.getJSON(user_search_endpoint, {q: e.target.value, _wpnonce: $('meta[name=x-inside-api-nonce').attr('content')}, function(data) {
                console.log(data);
                if(data.meta.num_results === 0) {
                    $('.search-result-list').html('Ingen treff.');
                    $('.search-results .meta').html('');
                    return;
                }
                //console.log(data);
                var list = '<tbody><% _.each(results, function(u) { %>' +
                    '<tr><td><a href="'+ inside_url +'/?page=display-user&userid=<%= u.id %>"><%= u.firstname %> <%= u.lastname %></a></td><td><%= u.username %></td><td><a href="mailto:<%= u.email %>"><%= u.email %></a></td><td><a href="tlf:<%= u.number %>"><%= u.number %></a></td><td><% if(u.is_member != 0) { %>Ja<% } else { %>Nei<% } %></td><td class="groups"><%= u.groups %></td></li> <% }); %></tbody></table>';
                var html = header_template + _.template(list, data);
                $('.search-result-list').html(html);

                var search_meta = 'Antall treff: <%= meta.num_results %>';
                $('.search-results .meta').html(_.template(search_meta, data));
            });
        } else {
            $('.search-result-list').html('Skriv minst 3 tegn.');
            $('.search-results .meta').html('');
        }
    }, 300));

});
