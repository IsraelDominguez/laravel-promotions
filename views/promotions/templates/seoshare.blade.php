<div class="tab-pane active fade show" id="seoshare" role="tabpanel" aria-expanded="true">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Title *</label>
                <input type="text" class="form-control" name="title" id="title" value="{{ old('title', $promotion->seo->title ?? null) }}" required>
                <i class="form-group__bar"></i>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Share Text *</label>
                <input type="text" class="form-control" name="text_share" id="text_share" value="{{ old('text_share', $promotion->seo->text_share ?? null) }}" required>
                <i class="form-group__bar"></i>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Image (recomended 1200x900) *</label>
                <input type="file" class="form-control" name="image" id="image" accept="image/*" @if(empty($promotion->seo->image)) required @endif">
                <i class="form-group__bar"></i>
                @if(!empty($promotion->seo->image))
                    <img src="{{Storage::disk('public')->url($promotion->seo->image)}}" width="250px"/>

                    <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="remove_image">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">Remove</span>
                    </label>
                @endif
            </div>
        </div>

    {{--    <div class="col-md-6">--}}
    {{--        <div class="form-group">--}}
    {{--            <label>Facebook</label>--}}
    {{--            <input type="text" class="form-control" name="facebook" id="facebook" value="{{ old('facebook', $promotion->seo->facebook ?? null) }}">--}}
    {{--            <i class="form-group__bar"></i>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--    <div class="col-md-6">--}}
    {{--        <div class="form-group">--}}
    {{--            <label>Twitter</label>--}}
    {{--            <input type="text" class="form-control" name="twitter" id="twitter" value="{{ old('twitter', $promotion->seo->twitter ?? null) }}">--}}
    {{--            <i class="form-group__bar"></i>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--    <div class="col-md-6">--}}
    {{--        <div class="form-group">--}}
    {{--            <label>WhatsApp</label>--}}
    {{--            <input type="text" class="form-control" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', $promotion->seo->whatsapp ?? null) }}">--}}
    {{--            <i class="form-group__bar"></i>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    </div>
</div>
