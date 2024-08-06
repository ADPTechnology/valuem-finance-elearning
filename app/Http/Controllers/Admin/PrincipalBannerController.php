<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PrincipalBanner\PBannerRequestStore;
use App\Http\Requests\PrincipalBanner\PBannerRequestUpdate;
use App\Models\Publishing;
use App\Services\PrincipalBannerService;
use Exception;
use Illuminate\Http\Request;

class PrincipalBannerController extends Controller
{
    protected $pBannerService;

    public function __construct(PrincipalBannerService $service)
    {
        $this->pBannerService = $service;
    }

    public function store(PBannerRequestStore $request)
    {
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $this->pBannerService->store($request, $storage);
            $success = true;
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'stored');

        if ($success) {
            $principalBanners = $this->pBannerService->getPrincipalBanners();
            $html = view('admin.settings.partials._p_carrousel_list', compact(
                'principalBanners'
            ))->render();
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html ?? NULL
        ]);
    }

    public function getBannersOrder()
    {
        $orders = ($this->pBannerService->getPrincipalBanners())->map(function ($banner) {
            return [
                "id" => $banner->publishing_order,
                "publishing_order" => $banner->publishing_order
            ];
        });

        return response()->json($orders);
    }

    public function edit(Publishing $banner)
    {
        $banner->loadImage();

        return response()->json([
            "banner" => $banner,
            "url_img" => verifyImage($banner->file)
        ]);
    }

    public function update(PBannerRequestUpdate $request, Publishing $banner)
    {
        $banner->loadImage();
        $storage = env('FILESYSTEM_DRIVER');

        try {
            $this->pBannerService->update($request, $banner, $storage);
            $success = true;
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'updated');

        if ($success) {
            $principalBanners = $this->pBannerService->getPrincipalBanners();
            $html = view('admin.settings.partials._p_carrousel_list', compact(
                'principalBanners'
            ))->render();
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html ?? NULL
        ]);
    }

    public function destroy(Publishing $banner)
    {
        $banner->loadImage();

        $storage = env('FILESYSTEM_DRIVER');

        try {
            $success = $this->pBannerService->destroy($banner, $storage);
        } catch (Exception $e) {
            $success = false;
        }

        $message = getMessageFromSuccess($success, 'deleted');

        if ($success) {
            $principalBanners = $this->pBannerService->getPrincipalBanners();
            $html = view('admin.settings.partials._p_carrousel_list', compact(
                'principalBanners'
            ))->render();
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html ?? NULL
        ]);
    }
}
