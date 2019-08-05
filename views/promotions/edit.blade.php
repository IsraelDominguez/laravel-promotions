@extends('genetsis-admin::layouts.admin-sections')

@section('section-card-header')
    @component('genetsis-admin::partials.card-header')
        @slot('card_title')
            Edit {{ title_case($section) }}
        @endslot
    @endcomponent
@endsection

@section('section-content')
    <form action="{{ route('promotions.update', $promotion->id) }}" id="form" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}

        @include('promotion::promotions.form')

    </form>
@endsection

@push('custom-js')
    @include('promotion::promotions.scripts')
@endpush
