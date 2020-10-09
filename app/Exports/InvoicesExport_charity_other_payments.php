<?php


namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InvoicesExport_charity_other_payments implements FromView
{
    protected $invoices;

    public function __construct(array $invoices)
    {
        $this->invoices = $invoices;
    }

    public function view(): View
    {
        return view('panel.charity.other_payment.table', [
            'otherPayments' => $this->invoices['otherPayments']
        ]);
    }
}