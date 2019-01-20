@if ($answers_count > 0)
    <div class="row mt-4" v-cloak>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h2>{{ $answers_count . " " . str_plural('Answer', $answers_count) }}</h2>
                    </div>
                    <hr />
                    
                    @include('layouts._messages')
                    
                    @foreach ($answers as $answer)
                        
                        @include('answers._answer')

                    @endforeach

                </div>
            </div>
        </div>
    </div>
@endif