<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\SponsorMaster;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use \Maatwebsite\Excel\Sheet;

class SponsorMasterExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $sponsors = SponsorMaster::select('id','sponsor_name', 'sponsor_type', 'is_active')
                                   ->where('is_delete', 0)
                                   ->orderBy('id', 'DESC')
                                   ->get();

        $data = $sponsors->map(function ($sponsor, $index){
            return[
                'Sr. No' => $index +1,
                'Sponsor Name' => $sponsor->sponsor_name ? $sponsor->sponsor_name : '---',
                'Sponsor Type' => $sponsor->sponsor_type ? $sponsor->sponsor_type : '---',
                'Status' => $sponsor->is_active ? '1' : '0' 
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
                $sheet->setCellValue('A1', "All Sponsor Master | Study Management System");
                
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
            'Sr. No',
            'Sponsor Name',
            'Sponsor Type',
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
