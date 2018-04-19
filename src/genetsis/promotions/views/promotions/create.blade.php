@extends('promotion::layouts.admin-sections')

@section('section-content')

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">New {{ title_case($section) }}</h2>
        </div>

        <div class="card-block">
            @include('promotion::partials.show_errors')

            <form action="{{ route('promotions.store') }}" id="form" method="POST">
                {{ csrf_field() }}

                @include('promotion::promotions.form')

            </form>

        </div>
    </div>
@endsection

@section('custom-js')
    <script>

        $(document).ready(function() {
            add_reward('','','');
            add_extra_field('','');

            $("#new_extra_field").click(function (e) {
                e.preventDefault();
                add_extra_field('', '');
            });

            $("#new_reward").click(function (e) {
                e.preventDefault();
                add_reward('', '', '');
            });

            $("#submit").click(function() {
                $("#form").submit();
            });
        });
    </script>
@endsection