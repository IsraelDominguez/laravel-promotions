@extends('promotion::layouts.admin-sections')

@section('section-content')
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Campaigns List</h2>
            <div class="actions">
                <a class="btn btn-success btn--icon-text waves-effect" href="{{ route('campaigns.create') }}"><i class="zmdi zmdi-plus"></i> New Campaign</a>
            </div>
        </div>

        <div class="card-block">
            @include('promotion::partials.show_errors')

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
                                    <a class="actions__item zmdi zmdi-delete" onclick="javascript:$('#form-{{$campaign->id}}').submit();" id="del"></a>
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
            {{ $campaigns->links('partials.pagination.bootstrap-4') }}

        </div>
    </div>
@endsection

@section('custom-js')
    @if ($message = Session::get('success'))
    <script>
        $(document).ready(function() {
            notify('{{ $message }}');
        });
    </script>
    @endif
@endsection