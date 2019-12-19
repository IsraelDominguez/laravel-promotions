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
<div class="row">

    <div class="card">
        <div class="card-header"><h2 class="card-title">Winners</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="data-winners">
                    <thead class="thead-inverse">
                    <tr>
                        <td>User Id</td>
                        <td>Code</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($promotion->winners()->get() as $winner)
                        <tr>
                            <td>{{ $winner->user->id }}</td>
                            <td>{{ $winner->user->sponsor_code }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h2 class="card-title">Reserves</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="data-reserves">
                    <thead class="thead-inverse">
                    <tr>
                        <td>User Id</td>
                        <td>Code</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($promotion->reserves()->get() as $reserve)
                        <tr>
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
        <div class="form-group">
            <label>Final Winners (comma separated)</label>
            <input type="text" class="form-control" name="winners" id="winners" value="{{ old('winners', 1) }}" required>
            <i class="form-group__bar"></i>
        </div>
    </div>
    <div class="col-12">
        <ul class="nav justify-content-center">
            <li class="nav-item">
                <a class="btn btn-success btn--icon-text waves-effect" id="send-winners" href="#"><i class="zmdi zmdi-refresh"></i> Send Winners</a>
            </li>
        </ul>
    </div>
</div>

<style>
    .table-selected {
        background-color: #acbad4 !important;
    }
</style>

@push('custom-js')
    <script>
        $(document).ready(function () {

            var table_winners = $('#data-winners').DataTable({
                paging: false,
                ordering: false,
                searching: false
            });

            var table_reserves = $('#data-reserves').DataTable({
                paging: false,
                ordering: false,
                searching: false
            });



            // $('#data-winners tbody').on( 'click', 'tr', function () {
            //     $(this).toggleClass('table-selected');
            // } );
            //
            // $('#data-reserves tbody').on( 'click', 'tr', function () {
            //     $(this).toggleClass('table-selected');
            // } );

            // $('#send-winners').click( function () {
            //     var winners_selected = table_winners.rows('.table-selected').data().concat(table_reserves.rows('.table-selected').data());
            //     console.log(winners_selected);
            //     winners_selected.each(function(winner) {
            //         console.log(winner);
            //     });
            //
            // } );

            $('#getwinners').on('click', function() {
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
                            notify('An error has ocurred: '+response.message,'top','right','','danger');
                        }
                    },
                    error: function(response) {
                        notify('An error has ocurred: '+response.message,'top','right','','danger');
                    }
                });
            });

        });
    </script>
@endpush
