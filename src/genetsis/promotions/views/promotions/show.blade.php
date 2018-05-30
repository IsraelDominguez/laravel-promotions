@extends('promotion::layouts.admin-sections')

@section('section-content')
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Promotion: {{ $promotion->name }}</h2>
        </div>

        <div class="card-block">
            <div class="tab-container">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#home" role="tab" aria-expanded="true">Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#participations" role="tab" aria-expanded="false">Participations</a>
                    </li>
                    {{--<li class="nav-item">--}}
                    {{--<a class="nav-link" data-toggle="tab" href="#messages" role="tab">Pincodes</a>--}}
                    {{--</li>--}}
                    {{--<li class="nav-item">--}}
                    {{--<a class="nav-link" data-toggle="tab" href="#settings" role="tab">Win Moments</a>--}}
                    {{--</li>--}}
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#statistics" role="tab">Statistics</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade active show" id="home" role="tabpanel" aria-expanded="true">
                        Number of Participations: {{ count($promotion->participations) }}
                        <br>
                        Unique Users Participations: {{ $unique_users }}
                        <br>
                        @isset($pincodes)
                            Pincodes: {{ count($pincodes->all) }}
                            <br>- Used: {{ count($pincodes->used) }}
                            <br>- Free: {{ count($pincodes->all)-count($pincodes->used) }}
                        @endisset
                    </div>
                    <div class="tab-pane fade" id="participations" role="tabpanel" aria-expanded="false">
                        <div class="table-responsive">
                            <table id="data-participations" class="table table-bordered table-striped">
                                <thead class="thead-inverse">
                                <tr>
                                    <td>#</td>
                                    <td>User ID</td>
                                    <td>Email</td>
                                    <td>Date</td>
                                    <td>Origin</td>
                                    <td>Sponsor</td>
                                    @isset($pincodes) <td>Pincode</td> @endisset
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="statistics" role="tabpanel">
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
                    </div>
                </div>
            </div>


            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="btn btn-danger btn--icon-text waves-effect pull-2" href="{{ route('promotions.home') }}"><i class="zmdi zmdi-arrow-back"></i> Back</a>
                </li>
            </ul>
        </div>

    </div>


    <div class="modal fade" id="modal-edition" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Participation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="edit-form" method="POST">
                        <input type="hidden" name="id" id="id" value=""/>
                        <div class="form-group">
                            <label for="recipient-name" class="form-control-label">Origin:</label>
                            <input type="text" class="form-control" id="origin" name="origin" value="">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="form-control-label">Status:</label>
                            <input type="text" class="form-control" id="status" name="status" value="">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="edit-submit">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')

    <script>
        $(document).ready(function () {
            var table = $('#data-participations').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/api/v1/participations/{{$promotion->id}}',
                columns: [
                    {data: 'id'},
                    {data: 'user_id'},
                    {data: 'user.email'},
                    {data: 'date'},
                    {data: 'origin'},
                    {data: 'sponsor'},
                        @isset($pincodes) {data: 'code.code'}, @endisset
                    {data: 'status'},
                    {data: 'extra', name: 'extra', orderable: false, searchable: false},
                    {data: 'delete', name: 'delete', orderable: false, searchable: false, className: 'delete'},
                    {data: 'edit', name: 'edit', orderable: false, searchable: false, className: 'edit'}
                ]
            });

            $('#modal-edition').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget) // Button that triggered the modal
                var id_participation = button.data('id');

                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                var modal = $(this)

                modal.find('#edit-form #id').val(id_participation);

                $.ajax({
                    url: '/api/v1/participation/'+id_participation,
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        modal.find('#edit-form #origin').val(response.origin);
                        modal.find('#edit-form #status').val(response.status);
                    },
                    error: function(response) {
                        notify('An error has ocurred','top','right','','danger');
                    }
                });
            });

            $('#edit-submit').on('click', function() {
                $.ajax({
                    url: '/api/v1/participation/'+$('#edit-form #id').val(),
                    method: 'PUT',
                    data: $("#edit-form").serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        table.ajax.reload();
                        $('#modal-edition').modal('toggle');
                        notify(response.message);
                    },
                    error: function(response) {
                        $('#modal-edition').modal('toggle');
                        notify('An error has ocurred','top','right','','danger');
                    }
                });
            });

//            $('#data-participations tbody').on('click', 'td.edit', function () {
//                var tr = $(this).closest('tr');
//                var row = table.row(tr);
//                var id = row.data().id;
//
//                $('#modal-edition').modal('toggle');
//            });

            $('#data-participations tbody').on('click', 'td.delete', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var id = row.data().id;

                swal({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this Participation!',
                    type: 'warning',
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-danger',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonClass: 'btn btn-secondary'
                }).then(function(){
                    $.ajax({
                        url: '/api/v1/participations/delete/'+id,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            notify(response.message);
                            table.ajax.reload();
                        },
                        error: function(response) {
                            notify('An error has ocurred','top','right','','danger');
                        }
                    });
                }).catch(function(){
                    //Nothing to do
                });
            });

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

                if ($(e.target).attr("href") == "#statistics") {
                    var weekDayParticipations = [];
                    <?php foreach ($days as $key => $dayParticipation) { ?>
                    weekDayParticipations.push(["<?php echo $key; ?>", "<?php echo $dayParticipation; ?>"]);
                    <?php } ?>

                    // === Make WeekDays chart === //
                    var weekdaysChart = $.plot($("#weekdaysChart"),
                        [{data: weekDayParticipations, label: "Participations", color: "#4fabd2"}], {
                            series: {
                                bars: {show: true, barWidth: 0.5, fill: 1},
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
                            xaxis: {
                                mode: "time",
                                tickSize: [1, "day"],
                                timeformat: "%a",
                                ticks: [[1, "Mon"], [2, "Tue"], [3, "Wed"], [4, "Thu"], [5, "Fri"], [6, "Sat"], [0, "Sun"]]
                            },
                        });


                    var hourParticipations = [];
                    <?php foreach ($hours as $key => $hourParticipation) { ?>
                    hourParticipations.push([<?php echo $key;?>, <?php echo $hourParticipation; ?>]);
                    <?php } ?>


                    // === Make Hours chart === //
                    var hoursChart = $.plot($("#hoursChart"),
                        [{data: hourParticipations, label: "Participations", color: "#4fabd2"}], {
                            series: {
                                bars: {show: true},
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
                        });


                    var participations = [];
                    <?php foreach ($participations as $key => $participation) { ?>
                    participations.push([<?php echo $key;?>, <?php echo $participation; ?>]);
                    <?php } ?>


                    // === Make Hours chart === //
                    var participationsChart = $.plot($("#participationsChart"), [{
                        data: participations,
                        label: "Participations",
                        color: "#4fabd2"
                    }], {
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
                    });


                    // Tooltips for Flot Charts
                    if ($('.flot-chart')[0]) {
                        $('.flot-chart').bind('plothover', function (event, pos, item) {
                            if (item) {
                                var x = item.datapoint[0].toFixed(2),
                                    y = item.datapoint[1].toFixed(2);
                                $('.flot-tooltip').html(item.series.label + ' of ' + x + ' = ' + y).css({
                                    top: item.pageY + 5,
                                    left: item.pageX + 5
                                }).show();
                            }
                            else {
                                $('.flot-tooltip').hide();
                            }
                        });

                        $('<div class="flot-tooltip"></div>').appendTo('body');
                    }

                }
            });
        });


    </script>

@endsection
