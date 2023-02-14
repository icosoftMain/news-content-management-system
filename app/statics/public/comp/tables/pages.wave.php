<table id="tbl-admin-page" class="table border">
    <thead>
        <tr class="right_align"></tr>
        <tr class="centerit">
            <th>Title</th>
            <th class="center-child">Page Type</th>
            <th class="center-child">Published</th>
            <th class="center-child">Date Added</th>
            <th class="center-child">Last Visited</th>
            <th class="center-child">Edit</th>
            <th class="center-child">Delete</th>
        </tr>
    </thead>
    <tbody>
        @each $pages as $key => $page:
            @if $key === 'pagLim': @thenend @endif
            <tr>
                <td>{# $page['title'] #}</td>
                <td class="center-child">{# $page['pageType'] #}</td>
                <td class="center-child">{# $page['published'] #}</td>
                <td class="center-child">{# $page['dateAdded'] #}</td>
                <td class="center-child">{# $page['lastVisited'] #}</td>
                <td class="center-child">
                    <a href="{# url(':edit_page?page='.$page['pageId']) #}" class="edit_category" title="Edit"><i class="fa fa-edit"></i></a>
                </td>
                <td class="center-child">
                    <input type="hidden" class="pageId_token" value="{# $page['pageId'] #}"/>
                    <a href="#!" class="delete_page" title="Delete" data-toggle="modal" data-target="#deletePageModal"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
        @endeach
    </tbody>
</table>

<wv-comp.modal
    deleteModal
    modalId={deletePageModal}
    footerComponent={
        <button class="btn btn-primary" id="delete_page">Yes</button>&nbsp;
        <button class="btn btn-custom modal-close" data-dismiss="modal">No</button>
    }
>
    <div style="font-size: 22px;">
        <i class="fa fa-warning red-text"></i>
        Are you sure you want to delete this page ?
    </div>
</wv-comp.modal>