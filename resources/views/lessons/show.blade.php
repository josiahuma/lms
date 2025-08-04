<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold">{{ $lesson->title }}</h1>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 px-4 md:flex gap-8">
        <!-- Left: Lesson Content -->
        <div class="md:w-2/3 space-y-6">

            <!-- Video Embed -->
            @if ($lesson->video_url)
                @php
                    $videoUrl = $lesson->video_url;
                    $embedUrl = null;

                    if (str_contains($videoUrl, 'youtube.com') || str_contains($videoUrl, 'youtu.be')) {
                        preg_match('/(youtu\.be\/|v=)([a-zA-Z0-9_-]+)/', $videoUrl, $matches);
                        $videoId = $matches[2] ?? null;
                        $embedUrl = $videoId ? "https://www.youtube.com/embed/{$videoId}" : null;
                    } elseif (str_contains($videoUrl, 'vimeo.com')) {
                        preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $matches);
                        $videoId = $matches[1] ?? null;
                        $embedUrl = $videoId ? "https://player.vimeo.com/video/{$videoId}" : null;
                    }
                @endphp

                @if ($embedUrl)
                    <div class="w-full aspect-video rounded overflow-hidden shadow">
                        <iframe
                            src="{{ $embedUrl }}"
                            class="w-full h-full"
                            frameborder="0"
                            allowfullscreen>
                        </iframe>
                    </div>
                @endif
            @endif

            <!-- Lesson Content -->
            @if ($lesson->content)
                <div class="prose max-w-none text-gray-800">
                    {!! nl2br(e($lesson->content)) !!}
                </div>
            @endif

            <!-- Completion Logic -->
            @php
                $isCompleted = auth()->user()->completedLessons->contains($lesson->id);
            @endphp

            @if (!$isCompleted)
                <form method="POST" action="{{ route('lessons.complete', $lesson->id) }}">
                    @csrf
                    <button class="mt-6 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded">
                        ✅ Mark as Complete
                    </button>
                </form>
            @else
                <div class="mt-6">
                    @if ($lesson->quiz)
                        <a href="{{ route('lessons.quiz.paginated', ['lesson' => $lesson->id, 'question' => 0]) }}" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded inline-block">
                            🧪 Take Quiz
                        </a>
                    @elseif ($nextLesson)
                        <a href="{{ route('lessons.show', $nextLesson->id) }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded inline-block">
                            ⏭️ Next Lesson
                        </a>
                    @else
                        <a href="{{ route('student.dashboard') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded inline-block">
                            🎉 View Progress / Download Certificate
                        </a>
                    @endif
                </div>
            @endif
        </div>

        <!-- Right: Instructor Tools -->
        <div class="md:w-1/3 mt-10 md:mt-0">
            @if (auth()->user()->role === 'instructor')
                <div class="bg-white p-6 rounded shadow sticky top-20">
                    <h2 class="text-lg font-semibold mb-4">📘 Lesson Management</h2>

                    @if ($lesson->quiz)
                        <p class="text-green-700 font-medium mb-2">✅ A quiz exists for this lesson.</p>
                        <div class="space-y-2">
                            <a href="{{ route('quizzes.view', $lesson->quiz) }}" class="block text-blue-600 hover:underline">🧪 View Quiz</a>
                            <a href="{{ route('quizzes.edit', $lesson->quiz) }}" class="block text-yellow-600 hover:underline">✏️ Edit Quiz</a>
                            <form action="{{ route('quizzes.destroy', $lesson->quiz) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this quiz?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">🗑️ Delete Quiz</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('quizzes.create', $lesson) }}"
                            class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            ➕ Create Quiz for this Lesson
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
