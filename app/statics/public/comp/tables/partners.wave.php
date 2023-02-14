<table id="tbl-admin-partners" class="table border">
    <thead>
        <tr class="right_align"></tr>
        <tr class="centerit">
            <th>Partner Name</th>
            <th class="center-child">Partner Website</th>
            <th class="center-child">Edit</th>
            <th class="center-child">Delete</th>
        </tr>
    </thead>
    <tbody>
        @each $partners as $key => $partner:
            @if $key === 'pagLim': @thenend @endif
            <tr>
                <td>{# $partner['partName'] #}</td>
                <td class="center-child">
                    <a href="{# $partner['partWebName'] #}" target="_blank">{# $partner['partWebName'] #}</a>
                </td>
                <td class="center-child">
                    <a href="{# url(':edit_partner?partner='.$partner['partId']) #}" class="edit_category" title="Edit"><i class="fa fa-edit"></i></a>
                </td>
                <td class="center-child">
                    <input type="hidden" class="partId_token" value="{# $partner['partId'] #}"/>
                    <a href="#!" class="delete_partner" title="Delete" data-toggle="modal" data-target="#deletePartnerModal"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
        @endeach
    </tbody>
</table>

<wv-comp.modal
    deleteModal
    modalId={deletePartnerModal}
    footerComponent={
        <button class="btn btn-primary" id="delete_partner">Yes</button>&nbsp;
        <button class="btn btn-custom modal-close" data-dismiss="modal">No</button>
    }
>
    <div style="font-size: 22px;">
        <i class="fa fa-warning red-text"></i>
        Are you sure you want to delete this partner ?
    </div>
</wv-comp.modal>