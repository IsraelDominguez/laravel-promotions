@extends('genetsis-admin::layouts.admin-sections')


@section('section-card-header')
    @component('genetsis-admin::partials.card-header')
        @slot('card_title')
            {{ \Str::plural(\Str::title($section)) }} List
        @endslot

        <a class="btn btn-success btn--icon-text waves-effect" href="{{ route(\Str::plural($section).'.create') }}"><i class="zmdi zmdi-plus"></i> New {{ \Str::title($section) }}</a>
    @endcomponent
@endsection



@section('section-content')

    <div class="table-responsive">
        <table id="data-promotions" class="table table-bordered table-striped">
            <thead class="thead-inverse">
                <tr>
                    <td>#</td>
                    <td>Name</td>
                    <td>Starts</td>
                    <td>Ends</td>
                    <td>Campaign</td>
                    <td>Type</td>
                    <td>Entrypoint</td>
                    <td>Participations</td>
                </tr>
            </thead>
        </table>
    </div>


@endsection

@push('custom-js')
    <script>
        $(document).ready(function() {

            var table = $('#data-promotions').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{route('promotions.api')}}',
                columns: [
                    {data: 'id', orderable: false, searchable: false},
                    {data: 'name'},
                    {data: 'starts'},
                    {data: 'ends'},
                    {data: 'campaign.name'},
                    {data: 'type.name'},
                    {data: 'entrypoint_id', orderable: false},
                    {data: 'participations', searchable: false},
                    {data: 'options', name: 'options', orderable: false, searchable: false, className: 'options-actions'},
                    {data: 'delete', name: 'delete', orderable: false, searchable: false, className: 'options-delete'},
                ],
                order: [[2,'desc']]
            });

            $('#data-promotions tbody').on('click', 'td.options-delete', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var id = row.data().id;

                swal({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this Promotion!',
                    type: 'warning',
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-danger',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonClass: 'btn btn-secondary'
                }).then(function(){
                    delete_url = '{{route('promotions.destroy', ':id')}}';
                    delete_url = delete_url.replace(':id', id);

                    $.ajax({
                        url: delete_url,
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
                }).catch(swal.noop);
            });

            @if ($message = Session::get('success'))
                notify('{{ $message }}');
            @endif
        });
    </script>
@endpush
