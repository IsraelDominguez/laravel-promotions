<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Name *</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $promotion->name ?? null) }}" required maxlength="250">
            <i class="form-group__bar"></i>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="key">Key *</label>
            <input type="text" class="form-control" name="key" id="key" value="{{ old('key', $promotion->key ?? null) }}" required maxlength="250">
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
        <input type="hidden" name="campaign_id" value="{{(count($campaigns) == 1) ? $promotion->campaign_id ?? $campaigns[0]->id : ''}}"/>
    @endif

    @if (config('genetsis_admin.manage_druid_apps'))
        <div class="col-md-6">
            <div class="form-group">
                <label>Entry Point</label>
                <select class="select2 required" name="entrypoint_id" id="entry_points">
                    <option value="">- Select -</option>
                    @isset($promotion)
                        @foreach ($promotion->campaign->druid_app->entrypoints as $entrypoint)
                            <option value="{{$entrypoint->key}}"
                                    @if ((old('entrypoint_id', $promotion->entrypoint_id ?? null) == $entrypoint->client_id))
                                    selected
                                @endif
                            >{{$entrypoint->key}}</option>
                        @endforeach
                    @endisset
                </select>
                <i class="form-group__bar"></i>
            </div>
        </div>
    @else
        <div class="col-md-6">
            <div class="form-group">
                <label for="entry_point">Entry Point *</label>
                <input type="text" class="form-control" name="entry_point" id="entry_point" value="{{ old('entry_point', $promotion->entry_point ?? null) }}">
                <i class="form-group__bar"></i>
            </div>
        </div>
    @endif
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Promo Type *</label>

            <select class="select2 required" name="type_id" id="promo_type">
                <option value="">- Select -</option>
                @foreach ($promo_types as $type)
                    <option value="{{$type->id}}"
                            @if ((old('type_id', $promotion->type_id ?? null) == $type->id))
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
    <div class="col-md-6">
        <div class="form-group">
            <label>Is Public</label>
            <br>
            <div class="toggle-switch">
                <input type="checkbox" name="is_public" class="toggle-switch__checkbox" @if ((old('is_public') == 'on')||($promotion->is_public ?? true)) checked @endif>
                <i class="toggle-switch__helper"></i>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Has MGM</label>
            <br>
            <div class="toggle-switch">
                <input type="checkbox" name="has_mgm" class="toggle-switch__checkbox" @if ((old('has_mgm') == 'on')||($promotion->has_mgm ?? false)) checked @endif>
                <i class="toggle-switch__helper"></i>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label>Max User Participations</label>
            <input type="text" class="form-control number" maxlength="2" name="max_user_participations" id="max_user_participations" value="{{ old('max_user_participations', $promotion->max_user_participations ?? '1') }}">
            <i class="form-group__bar"></i>
        </div>
    </div>

    @if (count(config('promotion.langs')) > 1)
        <div class="col-md-6">
            <div class="form-group">
                <label>Lang *</label>

                <select class="select2 required" name="lang" id="lang">
                    <option value="">- Select -</option>
                    @foreach (config('promotion.langs') as $lang => $timezone)
                        <option value="{{$lang}}"
                                @if ((old('lang', $promotion->lang ?? null) == $lang))
                                selected
                            @endif
                        >{{$lang}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @else
        <input type="hidden" name="lang" value="{{array_key_first(config('promotion.langs'))}}">
    @endif


{{--    <div class="col-6">--}}
{{--        <div class="form-group">--}}
{{--            <label>Max User Participations By Day</label>--}}
{{--            <input type="text" class="form-control" maxlength="2" name="max_user_participations_by_day" id="max_user_participations_by_day" value="{{ old('max_user_participations_by_day', isset($promotion) ? $promotion->max_user_participations_by_day : null) }}">--}}
{{--            <i class="form-group__bar"></i>--}}
{{--        </div>--}}
{{--    </div>--}}

</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Starts</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                <div class="form-group">
                    <input type="text" class="form-control my-datetime-picker flatpickr-input" name="starts" id="starts" value="{{ old('starts', $promotion->starts ?? null) }}" required>
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
                    <input type="text" class="form-control my-datetime-picker flatpickr-input" name="ends" id="ends" value="{{ old('ends', $promotion->ends ?? null)  }}">
                    <i class="form-group__bar"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="form-group">
            <label>Legal</label>
            <input type="file" class="form-control" name="legal_file" id="legal_file" accept="pdf">

            <div class="input-group actions">
                <span class="input-group-addon"><a href="{{ isset($promotion) ? (\Str::startsWith($promotion->legal, 'http') ? $promotion->legal : Storage::disk('public')->url($promotion->legal))  : '#'}}" class="actions__item zmdi zmdi-link" target="_blank"></a></span>
                <div class="form-group">
                    <input type="text" class="form-control" maxlength="100" placeholder="or link to file" name="legal" id="legal" value="{{ old('legal', $promotion->legal ?? null) }}">
                    <i class="form-group__bar"></i>
                </div>
            </div>
        </div>

    </div>
@includeWhen(config('promotion.front_templates_enabled'), 'promotion::promotions.templates')
@includeWhen(config('promotion.extra_fields_enabled'), 'promotion::promotions.partials.extrafields')
@includeWhen(config('promotion.rewards_fields_enabled'), 'promotion::promotions.partials.rewards')
</div>

<div class="row justify-content-center">
    <div>
        <a class="btn btn-danger btn--icon-text waves-effect" href="{{ route('promotions.home') }}"><i class="zmdi zmdi-arrow-back"></i> Back</a>
        <a class="btn btn-success btn--icon-text waves-effect" id="submit" href="#"><i class="zmdi zmdi-check"></i> Submit</a>
    </div>
</div>
