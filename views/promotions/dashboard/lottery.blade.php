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
    <div class="col-md-6">
        <div class="table-responsive">
            <table class="table table-sm table-striped mb-3">
                <thead class="thead-inverse">
                <tr>
                    <th>Winners</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($promotion->winners()->get() as $winner)
                    <tr>
                        <td>{{ $winner->user->id }} - {{ $winner->user->sponsor_code }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <div class="table-responsive">
            <table class="table table-sm table-striped mb-3">
                <thead class="thead-inverse">
                <tr>
                    <th>Reserves</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($promotion->reserves()->get() as $reserve)
                    <tr>
                        <td>{{ $reserve->user->id }} - {{ $reserve->user->sponsor_code }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@push('custom-js')
    <script>
        $(document).ready(function () {
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
                            console.log(response.data.winners);
                            console.log(response.data.reserves);

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
