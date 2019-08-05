<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Name *</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $promotion->name ?? null) }}" required>
            <i class="form-group__bar"></i>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="key">Key *</label>
            <input type="text" class="form-control" name="key" id="key" value="{{ old('key', $promotion->key ?? null) }}">
            <i class="form-group__bar"></i>
        </div>
    </div>

    @if (count($campaigns) > 1)
        <div class="col-md-6">
            <div class="form-group">
                <label>Campaign *</label>
                <select class="select2" name="campaign_id">
                    <option value="">- Select -</option>
                    @foreach ($campaigns as $campaign)
                        <option value="{{$campaign->id}}"
                        @if ((old('campaign_id', $promotion->campaign->id ?? null) == $campaign->id))
                            selected
                        @endif
                        >{{$campaign->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @else
        <input type="hidden" name="campaign_id" value="{{$promotion->campaign_id ?? $campaigns->pop()->id}}"/>
    @endif

    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label>Entry Point</label>
            <select class="select2" name="entrypoint_id" id="entry_points">
                <option value="">- Select -</option>
                @isset($promotion)
                    @foreach ($promotion->campaign->entrypoints as $entrypoint)
                        <option value="{{$entrypoint->key}}"
                                @if ((old('entrypoint_id', isset($promotion) ? $action->entrypoint_id : null) == $entrypoint->key))
                                selected
                            @endif
                        >{{$entrypoint->key}}</option>
                    @endforeach
                @endisset
            </select>
            <i class="form-group__bar"></i>
        </div>
    </div>


{{--    <div class="col-md-6">--}}
{{--        <div class="form-group">--}}
{{--            <label for="entry_point">Entry Point *</label>--}}
{{--            <input type="text" class="form-control" name="entry_point" id="entry_point" value="{{ old('entry_point', isset($promotion) ? $promotion->entry_point : null) }}">--}}
{{--            <i class="form-group__bar"></i>--}}
{{--        </div>--}}
{{--    </div>--}}
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Promo Type *</label>

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


@include("promotion::promotions.types.qrs")

@include("promotion::promotions.types.winmoment")

@include("promotion::promotions.types.pincode")

</div>
<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label>Max User Participations</label>
            <input type="text" class="form-control" maxlength="2" name="max_user_participations" id="max_user_participations" value="{{ old('max_user_participations', isset($promotion) ? $promotion->max_user_participations : "1") }}">
            <i class="form-group__bar"></i>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Has MGM</label>
            <br>
            <div class="toggle-switch">
                <input type="checkbox" name="has_mgm" class="toggle-switch__checkbox" {{ old('has_mgm', isset($promotion) ? (($promotion->has_mgm) ? 'checked' : '') : '' )}}>
                <i class="toggle-switch__helper"></i>
            </div>
        </div>
    </div>
{{--    <div class="col-6">--}}
{{--        <div class="form-group">--}}
{{--            <label>Max User Participations By Day</label>--}}
{{--            <input type="text" class="form-control" maxlength="2" name="max_user_participations_by_day" id="max_user_participations_by_day" value="{{ old('max_user_participations_by_day', isset($promotion) ? $promotion->max_user_participations_by_day : null) }}">--}}
{{--            <i class="form-group__bar"></i>--}}
{{--        </div>--}}
{{--    </div>--}}

    <div class="col-md-6">
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

    <div class="col-md-6">
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

    <div class="col-md-6">
        <div class="form-group">
            <label>Legal</label>
            <input type="file" class="form-control" name="legal_file" id="legal_file" accept=".pdf">
            <input type="text" class="form-control" maxlength="100" placeholder="or link to file" name="legal" id="legal" value="{{ old('legal', isset($promotion) ? $promotion->legal : null) }}">
            <i class="form-group__bar"></i>
        </div>
    </div>


@includeWhen(config('promotion.front_templates_enabled'), 'promotion::promotions.templates')

@includeWhen(config('promotion.extra_fields_enabled'), 'promotion::promotions.partials.extrafields')
@includeWhen(config('promotion.rewards_fields_enabled'), 'promotion::promotions.partials.rewards')

</div>


<ul class="nav justify-content-center">
    <li class="nav-item">
        <a class="btn btn-danger btn--icon-text waves-effect pull-2" href="{{ route('promotions.home') }}"><i class="zmdi zmdi-arrow-back"></i> Back</a>
    </li>
    <li class="nav-item">
        <a class="btn btn-success btn--icon-text waves-effect" id="submit" href="#"><i class="zmdi zmdi-check"></i> Submit</a>
    </li>
</ul>



