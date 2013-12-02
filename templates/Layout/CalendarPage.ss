$Content

<table id="calendartable">
    <colgroup><col width="*"><col width="*"></colgroup>
    <tr>
        <td>
            <% if $Events %>
                <%-- if $Events.MoreThanOnePage --%>
                    <div class="calendarentry" id="calendarpagesbox">
                    <% if $Events.NotFirstPage %>
                        <a class="prev" href="$Events.PrevLink"><<</a>
                    <% end_if %>
                    <% loop $Events.Pages %>
                        <% if $CurrentBool %>
                            <div id="calendarpagescurrent">$PageNum</div>
                        <% else %>
                            <% if $Link %>
                                <a href="$Link">$PageNum</a>
                            <% else %>
                                ...
                            <% end_if %>
                        <% end_if %>
                        <% end_loop %>
                    <% if $Events.NotLastPage %>
                        <a class="next" href="$Events.NextLink">>></a>
                    <% end_if %>
                    </div>
                <%-- end_if --%>
                <% loop $Events %>
                        <% include CalendarEntry %>
                <% end_loop %>
            <% else %>
                <div class="error">
                <h1>There are no upcoming events.</h1>
                </div>
            <% end_if %>
        </td>
        <td>
            <div id="calendar" class="typography"></div>
            <div id="calendarpoweredby"><strong>Powered by <a href="http://arshaw.com/fullcalendar/">FullCalendar</a></strong> (MIT License)</div>
        </td>
    </tr>
</table>