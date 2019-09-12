<script>
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
        var element = $(control.domEl).attr("id");
        var id = "img-"+element;
        $('#'+$(control.domEl).attr("id")+' #promo_image').attr('name', "promo_image_"+element);


        var image_field_tmpl = `
                    <div id="${id}" style="display:none">
                        <table>
                            <tr>
                                <td nowrap="nowrap" class="imagePreview" style='width: 220px'>
                                </td>
                                <td><input type="checkbox" name="delete_image_${element}" id="deleteImage"><label for="deleteImage">Remove</label></td>
                            </tr>
                        </table>
                    </div>
                `;

        control.getFieldEl().append(image_field_tmpl);

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
                    var src_img = data[0];
                    var img_tmpl = `<img style="max-width: 200px; max-height: 200px" src="${src_img}">`;
                    $("#"+id+" .imagePreview").html("").append(img_tmpl);
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
                            menubar: false,
                            //toolbar: n.options.toolbar,
                            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
                            plugins: ["link autolink wordcount paste"],
                            paste_as_test: true
                        })
                    }
                }), callback()
            })
        },
    });
    Alpaca.registerFieldClass("tinymce", Alpaca.Fields.TinyMCEField);

</script>
