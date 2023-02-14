<table id="tbl-admin-page-links" class="table border">
    <thead>
        <tr class="right_align"></tr>
        <tr class="centerit">
            <th>Link</th>
            <th class="center-child">Type</th>
            <th class="center-child">Edit Link</th>
            <th class="center-child">Delete Link</th>
        </tr>
    </thead>
    <tbody>
        @each $pageLinks as $lk:
            @if $lk['spId'] <> $sidePage['id']: @thenskip @endif
            <tr>        
                @if($lk['lType'] === 'linked'):
                    <td><a target="_blank" href="{# $lk['item'] #}">{# $lk['levelName'] #}</a></>
                    <td class="center-child" title="link"><i class="fa fa-link"></i></td>
                    <td class="center-child">
                        <a href="{# url(':edit_side_itm?link='.$lk['levelId']).'&t=link' #}" class="edit_link" title="Edit"><i class="fa fa-edit"></i></a>
                    </td>
                @else:
                    <td><a target="_blank" href="@statics('docs/'.$lk['item'])">{# $lk['levelName'] #}</a></td>
                    <td class="center-child" title="file"><i class="fa fa-file"></i></td>
                    <td class="center-child">
                        <a href="{# url(':edit_side_itm?link='.$lk['levelId']).'&t=doc' #}" class="edit_link" title="Edit"><i class="fa fa-edit"></i></a>
                    </td>
                @endif
                
                <td class="center-child">
                    <input type="hidden" class="levelId_token" value="{# $lk['levelId'] #}"/>
                    <a href="#!" class="delete_sidelink" title="Delete" data-toggle="modal" data-target="#deleteLinkModal"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
        @endeach
    </tbody>
</table>


