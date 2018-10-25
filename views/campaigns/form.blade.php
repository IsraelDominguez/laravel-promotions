
<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ old('name', isset($campaign) ? $campaign->name : null) }}">
            <i class="form-group__bar"></i>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label for="key">Key</label>
            <input type="text" class="form-control" name="key" id="key" value="{{ old('key', isset($campaign) ? $campaign->key : null) }}">
            <i class="form-group__bar"></i>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label for="entry_point">Entry Point</label>
            <input type="text" class="form-control" name="entry_point" id="entry_point" value="{{ old('key', isset($campaign) ? $campaign->entry_point : null) }}">
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
                    <input type="text" class="form-control my-datetime-picker flatpickr-input" name="starts" id="starts" value="{{ old('starts', isset($campaign) ? $campaign->starts : null) }}">
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
                    <input type="text" class="form-control my-datetime-picker flatpickr-input" name="ends" id="ends" value="{{ old('ends', isset($campaign) ? $campaign->ends : null)  }}">
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