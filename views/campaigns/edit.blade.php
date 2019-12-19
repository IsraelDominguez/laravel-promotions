@extends('genetsis-admin::layouts.admin-sections')

@section('section-card-header')
    @component('genetsis-admin::partials.card-header')
        @slot('card_title')
            Edit {{ \Str::title($section) }}
        @endslot
    @endcomponent
@endsection

@section('section-content')
    <form action="{{ route('campaigns.update', $campaign->id) }}" id="form" method="POST">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}

        @include('promotion::campaigns.form')
    </form>

    @includeWhen(!empty($campaign->client_id) && !empty($campaign->secret), 'promotion::campaigns.entrypoints')

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

            @if ($message = Session::get('success'))
            notify('{{ $message }}');
            @endif
        });
    </script>
@endpush
