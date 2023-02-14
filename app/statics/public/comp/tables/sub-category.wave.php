<table id="tbl-admin-subcategory" class="table border">
    <thead>
        <tr class="right_align"></tr>
        <tr class="centerit">
            <th>Sub Category Name</th>
            <th class="center-child">Category Name</th>
            <th class="center-child">Edit Sub Category</th>
        </tr>
    </thead>
    <tbody>
        @for $index = 0; $index < count($subCategories); $index++:
            @if !isset($subCategories[$index]): @thenend @endif
            
            <tr class="centerit">
                <td>{# $subCategories[$index]['levelName'] #}</td>
                <td class="center-child">
                    {#
                       ($Category::get($subCategories[$index]['categoryId']))
                        ->categoryName
                    #}
                </td>
                <td class="center-child">
                    <a href="{#
                         url(':edit_sub_category?level='.$subCategories[$index]['levelId'].
                         '&cat='.($Category::get($subCategories[$index]['categoryId']))
                        ->categoryId)
                    #}" title="Edit"><i class="fa fa-edit"></i></a>
                </td>
            </tr>
        @endfor
    </tbody>
</table>