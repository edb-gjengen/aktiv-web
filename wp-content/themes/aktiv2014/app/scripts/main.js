'use strict';
/*global moment, jQuery, document, _, $, window */
var program_endpoint = 'https://studentersamfundet.no/api/events/get_today/';
var inside_url = 'https://inside.studentersamfundet.no';
var inside_groups_url = inside_url + '/api/groups.php';
var user_search_endpoint = '/inside-api/';
var email_search_endpoint = '/email-api/';
var query_params = {
    //meta_key: '_neuf_events_starttime',
    //meta_value: moment().add('days', 1).format('X'),
    //meta_compare: '<='
    //count: 10
};

function getParameterByName(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)'),
        results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

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
    var header_template = '<table><thead><tr><th>Navn</th><th>Brukernavn</th><th>Epost</th><th>Telefon</th><th>Har medlemskap</th><th>Grupper</th></tr></thead>';
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
        '<tr>' +
        '<td><a href="'+ inside_url +'/?page=display-user&userid=<%= u.id %>"><%= u.firstname %> <%= u.lastname %></a></td>' +
        '<td><%= u.username %></td>' +
        '<td><a href="mailto:<%= u.email %>"><%= u.email %></a></td>' +
        '<td><a href="tlf:<%= u.number %>"><%= u.number %></a></td>' +
        '<td><% if(u.is_member != 0) { %>Ja<% } else { %>Nei<% } %></td>' +
        '<td class="groups"><% _.each(u.groups, function(g, i) { %><%= g.name %><% if(u.groups.length !== i +1){ %>, <% } %><% }); %></td>'+
        '</tr> <% }); %>' +
        '</tbody></table>';
    var html = header_template + _.template(list, data);
    $('.search-result-list').html(html);

    var search_meta = 'Antall treff: <%= meta.num_results %>';
    $('.search-results .meta').html(_.template(search_meta, data));
}

function do_search() {
    var validMembershipToggle = $('.filters [data-search-filter="has_valid_membership"]');
    var groupString = '';
    var groupSelect = $('.groups-select').val();
    if( groupSelect !== null) {
        groupString = groupSelect.join();
    }
    var params = {
        q: $('.search-field').val(),
        filter_groups: groupString,
    };
    if(validMembershipToggle.hasClass('active')) {
        params.has_valid_membership = true;
    }
    var querystring = $.param(params);
    window.History.pushState(null, null, '?'+querystring);

    params._wpnonce = $('meta[name=x-inside-api-nonce]').attr('content');
    $.getJSON(
        user_search_endpoint,
        params,
        format_results
    );
}

function load_initial_values() {
    var param_set = false;
    var q = getParameterByName('q');
    if(q && q.length > 0) {
        $('.search-field').val(q);
        param_set = true;
    }

    var hvm = getParameterByName('has_valid_membership');
    if(hvm && hvm === 'true') {
        $('.filters [data-search-filter="has_valid_membership"]').addClass('active');
        param_set = true;
    }

    var fg = getParameterByName('filter_groups');
    var groups = [];
    if(fg && fg.length > 0) {
        if( fg.indexOf(',') !== -1 ) {
            // has comma
            groups = fg.split(',');
        } else {
            groups = [fg];
        }
        // is every group numeric?
        var has_invalid_group = false;
        var i=0;
        for(i=0; i<groups.length; i++) {
            var g = groups[i];
            if( !$.isNumeric(g) ) {
                has_invalid_group = true;
            }
        }
        if(!has_invalid_group) {
            param_set = true;
            $('.groups-select').val(groups);
            $('.groups-select').trigger('chosen:updated');
        }
    }
    if(param_set) {
        do_search();
    }
}
function save_user_meta(key, value) {
    console.log(key, value);
    var url = $('meta[name=x-siteurl]').attr('content') + '/wp-admin/admin-ajax.php';
    var params = {
        action: 'neuf_save_user_meta',
        meta_key: key,
        meta_value: value,
        unique: true,
        user_id: $('meta[name=x-user-id]').attr('content'),
        _wpnonce: $('meta[name=x-user-meta-nonce]').attr('content')
    };
    $.post(url, params, function() {
        localStorage.setItem(key, value);
    });
 }
 function get_user_meta(key, callback) {
    // TODO you are here
    try {
        var value = localStorage.getItem(key);
        callback({result:value});
        return;
    } catch(err) {
        console.log('no '+ key);
    }
    var url = $('meta[name=x-siteurl]').attr('content') + '/wp-admin/admin-ajax.php';
    var params = {
        action: 'neuf_get_user_meta',
        meta_key: key,
        single: true,
        user_id: $('meta[name=x-user-id]').attr('content'),
        _wpnonce: $('meta[name=x-user-meta-nonce]').attr('content')
    };
    $.get(url, params, callback);
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
    /* User intro dismiss and save */
    $('[data-toggle-introduction]').on('click', function() {
        //$(this).parent().fadeOut(600);
        save_user_meta('introduction_dismissed', true);
    });
    if( $('.introduction').length ) {
        get_user_meta('introduction_dismissed', function(data) {
            if(data.hasOwnProperty('result') && data.result !== 'true') {
                $('.introduction').show();
                $('.introduction').addClass('fadein');
            }
        });   
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

            /* Load intial values from url query */
            load_initial_values();
        });
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

    /* Profile page */
    if( $('.profile').length ) {
        /* Load groups and memberships */
        var params = {
            q: $('meta[name=x-username]').attr('content'),
            exact: true,
            _wpnonce: $('meta[name=x-inside-api-nonce]').attr('content')
        };

        $.getJSON(
            user_search_endpoint,
            params,
            function(data) {
                if( data.hasOwnProperty('results') && data.results.length === 1) {
                    var u = data.results[0];
                    console.log(u);
                    var group_html = '';
                    for(var i=0;i<u.groups.length; i++) {
                        group_html += '<li>' + u.groups[i].name + '</li>';
                    }
                    $('.group-list').html(group_html);
                    var is_member_field = $('.is-member');
                    if(u.is_member === '1') {
                        is_member_field.html('Gyldig medlemskap.');
                    } else {
                        is_member_field.html('Du har ikke et gyldig medlemskap. Du kan forny medlemskapet ditt via SMS, via <a href="http://snapporder.com">SnappOrder</a> eller via nettbutikken i <a href="https://inside.studentersamfundet.no">Inside</a>.');
                    }
                }
            }
        );
        /* Load email aliases */
        params.q = $('.profile-details .user-email').text();
        params.inherited = true;
        $.getJSON(
            email_search_endpoint,
            params,
            function(data) {
                var list = '<% _.each(results, function(r) { %>' +
                    '<li><%= r.name %> <a href="<%= r.admin_url %>" class="email-<%= r.type %>" title="<%= r.type %>"><span class="dashicons dashicons-<% if( r.admin_type == "selfservice" ) { %>edit<% } else { %>email<% } %>"></span></a></li>' +
                    '<% }); %>';
                var html = _.template(list, data);
                $('.account-details .email-list').html(html);
                if( params.inherited ) {
                    data.results = data.inherited_results; // reuse template
                    html = _.template(list, data);
                    $('.account-details .email-list-inherited').html(html);
                }
            }
        );
    }
});
