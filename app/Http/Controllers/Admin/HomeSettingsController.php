<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;


class HomeSettingsController extends Controller
{
    //
    public function edit()
    {
        $settings = SiteSetting::firstOrCreate([]);
        
        if (is_string($settings->featured_course_ids)) {
            $settings->featured_course_ids = json_decode($settings->featured_course_ids, true);
        }
        $courses = \App\Models\Course::all();
        return view('admin.home.edit', compact('settings', 'courses'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'hero_heading' => 'nullable|string',
            'hero_subheading' => 'nullable|string',
            'hero_button_text' => 'nullable|string',
            'hero_button_link' => 'nullable|url',
            'hero_background_color' => 'nullable|string',
            'featured_course_ids' => 'nullable|array',
        ]);

        $data['featured_course_ids'] = json_encode($data['featured_course_ids'] ?? []);


        $settings = SiteSetting::first();
        $settings->update($data);
        

        return back()->with('success', 'Home settings updated!');
    }
}
