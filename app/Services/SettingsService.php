<?php

namespace App\Services;

use App\Models\{Config};
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Storage;

class SettingsService
{
    public function updateLogo(Request $request, Config $config, $storage)
    {
        if ($request->hasFile('image')) {

            if ($config->logo_url != '') {
                $this->destroyStorageLogo($config, $storage);
            }

            $file_type = 'imagenes';
            $category = 'logo';
            $file = $request->file('image');

            $directory = $directory = $file_type . '/' . $category;
            $filename = app(FileService::class)->getFileName($directory, $file, $storage);
            [$file_path, $file_url] = app(FileService::class)->storeInStorage($directory, $filename, $file, $storage);

            return $config->update([
                'logo_url' => $file_url
            ]);
        }

        throw new Exception(config('parameters.exception_message'));
    }

    public function destroyLogo(Config $config, $storage)
    {
        $this->destroyStorageLogo($config, $storage);

        return $config->update([
            'logo_url' => ""
        ]);
    }

    public function destroyStorageLogo(Config $config, $storage)
    {
        if (Storage::disk($storage)->exists($config->logo_url)) {
            Storage::disk($storage)->delete($config->logo_url);
        }
    }

}
