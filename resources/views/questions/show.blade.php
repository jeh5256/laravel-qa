@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <div class="d-flex align-items-center">
                            <h1>{{ $question->title }}</h1>
                            <div class="ml-auto">
                                <a href="{{ route('questions.index') }}" class="btn btn-outline-secondary">
                                    Back to all questions
                                </a>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="media">
                        <div class="d-flex flex-column vote-controls">
                            <a 
                                title="This question is useful" 
                                class="up-vote {{ Auth::guest() ? 'off' : '' }}"
                                onClick="event.preventDefault(); document.getElementById('up-vote-question-{{ $question->id }}').submit();"
                            >
                                <i class="fas fa-caret-up fa-3x"></i>
                            </a>
                            <form id="up-vote-question-{{ $question->id }}" action="/questions/{{ $question->id }}/vote" method="POST">
                                @csrf
                                <input type="hidden" name="vote" value="1" />
                            </form>
                            <span class="votes-count">{{ $question->votes_count }}</span>
                            <a 
                                title="This question is not useful" 
                                class="down-vote {{ Auth::guest() ? 'off' : '' }}"
                                onClick="event.preventDefault(); document.getElementById('down-vote-question-{{ $question->id }}').submit();"
                            >
                                <i class="fas fa-caret-down fa-3x"></i>
                            </a>
                            <form id="down-vote-question-{{ $question->id }}" action="/questions/{{ $question->id }}/vote" method="POST">
                                @csrf
                                <input type="hidden" name="vote" value="-1" />
                            </form>
                            <a title="Click to mark as favorite (Click to unfavorite)" class="favorite favorited mt-4">
                                <i class="fas fa-star fa-2x"></i>
                            </a>
                            <span class="favorite-count">1</span>
                        </div>
                        <div class="media-body">
                            {!! $question->body_html !!}
                            <div class="float-right">
                                <span class="text-muted">
                                    Asked on {{ $question->created_at }}
                                </span>
                                <div class="media mt-2">
                                    <a href="{{ $question->user->url }}" class="pr-2">
                                        <img src="{{ $question->user->avatar }}" />
                                    </a>
                                    <div class="media-body mt-1">
                                        <a href="{{ $question->user->url }}">
                                            {{ $question->user->name }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('answers._index', [
        'answers' => $question->answers,
        'answers_count' =>$question->answers_count
    ])
    @include('answers._create')
</div>
@endsection
