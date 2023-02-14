@if {:val.deleteModal}:
    {# @CSRF #}
@endif
<div class="modal fade" id="{:val.modalId}" tabindex="-5" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-right" role="document">
        <div class="modal-content">
            <div class="modal-body">{:children}</div>
            <div class="modal-footer">{:val.footerComponent}</div>
        </div>
    </div>
</div>
