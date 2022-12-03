<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;

class GeoIpController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.geoip.index');
    }

    public function setting(Request $request)
    {
        // Save setting
        if ($request->isMethod('post')) {
            // make validator
            $validator = \Validator::make($request->all(), [
                'source' => 'required',
            ]);

            if (!$validator->fails()) {
                // validate service
                $validator->after(function ($validator) {
                    try {
                        // testing something in service side
                        sleep(2);
                        10/0; // error for sure
                    } catch (\Exception $e) {
                        $validator->errors()->add('source', 'Service connection was failed. Error: ' . $e->getMessage());
                    }
                });
            }

            //
            if ($validator->fails()) {
                return response()->view('admin.geoip.setting', [
                    'errors' => $validator->errors(),
                ], 400);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'GeoIp setting was updated!',
            ], 201);
        }

        return view('admin.geoip.setting');
    }

    public function reset(Request $request)
    {
        sleep(2);

        if (false) {
            return response()->json([
                'error' => 'Something went wrong!. Error: lalala',
            ], 400);
        } else {
            return response()->json([
                'message' => 'GeoIp was reset successfully!',
            ]);
        }
    }
}
