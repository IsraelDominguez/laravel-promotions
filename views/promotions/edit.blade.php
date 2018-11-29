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


@push('custom-js')
    <script>
        $(document).ready(function() {
            extra_fields_types.push({!!'"' . implode('", "', \Genetsis\Promotions\Models\ExtraFields::TYPES) . '"'!!});

            if ({{count($promotion->extra_fields)}} > 0) {
                @foreach($promotion->extra_fields as $extra_field)
                add_extra_field('{{$extra_field->key}}', '{{$extra_field->name}}', '{{$extra_field->type}}');
                @endforeach
            } else {
                add_extra_field('', '','');
            }

            if ({{count($promotion->rewards)}} > 0) {
                @foreach($promotion->rewards as $reward)
                add_reward('{{$reward->key}}', '{{$reward->name}}', '{{$reward->stock}}');
                @endforeach
            } else {
                add_reward('', '', '');
            }
        });
    </script>
@endpush

@push('custom-js')
    @include('promotion::promotions.scripts')
@endpush