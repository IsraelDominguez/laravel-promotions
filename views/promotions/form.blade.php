<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ old('name', isset($promotion) ? $promotion->name : null) }}">
            <i class="form-group__bar"></i>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label for="key">Key</label>
            <input type="text" class="form-control" name="key" id="key" value="{{ old('key', isset($promotion) ? $promotion->key : null) }}">
            <i class="form-group__bar"></i>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label>Campaign</label>

            <select class="select2" name="campaign_id">
                <option value="">- Select -</option>
                @foreach ($campaigns as $campaign)
                    <option value="{{$campaign->id}}"
                    @if ((old('campaign_id', isset($promotion) ? $promotion->campaign->id : null) == $campaign->id))
                        selected
                    @endif
                    >{{$campaign->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label for="entry_point">Entry Point</label>
            <input type="text" class="form-control" name="entry_point" id="entry_point" value="{{ old('key', isset($promotion) ? $promotion->entry_point : null) }}">
            <i class="form-group__bar"></i>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label>Promo Type</label>

            <select class="select2" name="type_id" id="promo_type">
                <option value="">- Select -</option>
                @foreach ($promo_types as $type)
                    <option value="{{$type->id}}"
                            @if ((old('type_id', isset($promotion) ? $promotion->type_id : null) == $type->id))
                            selected
                            @endif
                    >{{$type->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label>Has MGM</label>
            <br>
            <div class="toggle-switch">
                <input type="checkbox" name="has_mgm" class="toggle-switch__checkbox" {{ old('has_mgm', isset($promotion) ? (($promotion->has_mgm) ? 'checked' : '') : '' )}}>
                <i class="toggle-switch__helper"></i>
            </div>
        </div>
    </div>
</div>

<div class="card fields-types card-outline-info" style="display:{{(isset($promotion)&&($promotion->type->code == \Genetsis\Promotions\Models\PromoType::QRS_TYPE)) ? 'block' : 'none'}};" id="fields-type-4">
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

<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label>Max User Participations</label>
            <input type="text" class="form-control" maxlength="2" name="max_user_participations" id="max_user_participations" value="{{ old('max_user_participations', isset($promotion) ? $promotion->max_user_participations : null) }}">
            <i class="form-group__bar"></i>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label>Max User Participations By Day</label>
            <input type="text" class="form-control" maxlength="2" name="max_user_participations_by_day" id="max_user_participations_by_day" value="{{ old('max_user_participations_by_day', isset($promotion) ? $promotion->max_user_participations_by_day : null) }}">
            <i class="form-group__bar"></i>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label>Starts</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                <div class="form-group">
                    <input type="text" class="form-control my-datetime-picker flatpickr-input" name="starts" id="starts" value="{{ old('starts', isset($promotion) ? $promotion->starts : null) }}">
                    <i class="form-group__bar"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label>Ends</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                <div class="form-group">
                    <input type="text" class="form-control my-datetime-picker flatpickr-input" name="ends" id="ends" value="{{ old('ends', isset($promotion) ? $promotion->ends : null)  }}">
                    <i class="form-group__bar"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-6">
        <h3 class="card-block__title">Extra Fields</h3>
        <div id="extra_fields">

        </div>
    </div>

    <div class="col-xs-12 col-md-6">
        <h3 class="card-block__title">Rewards</h3>
        <div id="rewards">

        </div>
    </div>
</div>

<ul class="nav justify-content-center">
    <li class="nav-item">
        <a class="btn btn-danger btn--icon-text waves-effect pull-2" href="{{ route('promotions.home') }}"><i class="zmdi zmdi-arrow-back"></i> Back</a>
    </li>
    <li class="nav-item">
        <a class="btn btn-success btn--icon-text waves-effect" id="submit" href="#"><i class="zmdi zmdi-check"></i> Submit</a>
    </li>
</ul>



