<table id="tbl-admin-speakers" class="table border">
    <thead>
        <tr class="right_align"></tr>
        <tr class="centerit">
            <th>Image</th>
            <th class="center-child">Title</th>
            <th class="center-child">Full Name</th>
            <th class="center-child">Email</th>
            <th class="center-child">Phone Number</th>
            <th class="center-child">Assign Event</th>
            <th class="center-child">View Details</th>
            <th class="center-child">View Schedules</th>
            <th class="center-child">Edit Details</th>
            <th class="center-child">Delete Details</th>
        </tr>
    </thead>
    <tbody>
        @each $speakers as $key => $speaker:
            @if $key === 'pagLim': @thenend @endif

            <tr>
                <td>
                    <img 
                        class="table-pic"
                        src="@statics('images/speakers_pics/'.$speaker['imageName'])"
                        alt="{# $speaker['lastName'].'\'s picture' #}"
                    />
                </td>
                <td class="center-child">{# $speaker['title'] #}</td>
                <td class="center-child">{# "{$speaker['firstName']} {$speaker['lastName']}" #}</td>
                <td class="center-child">{# $speaker['email'] #}</td>
                <td class="center-child">{# $speaker['phoneNumber'] #}</td>
                <td class="center-child">
                    <a href="@url(':add_speaker_schedules?speaker='.$speaker['speakerId'])" class="assign_event" title="Assign Event"><i class="fa fa-calendar"></i></a>
                </td>
                <td class="center-child">
                    <a href="#!" class="view_speaker" title="View Speaker Details" data-toggle="modal" data-target="#{# 'about'.$speaker['speakerId'] #}"><i class="fa fa-eye"></i></a>
                </td>
                <td class="center-child">
                    <a href="@url(':assigned_schedules?speaker='.$speaker['speakerId'])" class="view_schedule" title="View Schedule"><i class="fa fa-eye"></i><i class="fa fa-calendar"></i></a>
                </td>
                <td class="center-child">
                    <a href="{# url(':edit_speaker?speaker='.$speaker['speakerId']) #}" class="edit_speaker" title="Edit"><i class="fa fa-edit"></i></a>
                </td>
                <td class="center-child">
                    <input type="hidden" class="speakerId_token" value="{# $speaker['speakerId'] #}"/>
                    <a href="#!" class="delete_speaker" title="Delete Speaker" data-toggle="modal" data-target="#deleteSpeakerModal"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
        @endeach
    </tbody>
</table>

@each $speakers as $speaker:
    <wv-comp.staticModal 
        TargetId={{# 'about'.$speaker['speakerId'] #}}
        Title={
            <fml_fragment>
                <i class="fa fa-user"></i>
                {#$speaker['firstName']."'s details"#}
            </fml_fragment>
        }
    >
        <wv-comp.speakerImage 
            W={30}
            H={155}
            imageName={images/speakers_pics/{$speaker['imageName']}}
        />
        <br/>
        <div style="font-size: 19.5px;">
          {# html_entity_decode($speaker['about']) #}    
        </div>
    </wv-comp.staticModal>
@endeach

<wv-comp.modal 
    deleteModal
    modalId={deleteSpeakerModal}
    footerComponent={
        <button class="btn btn-primary" id="delete_speaker">Yes</button>&nbsp;
        <button class="btn btn-custom modal-close" data-dismiss="modal">No</button>
    }
>
    <div style="font-size: 22px;">
        <i class="fa fa-warning red-text"></i>
        Are you sure you want to delete this event ?
    </div>
</wv-comp.modal>