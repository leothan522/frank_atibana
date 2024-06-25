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
    public $almacenes_id, $almacen, $empresa, $listarMovimientos;

    public function __construct($empresa, $almacenes_id, $almacen, $listarMovimientos)
    {
        $this->empresa = $empresa;
        $this->almacenes_id = $almacenes_id;
        $this->almacen = $almacen;
        $this->listarMovimientos = $listarMovimientos;
    }

    public function view(): View
    {
        return view('dashboard.stock.excel_movimientos')
            ->with('empresa', $this->empresa)
            ->with('almacenes_id', $this->almacenes_id)
            ->with('almacen', $this->almacen)
            ->with('listarMovimientos', $this->listarMovimientos)
            ;
    }

    public function title(): string
    {
        return mb_strtoupper($this->almacen->nombre, 'utf8');
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_DATETIME,
            'I' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }
}
