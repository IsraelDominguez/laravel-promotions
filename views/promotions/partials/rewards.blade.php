<div class="col-md-6">
    <h3 class="card-block__title">Rewards</h3>
    <div id="rewards">

    </div>
</div>

@prepend('custom-js')
    <script>
        $(document).ready(function() {
            @if (isset($promotion))
                if ({{count($promotion->rewards)}} > 0) {
                    @foreach($promotion->rewards as $reward)
                    add_reward('{{$reward->key}}', '{{$reward->name}}', '{{$reward->stock}}');
                    @endforeach
                } else {
                    add_reward('', '', '');
                }
            @else
                add_reward('','','');
            @endif

            $("#new_reward").click(function (e) {
                e.preventDefault();
                add_reward('', '', '');
            });
        });
    </script>
@endprepend
