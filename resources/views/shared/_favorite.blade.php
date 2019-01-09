<a 
    title="Click to mark as favorite (Click to unfavorite)" 
    class="favorite {{ Auth::guest() ? 'off' : ($model->is_favorited ? 'favorited' : '') }} mt-4"
    onclick="event.preventDefault(); document.getElementById('favorite-question-{{ $model->id }}').submit();"
>
    <i class="fas fa-star fa-2x"></i>
    <span class="favorite-count">{{ $model->favorites_count }}</span>
</a>
<form 
    id="favorite-{{ $name }}-{{ $model->id }}" 
    action="/{{ $firstURIPart }}/{{ $model->id }}/favorites"
    method="POST"
    style="display:none;"
>
    @csrf

    @if ($model->is_favorited)
        @method('DELETE');
    @endif

</form>