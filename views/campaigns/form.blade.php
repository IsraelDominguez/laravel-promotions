
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $campaign->name ?? '') }}">
            <i class="form-group__bar"></i>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="key">Key</label>
            <input type="text" class="form-control" name="key" id="key" value="{{ old('key', $campaign->key ?? null) }}">
            <i class="form-group__bar"></i>
        </div>
    </div>

    @if (config('genetsis_admin.manage_druid_apps') == true)
        @if (count($druid_apps) > 1)
            <div class="col-md-6">
                <div class="form-group">
                    <label>Druid App *</label>
                    <select class="select2" name="client_id">
                        <option value="">- Select -</option>
                        @foreach ($druid_apps as $app)
                            <option value="{{$app->client_id}}"
                                    @if ((old('client_id', $campaign->client_id ?? null) == $app->client_id))
                                    selected
                                @endif
                            >{{$app->client_id}} - {{$app->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @else
            <input type="hidden" name="client_id" value="{{(count($druid_apps)==1) ? (empty($campaign->client_id) ? $druid_apps->pop()->client_id : $campaign->client_id) : ''}}"/>
        @endif
    @else
        <div class="col-md-6">
            <div class="form-group">
                <label for="entry_point">Entry Point</label>
                <input type="text" class="form-control" name="entry_point" id="entry_point" value="{{ old('key', $campaign->entry_point ?? null) }}">
                <i class="form-group__bar"></i>
            </div>
        </div>
    @endif
</div>

<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label>Starts</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                <div class="form-group">
                    <input type="text" class="form-control my-datetime-picker flatpickr-input" name="starts" id="starts" value="{{ old('starts', $campaign->starts ?? null) }}">
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
                    <input type="text" class="form-control my-datetime-picker flatpickr-input" name="ends" id="ends" value="{{ old('ends', $campaign->ends ?? null)  }}">
                    <i class="form-group__bar"></i>
                </div>
            </div>
        </div>
    </div>
</div>


<ul class="nav justify-content-center">
    <li class="nav-item">
        <a class="btn btn-danger btn--icon-text waves-effect pull-2" href="{{ route('campaigns.home') }}"><i class="zmdi zmdi-arrow-back"></i> Back</a>
    </li>
    <li class="nav-item">
        <a class="btn btn-success btn--icon-text waves-effect" id="submit" href="#"><i class="zmdi zmdi-check"></i> Submit</a>
    </li>
</ul>
