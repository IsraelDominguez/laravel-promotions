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
                    <th>Name</th>
                    <th>Starts</th>
                    <th>Ends</th>
                    <th>Campaign</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($promotions as $promotion)
                <tr>
                    <th scope="row">{{ ++$i }}</th>
                    <td>{{ $promotion->name}}</td>
                    <td>{{ $promotion->starts }}</td>
                    <td>{{ $promotion->ends }}</td>
                    <td>{{ $promotion->campaign->name }}</td>
                    <td>
                        <div class="actions">
                            <a class="actions__item zmdi zmdi-eye" href="{{ route('promotions.show',$promotion->id) }}"></a>
                            <a class="actions__item zmdi zmdi-edit" href="{{ route('promotions.edit',$promotion->id) }}"></a>
                            <a class="actions__item zmdi zmdi-delete del" data-id="{{$promotion->id}}"></a>
                        </div>
                        <form action="{{ route('promotions.destroy', $promotion->id) }}" method="POST" id="form-{{$promotion->id}}">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $promotions->links('genetsis-admin::partials.pagination.bootstrap-4') }}
@endsection

@push('custom-js')
    <script>
        $(document).ready(function() {
            $('.del').click(function(){
                console.log('uno');
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
