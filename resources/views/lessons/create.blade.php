<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">Add New Lesson</h2>
    </x-slot>

    <div class="py-6 px-4 max-w-3xl mx-auto">
        <form action="{{ route('lessons.store', $course) }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block font-semibold">Lesson Title</label>
                <input type="text" name="title" class="form-control w-full" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold">Lesson Content</label>
                <textarea name="content" class="form-control w-full" rows="4"></textarea>
            </div>

            <div class="mb-4">
                <label class="block font-semibold">Video URL (YouTube or Vimeo)</label>
                <input type="url" name="video_url" class="form-control w-full" placeholder="https://youtube.com/... or https://vimeo.com/...">
            </div>

            <button type="submit" class="btn btn-primary">Create Lesson</button>
        </form>
    </div>
</x-app-layout>
