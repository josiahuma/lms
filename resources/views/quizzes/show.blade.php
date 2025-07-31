<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">🧪 Quiz for: {{ $quiz->lesson->title }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4">
            @foreach ($quiz->questions as $index => $question)
                <div class="mb-6 border p-4 rounded shadow-sm bg-white">
                    <h3 class="text-lg font-semibold mb-2">Question {{ $index + 1 }}</h3>
                    <p class="mb-3">{{ $question->question }}</p>

                    <ul class="list-disc ml-6 space-y-1">
                        @foreach ($question->answers as $answer)
                            <li class="@if($answer->is_correct) text-green-700 font-semibold @endif">
                                {{ $answer->answer_text }}
                                @if($answer->is_correct) ✅ Correct @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

            <div class="text-center mt-8">
                <a href="{{ route('lessons.show', $quiz->lesson) }}"
                   class="text-blue-600 underline hover:text-blue-800 font-medium">
                    ⬅ Back to Lesson
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
