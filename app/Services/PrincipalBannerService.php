<?php

namespace App\Services;

use App\Models\Publishing;
use Auth;
use Carbon\Carbon;
use Exception;

class PrincipalBannerService
{
    public function getPrincipalBanners()
    {
        return Publishing::where('type', 'PRINCIPALBANNER')
                        ->with('file')
                        ->orderBy('publishing_order', 'ASC')
                        ->get();
    }

    private function getBannerLastOrder()
    {
        $lastOrder = Publishing::where('type', 'PRINCIPALBANNER')->max('publishing_order');
        return $lastOrder ?? 0;
    }

    private function updateBannersOrder()
    {
        $banners = $this->getPrincipalBanners();
        $order = 1;
        foreach ($banners as $banner) {
            $banner->update([
                "publishing_order" => $order
            ]);
            $order++;
        }

        return true;
    }

    public function store($request, $storage)
    {
        $data = $request->validated();
        $data['status'] = isset($data['status']) ? 1 : 0;

        $banner = Publishing::create($data + [
            "type" => "PRINCIPALBANNER",
            "publishing_order" => $this->getBannerLastOrder() + 1,
            'publication_time' => Carbon::now('America/Lima'),
            'user_id' => Auth::user()->id,
        ]);

        if ($banner && $request->hasFile('image')) {
            $file_type = 'imagenes';
            $category = 'publicaciones';
            $file = $request->file('image');
            $belongsTo = 'publicaciones';
            $relation = 'one_one';

            return app(FileService::class)->store(
                $banner,
                $file_type,
                $category,
                $file,
                $storage,
                $belongsTo,
                $relation
            );;
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function update($request, Publishing $banner, $storage)
    {
        $data = $request->validated();
        $data['status'] = isset($data['status']) ? 1 : 0;

        if (Publishing::where('type', 'PRINCIPALBANNER')
            ->where('publishing_order', $data['publishing_order'])
            ->update([
                "publishing_order" => $banner->publishing_order
            ])
        ) {
            if ($request->hasFile('image')) {

                app(FileService::class)->destroy($banner->file, $storage);

                $file_type = 'imagenes';
                $category = 'publicaciones';
                $file = $request->file('image');
                $belongsTo = 'publicaciones';
                $relation = 'one_one';

                app(FileService::class)->store(
                    $banner,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );
            }

            return $banner->update($data);
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function destroy(Publishing $banner, $storage)
    {
        if ($banner->file) {
            app(FileService::class)->destroy($banner->file, $storage);
        };

        if ($banner->delete()) {
            return $this->updateBannersOrder();
        }

        throw new Exception(config('parameters.exception_message'));
    }
}
