<x-app-layout>
    <x-slot name="header">
        <h2>Create a New Course</h2>
    </x-slot>

    <div class="py-4 px-4">
        <form method="POST" action="{{ route('courses.store') }}">
            @csrf

            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control w-full" required>
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control w-full"></textarea>
            </div>

            <div class="mb-3">
                <label>Price (£)</label>
                <input type="number" step="0.01" name="price" class="form-control w-full" required>
            </div>

            <button type="submit" class="btn btn-success">Save Course</button>
        </form>
    </div>
</x-app-layout>
