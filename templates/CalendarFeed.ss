<% if Image %><% control Image.setWidth(150) %>
    <img src="$AbsoluteURL" width="$Width" height="$Height" alt="$Title" />
<% end_control %><% end_if %>
<p>$StartDate.Nice()<% if EndDate %> - $EndDate.Nice()<% end_if %>
$Text
<% if Link %><p>Mehr unter: <a href="$AbsoluteLink"><% if Linktitle %>$Linktitle<% else %>Link<% end_if %></a></p><% end_if %>