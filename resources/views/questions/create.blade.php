@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h2>Ask Questions</h2>
                        <div class="ml-auto">
                            <a href="{{ route('questions.index') }}" class="btn btn-outline-secondary">
                                Back to all questions
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('questions.store') }}">
                        @csrf()
                        <div class="form-group">
                            <label for="question-title">Question Title</label>
                            <input 
                                type="text" 
                                name="title" 
                                id="question-title" 
                                class="form-control {{ $errors->has('title') ? 'invalid' : ''}}" 
                            />

                            @if ($errors->has('title'))
                                <div class="invalid-feedback">
                                    <strong> {{ $errors->has('title') }}</strong>
                                </div>
                            @endif

                        </div>
                        <div class="form-group">
                            <label for="question-description">Question Description</label>
                            <textarea 
                                class="form-control {{ $errors->has('body') ? 'invalid' : ''}}" 
                                name="description" 
                                id="question-description" 
                                rows="10">
                            </textarea>

                            @if ($errors->has('body'))
                                <div class="invalid-feedback">
                                    <strong> {{ $errors->has('body') }}</strong>
                                </div>
                            @endif

                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-outline-primary btn-lg">Ask</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
