<div class="tab-pane fade" id="initialpage" role="tabpanel" aria-expanded="true">
<?php
if (!empty($promotion)) {
    $initial_page = $promotion->templates()->page('initial_page')->first();
}
?>
<input type="hidden" name="initial_page_data" id="initial_page_data" value=""/>
<div class="row">
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
    @isset($promotion)
    <div class="col-md-6">
        <a class="btn btn-sm btn-info btn--icon-text waves-effect" href="{{route('promotions.preview', ['id' => $promotion->id, 'page' => 'initial_page'])}}" target="_blank"><i class="zmdi zmdi-eye"></i> Preview</a>
    </div>
    @endisset
</div>

<div id="initial_page_template_left" style="display: {{isset($initial_page)&&($initial_page->template == \Genetsis\Promotions\Models\Templates::TEMPLATE_LEFT) ? 'block' : 'none'}}" class="initial_page_template_type"></div>
<div id="initial_page_template_right" style="display: {{isset($initial_page)&&($initial_page->template == \Genetsis\Promotions\Models\Templates::TEMPLATE_RIGHT) ? 'block' : 'none'}}" class="initial_page_template_type"></div>

</div>


@push('custom-js')
    <script type="text/javascript">

        $(document).ready(function() {

            $("#initial_page_template").change(function (e) {
                $(".initial_page_template_type").hide();
                $("#initial_page_template_"+$(this).val()).show();
            });

            var initial_data = {
                "promo_title": "",
                "promo_text": "",
                "promo_image": ""
            };

            @if (isset($initial_page) && !empty($initial_page->content))
                initial_data = {!! $initial_page->content !!};
            @endif

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
