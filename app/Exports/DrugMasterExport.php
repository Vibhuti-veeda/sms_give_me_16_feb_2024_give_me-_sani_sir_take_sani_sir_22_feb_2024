<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\DrugMaster;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use \Maatwebsite\Excel\Sheet;

class DrugMasterExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection() {
        $drugs = DrugMaster::select('id','drug_name', 'drug_type', 'remarks', 'is_active')
                            ->where('is_delete', 0)
                            ->orderBy('id', 'DESC')
                            ->get();

        $data = $drugs->map(function ($drug, $index){
            return[
                'Sr. No' => $index +1,
                'Drug Name' => $drug->drug_name ? $drug->drug_name : '---', 
                'Drug Type' => $drug->drug_type ? $drug->drug_type : '---',
                'Remarks' => $drug->remarks ? $drug->remarks : '---',
                'Status' => $drug->is_active ? '1' : '0' 
            ];
        });

        return $data;
    }

    public function startCell(): string {
        return 'A2';
    }

    public function registerEvents(): array {

        return [
            AfterSheet::class => function(AfterSheet $event) {
                /** @var Sheet $sheet */
                $sheet = $event->sheet;

                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', "All Drugs | Study Management System");
                
                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                
                $cellRange = 'A1:E1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }

    public function headings(): array {
        return [
            'Sr. No',
            'Drug Name', 
            'Drug Type',
            'Remarks',
            'Status'
        ];
    }

    public function styles(Worksheet $sheet): array {
        // Define custom styles for the first row (heading row)
        return [
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['bold' => true]],
        ];
    }
}
