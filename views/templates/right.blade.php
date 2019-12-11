<div class="container-fluid" style="background:#FFFFFF">
    <div class="container ct">
        <div class="row">
            <div class="col-lg-6 align-top">

                <div class="titulo" style="display:block">
                    <p>{{__('To')}} {{\Carbon\Carbon::parse($promotion->ends)->locale(\App::getLocale())->isoformat('D MMMM YYYY')}} {{__('at')}} {{\Carbon\Carbon::parse($promotion->ends)->locale(\App::getLocale())->isoformat('H:mm')}}</p>
                    <h1>{{strtoupper($promotion->name)}}<span>{{ $promo_title ?? '' }}</span></h1>
                </div>

            </div>
            <div class="col-lg-6 align-top">
                <div class="imag"><img src="{{(empty($promo_image)) ? Storage::disk('public')->url($promotion->seo->image) : Storage::disk('public')->url($promo_image)}}"></div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-lg-6 align-top">
            <div class="blk participa" style="display:block">
                <div class="texto">
                    <p>{!! $promo_text ?? '' !!}</p>

                    @foreach($errors->all() as $error)
                        {!! $error !!}
                    @endforeach

                    @include('promotions.'.$page.'_' .$promotion->type->code)
                </div>
            </div>
        </div>
        <div class="col-lg-6 align-top">&nbsp;</div>
    </div>
</div>
