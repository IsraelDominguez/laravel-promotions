<div class="col-12">
    <div class="tab-container">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#seoshare" role="tab">Seo and Share</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#initialpage" role="tab">Initial Page</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#resultpage" role="tab">Result Page</a>
            </li>

        </ul>
        <div class="tab-content">
            <div class="tab-pane active fade show" id="seoshare" role="tabpanel" aria-expanded="true">
                @include("promotion::promotions.templates.seoshare")
            </div>

            <div class="tab-pane fade" id="initialpage" role="tabpanel" aria-expanded="true">
                <input type="hidden" name="initial_page_data" id="initial_page_data" value=""/>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Template </label>

                        <select class="select2" name="initial_page_template" id="initial_page_template">
                            <option value="">- Select -</option>
                            <option value="left">Image Left</option>
                            <option value="right">Image Right</option>
                        </select>
                    </div>
                </div>

                <div id="initial_page_template_left" style="display: none" class="initial_page_template_type"></div>
                <div id="initial_page_template_right" style="display: none" class="initial_page_template_type"></div>
            </div>


            <div class="tab-pane fade" id="resultpage" role="tabpanel" aria-expanded="true">
                <input type="hidden" name="result_page_data" id="result_page_data" value=""/>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Template </label>

                        <select class="select2" name="result_page_template" id="result_page_template">
                            <option value="">- Select -</option>
                            <option value="left">Image Left</option>
                            <option value="right">Image Right</option>
                        </select>
                    </div>
                </div>

                <div id="result_page_template_left" style="display: none" class="result_page_template_type"></div>
                <div id="result_page_template_right" style="display: none" class="result_page_template_type"></div>
            </div>
        </div>
    </div>

</div>



@push('custom-js')
    <script type="text/javascript">
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
        var postRenderCallbackLeft = function(control) {
            var id = "LeftImageInfo-" + control.id;
            $('#promo_image').attr('name', "promo_image_"+$(control.domEl).attr("id"));

            control.getFieldEl().append("<div id='"+id+"' style='display:none'><table><tr><td nowrap='nowrap' class='imagePreview' style='width: 220px'> </td></tr></table></div>");
        };
        var options_left = {
            "fields": {
                "promo_text": {
                    "type": "tinymce"
                },
                "promo_image": {
                    "type": "file",
                    "id": "promo_image",
                    "fieldClass": "input-file",
                    "selectionHandler": function(files, data) {
                        var id = "#LeftImageInfo-" + this.parent.id;
                        var img = $(id+" .imagePreview").html("").append("<img style='max-width: 200px; max-height: 200px' src='" + data[0] + "'>");
                        $(id).css({
                            "display": "block"
                        });

                    }
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
        var postRenderCallbackRight = function(control) {
            //var shareField = control.childrenByPropertyId["promo_image"];
            var id = "RightImageInfo-" + control.id;
            control.getFieldEl().append("<div id='"+id+"' style='display:none'><table><tr><td nowrap='nowrap' class='imagePreview' style='width: 220px'> </td></tr></table></div>");
        };
        var options_right = {
            "fields":{
                "promo_text": {
                    "type": "tinymce"
                },
                "promo_image": {
                    "type": "file",
                    "selectionHandler": function(files, data) {
                        var id = "#RightImageInfo-" + this.parent.id;
                        var img = $(id+" .imagePreview").html("").append("<img style='max-width: 200px; max-height: 200px' src='" + data[0] + "'>");
                        $(id).css({
                            "display": "block"
                        });
                    }
                }
            }
        };


        $(document).ready(function() {

            $("#result_page_template").change(function (e) {
                $(".result_page_template_type").hide();
                $("#result_page_template_"+$(this).val()).show();
            });

            $("#initial_page_template").change(function (e) {
                $(".initial_page_template_type").hide();
                $("#initial_page_template_"+$(this).val()).show();
            });

            $(".input-file input").change(function (e) {
                console.log(e);
            });

            var data_left = {
                "promo_title": "",
                "promo_text": "",
                "promo_image": ""
            };


            var data_right = {
                "promo_title": "",
                "promo_text": "",
                "promo_image": ""
            };


            $("#result_page_template_left").alpaca({
                "schema": schema_left,
                "data": data_left,
                "options": options_left,
                "postRender": postRenderCallbackLeft
            });
            //
            // $("#result_page_template_right").alpaca({
            //     "schema": schema_right,
            //     "data": data_right,
            //     "options": options_right,
            //     "postRender": postRenderCallbackRight,
            // });
            //
            // $("#initial_page_template_right").alpaca({
            //     "schema": schema_right,
            //     "data": data_right,
            //     "options": options_right,
            //     "postRender": postRenderCallbackRight,
            // });

            $("#initial_page_template_left").alpaca({
                "schema": schema_left,
                "data": data_left,
                "options": options_left,
                "postRender": postRenderCallbackLeft,
            });
        });
    </script>
@endpush
