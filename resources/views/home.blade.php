<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4">

            {{-- Hero Section --}}
            <section class="bg-indigo-600 text-white py-20 rounded-lg mb-12">
                <div class="text-center px-4">
                    <h1 class="text-4xl font-bold mb-4">Unlock Your Potential with Our Courses</h1>
                    <p class="mb-6 text-lg">Learn at your own pace with top instructors from around the world.</p>
                    <a href="{{ route('courses.index') }}"
                       class="bg-white text-indigo-600 font-semibold px-6 py-3 rounded shadow hover:bg-gray-100">
                        Browse Courses
                    </a>
                </div>
            </section>

            {{-- Categories --}}
            <!--<section class="mb-12">
                <h2 class="text-2xl font-bold mb-6">Popular Categories</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach(['Development', 'Design', 'Marketing', 'Business'] as $category)
                        <div class="bg-white p-4 rounded shadow text-center hover:bg-gray-50 cursor-pointer">
                            {{ $category }}
                        </div>
                    @endforeach
                </div>
            </section>-->



            {{-- Featured Courses --}}
            <section class="py-12">
                <div class="max-w-7xl mx-auto px-4">
                    <h2 class="text-2xl font-bold mb-6">Featured Courses</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach ($courses as $course)
                            <div class="bg-white border rounded shadow hover:shadow-lg transition overflow-hidden">
                                <img src="{{ $course->thumbnail_url ?? asset('images/default-course.png') }}" alt="Course Thumbnail" class="w-full h-48 object-cover rounded-t">
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold">{{ $course->title }}</h3>
                                    <p class="text-sm text-gray-600">By {{ $course->instructor->name }}</p>
                                    <div class="flex items-center text-yellow-400 text-sm my-2">
                                        ⭐⭐⭐⭐⭐
                                        <span class="ml-2 text-gray-600">(5.0)</span>
                                    </div>
                                    <p class="text-indigo-600 font-bold">£{{ number_format($course->price, 2) }}</p>
                                    <a href="{{ route('courses.show', $course) }}" class="mt-4 inline-block text-indigo-600 hover:underline">
                                        View Course
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>



        </div>
    </div>
</x-app-layout>
