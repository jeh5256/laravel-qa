<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h2>{{ $answers_count . " " . str_plural('Answer', $answers_count) }}</h2>
                </div>
                <hr />
                
                @include('layouts._messages')
                
                @foreach ($answers as $answer)
                    <div class="media">
                        <div class="d-flex flex-column vote-controls">
                            <a 
                                title="This answer is useful" 
                                class="up-vote {{ Auth::guest() ? 'off' : '' }}"
                                onClick="event.preventDefault(); document.getElementById('up-vote-answer-{{ $answer->id }}').submit();"
                            >
                                <i class="fas fa-caret-up fa-3x"></i>
                            </a>
                            <form id="up-vote-answer-{{ $answer->id }}" action="/answers/{{ $answer->id }}/vote" method="POST">
                                @csrf
                                <input type="hidden" name="vote" value="1" />
                            </form>
                            <span class="votes-count">{{ $answer->vote_count }}</span>
                            <a 
                                title="This answer is not useful" 
                                class="down-vote {{ Auth::guest() ? 'off' : '' }}"
                                onClick="event.preventDefault(); document.getElementById('down-vote-answer-{{ $answer->id }}').submit();"
                            >
                                <i class="fas fa-caret-down fa-3x"></i>
                            </a>
                            <form id="down-vote-answer-{{ $answer->id }}" action="/answers/{{ $answer->id }}/vote" method="POST">
                                @csrf
                                <input type="hidden" name="vote" value="-1" />
                            </form>

                            @can('accept', $answer)
                                <a title="Click to mark as best answer" 
                                    class="{{ $answer->status }} mt-2"
                                    onclick="event.preventDefault(); document.getElementById('accept-answer-{{ $answer->id }}').submit();"
                                >
                                    <i class="fas fa-check fa-2x"></i>
                                </a>
                                <form 
                                    id="accept-answer-{{ $answer->id }}" 
                                    action="{{ route('answers.accept', $answer->id) }}"
                                    method="POST"
                                    style="display:none;"
                                >
                                    @csrf
                                </form>
                            @else
                                @if ($answer->is_best_answer)
                                <a title="Accepted as best answer" class="{{ $answer->status }} mt-2">
                                    <i class="fas fa-check fa-2x"></i>
                                </a>
                            
                                @endif
                            @endcan

                        </div>
                        <div class="media-body">
                            {!! $answer->body_html !!}
                            <div class="row">
                                <div class="col-4">
                                    <div class="ml-auto">

                                        @can ('update', $answer)
                                            <a href="{{ route('questions.answers.edit', [$answer->id, $answer->id]) }}" class="btn btn-sm btn-outline-info">Edit</a>
                                        @endcan

                                        @can ('delete', $answer)
                                            <form method="POST" action="{{ route('questions.answers.destroy', [$answer->id, $answer->id]) }}" class="form-delete">
                                                @method('delete')
                                                @csrf()
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onClick="return confirm('Are you sure?')">
                                                        Delete
                                                </button>
                                            </form>
                                        @endcan

                                    </div>
                                </div>
                                <div class="col-4"></div>
                                <div class="col-4">
                                    <span class="text-muted">
                                        Answered {{ $answer->created_at }}
                                    </span>
                                    <div class="media mt-2">
                                        <a href="{{ $answer->user->url }}" class="pr-2">
                                            <img src="{{ $answer->user->avatar }}" />
                                        </a>
                                        <div class="media-body mt-1">
                                            <a href="{{ $answer->user->url }}">
                                                {{ $answer->user->name }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr/>
                @endforeach

            </div>
        </div>
    </div>
</div>