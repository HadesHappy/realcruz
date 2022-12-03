<?php

namespace Acelle\Http\Controllers\Api;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;

/**
 * /api/v1/file - API controller for managing user's files.
 */
class FileController extends Controller
{
    /**
     * Upload file to user directory.
     *
     * GET /api/v1/file/upload
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        $user = \Auth::guard('api')->user();

        // Get path
        $path = $user->getAssetsPath();

        if (!is_array($request->all()["files"])) {
            $files = json_decode($request->all()["files"]);
        } else {
            $files = $request->all()["files"];
        }

        $result = [];
        foreach ($files as $file) {
            $fileUrl = $file->url;

            // Check and merge custom path
            $path = $user->getAssetsPath();
            if ($file->subdirectory) {
                $parts = explode('/', $file->subdirectory);
                $validParts = [];
                foreach ($parts as $part) {
                    if ($part) {
                        if (strpbrk($part, "\\/?%*:|\"<>") === false) {
                            $validParts[] = $part;
                        } else {
                            // return response()->json('Path is not valid', 400);
                            $result[] = [
                                'file' => $fileUrl,
                                'status' => 0,
                                'message' => 'Subdirectory is not valid',
                            ];
                            continue;
                        }
                    }
                }

                $path = $path . implode('/', $validParts) . '/';
            }

            // Get file name
            $urlArr = explode('/', $fileUrl);
            $ct = count($urlArr);
            $name = $urlArr[$ct-1];

            // Update drestination path + file name
            $destinationPath = $path . $name;

            // Check if file exist
            if (file_exists($destinationPath)) {
                // return response()->json('The same file name already exists', 400);
                $result[] = [
                    'file' => $fileUrl,
                    'status' => 0,
                    'message' => 'The same file name already exists',
                ];
                continue;
            }

            // Download file
            try {
                if (! \File::isDirectory($path)) {
                    \File::makeDirectory($path, 0777, true);
                }

                file_put_contents($destinationPath, file_get_contents($fileUrl));

                $result[] = [
                    'file' => $fileUrl,
                    'status' => 1,
                    'message' => trans('messages.file_uploaded'),
                ];
            } catch (\Exception $ex) {
                // return response()->json($ex->getMessage(), 400);
                $result[] = [
                    'file' => $fileUrl,
                    'status' => 0,
                    'message' => $ex->getMessage(),
                ];
            }
        }

        return \Response::json($result, 200);
    }
}
