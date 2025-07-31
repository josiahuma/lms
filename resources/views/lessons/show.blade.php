<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 md:flex gap-8">

        <!-- Left: Main Lesson Content -->
        <div class="md:w-2/3 space-y-6">
            <!-- Lesson Title -->
            <h1 class="text-3xl font-bold">{{ $lesson->title }}</h1>

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

            <!-- Text Content -->
            @if ($lesson->content)
                <div class="prose max-w-none text-gray-800">
                    {!! nl2br(e($lesson->content)) !!}
                </div>
            @endif

            <!-- Completion Action -->
            @php
                $isCompleted = auth()->user()->completedLessons->contains($lesson->id);
            @endphp

            <div>
                @if ($isCompleted)
                    <div class="bg-green-100 text-green-800 px-4 py-2 rounded shadow">
                        ✅ You’ve already completed this lesson.
                    </div>

                    @if ($lesson->quiz)
                        <a href="{{ route('lessons.quiz.paginated', ['lesson' => $lesson, 'question' => 0]) }}"
                            class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                            📝 Take Quiz
                        </a>
                    @endif
                @else
                    <form action="{{ route('lessons.complete', $lesson) }}" method="POST" class="mt-4">
                        @csrf
                        <button class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700">
                            ✅ Mark as Completed
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Right: Instructor Sidebar / Quiz Management -->
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
