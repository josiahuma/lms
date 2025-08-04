<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">
            {{ $lesson->title }} — Question {{ $questionIndex + 1 }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4">
            <form method="POST" action="{{ route('lessons.quiz.paginated.store', ['lesson' => $lesson->id]) }}">
                @csrf

                <div class="bg-white shadow border rounded p-6">
                    <h3 class="font-semibold text-lg mb-4">{{ $question->question }}</h3>

                    @php
                        $storedAnswers = session('quiz_answers', []);
                        $selectedOption = $storedAnswers[$question->id] ?? null;
                    @endphp

                    @foreach ($question->answers as $answer)
                        <div class="mb-3">
                            <label class="flex items-center gap-2">
                                <input
                                    type="radio"
                                    name="selected_option"
                                    value="{{ $answer->id }}"
                                    {{ $selectedOption == $answer->id ? 'checked' : '' }}
                                    required
                                >
                                <span>{{ $answer->answer_text }}</span>
                            </label>
                        </div>
                    @endforeach


                    <input type="hidden" name="question_id" value="{{ $question->id }}">
                    <input type="hidden" name="current_question" value="{{ $questionIndex }}">
                </div>

                {{-- Buttons --}}
                <div class="mt-6 flex justify-between">
                    @if ($questionIndex > 0)
                        <a href="{{ route('lessons.quiz.paginated', ['lesson' => $lesson->id, 'question' => $questionIndex - 1]) }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-5 py-2 rounded">
                            ← Back
                        </a>
                    @else
                        <div></div>
                    @endif

                    @if ($questionIndex + 1 === $lesson->quiz->questions->count())
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded">
                            ✅ Submit Quiz
                        </button>
                    @else
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">
                            ➡️ Next Question
                        </button>
                    @endif
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
