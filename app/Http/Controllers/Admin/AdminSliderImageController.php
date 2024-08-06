<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SliderImage;
use App\Services\SliderImageService;
use Exception;
use Illuminate\Http\Request;

class AdminSliderImageController extends Controller
{
    protected $useService;

    public function __construct(SliderImageService $service)
    {
        $this->useService = $service;
    }

    public function store(Request $request)
    {

        $storage = env('FILESYSTEM_DRIVER');
        $html = null;

        try {
            $this->useService->storeSliderImage($request, $storage);
            $success = true;
            $message = config('parameters.stored_message');
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        if ($success) {
            $sliderImages = $this->useService->getSliderImages();
            $html = view('admin.sliderImages.partials.boxes._images_list', compact('sliderImages'))->render();
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html
        ]);
    }

    public function edit(SliderImage $sliderImage)
    {

        $sliderImage->loadImage();
        $url = verifyImage($sliderImage->file);
        $sliderImages = SliderImage::select('id', 'order')->orderBy('order', 'ASC')->get();

        return response()->json([
            "sliderImage" => $sliderImage,
            "url_img" => $url,
            "sliderImages" => $sliderImages
        ]);

    }

    public function update(Request $request, SliderImage $sliderImage)
    {
        $sliderImage->loadImage();

        $storage = env('FILESYSTEM_DRIVER');
        $html = null;

        try {
            $success = $this->useService->updateSliderImage($sliderImage, $request, $storage);
            $message = config('parameters.updated_message');
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        if ($success) {
            $sliderImages = $this->useService->getSliderImages();
            $html = view('admin.sliderImages.partials.boxes._images_list', compact('sliderImages'))->render();
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html
        ]);
    }


    public function destroy(SliderImage $sliderImage)
    {
        $sliderImage->loadImage();

        $storage = env('FILESYSTEM_DRIVER');
        $html = null;

        try {
            $success = $this->useService->destroySliderImage($sliderImage, $storage);
            $message = config('parameters.deleted_message');
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        if ($success) {
            $sliderImages = $this->useService->getSliderImages();
            $html = view('admin.sliderImages.partials.boxes._images_list', compact('sliderImages'))->render();
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html
        ]);

    }


}
