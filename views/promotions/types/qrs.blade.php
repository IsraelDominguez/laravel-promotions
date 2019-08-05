
<div class="col-12 card fields-types card-outline-info" style="display:{{(isset($promotion)&&($promotion->type->code == \Genetsis\Promotions\Models\PromoType::QRS_TYPE)) ? 'block' : 'none'}};" id="fields-type-4">
    <div class="card-header">
        <h2 class="card-title">Consumer Rewards Pack</h2>
        <small class="card-subtitle">If you need create a new Pack, left empty pack field</small>
    </div>
    <div class="row card-block">
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <label>Pack</label>
                <input type="text" class="form-control" maxlength="100" name="pack" id="pack" value="{{ old('pack', isset($promotion)&&(isset($promotion->qrspack)) ? $promotion->qrspack->pack : null) }}">
                <i class="form-group__bar"></i>
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control" maxlength="100" name="pack_name" id="pack_name" value="{{ old('pack_name', isset($promotion)&&(isset($promotion->qrspack)) ? $promotion->qrspack->name : null) }}">
                <i class="form-group__bar"></i>
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <label>Key</label>
                <input type="text" class="form-control" maxlength="100" name="pack_key" id="pack_key" value="{{ old('pack_key', isset($promotion)&&(isset($promotion->qrspack)) ? $promotion->qrspack->key : null) }}">
                <i class="form-group__bar"></i>
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <label>Max Qrs</label>
                <input type="text" class="form-control" maxlength="5" name="pack_max" id="pack_max" value="{{ old('pack_max', isset($promotion)&&(isset($promotion->qrspack)) ? $promotion->qrspack->max : null) }}">
                <i class="form-group__bar"></i>
            </div>
        </div>
    </div>
</div>
