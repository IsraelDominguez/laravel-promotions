<div class="col-12 card fields-types card-outline-info" style="display:{{(isset($promotion)&&($promotion->type->code == \Genetsis\Promotions\Models\PromoType::PINCODE_TYPE)) ? 'block' : 'none'}};" id="fields-type-2">
    <div class="card-header">
        <h2 class="card-title">Pincode Data</h2>
    </div>
    <div class="row card-block">
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <label>Import File (csv) <a href="{{route('download-sample', ['file' => 'pincodes_sample'])}}" target="_blank">download sample</a></label>
                <input type="file" class="form-control" name="pincodes_file" id="pincodes_file" accept="csv">
                <i class="form-group__bar"></i>
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <label>Remove Previous Moments</label>
                <br>
                <div class="toggle-switch">
                    <input type="checkbox" name="remove_prev" class="toggle-switch__checkbox">
                    <i class="toggle-switch__helper"></i>
                </div>
            </div>
        </div>

        @if(!empty($promotion) && ($promotion->type->code == \Genetsis\Promotions\Models\PromoType::PINCODE_TYPE))
            <div class="col-xs-12 col-md-6">
                Total Moments: {{ count($promotion->codes) }}
                <br/>
                Used: {{$promotion->participations->filter(function($p) { return $p->code; })->count()}}
            </div>
        @endif
    </div>
</div>
