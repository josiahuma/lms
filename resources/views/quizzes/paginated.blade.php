<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">Question {{ $index + 1 }} of {{ $total }}</h2>
    </x-slot>

    <div class="py-6 px-4">
        <form method="POST" action="{{ route('lessons.quiz.paginated.store', $lesson) }}">
            @csrf
            <input type="hidden" name="index" value="{{ $index }}">
            <input type="hidden" name="question_id" value="{{ $question->id }}">

            <h3 class="font-semibold mb-4 text-lg">{{ $question->question }}</h3>

            @foreach ($question->answers as $answer)
                <div class="mb-3">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="answer_id" value="{{ $answer->id }}" required>
                        <span>{{ $answer->answer_text }}</span>
                    </label>
                </div>
            @endforeach

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mt-4">
                {{ $index + 1 == $total ? 'Submit Quiz' : 'Next Question' }}
            </button>
        </form>
    </div>
</x-app-layout>
