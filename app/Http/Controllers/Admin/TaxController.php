<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;
use Acelle\Model\Template;
use Acelle\Model\Setting;
use App;

class TaxController extends Controller
{
    public function settings(Request $request)
    {
        if ($request->isMethod('post')) {
            \Acelle\Model\Setting::setTaxSettings($request->tax);

            return response()->json([
                'status' => 'success',
                'message' => trans('messages.tax.settings.updated'),
            ]);
        }

        return view('admin.taxes.settings');
    }

    public function countries(Request $request)
    {
        return view('admin.taxes.countries');
    }

    public function addTax(Request $request)
    {
        $country = \Acelle\Model\Country::find($request->country_id);

        if ($request->isMethod('post')) {
            \Acelle\Model\Setting::setTaxSettings($request->tax);

            return response()->json([
                'status' => 'success',
                'message' => trans('messages.tax.settings.updated'),
            ]);
        }

        return view('admin.taxes.addTax', [
            'country' => $country,
        ]);
    }

    public function removeCountry(Request $request)
    {
        \Acelle\Model\Setting::removeTaxCountryByCode($request->code);

        return response()->json([
            'status' => 'success',
            'message' => trans('messages.tax.settings.updated'),
        ]);
    }
}
