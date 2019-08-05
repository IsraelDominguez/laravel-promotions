<div class="col-md-6">
    <h3 class="card-block__title">Extra Fields</h3>
    <div id="extra_fields">

    </div>
</div>

@prepend('custom-js')
    <script>

        $(document).ready(function() {

            extra_fields_types.push({!!'"' . implode('", "', \Genetsis\Promotions\Models\ExtraFields::TYPES) . '"'!!});

            @if (isset($promotion))
                if ({{count($promotion->extra_fields)}} > 0) {
                    @foreach($promotion->extra_fields as $extra_field)
                    add_extra_field('{{$extra_field->key}}', '{{$extra_field->name}}', '{{$extra_field->type}}');
                    @endforeach
                } else {
                    add_extra_field('', '','');
                }
            @else
                add_extra_field('','','');
            @endif

            $("#new_extra_field").click(function (e) {
                e.preventDefault();
                add_extra_field('', '', '');
            });
        });
    </script>
@endprepend
