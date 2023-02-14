@if {:val.Type} === 'success':
    <p role="alert" class="alert alert-success">{:val.Text}</p>
    @elif {:val.Type} === 'error':
        <p role="alert" class="alert alert-danger">{:val.Text}</p>
@endif