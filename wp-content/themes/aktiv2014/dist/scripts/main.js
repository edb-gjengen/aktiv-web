"use strict";function getParameterByName(e){e=e.replace(/[\[]/,"\\[").replace(/[\]]/,"\\]");var t=new RegExp("[\\?&]"+e+"=([^&#]*)"),a=t.exec(location.search);return null===a?"":decodeURIComponent(a[1].replace(/\+/g," "))}function format_posts(e){var t="",a=0;if(0===e.length)return"Idag er det stille på huset :-/";for(a=0;a<e.length;a++){var r=e[a];t+='<li class="fadein">',t+='<span class="entry-starttime">'+moment.unix(r.custom_fields._neuf_events_starttime).utc().format("HH.mm")+"</span>: ",t+='<a href="'+r.url+'">'+r.title+"</a>",t+=' <span class="entry-venue">'+r.custom_fields._neuf_events_venue+"</span>",t+="</li>"}return t}function format_results(e){var t="<table><thead><tr><th>Navn</th><th>Brukernavn</th><th>Epost</th><th>Telefon</th><th>Har medlemskap</th><th>Grupper</th></tr></thead>";if(e.error)return console.log(e.error),$(".search-result-list").html(""),void $(".search-results .meta").html("");if(0===e.meta.num_results)return $(".search-result-list").html("Uffda, ingen treff."),void $(".search-results .meta").html("");var a='<tbody><% _.each(results, function(u) { %><tr><td><a href="'+inside_url+'/?page=display-user&userid=<%= u.id %>"><%= u.firstname %> <%= u.lastname %></a></td><td><%= u.username %></td><td><a href="mailto:<%= u.email %>"><%= u.email %></a></td><td><a href="tlf:<%= u.number %>"><%= u.number %></a></td><td><% if(u.is_member != 0) { %>Ja<% } else { %>Nei<% } %></td><td class="groups"><% _.each(u.groups, function(g, i) { %><%= g.name %><% if(u.groups.length !== i +1){ %>, <% } %><% }); %></td></tr> <% }); %></tbody></table>',r=t+_.template(a,e);$(".search-result-list").html(r);var s="Antall treff: <%= meta.num_results %>";$(".search-results .meta").html(_.template(s,e))}function do_search(){var e=$('.filters [data-search-filter="has_valid_membership"]'),t="",a=$(".groups-select").val();null!==a&&(t=a.join());var r={q:$(".search-field").val(),filter_groups:t};e.hasClass("active")&&(r.has_valid_membership=!0);var s=$.param(r);window.History.pushState(null,null,"?"+s),r._wpnonce=$("meta[name=x-inside-api-nonce]").attr("content"),$.getJSON(user_search_endpoint,r,format_results)}function load_initial_values(){var e=!1,t=getParameterByName("q");t&&t.length>0&&($(".search-field").val(t),e=!0);var a=getParameterByName("has_valid_membership");a&&"true"===a&&($('.filters [data-search-filter="has_valid_membership"]').addClass("active"),e=!0);var r=getParameterByName("filter_groups"),s=[];if(r&&r.length>0){s=-1!==r.indexOf(",")?r.split(","):[r];var n=!1,i=0;for(i=0;i<s.length;i++){var l=s[i];$.isNumeric(l)||(n=!0)}n||(e=!0,$(".groups-select").val(s),$(".groups-select").trigger("chosen:updated"))}e&&do_search()}var program_endpoint="https://studentersamfundet.no/api/events/get_today/",inside_url="https://inside.studentersamfundet.no",inside_groups_url=inside_url+"/api/groups.php",user_search_endpoint="/inside-api/",email_search_endpoint="/email-api/",query_params={};jQuery(document).ready(function(){moment.lang("nb");var e=jQuery;e("#menu .menu-toggle").on("click",function(t){t.preventDefault();var a=e("#menu .main-menu, #menu .user-menu-wrap");a.toggleClass("visible")}),e("#menu a.profile-badge").on("click",function(t){t.preventDefault();var a=e("#menu .user-menu");a.toggleClass("visible")}),e("[data-toggle-introduction]").on("click",function(){e(this).parent().fadeOut(600),e.cookie("introduction-dismissed","1")}),"1"!==e.cookie("introduction-dismissed")&&(e(".introduction").show(),e(".introduction").addClass("fadein"));var t=".program-list";if(e(t).length&&e.getJSON(program_endpoint+"?callback=?",query_params,function(a){if(a&&a.events){var r=format_posts(a.events);e(t).html(r)}}),e(".search-form").length&&(e.getJSON(inside_groups_url,function(t){var a='<select multiple class="groups-select" data-placeholder="Velg en gruppe..."><option value=""></option><% _.each(results, function(g) { %><option value="<%=g.group_id %>"><%= g.group_name %></option><% }); %></select>',r=_.template(a,t);e(".groups-select-wrap").html(r),e(".groups-select").chosen({no_results_text:"Uffda, ingen treff!",allow_single_deselect:!0}).change(function(){do_search()}),load_initial_values()}),e(".search-field").on("keyup keypress",function(e){(10===e.keyCode||13===e.keyCode)&&e.preventDefault()}),e(".search-field").on("keyup keypress",_.debounce(function(){do_search()},300)),e(".roles a").on("click",function(t){t.preventDefault(),e(this).toggleClass("active"),do_search()})),e(".profile").length){var a={q:e("meta[name=x-username]").attr("content"),exact:!0,_wpnonce:e("meta[name=x-inside-api-nonce]").attr("content")};e.getJSON(user_search_endpoint,a,function(t){if(t.hasOwnProperty("results")&&1===t.results.length){var a=t.results[0];console.log(a);for(var r="",s=0;s<a.groups.length;s++)r+="<li>"+a.groups[s].name+"</li>";e(".group-list").html(r);var n=e(".is-member");n.html("1"===a.is_member?"Gyldig medlemskap.":'Du har ikke et gyldig medlemskap. Du kan forny medlemskapet ditt via SMS, via <a href="http://snapporder.com">SnappOrder</a> eller via nettbutikken i <a href="https://inside.studentersamfundet.no">Inside</a>.')}}),a.q=e(".profile-details .user-email").text(),a.inherited=!0,e.getJSON(email_search_endpoint,a,function(t){var r='<% _.each(results, function(r) { %><li><%= r.name %> <a href="<%= r.admin_url %>" class="email-<%= r.type %>" title="<%= r.type %>"><span class="dashicons dashicons-<% if( r.admin_type == "selfservice" ) { %>edit<% } else { %>email<% } %>"></span></a></li><% }); %>',s=_.template(r,t);e(".account-details .email-list").html(s),a.inherited&&(t.results=t.inherited_results,s=_.template(r,t),e(".account-details .email-list-inherited").html(s))})}});