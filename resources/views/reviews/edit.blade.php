<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">
            ✏️ Edit Your Review for "{{ $course->title }}"
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow border">
            @if ($errors->any())
                <div class="mb-4 text-red-600">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('courses.reviews.update', [$course, $review]) }}">
                @csrf
                @method('PUT')

                {{-- Rating --}}
                <div class="mb-4">
                    <label for="rating" class="block font-semibold mb-1">Rating (1 to 5)</label>
                    <select name="rating" id="rating" class="w-full border rounded px-3 py-2" required>
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" @selected($review->rating == $i)>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>

                {{-- Review Text --}}
                <div class="mb-4">
                    <label for="review" class="block font-semibold mb-1">Your Review</label>
                    <textarea name="review" id="review" rows="4" class="w-full border rounded px-3 py-2">{{ old('review', $review->review) }}</textarea>
                </div>

                <div class="mt-6 flex items-center justify-between">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        💾 Update Review
                    </button>

                    <a href="{{ route('courses.show', $course) }}" class="text-gray-600 hover:underline">
                        ← Back to Course
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
