@extends('genetsis-admin::layouts.admin-sections')

@section('section-card-header')
    @component('genetsis-admin::partials.card-header')
        @slot('card_title')
            Edit {{ title_case($section) }}
        @endslot
    @endcomponent
@endsection

@section('section-content')
    <form action="{{ route('promotions.update', $promotion->id) }}" id="form" method="POST">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}

        @include('promotion::promotions.form')

    </form>
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