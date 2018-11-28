@extends('genetsis-admin::layouts.admin-sections')

@section('section-card-header')
    @component('genetsis-admin::partials.card-header')
        @slot('card_title')
            New {{ title_case($section) }}
        @endslot
    @endcomponent
@endsection

@section('section-content')
    <form action="{{ route('promotions.store') }}" id="form" method="POST">
        {{ csrf_field() }}
        @include('promotion::promotions.form')
    </form>
@endsection

@push('custom-js')
    <script>
        $(document).ready(function() {
            add_reward('','','');
            add_extra_field('','','');
        });
    </script>
@endpush

@push('custom-js')
    @include('promotion::promotions.scripts')
@endpush