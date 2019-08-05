<script>
    $(document).ready(function() {
        $('#name').blur(function() {
            $("#key").val(slugify($('#name').val()));
        });

        @if (count($campaigns) > 1)
        $('#campaign_id').on('change', function() {
            var campaign_id = $('#campaign_id').val();
            fillEntrypoints(campaign_id);
        });
        @else
            fillEntrypoints({{$campaigns[0]->id}});
        @endif

        $("#submit").click(function () {
            $("#form").submit();
        });

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

    function fillEntrypoints(campaign_id) {
        $('#entry_points').empty();
        $('#entry_points').html('<option selected="selected" value="">- Select -</option>\n' +
            '<option value="simple">New Simple Fields</option>\n' +
            '<option value="complete">New Complete Fields</option>');

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
                    $.each(response.data, function(key, value) {
                        $('#entry_points').append('<option value="'+value.key+'">'+value.name+'</option>');
                    });
                }
            });
        }
    }
</script>
