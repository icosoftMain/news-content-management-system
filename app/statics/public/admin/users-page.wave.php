<wv-admin-main crumpTitle={User Details} crumpLink={:manage_user} crumpSlashTitle={/&nbsp;User Details}>
    <wv-comp.form-alert Type={$alertType} Text={{#$alertText.' was successfully quarantined '#}}/>
    <wv-comp.admin-white-box>
        <div class="table-responsive">
            <div class="col-md-6">
                <a href="{# url(':add_user') #}">
                    <i class="fa fa-user"></i>
                    New User
                </a>
            </div>
            <div class="col-md-6">
                <wv-comp.searchInput model={$allUsers}/>   
            </div>
            <div id="member_details">
                <wv-comp.tables.users/>
            </div>
            @if $allUsers['pagLim'] > 1:
                <wv-comp.pagination>
                    @for $index = 0; $index < $allUsers['pagLim']; $index++:
                        <li class="page-item{# ($index + 1) === 1? ' active':''#} pagination-item">
                            <span class="page-link pag-numbers">{# $index+1 #}</span>
                        </li>
                    @endfor
                </wv-comp.pagination>
            @endif
        </div>
        <wv-comp.modal
           deleteModal
           modalId={deleteAlertModal}
           footerComponent={
                <button class="btn btn-primary" id="delete_member">Yes</button>
                <button class="btn btn-custom modal-close" data-dismiss="modal">No</button>
           }
        >
           <div style="font-size: 22px;">
               <i class="fa fa-warning red-text"></i>
               Are you sure you want to delete <span id="modal_user_fullName"></span>?
           </div>
        </wv-comp.modal>
    </wv-comp.admin-white-box>
</wv-admin-main>