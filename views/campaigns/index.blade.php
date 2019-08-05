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
        <table class="table table-sm  table-striped mb-3">
            <thead class="thead-inverse">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Starts</th>
                    <th>Ends</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($campaigns as $campaign)
                <tr>
                    <th scope="row">{{ ++$i }}</th>
                    <td>{{ $campaign->name}}</td>
                    <td>{{ $campaign->starts }}</td>
                    <td>{{ $campaign->ends }}</td>
                    <td>
                        <div class="actions">
                            <a class="actions__item zmdi zmdi-eye" href="{{ route('campaigns.show',$campaign->id) }}"></a>
                            <a class="actions__item zmdi zmdi-edit" href="{{ route('campaigns.edit',$campaign->id) }}"></a>
                            <a class="actions__item zmdi zmdi-delete del" data-id="{{$campaign->id}}"></a>
                        </div>
                        <form action="{{ route('campaigns.destroy', $campaign->id) }}" method="POST" id="form-{{$campaign->id}}">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $campaigns->links('genetsis-admin::partials.pagination.bootstrap-4') }}
@endsection

@push('custom-js')
    <script>
        $(document).ready(function() {
            $('.del').click(function(){
                clicked = this.dataset.id;
                swal({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this promotion!',
                    type: 'warning',
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-danger',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonClass: 'btn btn-secondary'
                }).then(function(){
                    $('#form-'+clicked).submit()
                }).catch(swal.noop);
            });

            @if ($message = Session::get('success'))
            notify('{{ $message }}');
            @endif
        });
    </script>
@endpush
