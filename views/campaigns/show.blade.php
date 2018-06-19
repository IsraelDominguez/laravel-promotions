@extends('genetsis-admin::layouts.admin-sections')

@section('section-content')

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Campaign - {{ $campaign->name }}</h2>

        </div>

        <div class="card-block">

            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <strong>Name:</strong>
                        {{ $campaign->name}}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <strong>Starts:</strong>
                        {{ $campaign->starts}}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <strong>Ends:</strong>
                        {{ $campaign->ends}}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <strong>Promotions:</strong>
                    @foreach ($campaign->promotions as $promotion)
                        {{ $promotion->name }}
                    @endforeach
                    </div>
                </div>
            </div>

            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="btn btn-danger btn--icon-text waves-effect pull-2" href="{{ route('campaigns.home') }}"><i class="zmdi zmdi-arrow-back"></i> Back</a>
                </li>
            </ul>
        </div>
    </div>

@endsection
