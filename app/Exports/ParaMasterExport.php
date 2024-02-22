<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\ParaMaster;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use \Maatwebsite\Excel\Sheet;

class ParaMasterExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection() {
        $paraMasters = ParaMaster::select('id', 'para_code', 'para_description', 'is_active')
                            ->where('is_delete', 0)
                            ->orderBy('id', 'DESC')
                            ->get();

        $data = $paraMasters->map(function ($paraMaster, $index){
            return[
                'Sr. No.' => $index +1,
                'Para Code' => $paraMaster->para_code ? $paraMaster->para_code : '---',
                'Para Description' => $paraMaster->para_description ? $paraMaster->para_description : '---',
                'Status' => $paraMaster->is_active ? '1' : '0'
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

                $sheet->mergeCells('A1:D1');
                $sheet->setCellValue('A1', "All Para Master | Study Management System");
                
                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                
                $cellRange = 'A1:D1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }

    public function headings(): array {
        return [
            'Sr. No.',
            'Para Code',
            'Para Description',
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
