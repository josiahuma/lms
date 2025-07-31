<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">Create a New Course</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow border">
            <form method="POST" action="{{ route('courses.store') }}">
                @csrf

                {{-- Title --}}
                <div class="mb-4">
                    <label for="title" class="block font-semibold mb-1">Title</label>
                    <input type="text" name="title" id="title" class="w-full border rounded px-3 py-2" required>
                </div>

                {{-- Description --}}
                <div class="mb-4">
                    <label for="description" class="block font-semibold mb-1">Description</label>
                    <textarea name="description" id="description" class="w-full border rounded px-3 py-2" rows="4"></textarea>
                </div>

                {{-- Price --}}
                <div class="mb-4">
                    <label for="price" class="block font-semibold mb-1">Price (£)</label>
                    <input type="number" step="0.01" name="price" id="price" class="w-full border rounded px-3 py-2" required>
                </div>

                {{-- Submit --}}
                <div class="mt-6">
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                        💾 Save Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
