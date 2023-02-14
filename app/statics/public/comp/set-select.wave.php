@def generate_select(array $options, $flag):
    @if array_key_exists($flag,$options):
        <option value="{# $flag #}" selected>{# $options[$flag] #}</option>
        {# $options[$flag] = ""#}
        @else:
            <option selected value="">Select a security question</option>
    @endif
    @each $options as $value => $option:
        @if trim($option) <> "" :
            <option value="{# $value #}">{# $option #}</option>
        @endif
    @endeach
@endef

{# generate_select({:val.options},{:val.flag}) #}

