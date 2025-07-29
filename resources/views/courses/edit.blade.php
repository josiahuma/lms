<x-app-layout>
    <x-slot name="header">
        <h2>Edit Course</h2>
    </x-slot>

    <div class="py-4 px-4">
        <form method="POST" action="{{ route('courses.update', $course) }}">
            @csrf
            @method('PUT')

            <div class="mb-2">
                <label>Title</label>
                <input name="title" value="{{ $course->title }}" class="form-control w-full" required>
            </div>

            <div class="mb-2">
                <label>Description</label>
                <textarea name="description" class="form-control w-full">{{ $course->description }}</textarea>
            </div>

            <div class="mb-2">
                <label>Price</label>
                <input name="price" type="number" step="0.01" value="{{ $course->price }}" class="form-control w-full" required>
            </div>

            <button class="btn btn-success">Update Course</button>
        </form>
    </div>
</x-app-layout>
