@extends('genetsis-admin::layouts.admin-sections')

@section('section-card-header')
    @component('genetsis-admin::partials.card-header')
        @slot('card_title')
            New {{ \Str::title($section) }}
        @endslot
    @endcomponent
@endsection

@section('section-content')
    <link type="text/css" rel="stylesheet" href="//cdn.jsdelivr.net/npm/alpaca@1.5.27/dist/alpaca/bootstrap/alpaca.min.css">

    <form action="{{ route('promotions.store') }}" id="form" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        @include('promotion::promotions.form')
    </form>
@endsection

@push('custom-js')
    @if (config('promotion.front_templates_enabled'))
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.js"></script>
        <script type="text/javascript" src="//cdn.jsdelivr.net/npm/alpaca@1.5.27/dist/alpaca/bootstrap/alpaca.min.js"></script>
        <script src="//cdn.tiny.cloud/1/9gr97pwjo4y6zq0v4ef9wq1td85291mbhcgh45mfvgowdvfb/tinymce/5/tinymce.min.js"></script>

        @include('promotion::promotions.templates.scripts', compact('promotion','campaigns'))
    @endif

    @include('promotion::promotions.scripts', ['campaigns' => $campaigns])
@endpush
