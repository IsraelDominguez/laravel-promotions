@extends('promotion::layouts.admin-sections')

@section('section-content')

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Edit {{ title_case($section) }}</h2>
        </div>

        <div class="card-block">
            @include('promotion::partials.show_errors')

            <form action="{{ route('promotions.update', $promotion->id) }}" id="form" method="POST">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                @include('promotion::promotions.form')

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

            if ({{count($promotion->extra_fields)}} > 0) {
                @foreach($promotion->extra_fields as $extra_field)
                    add_extra_field('{{$extra_field->key}}', '{{$extra_field->name}}');
                @endforeach
            } else {
                add_extra_field('', '');
            }

            if ({{count($promotion->rewards)}} > 0) {
                @foreach($promotion->rewards as $reward)
                    add_reward('{{$reward->key}}', '{{$reward->name}}', '{{$reward->stock}}');
                @endforeach
            } else {
                add_reward('', '', '');
            }

            $("#new_extra_field").click(function (e) {
                e.preventDefault();
                add_extra_field('', '');
            });

            $("#new_reward").click(function (e) {
                e.preventDefault();
                add_reward('', '', '');
            });
        });
    </script>

@endsection