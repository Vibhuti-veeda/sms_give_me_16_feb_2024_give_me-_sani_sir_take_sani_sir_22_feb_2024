<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\ReasonMaster;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use \Maatwebsite\Excel\Sheet;

class ReasonMasterExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection() {
        $reasonMasters = ReasonMaster::select('id', 'activity_type_id', 'activity_id', 'start_delay_remark', 'end_delay_remark', 'is_active')
                                    ->where('is_delete', 0)
                                    ->orderBy('id', 'DESC')
                                    ->with([
                                        'activityType',
                                        'activityName'
                                    ])
                                    ->get();

        $data = $reasonMasters->map(function ($reasonMaster, $index){
            return[
                'Sr. No' => $index +1,
                'Activity Name' => $reasonMaster->activityType ? $reasonMaster->activityType->para_value : '---',
                'Activity' => $reasonMaster->activityName ? $reasonMaster->activityName->activity_name : '---',
                'Start Delay Remark' => $reasonMaster->start_delay_remark ? $reasonMaster->start_delay_remark : '---',
                'End Delay Remark' => $reasonMaster->end_delay_remark ? $reasonMaster->end_delay_remark : '---',
                'Status' => $reasonMaster->is_active ? '1' : '0'   
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
                $sheet->setCellValue('A1', "All Reason Master | Study Management System");
                
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
            'Activity Name',
            'Activity',
            'Start Delay Remark',
            'End Delay Remark',
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
