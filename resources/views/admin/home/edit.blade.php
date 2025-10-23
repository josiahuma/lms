<x-app-layout>
    <x-slot name="header">Edit Homepage Settings</x-slot>

    <form action="{{ route('admin.home-settings.update') }}" method="POST">
        @csrf @method('PUT')

        <div class="space-y-4">

            <div>
                <label>Hero Heading</label>
                <input type="text" name="hero_heading" value="{{ old('hero_heading', $settings->hero_heading) }}" class="w-full border rounded p-2">
            </div>

            <div>
                <label>Hero Subheading</label>
                <input type="text" name="hero_subheading" value="{{ old('hero_subheading', $settings->hero_subheading) }}" class="w-full border rounded p-2">
            </div>

            <div>
                <label>Button Text</label>
                <input type="text" name="hero_button_text" value="{{ old('hero_button_text', $settings->hero_button_text) }}" class="w-full border rounded p-2">
            </div>

            <div>
                <label>Button Link</label>
                <input type="url" name="hero_button_link" value="{{ old('hero_button_link', $settings->hero_button_link) }}" class="w-full border rounded p-2">
            </div>

            <div>
                <label>Hero Background Color</label>
                <input type="color" name="hero_background_color" value="{{ old('hero_background_color', $settings->hero_background_color ?? '#4f46e5') }}">
            </div>

            <div>
                <label>Featured Courses</label>
                <select name="featured_course_ids[]" multiple class="w-full border rounded p-2">
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" @selected(in_array($course->id, $settings->featured_course_ids ?? []))>
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">Save Settings</button>
        </div>
    </form>
</x-app-layout>
