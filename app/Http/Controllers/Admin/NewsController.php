<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publishing;
use App\Services\NewsService;
use Exception;
use Illuminate\Http\Request;

class NewsController extends Controller
{

    protected $newsService;

    public function __construct(NewsService $service)
    {
        $this->newsService = $service;
    }

    public function store(Request $request)
    {
        $storage = env('FILESYSTEM_DRIVER');
        $html = null;

        try {
            $this->newsService->store($request, $storage);
            $success = true;
            $message = config('parameters.stored_message');
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        if ($success) {
            $news = $this->newsService->getNews();
            $html = view('admin.settings.partials._news', compact('news'))->render();
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html
        ]);
    }


    public function edit(Publishing $new)
    {
        $new->loadImage();

        $url = verifyImage($new->file);
        $orders = $this->newsService->getNews();

        return response()->json([
            "new" => $new,
            "orders" => $orders,
            "url_img" => $url
        ]);
    }
    public function update(Request $request, Publishing $new)
    {
        $new->loadImage();

        $storage = env('FILESYSTEM_DRIVER');
        $html = null;

        try {
            $success = $this->newsService->update($new, $request, $storage);
            $message = config('parameters.updated_message');
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        if ($success) {
            $news = $this->newsService->getNews();
            $html = view('admin.settings.partials._news', compact('news'))->render();

        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html,
            'new' => $request->all()
        ]);
    }



    public function destroy(Publishing $new)
    {
        $new->loadImage();

        $storage = env('FILESYSTEM_DRIVER');
        $html = null;

        try {
            $success = $this->newsService->destroy($new, $storage);
            $message = config('parameters.deleted_message');
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        if ($success) {
            $news = $this->newsService->getNews();
            $html = view('admin.settings.partials._news', compact('news'))->render();
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "html" => $html
        ]);

    }


}
