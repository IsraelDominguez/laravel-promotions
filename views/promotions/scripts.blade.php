<script>
    @if (config('promotion.front_templates_enabled'))
        var schema_left = {
            "properties": {
                "promo_title": {
                    "type": "string",
                    "title": "Title"
                },
                "promo_text": {
                    "type": "string",
                    "title": "Text"
                },
                "promo_image": {
                    "type": "string",
                    "format": "uri"
                }
            }
        };

        var schema_right = {
            "properties": {
                "promo_title": {
                    "type": "string",
                    "title": "Title"
                },
                "promo_text": {
                    "type": "string",
                    "title": "Text"
                },
                "promo_image": {
                    "type": "string"
                }
            }
        };

        var postRenderCallback = function(control) {
            var id = "img-"+$(control.domEl).attr("id");
            $('#'+$(control.domEl).attr("id")+' #promo_image').attr('name', "promo_image_"+$(control.domEl).attr("id"));
            control.getFieldEl().append("<div id='"+id+"' style='display:none'><table><tr><td nowrap='nowrap' class='imagePreview' style='width: 220px'> </td></tr></table></div>");
            if ((control.data.promo_image != undefined) && (control.data.promo_image != '')) {
                var img = $("#"+id+" .imagePreview").html("").append("<img style='max-width: 200px; max-height: 200px' src='<?php echo Storage::disk('public')->url('/') ?>"+control.data.promo_image+"'>");
                $("#"+id).css({
                    "display": "block"
                });
            }
        };
        var options = {
            "fields": {
                "promo_text": {
                    "type": "tinymce"
                },
                "promo_image": {
                    "type": "file",
                    "id": "promo_image",
                    "fieldClass": "input-file",
                    "selectionHandler": function(files, data) {
                        var id = "img-"+$(this.parent.domEl).attr("id");
                        $("#"+id+" .imagePreview").html("").append("<img style='max-width: 200px; max-height: 200px' src='" + data[0] + "'>");
                        $("#"+id).css({
                            "display": "block"
                        });

                    }
                }
            }
        };

        Alpaca.Fields.TinyMCEField = Alpaca.Fields.TextAreaField.extend({
            afterRenderControl: function(model, callback) {
                var n = this;
                this.base(model, function() {
                    !n.isDisplayOnly() && n.control && "undefined" != typeof tinyMCE && n.on("ready", function() {
                        if (!n.editor) {
                            var t = $(n.control)[0].id;
                            tinyMCE.init({
                                selector: "#" + t,
                                toolbar: n.options.toolbar,
                                plugins: ["link"]
                            })
                        }
                    }), callback()
                })
            },
        });
        Alpaca.registerFieldClass("tinymce", Alpaca.Fields.TinyMCEField);
    @endif

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
