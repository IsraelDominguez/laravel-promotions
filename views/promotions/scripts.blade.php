
<script>
    $(document).ready(function() {
        $('#name').blur(function() {
            $("#key").val(slugify($('#name').val()));
        });

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
</script>
