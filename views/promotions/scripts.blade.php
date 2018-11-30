
<script>
    $(document).ready(function() {
        $("#submit").click(function () {
            $("#form").submit();
        });

        $("#new_extra_field").click(function (e) {
            e.preventDefault();
            add_extra_field('', '', '');
        });

        $("#new_reward").click(function (e) {
            e.preventDefault();
            add_reward('', '', '');
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