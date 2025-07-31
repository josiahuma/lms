<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">🎓 My Dashboard</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 space-y-8">
            <h3 class="text-lg font-semibold">Welcome, {{ Auth::user()->name }}</h3>

            @foreach($enrollments as $enrollment)
                @php
                    $course = $enrollment->course;
                @endphp

                @if($course)
                    @php
                        $totalLessons = $course->lessons->count();
                        $completedLessons = $course->lessons->whereIn('id', $completions)->count();
                        $progressPercent = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
                        $eligibleForCertificate = $progressPercent === 100;
                    @endphp

                    <div class="border rounded-lg shadow bg-white p-6">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="text-lg font-bold">{{ $course->title }}</h4>
                            <span class="text-sm text-gray-600">{{ $completedLessons }}/{{ $totalLessons }} lessons completed</span>
                        </div>

                        <!-- Progress bar -->
                        <div class="w-full bg-gray-200 h-4 rounded-full overflow-hidden mb-4">
                            <div class="bg-green-500 h-4" style="width: {{ $progressPercent }}%"></div>
                        </div>

                        <ul class="space-y-2">
                            @foreach($course->lessons as $lesson)
                                <li class="border-b pb-2">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <span class="font-medium">{{ $lesson->title }}</span>
                                            @if(in_array($lesson->id, $completions))
                                                <span class="text-green-600 text-sm ml-2">✅ Completed</span>
                                            @endif
                                        </div>

                                        <div class="text-right text-sm space-x-2">
                                            @if($lesson->quiz)
                                                @php
                                                    $quiz = $lesson->quiz;
                                                    $submission = $submissions[$quiz->id] ?? null;
                                                    $score = $submission ? $submission->score : null;
                                                    $total = $quiz->questions->count();
                                                    $passed = $score !== null && $total > 0 && ($score / $total) * 100 >= 80;
                                                @endphp

                                                <span class="text-blue-600">
                                                    🧠 Score: {{ $score ?? 'N/A' }}/{{ $total }}
                                                </span>

                                                @if($passed)
                                                    <a href="{{ route('lessons.quiz.paginated', $lesson) }}" class="text-green-700 font-semibold">✅ Retake</a>
                                                @else
                                                    <a href="{{ route('lessons.quiz.paginated', $lesson) }}" class="text-red-500 font-semibold">🔁 Resume Quiz</a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        @if($passed)
                            <div class="mt-4 text-center">
                                <a href="{{ route('certificate.download', $course->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded shadow">
                                    🎓 Download Certificate
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</x-app-layout>
