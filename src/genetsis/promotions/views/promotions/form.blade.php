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
</div>
<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label>Promo Type</label>

            <select class="select2" name="type_id">
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

