<table id="tbl-admin-category" class="table border">
    <thead>
        <tr class="right_align"></tr>
        <tr class="centerit">
            <th>Category Name</th>
            <th class="center-child">Visible</th>
            <th class="center-child">Status</th>
            <th class="center-child">Edit Category Status</th>
        </tr>
    </thead>
    <tbody>
        @each $categories as $key => $category:
            @if $key === 'pagLim': @thenend @endif
            <tr>
                <td>{# $category['categoryName'] #}</td>
                <td class="center-child">{# $category['visible'] #}</td>
                <td class="center-child">{# $category['_status'] #}</td>
                <td class="center-child">
                    <a href="{# url(':edit_category').'?cat='.$category['categoryId'] #}" class="edit_category" title="Edit"><i class="fa fa-edit"></i></a>
                </td>
            </tr>
        @endeach
    </tbody>
</table>