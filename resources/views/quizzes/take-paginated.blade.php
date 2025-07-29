<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">
            {{ $lesson->title }} — Question {{ $questionIndex + 1 }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-6">
        <form method="POST" action="{{ route('lessons.quiz.take', ['lesson' => $lesson->id, 'question' => $questionIndex + 1]) }}">
            @csrf

            <div class="bg-white shadow rounded p-4">
                <h3 class="font-semibold text-lg mb-4">{{ $question->question }}</h3>

                @foreach ($question->answers as $answer)
                    <div class="mb-2">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="answer" value="{{ $answer->id }}" required>
                            <span>{{ $answer->answer_text }}</span>
                        </label>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                @if ($questionIndex + 1 === $lesson->quiz->questions->count())
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                        ✅ Submit Quiz
                    </button>
                @else
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                        ➡️ Next Question
                    </button>
                @endif
            </div>
        </form>
    </div>
</x-app-layout>
