@if {:val.formType} <> 'edit':
    <option value="" selected>{# ucwords({:str.label}) #}</option>
@endif

@each {:val.varName} as $value:
    @if strtolower($value['{:val.typeName}']) === 'about' and
        {:str.listType} === 'categoryName':
        @thenskip
        @elif isset($value['{:val.typeName}']):
        <option value="{# $value['{:val.typeName}'] #}">
            {# ucwords($value['{:val.typeName}']) #}
        </option>
    @endif   
@endeach