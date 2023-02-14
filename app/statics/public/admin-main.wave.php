<wv-index>
    <!-- ============================================================== -->
    <!-- Preloader -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Wrapper -->
    <!-- ============================================================== -->
    <div id="wrapper">               <!-- ============================================================== -->
        <wv-comp.admin-nav />
    </div>
    <div id="page-wrapper">
        <div class="container-fluid">
            <wv-comp.admin-crumpbar 
                crumpTitle={{:val.crumpTitle}} 
                crumpLink={{:val.crumpLink}}
                crumpSlashTitle={{:val.crumpSlashTitle}}
            />
            {:children}
        </div>
        <wv-comp.admin-footer />
    </div>
</wv-index>