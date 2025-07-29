<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">Quiz for: {{ $lesson->title }}</h2>
    </x-slot>

    <div class="py-6 px-4">
        <form action="{{ route('lessons.quiz.submit', $lesson) }}" method="POST">
            @csrf

            {{-- Example Question --}}
            <div class="mb-4">
                <label class="block font-semibold">1. Sample question goes here?</label>
                <div class="mt-2 space-y-2">
                    <label><input type="radio" name="q1" value="A"> Option A</label><br>
                    <label><input type="radio" name="q1" value="B"> Option B</label><br>
                    <label><input type="radio" name="q1" value="C"> Option C</label><br>
                    <label><input type="radio" name="q1" value="D"> Option D</label>
                </div>
            </div>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                Submit Answers
            </button>
        </form>
    </div>
</x-app-layout>
