@if ($model instanceof App\Question)
    @php
        $name = 'question';
        $firstURIPart = 'questions'
    @endphp
@elseif ($model instanceof App\Answer)
    @php
        $name = 'answer';
        $firstURIPart = 'answers'
    @endphp
@endif

@php
    $formID = $name . '-' . $model->id;
    $formAction = "/{$firstURIPart}/{$model->id}/vote";
@endphp

<div class="d-flex flex-column vote-controls">
    <a 
        title="This {{ $name }} is useful" 
        class="up-vote {{ Auth::guest() ? 'off' : '' }}"
        onClick="event.preventDefault(); document.getElementById('up-vote-{{ $formID }}').submit();"
    >
        <i class="fas fa-caret-up fa-3x"></i>
    </a>
    <form id="up-vote-{{ $formID }}" action="{{ $formAction }}" method="POST">
        @csrf
        <input type="hidden" name="vote" value="1" />
    </form>
    <span class="votes-count">{{ $model->vote_count }}</span>
    <a 
        title="This {{ $name }} is not useful" 
        class="down-vote {{ Auth::guest() ? 'off' : '' }}"
        onClick="event.preventDefault(); document.getElementById('down-vote-{{ $formID}}').submit();"
    >
        <i class="fas fa-caret-down fa-3x"></i>
    </a>
    <form id="down-vote-{{ $formID }}" action="{{ $formAction }}" method="POST">
        @csrf
        <input type="hidden" name="vote" value="-1" />
    </form>

    @if ($model instanceof App\Question)
        @include('shared._favorite',[
            'model' => $model
        ])
    @elseif ($model instanceof App\Answer)
        @include('shared._accept',[
            'model' => $model
        ])
    @endif

</div>