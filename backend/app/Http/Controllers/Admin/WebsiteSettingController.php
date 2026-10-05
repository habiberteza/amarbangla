<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Setting;
use Illuminate\Http\Request;

class WebsiteSettingController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.website_settings.logo');
    }

    public function logo()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.website_settings.logo', compact('settings'));
    }

    public function banner()
    {
        $settings = Setting::all()->pluck('value', 'key');
        $banners = Banner::where('position', 'home_banner_top')->orWhere('position', 'home_banner_bottom')->get();
        return view('admin.website_settings.banner', compact('settings', 'banners'));
    }

    public function bannerProduct()
    {
        $settings = Setting::all()->pluck('value', 'key');
        $banners = Banner::where('position', 'home_banner_side')->get();
        return view('admin.website_settings.banner_product', compact('settings', 'banners'));
    }

    public function slider()
    {
        $sliders = Banner::where('position', 'hero_slider')->orderBy('sort_order')->get();
        return view('admin.website_settings.slider', compact('sliders'));
    }

    public function footer()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.website_settings.footer', compact('settings'));
    }

    public function mobileSlider()
    {
        $sliders = Banner::where('position', 'hero_slider')->get();
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.website_settings.mobile_slider', compact('sliders', 'settings'));
    }

    public function homeBanner()
    {
        $banners = Banner::where('position', 'like', 'home_banner%')->get();
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.website_settings.home_banner', compact('banners', 'settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Website settings saved successfully.');
    }

    public function storeBanner(Request $request)
    {
        $validated = $request->validate([
            'title'      => 'nullable|string|max:255',
            'subtitle'   => 'nullable|string|max:255',
            'image_url'  => 'required|string',
            'link_url'   => 'nullable|string',
            'position'   => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Banner::create($validated);

        return back()->with('success', 'Banner element created successfully.');
    }

    public function destroyBanner(Banner $banner)
    {
        $banner->delete();
        return back()->with('success', 'Banner element removed.');
    }
}
