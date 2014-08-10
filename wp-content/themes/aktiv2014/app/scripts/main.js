'use strict';
/*global moment, jQuery, document, _, $ */
var program_endpoint = 'https://studentersamfundet.no/api/events/get_today/';
var inside_url = 'https://inside.studentersamfundet.no';
var inside_groups_url = inside_url + '/api/groups.php';
var user_search_endpoint = '/inside-api/';
var query_params = {
    //meta_key: '_neuf_events_starttime',
    //meta_value: moment().add('days', 1).format('X'),
    //meta_compare: '<='
    //count: 10
};

function format_posts(posts) {
    var html = '';
    var i=0;
    //var stylesheet_dir = jQuery('meta[name=x-stylesheet-directory]').attr('content');

    if(posts.length === 0) {
        return 'Idag er det stille på huset :-/';
    }

    for(i=0; i< posts.length; i++) {
        var post = posts[i];
        html += '<li class="fadein">';
        html += '<span class="entry-starttime">'+ moment.unix(post.custom_fields._neuf_events_starttime).utc().format('HH.mm') +'</span>: ';
        html += '<a href="'+ post.url +'">'+ post.title +'</a>';
        html += ' <span class="entry-venue">'+ post.custom_fields._neuf_events_venue +'</span>';
        html += '</li>';
    }

    return html;
}
function format_results(data) {
    var header_template = '<table><thead><tr><th>Navn</th><th>Brukernavn</th><th>Epost</th><th>Telefon</th><th>Medlem</th><th>Grupper</th></tr></thead>';
    if(data.error) {
        console.log(data.error);
        $('.search-result-list').html('');
        $('.search-results .meta').html('');
        return;
    }
    if(data.meta.num_results === 0) {
        $('.search-result-list').html('Uffda, ingen treff.');
        $('.search-results .meta').html('');
        return;
    }

    var list = '<tbody><% _.each(results, function(u) { %>' +
        '<tr><td><a href="'+ inside_url +'/?page=display-user&userid=<%= u.id %>"><%= u.firstname %> <%= u.lastname %></a></td><td><%= u.username %></td><td><a href="mailto:<%= u.email %>"><%= u.email %></a></td><td><a href="tlf:<%= u.number %>"><%= u.number %></a></td><td><% if(u.is_member != 0) { %>Ja<% } else { %>Nei<% } %></td><td class="groups"><%= u.groups %></td></li> <% }); %></tbody></table>';
    var html = header_template + _.template(list, data);
    $('.search-result-list').html(html);

    var search_meta = 'Antall treff: <%= meta.num_results %>';
    $('.search-results .meta').html(_.template(search_meta, data));
}

function do_search() {
    // TODO: add params from toggle
    var validMembershipToggle = $('.filters [data-search-filter="has_valid_membership"]');
    var groupString = '';
    var groupSelect = $('.groups-select').val();
    if( groupSelect !== null) {
        groupString = groupSelect.join();
    }

    $.getJSON(
        user_search_endpoint,
        {
            q: $('.search-field').val(),
            filter_groups: groupString,
            _wpnonce: $('meta[name=x-inside-api-nonce]').attr('content'),
            has_valid_membership: validMembershipToggle.hasClass('active').toString()
        },
        format_results
    );
}

jQuery(document).ready(function() {
    moment.lang('nb');

    var $ = jQuery;

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

    /* Program */
    var program_entrypoint = '.program-list';
    if( $(program_entrypoint).length ) {
        /* */
        $.getJSON(
            program_endpoint+'?callback=?',
            query_params,
            function(data) {
                if(data && data.events) {
                    // render template
                    var html = format_posts(data.events);
                    $(program_entrypoint).html(html);
                }
            }
        );
    }


    /* Search page */
    if( $('.search-form').length ) {
        $.getJSON(inside_groups_url, function(data) {
            // create select fields
            var list = '<select multiple class="groups-select" data-placeholder="Velg en gruppe..."><option value=""></option><% _.each(results, function(g) { %>' +
                '<option value="<%=g.group_id %>"><%= g.group_name %></option>' +
                '<% }); %></select>';

            var html = _.template(list, data);
            $('.groups-select-wrap').html(html);
            // run chosen
            $('.groups-select').chosen({
                no_results_text: 'Uffda, ingen treff!',
                allow_single_deselect: true
            }).change(function() {
                do_search();
            });
        });
        $('.search-field').focus();
        $('.search-field').on('keyup keypress', function(e) {
            // No <enter>
            if (e.keyCode === 10 || e.keyCode === 13)  {
                e.preventDefault();
            }
        });
        $('.search-field').on('keyup keypress', _.debounce(function() {
            do_search();
        }, 300));

        /* Toggles */
        $('.roles a').on('click', function(e) {
            e.preventDefault();
            $(this).toggleClass('active');
            do_search();
        });
    }
});
