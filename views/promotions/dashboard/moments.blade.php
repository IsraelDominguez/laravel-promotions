<div class="table-responsive">
    <table id="data-moments" class="table table-bordered table-striped">
        <thead class="thead-inverse">
        <tr>
            <td>Date</td>
            <td>Used</td>
            <td>Code</td>
            <td>Send</td>
        </tr>
        </thead>
    </table>
</div>


@push('custom-js')
    <script>
        $(document).ready(function () {
            var table_moments = $('#data-moments').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/api/v1/promotion/{{$promotion->id}}/moments',
                columns: [
                    {data: 'date'},
                    {data: 'used'},
                    {data: 'code_to_send'},
                    {data: 'code_send'}
                ],
                order: [[1, "desc"]]
            });
        });
    </script>
@endpush
