@extends('promotion::layouts.admin-sections')

@section('section-content')
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Edit {{ title_case($section) }}</h2>
        </div>

        <div class="card-block">
            @include('promotion::partials.show_errors')

            <form action="{{ route('campaigns.update', $campaign->id) }}" id="form" method="POST">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                @include('promotion::campaigns.form')

            </form>

        </div>
    </div>
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