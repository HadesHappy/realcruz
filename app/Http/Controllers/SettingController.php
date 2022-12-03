<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as LaravelLog;
use Acelle\Model\Setting;

class SettingController extends Controller
{
    /**
     * Render uploaded file.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function file(Request $request, $filename)
    {
        //return \Image::make(Setting::getUploadFilePath($filename))->response();
        $path = Setting::getUploadFilePath($filename);
        $type = mime_content_type($path);
        if ($type == 'image/svg') {
            $type = 'image/svg+xml';
        }
        return response()->file($path, ['Content-Type' => $type]);
    }
}
