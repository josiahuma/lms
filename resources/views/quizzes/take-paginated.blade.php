<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">
            {{ $lesson->title }} — Question {{ $questionIndex + 1 }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4">
            <form method="POST" action="{{ route('lessons.quiz.take', ['lesson' => $lesson->id, 'question' => $questionIndex + 1]) }}">
                @csrf

                <div class="bg-white shadow border rounded p-6">
                    <h3 class="font-semibold text-lg mb-4">{{ $question->question }}</h3>

                    @foreach ($question->answers as $answer)
                        <div class="mb-3">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="answer" value="{{ $answer->id }}" required>
                                <span>{{ $answer->answer_text }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 text-center">
                    @if ($questionIndex + 1 === $lesson->quiz->questions->count())
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded">
                            ✅ Submit Quiz
                        </button>
                    @else
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">
                            ➡️ Next Question
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
