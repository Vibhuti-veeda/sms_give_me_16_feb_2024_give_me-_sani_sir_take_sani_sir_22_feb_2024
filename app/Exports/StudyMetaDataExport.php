<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\StudyActivityMetadata;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use \Maatwebsite\Excel\Sheet;

class StudyMetaDataExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $studyMetaDatas = StudyActivityMetadata::select('id', 'study_schedule_id', 'activity_meta_id', 'actual_value')
                                            ->with([
                                                'activityMetadata' => function($q) {
                                                    $q->select('id', 'activity_id', 'source_value', 'source_question','is_activity')
                                                        ->with([
                                                            'activityName' => function($q) {
                                                                $q->select('id','activity_name')
                                                                  ->where('is_active', 1)
                                                                  ->where('is_delete', 0);
                                                            },
                                                        ])
                                                        ->where('is_active', 1)
                                                        ->where('is_delete', 0);
                                                },
                                                'studySchedule' => function($q) {
                                                    $q->select('id', 'study_id','activity_id','actual_start_date','actual_end_date', 'period_no')
                                                      ->with([
                                                            'studyNo' => function($q) {
                                                                $q->select('id', 'study_no')
                                                                  ->where('is_active', 1)
                                                                  ->where('is_delete', 0);
                                                            },
                                                        ])
                                                    ->where('is_active', 1)
                                                    ->where('is_delete', 0);
                                                },
                                            ])
                                            ->where('is_active', 1)
                                            ->where('is_delete', 0)
                                            ->orderBy('id', 'DESC')
                                            ->get();

        $data = $studyMetaDatas->map(function ($studyMetaData, $index){
            return[
                'Sr. No.' => $index +1,
                'Study No' => $studyMetaData->studySchedule && $studyMetaData->studySchedule->studyNo ? $studyMetaData->studySchedule->studyNo->study_no : '---',
                'Period' => $studyMetaData->studySchedule ? $studyMetaData->studySchedule->period_no : '---',
                'Activity Name' => $studyMetaData->activityMetadata && $studyMetaData->activityMetadata->activityName ? $studyMetaData->activityMetadata->activityName->activity_name : '---',
                'Title' => $studyMetaData->activityMetadata ? $studyMetaData->activityMetadata->source_question : '---',
                'Output Value' => $studyMetaData->actual_value ? $studyMetaData->actual_value : '---',  
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
                $sheet->setCellValue('A1', "All Study MetaData | Study Management System");
                
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
            'Sr. No.',
            'Study No',
            'Period',
            'Activity Name',
            'Title',
            'Output Value', 
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
