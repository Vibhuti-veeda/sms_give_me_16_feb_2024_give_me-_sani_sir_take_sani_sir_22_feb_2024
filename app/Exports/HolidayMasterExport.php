<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\HolidayMaster;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use \Maatwebsite\Excel\Sheet;

class HolidayMasterExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
        $holidaylists = HolidayMaster::select('id', 'holiday_year', 'holiday_name', 'holiday_type', 'holiday_date', 'is_active')
                                    ->where('is_delete', 0)
                                    ->orderBy('id', 'DESC')
                                    ->get();

        $data = $holidaylists->map(function ($holidaylist, $index){
            return[
                'Sr. No' => $index +1,
                'Holiday Year' => $holidaylist->holiday_year ? $holidaylist->holiday_year : '---',
                'Holiday Name' => $holidaylist->holiday_name ? $holidaylist->holiday_name : '---',
                'Holiday Type' => $holidaylist->holiday_type ? $holidaylist->holiday_type : '---',
                'Holiday Date' => $holidaylist->holiday_date ? $holidaylist->holiday_date : '---',
                'Status' => $holidaylist->is_active ? '1' : '0' 
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

                $sheet->mergeCells('A1:F1');
                $sheet->setCellValue('A1', "All Holiday Masters | Study Management System");
                
                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                
                $cellRange = 'A1:F1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }

    public function headings(): array {
        return [
            'Sr. No',
            'Holiday Year',
            'Holiday Name',
            'Holiday Type',
            'Holiday Date',
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
