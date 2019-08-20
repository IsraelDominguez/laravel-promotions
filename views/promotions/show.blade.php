@extends('genetsis-admin::layouts.admin-sections')

@section('section-card-header')
    @component('genetsis-admin::partials.card-header')
        @slot('card_title')
            Promotion: {{$promotion->name}}
        @endslot
    @endcomponent
@endsection

@section('section-content')
    <div class="tab-container">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#home" role="tab" aria-expanded="true">Info</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#participations" role="tab" aria-expanded="false">Participations</a>
            </li>
            @if ($promotion->type->code == \Genetsis\Promotions\Models\PromoType::PINCODE_TYPE)
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#pincodes" role="tab">Pincodes</a>
                </li>
            @endif
            @if ($promotion->type->code == \Genetsis\Promotions\Models\PromoType::MOMENT_TYPE)
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#moments" role="tab">Win Moments</a>
                </li>
            @endif
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#statistics" role="tab">Statistics</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade active show" id="home" role="tabpanel" aria-expanded="true">
                @include('promotion::promotions.dashboard.info')
            </div>
            <div class="tab-pane fade" id="participations" role="tabpanel" aria-expanded="false">
                @include('promotion::promotions.dashboard.participations')
            </div>
            <div class="tab-pane fade" id="moments" role="tabpanel" aria-expanded="true">
                @includeWhen(($promotion->type->code == \Genetsis\Promotions\Models\PromoType::MOMENT_TYPE), 'promotion::promotions.dashboard.moments')
            </div>
            <div class="tab-pane fade" id="pincodes" role="tabpanel" aria-expanded="true">
                @includeWhen(($promotion->type->code == \Genetsis\Promotions\Models\PromoType::PINCODE_TYPE), 'promotion::promotions.dashboard.pincodes')
            </div>
            <div class="tab-pane fade" id="statistics" role="tabpanel">
                @include('promotion::promotions.dashboard.statistics')
            </div>
        </div>
    </div>

    <ul class="nav justify-content-center">
        <li class="nav-item">
            <a class="btn btn-danger btn--icon-text waves-effect pull-2" href="{{ route('promotions.home') }}"><i class="zmdi zmdi-arrow-back"></i> Back</a>
        </li>
    </ul>
@endsection

