@php use Illuminate\Support\Facades\Storage; @endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">Instructor Dashboard</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4">
            <h3 class="text-2xl font-semibold mb-6">📚 Your Courses</h3>

            @if ($courses->isEmpty())
                <p class="text-gray-600">You haven't created any courses yet.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($courses as $course)
                        <div class="bg-white rounded-lg shadow p-4 border">
                            {{-- Thumbnail Placeholder --}}
                            <div class="h-48 overflow-hidden rounded-t">
                                <img src="{{ $course->featured_image ? Storage::url($course->featured_image) : asset('images/default-course.jpg') }}"
                                    alt="Course Thumbnail"
                                    class="w-full h-full object-cover">
                            </div>

                            <h4 class="text-lg font-bold">{{ $course->title }}</h4>
                            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($course->description, 100) }}</p>
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


                            <p class="text-sm text-gray-700 mb-1"><strong>Enrolled:</strong> {{ $course->students->count() }}</p>

                            

                            {{-- Actions --}}
                            <div class="mt-4 flex flex-wrap gap-3">
                                <a href="{{ route('courses.show', $course) }}" class="text-blue-600 text-sm underline">🔍 View</a>
                                <a href="{{ route('courses.students', $course) }}" class="text-blue-600 text-sm underline">👥 Students</a>
                                <a href="{{ route('courses.edit', $course) }}" class="text-yellow-600 text-sm underline">✏️ Edit</a>
                                <form action="{{ route('courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Delete this course?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 text-sm underline">🗑️ Deletes</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
