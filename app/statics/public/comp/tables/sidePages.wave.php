<table id="tbl-admin-side-page" class="table border">
    <thead>
        <tr class="right_align"></tr>
        <tr class="centerit">
            <th>Header Title</th>
            <th class="center-child">Publish</th>
            <th class="center-child">Add Link</th>
            <th class="center-child">Add PDF</th>
            <th class="center-child">View Items</th>
            <th class="center-child">Edit</th>
            <th class="center-child">Delete</th>
        </tr>
    </thead>
    <tbody>
        @each $sidePages as $key => $sidePage:
            @if $key === 'pagLim': @thenend @endif
            <tr>
                <td>{# $sidePage['pageName'] #}</td>
                <td class="center-child">{# $sidePage['publish'] #}</td>
                <td class="center-child">
                    <a href="@url(':add_side_itm?ps='.$sidePage['id'].'&t=link')" title="Add Link"><i class="fa fa-plus"></i><i class="fa fa-link"></i></a>
                </td>
                <td class="center-child">
                    <a href="@url(':add_side_itm?ps='.$sidePage['id'].'&t=doc')" title="Add PDF"><i class="fa fa-plus"></i><i class="fa fa-file"></i></a>
                </td>
                <td class="center-child">
                    <a href="#!" title="View Item" data-toggle="modal" data-target="#viewPageItemModal{# $sidePage['id'] #}"><i class="fa fa-eye"></i></a>
                </td>
                <td class="center-child">
                    <a href="{# url(':edit_sidepage?page='.$sidePage['id']) #}" class="edit_category" title="Edit"><i class="fa fa-edit"></i></a>
                </td>
                <td class="center-child">
                    <input type="hidden" class="id_token" value="{# $sidePage['id'] #}"/>
                    <a href="#!" class="delete_sidepage" title="Delete" data-toggle="modal" data-target="#deleteSidePageModal"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
        @endeach
    </tbody>
</table>

@each $sidePages as $sidePage:
    <wv-comp.staticModal 
        TargetId={viewPageItemModal{# $sidePage['id'] #}}
        Title={
            <fml_fragment>
                <i class="fa fa-link"></i>
                Link List
            </fml_fragment>
        }
    >
        <wv-comp.tables.sideLinks/>
    </wv-comp.staticModal>
@endeach


<wv-comp.modal
    deleteModal
    modalId={deleteSidePageModal}
    footerComponent={
        <button class="btn btn-primary" id="delete_sidepage">Yes</button>&nbsp;
        <button class="btn btn-custom modal-close" data-dismiss="modal">No</button>
    }
>
    <div style="font-size: 22px;">
        <i class="fa fa-warning red-text"></i>
        Are you sure you want to delete this side header, changes cannot be undone ?
    </div>
</wv-comp.modal>

<wv-comp.modal
    deleteModal
    modalId={deleteLinkModal}
    footerComponent={
        <button class="btn btn-primary" id="delete_sidelink">Yes</button>&nbsp;
        <button class="btn btn-custom modal-close" data-dismiss="modal">No</button>
    }
>
    <div style="font-size: 22px;">
        <i class="fa fa-warning red-text"></i>
        Are you sure you want to delete this link, changes cannot be undone ?
    </div>
</wv-comp.modal>