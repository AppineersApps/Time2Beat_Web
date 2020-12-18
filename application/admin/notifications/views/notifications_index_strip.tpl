<div class="headingfix">
    <!-- Top Header Block -->
    <div class="heading" id="top_heading_fix">
		<!-- Top Strip Title Block -->
        <h3>
            <div class="screen-title">
                <%$this->lang->line('GENERIC_LISTING')%> :: 
                <%if $smarty.get.men_notification_type eq 'SMS'%>
                    Sent SMS
                <%elseif $smarty.get.men_notification_type eq 'EmailNotify'%>
                    Sent Emails
                <%elseif $smarty.get.men_notification_type eq 'DesktopNotify'%>
                    Desktop Notifications
                <%else%>
                    <%$this->lang->line('NOTIFICATIONS_NOTIFICATIONS')%>
                <%/if%>
            </div>        
        </h3>
		<!-- Top Strip Dropdown Block -->
        <div class="header-right-drops">
            
            
        </div>
    </div>
</div>    