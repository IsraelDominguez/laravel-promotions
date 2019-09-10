@extends('genetsis-admin::layouts.admin-sections')

@section('section-card-header')
    @component('genetsis-admin::partials.card-header')
        @slot('card_title')
            New {{ \Str::title($section) }}
        @endslot
    @endcomponent
@endsection

@section('section-content')

    <form action="{{ route('promotions.store') }}" id="form" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        @include('promotion::promotions.form')
    </form>
@endsection

@push('custom-js')
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.js"></script>
    <script type="text/javascript" src="http://cdn.jsdelivr.net/npm/alpaca@1.5.27/dist/alpaca/bootstrap/alpaca.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/9gr97pwjo4y6zq0v4ef9wq1td85291mbhcgh45mfvgowdvfb/tinymce/5/tinymce.min.js"></script>

    @include('promotion::promotions.scripts', ['campaigns' => $campaigns])
@endpush
