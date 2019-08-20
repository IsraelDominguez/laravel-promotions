<div class="table-responsive">
    <table id="data-participations" class="table table-bordered table-striped">
        <thead class="thead-inverse">
        <tr>
            <td>#</td>
            <td>User ID</td>
            <td>Date</td>
            <td>Origin</td>
            @if ($promotion->has_mgm) <td>Sponsor</td> @endif
            <td>Status</td>
            @if ($promotion->type->code == \Genetsis\Promotions\Models\PromoType::PINCODE_TYPE) <td>Pincode</td> @endif
            @if ($promotion->type->code == \Genetsis\Promotions\Models\PromoType::MOMENT_TYPE) <td>Moment</td><td>Code</td> @endif
            @if ($promotion->type->code == \Genetsis\Promotions\Models\PromoType::QRS_TYPE) <td>QR</td> @endif
        </tr>
        </thead>
    </table>
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


@push('custom-js')
    <script>
        $(document).ready(function () {
            var table_participations = $('#data-participations').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/api/v1/participations/{{$promotion->id}}',
                columns: [
                    {data: 'id'},
                    {data: 'user_id'},
                    {data: 'date'},
                    {data: 'origin'},
                        @if ($promotion->has_mgm)
                    {
                        data: 'sponsor'
                    },
                        @endif
                        @if ($promotion->type->code == \Genetsis\Promotions\Models\PromoType::PINCODE_TYPE)
                    {
                        data: 'code.code'
                    },
                        @endif
                        @if ($promotion->type->code == \Genetsis\Promotions\Models\PromoType::MOMENT_TYPE)
                    {
                        data: function (data) {
                            return ((data.moment) ? data.moment.date : '')
                        }, searchable: false, orderable: false
                    },
                    {
                        data: function (data) {
                            return ((data.moment) ? data.moment.code_to_send : 'Not Win')
                        }, searchable: false, orderable: false
                    },
                        @endif
                        @if ($promotion->type->code == \Genetsis\Promotions\Models\PromoType::QRS_TYPE)
                    {
                        data: 'qr.object_id', orderable: true, searchable: true
                    },
                        @endif
                    {
                        data: 'status'
                    },
                        @if (count($promotion->extra_fields)>0)
                    {
                        data: 'extra', name: 'extra', orderable: false, searchable: true
                    },
                        @endif
                    {
                        data: 'delete', name: 'delete', orderable: false, searchable: false, className: 'delete pr-0'
                    },
                    {data: 'edit', name: 'edit', orderable: false, searchable: false, className: 'edit pl-0'}
                ],
                order: [[2, 'desc']]
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
        });
    </script>
@endpush


