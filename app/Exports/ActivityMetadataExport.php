<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\ActivityMetadata;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use \Maatwebsite\Excel\Sheet;

class ActivityMetadataExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $activityMetadataLists = ActivityMetadata::select('id', 'activity_id', 'control_id', 'source_value', 'source_question', 'is_mandatory', 'input_validation', 'is_activity', 'is_active')
                                                ->where('is_delete', 0)
                                                ->with([
                                                    'activityName' => function($q){
                                                        $q->select('id', 'activity_name')
                                                          ->where('is_active', 1)
                                                          ->where('is_delete', 0);
                                                    },
                                                    'controlName' => function($q){
                                                        $q->select('id', 'control_name')
                                                        ->where('is_active', 1)
                                                        ->where('is_delete', 0);
                                                    }
                                                ])
                                                ->orderBy('id', 'DESC')
                                                ->get();

        $data = $activityMetadataLists->map(function ($activityMetadataList, $index){
            return[
                'Sr. No.' => $index +1,
                'Activity Name' => $activityMetadataList->activityName ? $activityMetadataList->activityName->activity_name : '---',
                'Control Name' => $activityMetadataList->controlName ? $activityMetadataList->controlName->control_name : '---',
                'Source Title' => $activityMetadataList->source_question ? $activityMetadataList->source_question : '---',
                'Source Value' => $activityMetadataList->source_value ? $activityMetadataList->source_value : '---',
                'Is Mandatory' => $activityMetadataList->is_active ? '1' : '0', 
                'Status' => $activityMetadataList->is_mandatory == 1 ? 'Yes' : 'No'
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

                $sheet->mergeCells('A1:G1');
                $sheet->setCellValue('A1', "All Activity Metadata | Study Management System");
                
                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                
                $cellRange = 'A1:G1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }

    public function headings(): array {
        return [
            'Sr. No.',
            'Activity Name',
            'Control Name',
            'Source Title',
            'Source Value',
            'Is Mandatory', 
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
