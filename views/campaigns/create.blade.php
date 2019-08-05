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

@push('custom-js')
    <script>
        $(document).ready(function() {
            $('#name').blur(function() {
                $("#key").val(slugify($('#name').val()));
            });

            $("#submit").click(function() {
                $("#form").submit();
            });
        });
    </script>
@endpush
