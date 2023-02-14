<wv-admin-main crumpTitle={Messages} crumpLink={:messages} crumpSlashTitle={/&nbsp;Messages}>
    <wv-comp.form-alert Type={$alertType} Text={{#$alertText#}}/>
    <wv-comp.admin-white-box>
        <div class="table-responsive">
            @if isset($messages[0]) && !is_empty($messages):
                <div class="col-md-6">
                    <a href="#!" id="btn_read_messages"><i class="fas fa-list"></i>&nbsp;&nbsp;Read Messages</a>&nbsp;&nbsp;
                    <a href="#!" id="btn_unread_messages"><i class="fas fa-list"></i>&nbsp;&nbsp;Unread&nbsp;Messages</a>
                </div> 
            @endif 
            <div class="col-md-6">
                <wv-comp.searchInput model={$messages}/>
            </div>
            <div id="page_details">
                <wv-comp.tables.messages />
            </div>
            @if $messages['pagLim'] > 1:
                <wv-comp.pagination>
                    @for $index = 0; $index < $messages['pagLim']; $index++:
                        <li class="page-item{# ($index + 1) === 1? ' active':''#} pagination-item">
                            <span class="page-link pag-numbers">{# $index+1 #}</span>
                        </li>
                    @endfor
                </wv-comp.pagination>
            @endif
        </div>   
    </wv-comp.admin-white-box>
</wv-admin-main>