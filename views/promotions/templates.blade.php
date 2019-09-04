<div class="col-12">
    <div class="tab-container">
        <ul class="nav nav-tabs" role="tablist">
            @if (config('promotion.front_share_enabled'))
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#seoshare" role="tab">Seo and Share</a>
            </li>
            @endif
            @if (config('promotion.front_design_enabled'))
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#design" role="tab">Design</a>
            </li>
            @endif
            @if (config('promotion.front_pages_enabled'))
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#initialpage" role="tab">Initial Page</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#resultpage" role="tab">Result Page</a>
            </li>
            @endif
        </ul>
        <div class="tab-content">
            @includeWhen(config('promotion.front_share_enabled'), 'promotion::promotions.templates.seoshare')

            @includeWhen(config('promotion.front_design_enabled'), 'promotion::promotions.templates.design')

            @if (config('promotion.front_pages_enabled'))
                @include("promotion::promotions.templates.initialpage")
                @include("promotion::promotions.templates.resultpage")
            @endif
        </div>
    </div>
</div>
