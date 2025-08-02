@php use Illuminate\Support\Facades\Storage; @endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">All Courses</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4">
            {{-- Success Message --}}
            @if (session('success'))
                <p class="mb-4 text-green-600">{{ session('success') }}</p>
            @endif

            {{-- Instructor Only: Create Button --}}
            @if(auth()->check() && auth()->user()->role === 'instructor')
                <div class="mb-6">
                    <a href="{{ route('courses.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        ➕ Create Course
                    </a>
                </div>
            @endif

            {{-- Courses Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($courses as $course)
                    <div class="bg-white p-4 rounded shadow border">
                        {{-- Thumbnail Placeholder --}}
                        <div class="h-48 overflow-hidden rounded-t">
                            <img src="{{ $course->featured_image ? Storage::url($course->featured_image) : asset('images/default-course.jpg') }}"
                                alt="Course Thumbnail"
                                class="w-full h-full object-cover">
                        </div>


                        <h3 class="text-lg font-semibold">{{ $course->title }}</h3>
                        <p class="text-sm text-gray-600">Instructor: {{ $course->instructor->name }}</p>
                        @if ($course->sale_price && $course->sale_price > 0)
                            <p class="text-gray-500 line-through text-sm">
                                £{{ number_format($course->price, 2) }}
                            </p>
                            <p class="text-indigo-600 font-bold text-lg">
                                £{{ number_format($course->sale_price, 2) }}
                            </p>
                        @elseif ($course->price > 0)
                            <p class="text-indigo-600 font-bold text-lg">
                                £{{ number_format($course->price, 2) }}
                            </p>
                        @else
                            <p class="text-green-600 font-bold text-lg">FREE</p>
                        @endif

                        <a href="{{ route('courses.show', $course) }}" class="text-blue-600 text-sm underline">
                            🔍 View Course
                        </a>
                    </div>
                @empty
                    <p class="col-span-full text-gray-500">No courses available at the moment.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
