<script>
    var entrypoint_selected = '{{$promotion->entrypoint_id ?? ''}}';

    $(document).ready(function() {
        @if ($message = Session::get('success'))
        notify('{{ $message }}');
        @endif

        $('#name').blur(function() {
            $("#key").val(slugify($('#name').val()));
        });

        @if (count($campaigns) > 0)
            @if (count($campaigns) > 1)
                $('#campaign_id').on('change', function() {
                var campaign_id = $('#campaign_id').val();
                fillEntrypoints(campaign_id);
            });
            @else
                fillEntrypoints({{$campaigns[0]->id}}, '{{$campaigns[0]->entry_point}}');
            @endif
        @endif

        $("#submit").click(function () {
            @if (config('promotion.front_templates_enabled'))
                initial_template = $("#initial_page_template").val();
                result_template = $("#result_page_template").val();

                if (initial_template != '') {
                    $('#initial_page_data').val(JSON.stringify($("#initial_page_template_" + initial_template).alpaca("get").getValue()));
                }

                if (result_template != '') {
                    $('#result_page_data').val(JSON.stringify($("#result_page_template_" + result_template).alpaca("get").getValue()));
                }
            @endif

            $("#form").submit();
        });

        $("#form").validate({});

        $("#promo_type").change(function (e) {
            $(".fields-types").hide();
            $("#fields-type-"+$(this).val()).show();
        });

        @if (empty($promotion))
            @if (!empty(old('type_id')))
                $("#fields-type-{{old('type_id')}}").show();
            @endif
        @endif
    });

    function fillEntrypoints(campaign_id, entrypoint_id) {
        $('#entry_points').empty();

        if (entrypoint_id == '') {

            $('#entry_points').html('<option selected="selected" value="">- Select -</option>\n' +
                '<option value="simple" ' + (("simple" == entrypoint_selected) ? 'selected' : '') + '>New Simple Fields</option>\n' +
                '<option value="complete" ' + (("complete" == entrypoint_selected) ? 'selected' : '') + '>New Complete Fields</option>');

            if (campaign_id != '') {
                $.ajax({
                    url: '/api/v1/entrypoint/list/' + campaign_id,
                    method: 'GET',
                    datatype: 'json',
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $.each(response.data, function (key, value) {
                            selected = (value.key == entrypoint_selected) ? 'selected' : '';
                            $('#entry_points').append('<option value="' + value.key + '" ' + selected + '>' + value.name + '</option>');
                        });
                    }
                });
            }
        } else {
            $('#entry_points').html('<option value="'+entrypoint_id+'" selected>'+entrypoint_id+'</option>');
        }
    }
</script>
