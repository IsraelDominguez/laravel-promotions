<div class="tab-pane fade" id="resultpage" role="tabpanel" aria-expanded="true">
<?php
if (!empty($promotion)) {
    $result_page = $promotion->templates()->page('result_page')->first();
}
?>
<input type="hidden" name="result_page_data" id="result_page_data" value=""/>
<div class="row">
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
    @isset($promotion)
        <div class="col-md-6">
            <a class="btn btn-sm btn-info btn--icon-text waves-effect" href="{{route('promotions.preview', ['id' => $promotion->id, 'page' => 'result_page'])}}" target="_blank"><i class="zmdi zmdi-eye"></i> Preview</a>
        </div>
    @endisset
</div>

<div id="result_page_template_left" style="display: {{isset($result_page)&&($result_page->template == \Genetsis\Promotions\Models\Templates::TEMPLATE_LEFT) ? 'block' : 'none'}}" class="result_page_template_type"></div>
<div id="result_page_template_right" style="display: {{isset($result_page)&&($result_page->template == \Genetsis\Promotions\Models\Templates::TEMPLATE_RIGHT) ? 'block' : 'none'}}" class="result_page_template_type"></div>
</div>

@push('custom-js')
    <script type="text/javascript">
        $(document).ready(function() {
            $("#result_page_template").change(function (e) {
                $(".result_page_template_type").hide();
                $("#result_page_template_"+$(this).val()).show();
            });

            var result_data = {
                    "promo_title": "",
                    "promo_text": "",
                    "promo_image": ""
                };

            @if (isset($result_page) && !empty($result_page->content))
                result_data = {!! $result_page->content !!};
            @endif

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
        });
    </script>
@endpush
