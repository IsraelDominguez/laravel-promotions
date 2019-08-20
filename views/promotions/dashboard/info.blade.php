<div class="price-table__price color-green">
    {{$promotion->participations->count()}} | <small>Total Participations</small>
</div>
@if($promotion->max_user_participations > 1)
    <div class="price-table__price color-green">
        {{$unique_users}} | <small>Unique Users</small>
    </div>
@endif

@if($promotion->has_mgm)
    <div class="price-table__price color-green">
        {{$unique_users}} | <small>Members Get Members</small>
    </div>
@endif

@if ($promotion->type->code == \Genetsis\Promotions\Models\PromoType::PINCODE_TYPE)
    @component('promotion::promotions.dashboard.piecharts-component', ['total' => count($pincodes->all), 'used' => $pincodes->used, 'free' => count($pincodes->all)-$pincodes->used])
        @slot('title') Pincodes @endslot
    @endcomponent
@endisset

@if ($promotion->type->code == \Genetsis\Promotions\Models\PromoType::MOMENT_TYPE)
    @component('promotion::promotions.dashboard.piecharts-component', ['total' => count($moments->all), 'used' => $moments->used, 'free' => count($moments->all)-$moments->used])
        @slot('title') Moments @endslot
    @endcomponent
@endisset

{{--<div class="col-sm-6 col-md-3">--}}
{{--    <div class="stats__item text-center">--}}
{{--        <div class="stats__chart bg-cyan">--}}
{{--            <div class="participations-ok-chart easy-pie-chart" data-percent="50" data-size="80" data-track-color="rgba(0,0,0,0.08)" data-bar-color="#fff">--}}
{{--                <span class="easy-pie-chart__value">92</span>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="stats__info">--}}
{{--            <div>--}}
{{--                <h2>987,459</h2>--}}
{{--                <small>Website Traffics</small>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}



{{--<div class="card">--}}
{{--    <div class="card-header"><h2 class="card-title">Pincodes</h2></div>--}}
{{--    <div class="card-block">--}}
{{--        <div class="participations-ok-chart easy-pie-chart" data-percent="74" data-size="140" data-track-color="#eee" data-bar-color="#03A9F4">--}}
{{--            <span class="easy-pie-chart__value">74</span>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

@push('custom-js')
    <script>
        $(document).ready(function () {
            $('.participations-ok-chart').each(function () {
                var value = $(this).data('value');
                var size = $(this).data('size');
                var trackColor = $(this).data('track-color');
                var barColor = $(this).data('bar-color');

                $(this).find('.easy-pie-chart__value').css({
                    lineHeight: (size) + 'px',
                    fontSize: (size / 4) + 'px',
                    color: barColor
                });

                $(this).easyPieChart({
                    easing: 'easeOutBounce',
                    barColor: barColor,
                    trackColor: trackColor,
                    scaleColor: 'rgba(0,0,0,0)',
                    lineCap: 'round',
                    lineWidth: 2,
                    size: size,
                    animate: 3000,
                    onStep: function (from, to, percent) {
                        $(this.el).find('.percent').text(Math.round(percent));
                    }
                })
            });
        });
    </script>
@endpush
