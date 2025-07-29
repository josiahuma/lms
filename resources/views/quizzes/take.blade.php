<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">{{ $lesson->title }} - Quiz</h2>
    </x-slot>

    <div class="py-6 px-4">
        <form action="{{ route('lessons.quiz.submit', $lesson) }}" method="POST">
            @csrf

            @foreach ($lesson->quiz->questions as $question)
                <div class="mb-6">
                    <h3 class="font-semibold">{{ $question->question }}</h3>

                    @foreach ($question->answers as $answer)
                        <div>
                            <label>
                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $answer->id }}" required>
                                {{ $answer->answer_text }}
                            </label>
                        </div>
                    @endforeach
                </div>
            @endforeach

            <button type="submit" class="bg-green-600 text-black px-4 py-2 rounded">
                ✅ Submit Quiz
            </button>
        </form>
    </div>
</x-app-layout>
