<script>

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

    (function($) {
        var Alpaca = $.alpaca;

        Alpaca.Fields.TinyMCEField = Alpaca.Fields.TextAreaField.extend(
            /**
             * @lends Alpaca.Fields.tinyMCEField.prototype
             */
            {
                /**
                 * @see Alpaca.Fields.TextAreaField#getFieldType
                 */
                getFieldType: function() {
                    return "tinymce";
                },

                /**
                 * @see Alpaca.Fields.TextAreaField#setup
                 */
                setup: function()
                {
                    var self = this;

                    if (!this.data)
                    {
                        this.data = "";
                    }

                    if (!self.options.toolbar)
                    {
                        self.options.toolbar = "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image";
                    }

                    this.base();
                },

                setValue: function(value)
                {
                    var self = this;

                    // be sure to call into base method
                    this.base(value);

                    if (self.editor)
                    {
                        self.editor.setContent(value);
                    }
                },

                /**
                 * @see Alpaca.Fields.ControlField#getControlValue
                 */
                getControlValue:function()
                {
                    var self = this;

                    var value = null;

                    if (self.editor)
                    {
                        value = self.editor.getContent()
                    }

                    return value;
                },

                initTinyMCEEvents: function()
                {
                    var self = this;

                    if (self.editor) {

                        // click event
                        self.editor.on("click", function (e) {
                            self.onClick.call(self, e);
                            self.trigger("click", e);
                        });

                        // change event
                        self.editor.on("change", function (e) {
                            self.onChange();
                            self.triggerWithPropagation("change", e);
                        });

                        // blur event
                        self.editor.on('blur', function (e) {
                            self.onBlur();
                            self.trigger("blur", e);
                        });

                        // focus event
                        self.editor.on("focus", function (e) {
                            self.onFocus.call(self, e);
                            self.trigger("focus", e);
                        });

                        // keypress event
                        self.editor.on("keypress", function (e) {
                            self.onKeyPress.call(self, e);
                            self.trigger("keypress", e);
                        });

                        // keyup event
                        self.editor.on("keyup", function (e) {
                            self.onKeyUp.call(self, e);
                            self.trigger("keyup", e);
                        });

                        // keydown event
                        self.editor.on("keydown", function (e) {
                            self.onKeyDown.call(self, e);
                            self.trigger("keydown", e);
                        });
                    }
                },

                afterRenderControl: function(model, callback)
                {
                    var self = this;

                    this.base(model, function() {

                        if (!self.isDisplayOnly() && self.control && typeof(tinyMCE) !== "undefined")
                        {
                            // wait for Alpaca to declare the DOM swapped and ready before we attempt to do anything with CKEditor
                            self.on("ready", function() {

                                if (!self.editor)
                                {
                                    var rteFieldID = $(self.control)[0].id;

                                    tinyMCE.init({
                                        init_instance_callback: function(editor) {
                                            self.editor = editor;

                                            self.initTinyMCEEvents();
                                        },
                                        selector: "#" + rteFieldID,
                                        toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
                                        plugins: ["link autolink wordcount paste"],
                                        paste_as_test: true,
                                        menubar: false
                                    });

                                }
                            });
                        }

                        callback();
                    });
                },

                /**
                 * @see Alpaca.Field#destroy
                 */
                destroy: function()
                {
                    var self = this;

                    // destroy the plugin instance
                    if (self.editor)
                    {
                        self.editor.remove();
                        self.editor = null;
                    }

                    // call up to base method
                    this.base();
                },


                /* builder_helpers */

                /**
                 * @see Alpaca.Fields.TextAreaField#getTitle
                 */
                getTitle: function() {
                    return "TinyMCE Editor";
                },

                /**
                 * @see Alpaca.Fields.TextAreaField#getDescription
                 */
                getDescription: function() {
                    return "Provides an instance of a TinyMCE control for use in editing HTML.";
                },

                /**
                 * @private
                 * @see Alpaca.ControlField#getSchemaOfOptions
                 */
                getSchemaOfOptions: function() {
                    return Alpaca.merge(this.base(), {
                        "properties": {
                            "toolbar": {
                                "title": "TinyMCE toolbar options",
                                "description": "Toolbar options for TinyMCE plugin.",
                                "type": "string"
                            }
                        }
                    });
                },

                /**
                 * @private
                 * @see Alpaca.ControlField#getOptionsForOptions
                 */
                getOptionsForOptions: function() {
                    return Alpaca.merge(this.base(), {
                        "fields": {
                            "toolbar": {
                                "type": "text"
                            }
                        }
                    });
                }

                /* end_builder_helpers */
            });

        Alpaca.registerFieldClass("tinymce", Alpaca.Fields.TinyMCEField);

    })(jQuery);

</script>
