<table id="tbl-admin-messages" class="table border">
    <thead>
        <tr class="right_align"></tr>
        <tr class="centerit">
            <th>Full Name</th>
            <th class="center-child">Subject</th>
            <th class="center-child">Email</th>
            <th class="center-child">Date Received</th>
            <th class="center-child">View Message</th>
            <th class="center-child">Delete</th>
        </tr>
    </thead>
    <tbody>
        @each $messages as $key => $msg:
            @if $key === 'pagLim': @thenend @endif
            <tr>
                <td>{# $msg['fullName'] #}</td>
                <td class="center-child">{# $msg['subject'] #}</td>
                <td class="center-child">{# $msg['email'] #}</td>
                <td class="center-child">{# dateQuery($msg['dateReceived'],'D, d-M-Y \a\t h:i A') #}</td>
                <td class="center-child">
                    <a href="#!" class="view_message" title="Read Message" data-toggle="modal" data-target="#{#'view_'.$msg['contactId']#}"><i class="fa fa-eye"></i></a>
                </td>
                <td class="center-child">
                    <input type="hidden" class="contactId_token" value="{# $msg['contactId'] #}"/>
                    <a href="#!" class="delete_message" title="Delete" data-toggle="modal" data-target="#deleteMessageModal"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
        @endeach
    </tbody>
</table>
@each $messages as $msg:

    <wv-comp.staticModal 
            TargetId={view_{#$msg['contactId']#}}
            Title={
                <fml_fragment>
                    <i class="fa fa-user"></i>
                    {#$msg['fullName']."'s message"#}
                </fml_fragment>
            }
            Component={
                <button 
                    id="{#$msg['contactId']#}" 
                    type="button" 
                    class="btn btn-secondary {# $msg['status'] === 'read' ? 'btn_mark_unread': 'btn_mark_read'#}">
                    <i class="fa fa-check"></i>
                    <span>{# $msg['status'] === 'read' ? 'Mark as Unread': 'Mark as Read'#}</span>
                </button>
            }
        >
        <br/>
        <div style="font-size: 19.5px;">
            {# $msg['message'] #}    
        </div>
    </wv-comp.staticModal>
@endeach

<wv-comp.modal
    deleteModal
    modalId={deleteMessageModal}
    footerComponent={
        <button class="btn btn-primary" id="delete_message">Yes</button>&nbsp;
        <button class="btn btn-custom modal-close" data-dismiss="modal">No</button>
    }
>
    <div style="font-size: 22px;">
        <i class="fa fa-warning red-text"></i>
        Are you sure you want to delete this message ?
    </div>
</wv-comp.modal>