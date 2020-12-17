<?php


namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InvoicesExport_mega_report implements FromView
{
    protected $invoices;

    public function __construct(array $invoices)
    {
        $this->invoices = $invoices;
    }

    public function view(): View
    {
        return view('panel.charity.reports.megaReportTable', [
            'vow_list' => $this->invoices['vow_list']
        ]);
    }
}