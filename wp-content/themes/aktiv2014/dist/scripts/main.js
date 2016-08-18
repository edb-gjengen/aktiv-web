"use strict";function getParameterByName(e){e=e.replace(/[\[]/,"\\[").replace(/[\]]/,"\\]");var t=new RegExp("[\\?&]"+e+"=([^&#]*)"),a=t.exec(location.search);return null===a?"":decodeURIComponent(a[1].replace(/\+/g," "))}function format_posts(e){var t="",a=0;if(0===e.length)return"Idag er det stille på huset :-/";for(a=0;a<e.length;a++){var s=e[a];t+='<li class="fadein">',t+='<span class="entry-starttime">'+moment.unix(s.custom_fields._neuf_events_starttime).utc().format("HH.mm")+"</span>: ",t+='<a href="'+s.url+'">'+s.title+"</a>",t+=' <span class="entry-venue">'+s.custom_fields._neuf_events_venue+"</span>",t+="</li>"}return t}function format_results(e){var t="<table><thead><tr><th>Navn</th><th>Brukernavn</th><th>Epost</th><th>Telefon</th><th>Har medlemskap</th><th>Grupper</th></tr></thead>";if(e.error)return console.log(e.error),$(".search-result-list").html(""),void $(".search-results .meta").html("");if(0===e.meta.num_results)return $(".search-result-list").html("Uffda, ingen treff."),void $(".search-results .meta").html("");var a='<tbody><% _.each(results, function(u) { %><tr><td><a href="'+user_profile_url+'?username=<%= u.username %>"><%= u.firstname %> <%= u.lastname %></a></td><td><%= u.username %></td><td><a href="mailto:<%= u.email %>"><%= u.email %></a></td><td><a href="tlf:<%= u.number %>"><%= u.number %></a></td><td><% if(u.is_member != 0) { %>Ja<% } else { %>Nei<% } %></td><td class="groups"><% _.each(u.groups, function(g, i) { %><%= g.name %><% if(u.groups.length !== i +1){ %>, <% } %><% }); %></td></tr> <% }); %></tbody></table>',s=t+_.template(a)(e);$(".search-result-list").html(s);var n="Antall treff: <%= meta.num_results %>";$(".search-results .meta").html(_.template(n)(e))}function do_search(){var e=$('.filters [data-search-filter="has_valid_membership"]'),t="",a=$(".groups-select").val();null!==a&&(t=a.join());var s={q:$(".search-field").val(),filter_groups:t};e.hasClass("active")&&(s.has_valid_membership=!0);var n=$.param(s);window.History.pushState(null,null,"?"+n),s._wpnonce=$("meta[name=x-inside-api-nonce]").attr("content"),$.getJSON(user_search_endpoint,s,format_results)}function user_search_load_initial_values(){var e=!1,t=getParameterByName("q");t&&t.length>0&&($(".search-field").val(t),e=!0);var a=getParameterByName("has_valid_membership");a&&"true"===a&&($('.filters [data-search-filter="has_valid_membership"]').addClass("active"),e=!0);var s=getParameterByName("filter_groups"),n=[];if(s&&s.length>0){n=s.indexOf(",")!==-1?s.split(","):[s];var r=!1,i=0;for(i=0;i<n.length;i++){var l=n[i];$.isNumeric(l)||(r=!0)}r||(e=!0,$(".groups-select").val(n),$(".groups-select").trigger("chosen:updated"))}e&&do_search()}function save_user_meta(e,t){try{localStorage.setItem(e,t)}catch(e){}var a=$("meta[name=x-siteurl]").attr("content")+"/wp-admin/admin-ajax.php",s={action:"neuf_save_user_meta",meta_key:e,meta_value:t,unique:!0,user_id:parseInt($("meta[name=x-user-id]").attr("content"),10),_wpnonce:$("meta[name=x-user-meta-nonce]").attr("content")};$.post(a,s)}function get_user_meta(e,t){try{var a=localStorage.getItem(e);if(null!==a)return void t({result:a})}catch(e){}var s=$("meta[name=x-siteurl]").attr("content")+"/wp-admin/admin-ajax.php",n={action:"neuf_get_user_meta",meta_key:e,single:!0,user_id:parseInt($("meta[name=x-user-id]").attr("content"),10),_wpnonce:$("meta[name=x-user-meta-nonce]").attr("content")};$.getJSON(s,n,function(a){if(a.hasOwnProperty("result")&&""!==a.result)try{localStorage.setItem(e,a.result)}catch(e){}t(a)})}function mailing_list_show(e){$.getJSON(email_endpoint,{source:e,_wpnonce:$("meta[name=x-inside-api-nonce]").attr("content")},function(t){var a=t.lists[0];console.log(a);var s='<h5 class="list-members-title">Medlemmer på <%= name %></h5><span class="list-num-members"><%= destinations.length %> stk</span><ul id="members-result"><% _.each(destinations, function(d) { %><li><a href="mailto:<%= d %>"><span class="dashicons dashicons-email"></span> <%= d %></a></li><% }); %></ul>',n=_.template(s)(a);$(".list-members").html(n),$(".lists-list-wrap .meta").html('<a href="#members-result" class="button radius list-members-button">Vis medlemmer på '+a.name+"</a>"),$("[data-list-name='"+e+"']").addClass("selected")})}function mailing_lists_load_initial(){var e=getParameterByName("source");e&&e.length>0&&mailing_list_show(e)}var program_endpoint="https://studentersamfundet.no/api/events/get_today/",inside_url="https://inside.studentersamfundet.no",inside_groups_url=inside_url+"/api/groups.php",user_search_endpoint="/inside-api/",email_endpoint="/email-api/",user_profile_url="/profil/",query_params={};jQuery(document).ready(function(){moment.locale("nb");var e=jQuery;e("#menu .menu-toggle").on("click",function(t){t.preventDefault();var a=e("#menu .main-menu, #menu .user-menu-wrap");a.toggleClass("visible")}),e("#menu a.profile-badge").on("click",function(t){t.preventDefault();var a=e("#menu .user-menu");a.toggleClass("visible")}),e("[data-toggle-introduction]").on("click",function(){e(this).parent().fadeOut(600),save_user_meta("introduction_dismissed",!0)}),e(".front-page .introduction").length&&get_user_meta("introduction_dismissed",function(t){t.hasOwnProperty("result")&&"true"!==t.result&&(e(".introduction").show(),e(".introduction").addClass("fadein"))});var t=".program-list";if(e(t).length&&e.getJSON(program_endpoint+"?callback=?",query_params,function(a){if(a&&a.events){var s=format_posts(a.events);e(t).html(s)}}),e(".search-form").length&&(e.getJSON(inside_groups_url,function(t){var a='<select multiple class="groups-select" data-placeholder="Velg en gruppe..."><option value=""></option><% _.each(results, function(g) { %><option value="<%=g.group_id %>"><%= g.group_name %></option><% }); %></select>',s=_.template(a)(t);e(".groups-select-wrap").html(s),e(".groups-select").chosen({no_results_text:"Uffda, ingen treff!",allow_single_deselect:!0}).change(function(){do_search()}),user_search_load_initial_values()}),e(".search-field").on("keyup keypress",function(e){10!==e.keyCode&&13!==e.keyCode||e.preventDefault()}),e(".search-field").on("keyup keypress",_.debounce(function(){do_search()},300)),e(".roles a").on("click",function(t){t.preventDefault(),e(this).toggleClass("active"),do_search()})),e(".page-mailinglists").length&&e.getJSON(email_endpoint,{_wpnonce:e("meta[name=x-inside-api-nonce]").attr("content")},function(t){if(t.error)return void e(".lists-list").html(t.error);t.lists=_.filter(t.lists,function(e){return e.num>1});var a='<% _.each(lists, function(l) { %><li data-list-name="<%= l.name %>"><a href="'+window.location.pathname+'?source=<%= l.name %>" class="list-name"><%= l.name %></a><br><span class="list-num-members"><%= l.num %> medlemmer</span><a href="<%= l.admin_url %>" class="email-<%= l.type %> button-alt" title="<%= l.type %>"> <span class="dashicons dashicons-<% if( l.admin_type == "selfservice" ) { %>edit<% } else { %>email<% } %>"> </span>Endre</a></li><% }); %>',s=_.template(a)(t);e(".lists-list").html(s);var n={valueNames:["list-name","list-num-members"]};new List("mailinglists",n),mailing_lists_load_initial()}),e(".profile").length){var a={q:e("meta[name=x-username]").attr("content"),exact:!0,_wpnonce:e("meta[name=x-inside-api-nonce]").attr("content")};""!==getParameterByName("username")&&(a.q=getParameterByName("username")),e.getJSON(user_search_endpoint,a,function(t){if(t.hasOwnProperty("results")&&1===t.results.length){for(var a=t.results[0],s="",n=0;n<a.groups.length;n++)s+="<li>"+a.groups[n].name+"</li>";e(".group-list").html(s);var r=e(".is-member");"1"===a.is_member?r.html("Gyldig medlemskap."):r.html('Du har ikke et gyldig medlemskap. Du kan forny medlemskapet ditt via SMS, via <a href="http://snappordel.com">SnappOrder</a> eller via nettbutikken i <a href="https://inside.studentersamfundet.no">Inside</a>.'),e(".js-inside-link").attr("href","https://inside.studentersamfundet.no/?page=display-user&userid="+a.id)}}),a.destination=e(".profile-details .user-email").text(),e.getJSON(email_endpoint,a,function(t){var a='<% _.each(lists, function(r) { %><li><%= r.name %> <a href="<%= r.admin_url %>" class="email-<%= r.type %>" title="<%= r.type %>"><span class="dashicons dashicons-<% if( r.admin_type == "selfservice" ) { %>edit<% } else { %>email<% } %>"></span></a></li><% }); %>',s=_.template(a)(t);e(".account-details .email-list").html(s)})}});