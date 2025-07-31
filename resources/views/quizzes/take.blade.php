<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">{{ $lesson->title }} - Quiz</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 bg-white border rounded shadow">
            <form action="{{ route('lessons.quiz.submit', $lesson) }}" method="POST" class="py-6">
                @csrf

                @foreach ($lesson->quiz->questions as $index => $question)
                    <div class="mb-6">
                        <h3 class="font-semibold text-lg mb-2">
                            Question {{ $index + 1 }}: {{ $question->question }}
                        </h3>

                        @foreach ($question->answers as $answer)
                            <div class="mb-2">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $answer->id }}" required>
                                    <span>{{ $answer->answer_text }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endforeach

                <div class="text-center mt-8">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">
                        ✅ Submit Quiz
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
