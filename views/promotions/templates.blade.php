<?php
    if (!empty($promotion)) {
        $initial_page = $promotion->templates()->page('initial_page')->first();
        $result_page = $promotion->templates()->page('result_page')->first();
    }
?>

<div class="col-12">
    <div class="tab-container">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#seoshare" role="tab">Seo and Share</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#design" role="tab">Design</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#initialpage" role="tab">Initial Page</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#resultpage" role="tab">Result Page</a>
            </li>

        </ul>
        <div class="tab-content">
            <div class="tab-pane active fade show" id="seoshare" role="tabpanel" aria-expanded="true">
                @include("promotion::promotions.templates.seoshare")
            </div>

            <div class="tab-pane fade" id="design" role="tabpanel" aria-expanded="true">
                @include("promotion::promotions.templates.design")
            </div>

            <div class="tab-pane fade" id="initialpage" role="tabpanel" aria-expanded="true">
                @include("promotion::promotions.templates.initialpage")
            </div>

            <div class="tab-pane fade" id="resultpage" role="tabpanel" aria-expanded="true">
                @include("promotion::promotions.templates.resultpage")
            </div>
        </div>
    </div>
</div>
