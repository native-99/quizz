<!-- Success/Error Message Display -->


<div id="question-{{ $question->id }}" class="question col-12 text-left mt-4 p-4">
    <div class="card shadow p-2">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ $question->title }}</h6>
        </div>
        <div class="card-body">
            <form class="workout_questions" id="question-{{ $question->id }}" method="post"
                action="{{ route('quizWorkout') }}">
                @csrf

                <input type="hidden" value="{{ $question->id }}" name="question_id">
                <input type="hidden" value="{{ $workout->id }}" name="workout_id">
                <p>{{ $question->question_body }}</p>

                @forelse($answer->answers as $index => $answer)
                    <div class="p-2">
                        <div class="form-check">
                            <input value="{{ $index }}" name="answer-{{ $question->id }}"
                                class="form-check-input" type="radio"
                                id="reviewAnswerTest{{ $question->id . $index }}"
                                @if (session("workout_answers.{$question->id}") == $index) checked @endif>
                            <label class="form-check-label" for="reviewAnswerTest{{ $question->id . $index }}">
                                {{ $answer }}
                            </label>
                        </div>
                    </div>
                @empty
                    <p>{{ __('No answers available.') }}</p>
                @endforelse

                <input type="submit" value="{{ __('save') }}" class="btn btn-primary mt-3" />
            </form>
        </div>
    </div>
</div>
