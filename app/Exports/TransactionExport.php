<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Sheet;

class TransactionExport implements FromView, ShouldAutoSize, WithEvents
{
    public $report;
    public $report_type;

    public function __construct($report, $report_type)
    {
        $this->report = $report;
        $this->report_type = $report_type;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return view('export.report_excel', [
            'report' => $this->report,
            'report_type' => __($this->report_type)
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
            },
            BeforeSheet::class => function (BeforeSheet $event) {
                Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
                    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
                });
            }
        ];
    }
}
