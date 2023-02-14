<wv-comp.general-script />
@if $app_style_type === 'app':
    @usejs('libs/bootstrap/bootstrap.min')
    @usejs('libs/owl-carousel/owl.carousel.min') 
    @usejs('main') 
@elif $app_style_type === 'admin_dash':
    <wv-comp.dashboard-scripts include_chart={true} />
@elif $app_style_type === 'admin':
    <wv-comp.dashboard-scripts include_chart={false} />
@endif
{# usejs('init-dist',['data-md' => 'jswv_modules']) #}