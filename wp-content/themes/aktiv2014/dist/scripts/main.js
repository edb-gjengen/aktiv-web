"use strict";function format_posts(t){var e="",n=0;if(0===t.length)return"";for(e+='<thead><tr class="fadein"><th width="53%">Arrangement</th><th>Sted</th><th>Tid</th></tr></thead>',n=0;n<t.length;n++){var r=t[n];e+='<tr class="fadein">',e+='<td class="entry-title"><a href="'+r.url+'">'+r.title+"</a></td>",e+='<td class="entry-venue">'+r.custom_fields._neuf_events_venue+"</td>",e+='<td class="entry-starttime">'+moment.unix(r.custom_fields._neuf_events_starttime).utc().format("DD. MMMM YYYY, HH.mm")+"</td></tr>"}return e}var program_endpoint="https://studentersamfundet.no/api/events/get_upcoming/",inside_url="https://inside.studentersamfundet.no",user_search_endpoint=inside_url+"/api/user.php",query_params={count:10},entrypoint=".program-list";jQuery(document).ready(function(){moment.lang("nb");var t=jQuery;t.getJSON(program_endpoint+"?callback=?",query_params,function(e){if(e&&e.events){var n=format_posts(e.events);t(entrypoint).html(n)}}),t("#menu .menu-toggle").on("click",function(e){e.preventDefault();var n=t("#menu .main-menu, #menu .user-menu-wrap");n.toggleClass("visible")}),t("#menu a.profile-badge").on("click",function(e){e.preventDefault();var n=t("#menu .user-menu");n.toggleClass("visible")}),t("[data-toggle-introduction]").on("click",function(){t(this).parent().fadeOut(600),t.cookie("introduction-dismissed","1")}),"1"!==t.cookie("introduction-dismissed")&&(t(".introduction").show(),t(".introduction").addClass("fadein")),t(".search-field").focus();var e="<table><thead><tr><th>Navn</th><th>Brukernavn</th><th>Epost</th><th>Telefon</th></tr></thead>";t(".search-field").on("keyup",_.debounce(function(n){n.target.value.length>2?t.getJSON(user_search_endpoint,{q:n.target.value},function(n){if(0===n.meta.num_results)return t(".search-result-list").html("Ingen treff."),void t(".search-results .meta").html("");var r='<tbody><% _.each(results, function(u) { %><tr><td><a href="'+inside_url+'/?page=display-user&userid=<%= u.id %>"><%= u.firstname %> <%= u.lastname %></a></td><td><%= u.username %></td><td><a href="mailto:<%= u.email %>"><%= u.email %></a></td><td><a href="tlf:<%= u.number %>"><%= u.number %></a></td></li> <% }); %></tbody></table>',a=e+_.template(r,n);t(".search-result-list").html(a);var s="Antall treff: <%= meta.num_results %>";t(".search-results .meta").html(_.template(s,n))}):console.log("too short")},300))});