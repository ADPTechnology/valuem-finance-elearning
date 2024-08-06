<?php

namespace App\Services;

use App\Models\SliderImage;
use Exception;
use Yajra\DataTables\DataTables;
use Auth;

class SliderImageService
{

    public function getSliderImages()
    {

        return SliderImage::with('file')
            ->orderBy('order', 'ASC')
            ->get();

    }

    public function storeSliderImage($request, $storage)
    {
        $data = $request->all();


        $status = isset($data['status']) ? 1 : 0;

        if ($data['content'] != '') {

            $target = isset($data['blank_indicator']) ? '_BLANK' : '_SELF';
            $data['content'] = '<a href="' . $data['content'] . '"  target="' . $target . '">' . $data['content'] . '</a>';
        }

        $lastOrder = SliderImage::max('order');
        $sliderImage_order = $lastOrder == null ? 0 : $lastOrder;

        $sliderImage = SliderImage::create([
            'content' => $data['content'],
            'status' => $status,
            'order' => $sliderImage_order + 1,
            'user_id' => Auth::user()->id,
        ]);

        if ($request->hasFile('image')) {

            $file_type = 'imagenes';
            $category = 'sliderImages';
            $file = $request->file('image');
            $belongsTo = 'sliderImages';
            $relation = 'one_one';

            return app(FileService::class)->store(
                $sliderImage,
                $file_type,
                $category,
                $file,
                $storage,
                $belongsTo,
                $relation
            );
        }

        throw new Exception(config('parameters.exception_message'));

    }

    public function updateSliderImage(SliderImage $sliderImage, $request, $storage)
    {
        $data = $request->all();
        $data['status'] = isset($data['status']) ? 1 : 0;

        if ($data['content'] != '') {

            $target = isset($data['blank_indicator']) ? '_BLANK' : '_SELF';
            $data['content'] = '<a href="' . $data['content'] . '"  target="' . $target . '">' . $data['content'] . '</a>';
        }

        $sliderImageChanged = SliderImage::where('order', $data['order'])
            ->update(["order" => $sliderImage->order]);

        if ($sliderImageChanged) {

            $sliderImage->update($data);

            if ($request->hasFile('image')) {

                app(FileService::class)->destroy($sliderImage->file, $storage);

                $file_type = 'imagenes';
                $category = 'sliderImages';
                $file = $request->file('image');
                $belongsTo = 'sliderImages';
                $relation = 'one_one';

                app(FileService::class)->store(
                    $sliderImage,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );
            }

            return true;
        }

        throw new Exception(config('parameters.exception_message'));
    }


    public function destroySliderImage(SliderImage $sliderImage, $storage)
    {
        if (app(FileService::class)->destroy($sliderImage->file, $storage)) {

            if ($sliderImage->delete()) {
                $sliderImages = $this->getSliderImages();
                $order = 1;
                foreach ($sliderImages as $sliderImage) {
                    $sliderImage->update([
                        "order" => $order
                    ]);
                    $order++;
                }
                return true;
            }
        }
        ;

        throw new Exception(config('parameters.exception_message'));
    }



}


