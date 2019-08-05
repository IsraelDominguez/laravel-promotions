<div class="card">
    <div class="card-header">
        <h2 class="card-title">Entry Points</h2>
        <small class="card-subtitle">If you need, you can refresh from Dru-ID.</small>
        <div class="actions">
            <a href="{{route('campaign.refresh', $campaign->client_id)}}" id="refresh" class="actions__item zmdi zmdi-refresh"></a>
        </div>
    </div>

    <div class="card-block">
        <div class="table-responsive">
            <table class="table table-sm table-striped mb-3">
                <thead class="thead-inverse">
                <tr>
                    <th>Key</th>
                    <th>Name</th>
                    <th>Ids</th>
                    <th>Fields</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($campaign->entrypoints as $entrypoint)
                    <tr>
                        <td>{{ $entrypoint->key }}</td>
                        <td>{{ $entrypoint->name }}</td>
                        <td>{{ json_encode($entrypoint->ids) }}</td>
                        <td>{{ json_encode($entrypoint->fields) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
