<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regUmedidaModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportCatUmedida implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'ID',
            'UNIDAD_DE_MEDIDA',
            'ESTADO',
            'FECHA_REG'
        ];
    }

    public function collection()
    {
        return $regumedida = regUmedidaModel::select('UMED_ID','UMED_DESC', 'UMED_STATUS','UMED_FECREG')
                             ->orderBy('UMED_ID','desc')
                             ->get();                                
    }
}
