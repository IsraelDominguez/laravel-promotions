<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Title</label>
            <input type="text" class="form-control" name="title" id="title" value="{{ old('title', $promotion->seo->title ?? null) }}">
            <i class="form-group__bar"></i>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Facebook</label>
            <input type="text" class="form-control" name="facebook" id="facebook" value="{{ old('facebook', $promotion->seo->facebook ?? null) }}">
            <i class="form-group__bar"></i>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Twitter</label>
            <input type="text" class="form-control" name="twitter" id="twitter" value="{{ old('twitter', $promotion->seo->twitter ?? null) }}">
            <i class="form-group__bar"></i>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>WhatsApp</label>
            <input type="text" class="form-control" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', $promotion->seo->whatsapp ?? null) }}">
            <i class="form-group__bar"></i>
        </div>
    </div>
</div>
