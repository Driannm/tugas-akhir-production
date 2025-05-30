<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function show(Payment $payment)
    {
        $pdf = Pdf::loadView('exports.invoices', [
            'payment' => $payment,
            'construction' => $payment->construction,
        ]);

        return $pdf->stream('Invoice-' . $payment->reference_number . '.pdf');
    }
}
