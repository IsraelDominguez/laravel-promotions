@extends('genetsis-admin::layouts.admin-sections')

@section('section-card-header')
    @component('genetsis-admin::partials.card-header')
        @slot('card_title')
            New {{ title_case($section) }}
        @endslot
    @endcomponent
@endsection


@section('section-content')

    <form action="{{ route('campaigns.store') }}" id="form" method="POST">
        {{ csrf_field() }}

        @include('promotion::campaigns.form')

    </form>

@endsection

@section('custom-js')
    <script>
        $(document).ready(function() {
            $("#submit").click(function() {
                $("#form").submit();
            });
        });
    </script>
@endsection