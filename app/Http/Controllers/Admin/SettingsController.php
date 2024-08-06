<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Publishing;
use App\Models\SliderImage;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    protected $settingsService;

    public function __construct(SettingsService $service)
    {
        $this->settingsService = $service;
    }

    public function index(Request $request)
    {
        $sliderImages = SliderImage::with('file')
            ->orderBy('order', 'ASC')
            ->get();

        $banners = Publishing::where('type', 'BANNER')
            ->with('file')
            ->orderBy('publishing_order', 'ASC')
            ->get();

        $news = Publishing::where('type', 'NEWS')
            ->with('file')
            ->orderBy('publishing_order', 'ASC')
            ->get();

        $principalBanners = Publishing::where('type', 'PRINCIPALBANNER')
                            ->with('file')
                            ->orderBy('publishing_order', 'ASC')
                            ->get();

        $config = Config::firstOrCreate([
            'whatsapp_number' => env('WSP_CONTACT_NUMBER'),
            'whatsapp_message' => env('WSP_CONTACT_MESSAGE'),
            'email' => ''
        ]);

        return view('admin.settings.index', compact(
            'sliderImages',
            'banners',
            'news',
            'config',
            'principalBanners'
        ));
    }

    public function updateConfig(Request $request, Config $config)
    {
        $data = $request->all();

        $success = $config->update($data);

        $html = view('admin.settings.partials._form_config_edit', compact('config'))->render();


        $message = getMessageFromSuccess($success, 'updated');

        return response()->json([
            'success' => $success,
            'message' => $message,
            'html' => $html
        ]);
    }

    public function updateLogo(Request $request, Config $config)
    {
        $storage = env('FILESYSTEM_DRIVER');

        $success = $this->settingsService->updateLogo($request, $config, $storage);

        $html = view('admin.settings.partials.components._logo_list', compact('config'))->render();
        $html_sidebar = view('admin.common.partials.components._logo_content')->render();

        $message = getMessageFromSuccess($success, 'updated');

        return response()->json([
            'success', $success,
            'message' => $message,
            'html' => $html,
            'html_sidebar' => $html_sidebar
        ]);
    }

    public function destroyLogo(Config $config)
    {
        $storage = env('FILESYSTEM_DRIVER');

        $success = $this->settingsService->destroyLogo($config, $storage);

        $html = view('admin.settings.partials.components._logo_list', compact('config'))->render();

        $html_sidebar = view('admin.common.partials.components._logo_content')->render();

        $message = getMessageFromSuccess($success, 'deleted');

        return response()->json([
            'success' => $success,
            'message' => $message,
            'html' => $html,
            'html_sidebar' => $html_sidebar
        ]);
    }

}
