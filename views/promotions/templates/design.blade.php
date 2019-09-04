<div class="tab-pane fade" id="design" role="tabpanel" aria-expanded="true">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Background (max 100Kb SVG)</label>
                <input type="file" class="form-control" name="background_image" id="background_image" accept=".svg">
                <i class="form-group__bar"></i>
                @if(!empty($promotion->design->background_image))
                    <img src="data:image/svg+xml;base64,{{ $promotion->design->background_image}}" width="150px"/>

                    <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="remove_background">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">Remove</span>
                    </label>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>BackGround Color</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="zmdi zmdi-invert-colors"></i></span>

                    <div class="form-group color-picker">
                        <input type="text" name="background_color" class="form-control color-picker__value" value="{{ old('background_color', isset($promotion->design) ? $promotion->design->background_color : '#CD0A33') }}">
                        <i class="color-picker__preview" style="background-color: {{ old('background_color', isset($promotion->design) ? $promotion->design->background_color : '#CD0A33') }}"></i>
                        <i class="form-group__bar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
