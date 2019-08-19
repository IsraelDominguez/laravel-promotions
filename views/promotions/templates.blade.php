<div class="col-12">
    <div class="tab-container">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#seoshare" role="tab">Seo and Share</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#design" role="tab">Design</a>
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

            <div class="tab-pane fade" id="design" role="tabpanel" aria-expanded="true">
                @include("promotion::promotions.templates.design")
            </div>

            <div class="tab-pane fade" id="initialpage" role="tabpanel" aria-expanded="true">
                <input type="hidden" name="initial_page_data" id="initial_page_data" value=""/>

                <?php
                $initial_page = $promotion->templates()->page('initial_page')->first();
                ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Template </label>

                        <select class="select2" name="initial_page_template" id="initial_page_template">
                            <option value="">- Select -</option>
                            <option value="{{\Genetsis\Promotions\Models\Templates::TEMPLATE_LEFT}}" {{isset($initial_page)&&($initial_page->template == \Genetsis\Promotions\Models\Templates::TEMPLATE_LEFT) ? 'selected' : ''}}>Image Left</option>
                            <option value="{{\Genetsis\Promotions\Models\Templates::TEMPLATE_RIGHT}}" {{isset($initial_page)&&($initial_page->template == \Genetsis\Promotions\Models\Templates::TEMPLATE_RIGHT) ? 'selected' : ''}}>Image Right</option>
                        </select>
                    </div>
                </div>

                <div id="initial_page_template_left" style="display: {{isset($initial_page)&&($initial_page->template == \Genetsis\Promotions\Models\Templates::TEMPLATE_LEFT) ? 'block' : 'none'}}" class="initial_page_template_type"></div>
                <div id="initial_page_template_right" style="display: {{isset($initial_page)&&($initial_page->template == \Genetsis\Promotions\Models\Templates::TEMPLATE_RIGHT) ? 'block' : 'none'}}" class="initial_page_template_type"></div>
            </div>


            <div class="tab-pane fade" id="resultpage" role="tabpanel" aria-expanded="true">
                <input type="hidden" name="result_page_data" id="result_page_data" value=""/>

                <?php
                $result_page = $promotion->templates()->page('result_page')->first();
                ?>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Template </label>

                        <select class="select2" name="result_page_template" id="result_page_template">
                            <option value="">- Select -</option>
                            <option value="{{\Genetsis\Promotions\Models\Templates::TEMPLATE_LEFT}}" {{isset($result_page)&&($result_page->template == \Genetsis\Promotions\Models\Templates::TEMPLATE_LEFT) ? 'selected' : ''}}>Image Left</option>
                            <option value="{{\Genetsis\Promotions\Models\Templates::TEMPLATE_RIGHT}}" {{isset($result_page)&&($result_page->template == \Genetsis\Promotions\Models\Templates::TEMPLATE_RIGHT) ? 'selected' : ''}}>Image Right</option>
                        </select>
                    </div>
                </div>

                <div id="result_page_template_left" style="display: {{isset($result_page)&&($result_page->template == \Genetsis\Promotions\Models\Templates::TEMPLATE_LEFT) ? 'block' : 'none'}}" class="result_page_template_type"></div>
                <div id="result_page_template_right" style="display: {{isset($result_page)&&($result_page->template == \Genetsis\Promotions\Models\Templates::TEMPLATE_RIGHT) ? 'block' : 'none'}}" class="result_page_template_type"></div>
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

            console.log(id);
            control.getFieldEl().append("<div id='"+id+"' style='display:none'><table><tr><td nowrap='nowrap' class='imagePreview' style='width: 220px'> </td></tr></table></div>");

            if (control.data.promo_image != '') {
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


        $(document).ready(function() {

            $("#result_page_template").change(function (e) {
                $(".result_page_template_type").hide();
                $("#result_page_template_"+$(this).val()).show();
            });

            $("#initial_page_template").change(function (e) {
                $(".initial_page_template_type").hide();
                $("#initial_page_template_"+$(this).val()).show();
            });

            var initial_data = {
                "promo_title": "",
                "promo_text": "",
                "promo_image": ""
            };

            @isset($initial_page)
            initial_data = {!! $promotion->templates()->page('initial_page')->first()->content !!};
            @endisset

            var result_data = {
                "promo_title": "",
                "promo_text": "",
                "promo_image": ""
            };

            @isset($result_page)
            result_data = {!! $result_page->content !!};
            @endisset

            $("#result_page_template_left").alpaca({
                "schema": schema_left,
                "data": result_data,
                "options": options,
                "postRender": postRenderCallback
            });

            $("#result_page_template_right").alpaca({
                "schema": schema_right,
                "data": result_data,
                "options": options,
                "postRender": postRenderCallback,
            });

            $("#initial_page_template_right").alpaca({
                "schema": schema_right,
                "data": initial_data,
                "options": options,
                "postRender": postRenderCallback,
            });

            $("#initial_page_template_left").alpaca({
                "schema": schema_left,
                "data": initial_data,
                "options": options,
                "postRender": postRenderCallback,
            });
        });
    </script>
@endpush
