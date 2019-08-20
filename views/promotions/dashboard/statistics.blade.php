<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Participations</h2>
            </div>
            <div class="card-block">
                <div class="flot-chart" id="participationsChart"></div>
                {{--<div class="flot-chart-legends flot-chart-legends--bar"></div>--}}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-6">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Participations By WeekDay</h2>
            </div>
            <div class="card-block">
                <div class="flot-chart" id="weekdaysChart"></div>
                {{--<div class="flot-chart-legends flot-chart-legends--bar"></div>--}}
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Participations By Hours</h2>
            </div>
            <div class="card-block">
                <div class="flot-chart" id="hoursChart"></div>
                {{--<div class="flot-chart-legends flot-chart-legends--bar"></div>--}}
            </div>
        </div>
    </div>
</div>


@push('custom-js')
<script>
    $(document).ready(function () {
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            if ($(e.target).attr("href") == "#statistics") {

                var weekDayParticipations = [];
                <?php foreach ($days as $key => $dayParticipation) { ?>
                weekDayParticipations.push([<?php echo $key; ?>, <?php echo $dayParticipation; ?>]);
                <?php } ?>

                var weekdaydata = [
                    {
                        data: weekDayParticipations,
                        label: "Participations",
                        color: "#4fabd2"
                    }
                ];

                var weekdayopts = {
                    series: {
                        bars: {show: true, barWidth: 0.5, fill: 1, align: 'center'},
                    },
                    legend: { backgroundOpacity: 0.5},
                    grid: {
                        hoverable: true,
                        clickable: true,
                        borderColor: "#eeeeee",
                        borderWidth: 1,
                        color: "#AAAAAA"
                    },
                    yaxis: {min: 0, minTickSize: 1, tickDecimals: 0},
                    xaxis: {
                        mode: "time",
                        tickSize: [1, "day"],
                        timeformat: "%a",
                        ticks: [[1, "Mon"], [2, "Tue"], [3, "Wed"], [4, "Thu"], [5, "Fri"], [6, "Sat"], [0, "Sun"]]
                    }
                };

                var weekdaysChart = $.plot($("#weekdaysChart"),weekdaydata, weekdayopts);
                $('#weekdaysChart').UseTooltip();

                var hourParticipations = [];
                <?php foreach ($hours as $key => $hourParticipation) { ?>
                hourParticipations.push([<?php echo $key;?>, <?php echo $hourParticipation; ?>]);
                <?php } ?>

                var hoursdata = [{data: hourParticipations, label: "Participations", color: "#4fabd2"}];

                var hoursopts = {
                    series: {
                        bars: {show: true, align: 'center'},
                    },
                    legend: {backgroundOpacity: 0.5},
                    grid: {
                        hoverable: true,
                            clickable: true,
                            borderColor: "#eeeeee",
                            borderWidth: 1,
                            color: "#AAAAAA"
                    },
                    yaxis: {min: 0, minTickSize: 1, tickDecimals: 0},
                    xaxis: {tickSize: 1, minTickSize: 1, tickDecimals: 0},
                };

                var hoursChart = $.plot($("#hoursChart"), hoursdata, hoursopts);

                $('#hoursChart').UseTooltip();


                var participations = [];
                <?php foreach ($participations as $key => $participation) { ?>
                participations.push([<?php echo $key;?>, <?php echo $participation; ?>]);
                <?php } ?>

                var participationsdata = [{
                    data: participations,
                    label: "Participations",
                    color: "#4fabd2"
                }];

                var participtionsopts = {
                    legend: {backgroundOpacity: 0.5},
                    grid: {
                        hoverable: true,
                        clickable: true,
                        borderColor: "#eeeeee",
                        borderWidth: 1,
                        color: "#AAAAAA"
                    },
                    yaxis: {min: 0, minTickSize: 1, tickDecimals: 0},
                    xaxis: {
                        mode: "time",
                        timeformat: "%Y/%m/%d",
                        minTickSize: [1, "day"]
                    }
                };

                var participationsChart = $.plot($("#participationsChart"), participationsdata, participtionsopts);


                // // Tooltips for Flot Charts
                // if ($('.flot-chart')[0]) {
                //     $('.flot-chart').bind('plothover', function (event, pos, item) {
                //         if (item) {
                //             var x = item.datapoint[0].toFixed(2),
                //                 y = item.datapoint[1].toFixed(2);
                //             $('.flot-tooltip').html(item.series.label + ' of ' + x + ' = ' + y).css({
                //                 top: item.pageY + 5,
                //                 left: item.pageX + 5
                //             }).show();
                //         }
                //         else {
                //             $('.flot-tooltip').hide();
                //         }
                //     });
                //
                //     $('<div class="flot-tooltip"></div>').appendTo('body');
                // }

            }
        });
    });
</script>

@endpush
