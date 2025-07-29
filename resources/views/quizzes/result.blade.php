<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">🎯 Quiz Result for: {{ $lesson->title }}</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4 bg-white border rounded shadow">

        <div class="mb-6 text-center">
            <h3 class="text-2xl font-semibold">
                You scored {{ $score }} out of {{ $lesson->quiz->questions->count() }}
            </h3>
            <p class="text-gray-700 mt-2 text-lg">
                That's {{ number_format($percentage, 1) }}%
            </p>

            @if($percentage >= 80)
                <p class="mt-4 text-green-600 font-medium text-xl">🎉 Great job! You passed this quiz.</p>
            @else
                <p class="mt-4 text-red-600 font-medium text-xl">😅 You scored below 80%. Try again!</p>
            @endif
        </div>

        <div class="flex justify-center space-x-4 mt-6">
            @if($percentage >= 80)
                @php
                    $nextLesson = $lesson->course->lessons()->where('id', '>', $lesson->id)->orderBy('id')->first();
                @endphp

                @if($nextLesson)
                    <a href="{{ route('lessons.show', $nextLesson) }}"
                       class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                        👉 Next Lesson: {{ $nextLesson->title }}
                    </a>
                @else
                    <span class="text-gray-600">🎉 You've completed all lessons!</span>
                @endif
            @else
                <a href="{{ route('lessons.quiz.paginated', ['lesson' => $lesson->id, 'q' => 0]) }}"
                   class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded">
                    🔁 Retake Quiz
                </a>
            @endif
        </div>
    </div>
</x-app-layout>
