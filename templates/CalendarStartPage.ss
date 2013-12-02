<% require css(calendar/css/calendar.css) %>

<% if Entries %>
    <% loop Entries %>
    <% if Image %>
        <% control Image.setWidth(150) %>
            <img class="calendarimage" src="$URL" width="$Width" height="$Height" alt="$Title" />
        <% end_control %>
    <% end_if %>

    <div class="calendarcontent typography">
        <h3>$Title</h3>
        <p class="date">
            <strong>$StartDate.FormatI18N('%A, den %d. %B %G')
            <% if $StartDate.Time && $StartDate.Time != "12:00am" %>$StartDate.FormatI18N('- %H:%M Uhr')<% end_if %></strong>
            <% if $EndDate && $EndDate != $StartDate %><br/>bis&nbsp;<strong>$EndDate.FormatI18N('%A, den %d. %B %G')<% end_if %>
            <% if $EndDate.Time && $EndDate.Time != "12:00am" %>$EndDate.FormatI18N('- %H:%M Uhr')<% end_if %></strong>
        </p>

        <% if Description %>
                <p>$Description</p>
        <% end_if %>

        <% if Link %>
                <p>Mehr unter: <a href="$Link" target="_blank"><% if Linktitle %>$Linktitle<% else %>$Link<% end_if %></a></p>
        <% end_if %>
        <div class="clear"></div>
    </div>
    <% end_loop %>
<% else %>
    <p><strong>Entschuldigung, leider sind keine Einträge vorhanden.</strong></p>
<% end_if %>