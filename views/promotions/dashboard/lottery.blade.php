@if ($promotion->finalWinners->count() == 0)
<form name="promo-winners" id="promo-winners">
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Number of Winners *</label>
            <input type="text" class="form-control" name="winners" id="winners" value="{{ old('winners', 1) }}" required>
            <i class="form-group__bar"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="key">Reserves</label>
            <input type="text" class="form-control" name="reserves" id="reserves" value="{{ old('reservations', 0) }}">
            <i class="form-group__bar"></i>
        </div>
    </div>
    <div class="col-md-4">
        <ul class="nav justify-content-center">
            <li class="nav-item">
                <a class="btn btn-success btn--icon-text waves-effect" id="getwinners" href="#"><i class="zmdi zmdi-refresh"></i> Reset Winners</a>
            </li>
        </ul>
    </div>
</div>
</form>
@endif

<div class="row">
    <div class="card  {{ ($promotion->finalWinners->count() == 0) ? 'col-6' : 'col-12' }}">
        <div class="card-header"><h2 class="card-title">Winners</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="data-winners">
                    <thead class="thead-inverse">
                    <tr>
                        <td>#</td>
                        <td>User Id</td>
                        <td>Code</td>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($promotion->finalWinners->count() == 0)
                        @foreach ($promotion->winners()->get() as $winner)
                            <tr>
                                <td>{{ $winner->id  }}</td>
                                <td>{{ $winner->user->id }}</td>
                                <td>{{ $winner->user->sponsor_code }}</td>
                            </tr>
                        @endforeach
                    @else
                        @foreach ($promotion->finalWinners as $winner)
                            <tr>
                                <td>{{ $winner->id  }}</td>
                                <td>{{ $winner->user->id }}</td>
                                <td>{{ $winner->user->sponsor_code }}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($promotion->finalWinners->count() == 0)
    <div class="card col-6">
        <div class="card-header"><h2 class="card-title">Reserves</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="data-reserves">
                    <thead class="thead-inverse">
                    <tr>
                        <td>#</td>
                        <td>User Id</td>
                        <td>Code</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($promotion->reserves()->get() as $reserve)
                        <tr>
                            <td>{{ $reserve->id  }}</td>
                            <td>{{ $reserve->user->id }}</td>
                            <td>{{ $reserve->user->sponsor_code }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12">
        <ul class="nav justify-content-center">
            <li class="nav-item">
                <a class="btn btn-success btn--icon-text waves-effect" id="send-winners" href="#"><i class="zmdi zmdi-refresh"></i> Send Winners</a>
            </li>
        </ul>
    </div>
    @endif

</div>

<style>
    .table-selected {
        background-color: #acbad4 !important;
    }
</style>

@push('custom-js')
    <script>
        $(document).ready(function () {
            //var winners = ["5df414e21c5f4b1ef281968a61b012b6073cea24", "9234a1db215771a50053e7570eb4cd6b7d83449e", "aba032e736ff19cf7e473584bf0602ecd080b4e1"];
            var winners = [];

            var table_winners = $('#data-winners').DataTable({
                paging: false,
                ordering: false,
                searching: false,
                rowCallback: function( row, data ) {
                    if ( $.inArray(data[0], winners) !== -1 ) {
                        $(row).addClass('table-selected');
                    }
                }
            });

        @if ($promotion->finalWinners->count() == 0)
            var table_reserves = $('#data-reserves').DataTable({
                paging: false,
                ordering: false,
                searching: false,
                rowCallback: function( row, data ) {
                    if ( $.inArray(data[0], winners) !== -1 ) {
                        $(row).addClass('table-selected');
                    }
                }
            });

            if (winners.length === 0) {
                $('#data-winners tbody').on('click', 'tr', function () {
                    $(this).toggleClass('table-selected');
                });

                $('#data-reserves tbody').on('click', 'tr', function () {
                    $(this).toggleClass('table-selected');
                });

                $('#send-winners').click(function () {
                    var winners_selected = table_winners.rows('.table-selected').data();
                    var reserves_selected = table_reserves.rows('.table-selected').data();

                    var winners_to_send = [];
                    winners_selected.each(function (winner) {
                        winners_to_send.push(winner[0]);
                    });

                    reserves_selected.each(function (winner) {
                        winners_to_send.push(winner[0]);
                    });
                    console.log(winners_to_send);
                    $.ajax({
                        url: '{{route('promotion.sendwinners', $promotion->id)}}',
                        method: 'GET',
                        data: {
                            'winners': winners_to_send
                        },
                        datatype: 'json',
                        contentType: 'application/json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.success) {
                                window.location.href = window.document.location.toString() + 'lottery';
                                window.location.reload();
                            } else {
                                notify('An error has ocurred: ' + response.message, 'top', 'right', '', 'danger');
                            }
                        },
                        error: function (response) {
                            notify('An error has ocurred: ' + response.message, 'top', 'right', '', 'danger');
                        }
                    });
                });

                $('#getwinners').on('click', function () {
                    $.ajax({
                        url: '{{route('promotion.winners', $promotion->id)}}',
                        method: 'GET',
                        data: $("#promo-winners").serialize(),
                        datatype: 'json',
                        contentType: 'application/json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.success) {
                                window.location.href = window.document.location.toString() + 'lottery';
                                window.location.reload();
                            } else {
                                notify('An error has ocurred: ' + response.message, 'top', 'right', '', 'danger');
                            }
                        },
                        error: function (response) {
                            notify('An error has ocurred: ' + response.message, 'top', 'right', '', 'danger');
                        }
                    });
                });
            }
        @endif
        });
    </script>
@endpush
