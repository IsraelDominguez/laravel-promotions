@extends('genetsis-admin::layouts.admin-sections')

@section('section-card-header')
    @component('genetsis-admin::partials.card-header')
        @slot('card_title')
            Campaign: {{$campaign->name}}
        @endslot
    @endcomponent
@endsection

@section('section-content')
    <div class="tab-container">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#home" role="tab" aria-expanded="true">Info</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#participations" role="tab" aria-expanded="false">Participations</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade active show" id="home" role="tabpanel" aria-expanded="true">
                <div class="price-table__price color-green">
                    {{ $dashboard_data['promotions']->sum(function($promo) { return $promo->participations_count; }) }} | <small>Total Participations</small>
                </div>
                <div class="price-table__price color-green">
                    {{
                        $dashboard_data['promotions']->map(function($promo){
                            return $promo->participations->map(function($participation){
                                return $participation->user_id;
                            })->unique();
                        })->collapse()->unique()->count()
                    }} | <small>Unique Users</small>
                </div>

                <div class="price-table__price color-green">
                    {{ $dashboard_data['promotions']->sum(function($promo){ return count($promo->participations->filter(function($participation){ return !empty($participation->sponsor); }));})}} | <small>Members Get Members</small>
                </div>

                <div>
                    @foreach ($dashboard_data['promotions'] as $promotion)
                        <a href="{{route('promotions.show', $promotion->id)}}">{{ $promotion->name }}</a>
                        {{$promotion->participations_count}}
                    @endforeach
                </div>
            </div>
            <div class="tab-pane fade" id="participations" role="tabpanel" aria-expanded="false">

                <div class="table-responsive">
                    <table id="data-participations" class="table table-bordered table-striped">
                        <thead class="thead-inverse">
                        <tr>
                            <td>#</td>
                            <td>User ID</td>
                            <td>Date</td>
                            <td>Origin</td>
                            <td>Sponsor</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($dashboard_data['promotions'] as $promotion)
                            @foreach ($promotion->participations as $participation)
                                <tr>
                                    <td>{{$participation->id}}</td>
                                    <td>{{$participation->user_id}}</td>
                                    <td>{{$participation->date}}</td>
                                    <td>{{$participation->origin}}</td>
                                    <td>{{$participation->sponsor}}</td>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <ul class="nav justify-content-center">
        <li class="nav-item">
            <a class="btn btn-danger btn--icon-text waves-effect pull-2" href="{{ route('campaigns.home') }}"><i class="zmdi zmdi-arrow-back"></i> Back</a>
        </li>
    </ul>
@endsection

@push('custom-js')
    <script>
        $(document).ready(function () {
            var table_participations = $('#data-participations').DataTable();

        });
    </script>
@endpush
