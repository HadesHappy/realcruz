<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
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
}
