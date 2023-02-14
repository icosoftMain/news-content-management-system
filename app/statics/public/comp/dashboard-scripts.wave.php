@usejs('libs/ckeditor/ckeditor')
@usejs('libs/ckeditor/custom_config')
@usejs('libs/bootstrap/bootstrap.min') 
@usejs('css/admin/plugins/sidebar-nav/dist/sidebar-nav.min') 
@usejs('jquery.slimscroll') 
@usejs('waves') 
@usejs('css/admin/plugins/waypoints/lib/jquery.waypoints') 

@if {:val.include_chart} === true:
    @usejs('css/admin/plugins/counterup/jquery.counterup.min') 
    @usejs('css/admin/plugins/chartist-js/dist/chartist.min')
    @usejs('css/admin/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min') 
@endif

@usejs('css/admin/plugins/jquery-sparkline/jquery.sparkline.min') 
@usejs('css/admin/plugins/toast-master/js/jquery.toast') 
@usejs('custom.min') 

@if {:val.include_chart} === true:
    @usejs('dashboard') 
@endif

