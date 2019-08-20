<div class="table-responsive">
    <table id="data-pincodes" class="table table-bordered table-striped">
        <thead class="thead-inverse">
        <tr>
            <td>Used</td>
            <td>Code</td>
        </tr>
        </thead>
    </table>
</div>



@push('custom-js')
    <script>
        $(document).ready(function () {
            var table_pincodes = $('#data-pincodes').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/api/v1/promotion/{{$promotion->id}}/pincodes',
                columns: [
                    {data: 'used'},
                    {data: 'code'}
                ],
                order: [[0, "desc"]]
            });
        });
    </script>
@endpush
