"use strict";function format_posts(e){var t="",r=0;if(0===e.length)return"";for(t+='<thead><tr class="fadein"><th width="53%">Arrangement</th><th>Sted</th><th>Tid</th></tr></thead>',r=0;r<e.length;r++){var n=e[r];t+='<tr class="fadein">',t+='<td class="entry-title"><a href="'+n.url+'">'+n.title+"</a></td>",t+='<td class="entry-venue">'+n.custom_fields._neuf_events_venue+"</td>",t+='<td class="entry-starttime">'+moment.unix(n.custom_fields._neuf_events_starttime).utc().format("DD. MMMM YYYY, HH.mm")+"</td></tr>"}return t}function format_results(e){var t="<table><thead><tr><th>Navn</th><th>Brukernavn</th><th>Epost</th><th>Telefon</th><th>Medlem</th><th>Grupper</th></tr></thead>";if(e.error)return console.log(e.error),$(".search-result-list").html(""),void $(".search-results .meta").html("");if(0===e.meta.num_results)return $(".search-result-list").html("Ingen treff."),void $(".search-results .meta").html("");var r='<tbody><% _.each(results, function(u) { %><tr><td><a href="'+inside_url+'/?page=display-user&userid=<%= u.id %>"><%= u.firstname %> <%= u.lastname %></a></td><td><%= u.username %></td><td><a href="mailto:<%= u.email %>"><%= u.email %></a></td><td><a href="tlf:<%= u.number %>"><%= u.number %></a></td><td><% if(u.is_member != 0) { %>Ja<% } else { %>Nei<% } %></td><td class="groups"><%= u.groups %></td></li> <% }); %></tbody></table>',n=t+_.template(r,e);$(".search-result-list").html(n);var s="Antall treff: <%= meta.num_results %>";$(".search-results .meta").html(_.template(s,e))}var program_endpoint="https://studentersamfundet.no/api/events/get_upcoming/",inside_url="https://inside.studentersamfundet.no",inside_groups_url=inside_url+"/api/groups.php",user_search_endpoint="/inside-api/",query_params={count:10};jQuery(document).ready(function(){moment.lang("nb");var e=jQuery;e("#menu .menu-toggle").on("click",function(t){t.preventDefault();var r=e("#menu .main-menu, #menu .user-menu-wrap");r.toggleClass("visible")}),e("#menu a.profile-badge").on("click",function(t){t.preventDefault();var r=e("#menu .user-menu");r.toggleClass("visible")}),e("[data-toggle-introduction]").on("click",function(){e(this).parent().fadeOut(600),e.cookie("introduction-dismissed","1")}),"1"!==e.cookie("introduction-dismissed")&&(e(".introduction").show(),e(".introduction").addClass("fadein"));var t=".program-list";e(t).length&&e.getJSON(program_endpoint+"?callback=?",query_params,function(r){if(r&&r.events){var n=format_posts(r.events);e(t).html(n)}}),e(".search-form").length&&(e.getJSON(inside_groups_url,function(t){var r='<select class="groups-select" data-placeholder="Velg en gruppe..."><option value=""></option><% _.each(results, function(g) { %><option value="<%=g.group_id %>"><%= g.group_name %></option><% }); %></select>',n=_.template(r,t);e(".groups-select-wrap").html(n),e(".groups-select").chosen({no_results_text:"Isjda, ingen treff!",allow_single_deselect:!0}).change(function(t){e.getJSON(user_search_endpoint,{q:e(".search-field").val(),filter_groups:t.target.value,_wpnonce:e("meta[name=x-inside-api-nonce").attr("content")},format_results)})}),e(".search-field").focus(),e(".search-field").on("keyup keypress",function(e){(10===e.keyCode||13===e.keyCode)&&e.preventDefault()}),e(".search-field").on("keyup keypress",_.debounce(function(t){e.getJSON(user_search_endpoint,{q:t.target.value,filter_groups:e(".groups-select").val(),_wpnonce:e("meta[name=x-inside-api-nonce").attr("content")},format_results)},300)))});