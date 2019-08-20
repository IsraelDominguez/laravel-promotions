
<div class="card card-inverse widget-pie bg-info">
    <div class="card-header"><h2 class="card-title">{{$title}}</h2>
        <div class="stats__info">
            <div>
                <h2 class="color-white">{{$total}}</h2>
            </div>
        </div>
    </div>

    <div class="col-6 col-sm-4 col-md-6 col-lg-4 widget-pie__item">
        <div class="participations-ok-chart easy-pie-chart" data-percent="{{($used / $total * 100)}}" data-size="80" data-track-color="rgba(0,0,0,0.08)" data-bar-color="#fff">
            <span class="easy-pie-chart__value">{{number_format($used / $total * 100, 2)}}</span>
        </div>
        <div class="widget-pie__title"><strong>Used</strong><br>{{ $used }}</div>
    </div>

    <div class="col-6 col-sm-4 col-md-6 col-lg-4 widget-pie__item">
        <div class="participations-ok-chart easy-pie-chart" data-percent="{{($free / $total * 100)}}" data-size="80" data-track-color="rgba(0,0,0,0.08)" data-bar-color="#fff">
            <span class="easy-pie-chart__value">{{number_format($free / $total * 100, 2)}}</span>
        </div>
        <div class="widget-pie__title"><strong>Free</strong><br>{{ $free }}</div>
    </div>
</div>
