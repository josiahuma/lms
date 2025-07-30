<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">{{ $lesson->title }}</h2>
    </x-slot>

    <div class="py-6 px-4 space-y-4">
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
                <div class="aspect-w-16 aspect-h-9 mb-4">
                    <iframe
                        src="{{ $embedUrl }}"
                        frameborder="0"
                        allowfullscreen
                        class="w-full h-64 rounded shadow">
                    </iframe>
                </div>
            @endif
        @endif

        {{-- Text Content --}}
        @if ($lesson->content)
            <div class="prose max-w-none">
                {!! nl2br(e($lesson->content)) !!}
            </div>
        @endif

        {{-- Actions --}}
        @php
            $isCompleted = auth()->user()->completedLessons->contains($lesson->id);
        @endphp

        <div class="mt-6">
            @if ($isCompleted)
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded">
                    ✅ You’ve already completed this lesson.
                </div>
                @if ($lesson->quiz)
                    <a href="{{ route('lessons.quiz.paginated', ['lesson' => $lesson, 'question' => 0]) }}"
                        class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded">
                        📝 Take Quiz
                    </a>
                @endif

            @else
                <form action="{{ route('lessons.complete', $lesson) }}" method="POST">
                    @csrf
                    <button class="bg-green-600 text-black px-4 py-2 rounded">✅ Mark as Completed</button>
                </form>
            @endif
        </div>
        @if (auth()->user()->role === 'instructor')
            <div class="mt-6">
                @if ($lesson->quiz)
                    <div class="bg-white p-4 rounded shadow border">
                        <h3 class="text-lg font-semibold">📚 Quiz Exists for This Lesson</h3>

                        <div class="mt-2 flex gap-4">
                            <a href="{{ route('quizzes.view', $lesson->quiz) }}" class="text-blue-600 underline">🧪 View Quiz</a>
                            <a href="{{ route('quizzes.edit', $lesson->quiz) }}" class="text-yellow-600 underline">✏️ Edit Quiz</a>
                            <form action="{{ route('quizzes.destroy', $lesson->quiz) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this quiz?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 underline">🗑️ Delete Quiz</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('quizzes.create', $lesson) }}" class="bg-blue-600 text-white px-4 py-2 rounded inline-block">
                        ➕ Create Quiz for this Lesson
                    </a>
                @endif
            </div>
        @endif

    </div>
</x-app-layout>
