<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log as LaravelLog;
use Acelle\Model\Invoice;

class InvoiceController extends Controller
{
    public function download(Request $request)
    {
        $invoice = Invoice::findByUid($request->uid);

        return \Response::make($invoice->exportToPdf(), 200, [
            'Content-type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="invoice-'.$invoice->uid.'.pdf"',
        ]);
    }

    public function template(Request $request)
    {
        if ($request->isMethod('post')) {
            \Acelle\Model\Setting::set('invoice.custom_template', $request->content);
        }

        return view('admin.invoices.template');
    }
}
