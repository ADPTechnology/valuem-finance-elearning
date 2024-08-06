<?php

namespace App\Services;

use App\Models\Publishing;
use Exception;
use Illuminate\Support\Carbon;
use Auth;

class NewsService
{

    protected $fileService;


    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function getNews($active = false)
    {

        $query = Publishing::where('type', 'NEWS')
            ->with('file')
            ->orderBy('publishing_order', 'ASC');

        if ($active) {
            $query->where('status', '1');
        }

        return $query->get();
    }


    public function store($request, $storage)
    {
        $data = $request->all();

        $data['status'] = isset($data['status']) ? 1 : 0;

        if ($data['content'] != '') {

            $target = isset($data['blank_indicator']) ? '_BLANK' : '_SELF';
            $data['content'] = '<a href="' . $data['content'] . '"  target="' . $target . '">' . $data['content'] . '</a>';
        }

        $lastOrder = Publishing::where('type', 'NEWS')->max('publishing_order');
        $publishing_order = $lastOrder == null ? 0 : $lastOrder;

        $newsCount = Publishing::where('type', 'NEWS')->count();

        if ($newsCount < 3) {

            $banner = Publishing::create($data + [
                'type' => 'NEWS',
                'publishing_order' => $publishing_order + 1,
                'publication_time' => Carbon::now('America/Lima'),
                'user_id' => Auth::user()->id,
            ]);

            if ($banner && $request->hasFile('image')) {

                $file_type = 'imagenes';
                $category = 'noticias';
                $file = $request->file('image');
                $belongsTo = 'noticias';
                $relation = 'one_one';

                return $this->fileService->store(
                    $banner,
                    $file_type,
                    $category,
                    $file,
                    $storage,
                    $belongsTo,
                    $relation
                );
            }
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function update(Publishing $new, $request, $storage)
    {
        $data = $request->all();
        $data['status'] = isset($data['status']) ? 1 : 0;

        if ($data['content'] != '') {
            $target = isset($data['blank_indicator']) ? '_BLANK' : '_SELF';
            $data['content'] = '<a href="' . $data['content'] . '"  target="' . $target . '">' . $data['content'] . '</a>';
        }

        $newChange = Publishing::where('type', 'NEWS')
            ->where('publishing_order', $data['publishing_order'])
            ->update([
                "publishing_order" => $new->publishing_order
            ]);

        if ($newChange) {

            $new->update($data);

            if ($request->hasFile('image')) {

                $this->fileService->destroy($new->file, $storage);

                $file_type = 'imagenes';
                $category = 'publicaciones';
                $file = $request->file('image');
                $belongsTo = 'publicaciones';
                $relation = 'one_one';

                $this->fileService->store(
                    $new,
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


    public function destroy(Publishing $new, $storage)
    {
        if ($this->fileService->destroy($new->file, $storage)) {

            if ($new->delete()) {
                $news = $this->getNews();
                $order = 1;
                foreach ($news as $new) {
                    $new->update([
                        "publishing_order" => $order
                    ]);
                    $order++;
                }

                return true;
            }
        };

        throw new Exception(config('parameters.exception_message'));
    }
}
