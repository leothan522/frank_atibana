<?php

namespace App\Exports;

use App\Models\Ajuste;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class MovimientosExport implements FromView, ShouldAutoSize, WithTitle, WithColumnFormatting
{
    public $getAjustes, $getSaldo, $limit, $getNombre, $empresa;

    public function __construct($getAjustes, $getSaldo, $limit, $getNombre, $empresa)
    {
        $this->getAjustes = $getAjustes;
        $this->getSaldo = $getSaldo;
        $this->limit = $limit;
        $this->getNombre = $getNombre;
        $this->empresa = $empresa;
    }

    public function view(): View
    {
        return view('dashboard.stock.excel_movimientos')
            ->with('getAjustes', $this->getAjustes)
            ->with('getSaldo', $this->getSaldo)
            ->with('getLimit', $this->limit)
            ->with('getNombre', $this->getNombre)
            ->with('empresa', $this->empresa)
            ;
    }

    public function title(): string
    {
        return mb_strtoupper($this->getNombre, 'utf8');
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_NUMBER_00,
            'I' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }
}
