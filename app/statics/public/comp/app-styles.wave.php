<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>

@if $app_style_type === 'app':
    @usecss('bootstrap.min')
    @usecss('libs/owl-carousel/owl.carousel.min')
    @usecss('libs/owl-carousel/owl.theme.default.min')
    @usecss('main')
@elif $app_style_type === 'login':
    @usecss('login')
@elif $app_style_type === 'admin' || $app_style_type === 'admin_dash':
    <wv-comp.admin-styles />   
@endif

@usecss('fonts/fa-svg-with-js')

<script>
	 FontAwesomeConfig = { searchPseudoElements: true };
</script>
@usejs('fonts/fontawesome-all.min') 
<link href="//fonts.googleapis.com/css?family=Roboto+Condensed:400,700" rel="stylesheet" type="text/css">
<link href="//fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">