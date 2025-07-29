<x-app-layout>
    <x-slot name="header">
        <h2>Edit Lesson</h2>
    </x-slot>

    <div class="py-4 px-4">
        <form method="POST" action="{{ route('lessons.update', $lesson) }}">
            @csrf
            @method('PUT')

            <div class="mb-2">
                <label>Lesson Title</label>
                <input name="title" value="{{ $lesson->title }}" class="form-control w-full" required>
            </div>

            <div class="mb-2">
                <label>Lesson Content</label>
                <textarea name="content" class="form-control w-full">{{ $lesson->content }}</textarea>
            </div>

            <button class="btn btn-success">Update Lesson</button>
        </form>
    </div>
</x-app-layout>
