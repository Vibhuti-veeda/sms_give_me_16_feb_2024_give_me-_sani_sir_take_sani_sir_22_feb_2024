<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\ActivityMaster;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use \Maatwebsite\Excel\Sheet;

class ActivityMasterExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $activities = ActivityMaster::select('id', 'activity_name', 'days_required', 'next_activity', 'buffer_days', 'responsibility', 'activity_type', 'activity_days', 'sequence_no', 'previous_activity', 'is_milestone', 'parent_activity', 'is_active')
                                    ->where('is_delete', 0)
                                    ->with([
                                            'responsible', 
                                            'nextActivity', 
                                            'previousActivity', 
                                            'parentActivity',
                                            'activityType'
                                        ])
                                    ->get();

        $data = $activities->map(function ($activity, $index){
            return[
                'Sr. No' => $index +1,
                'Activity Name' => $activity->activity_name ? $activity->activity_name : '---',
                'Days Required' => $activity->days_required ? $activity->days_required : '---',
                'Next Activity' => $activity->nextActivity ? $activity->nextActivity->activity_name : '---',
                'Buffer Days' => $activity->buffer_days ? $activity->buffer_days : '---',
                'Responsibility' => $activity->responsible ? $activity->responsible->name : '---',
                'Activity Type' => $activity->activityType ? $activity->activityType->para_value : '---',
                'Day Type' => $activity->activity_days ? $activity->activity_days : '---',
                'Sequence No' => $activity->sequence_no ? $activity->sequence_no : '---',
                'Previous Activity' => $activity->previousActivity ? $activity->previousActivity->activity_name : '---',
                'Milestone Activity' => $activity->is_milestone == '1' ? 'Yes' : 'No',
                'Parent Activity' => $activity->parentActivity ? $activity->parentActivity->activity_name : '---',
                'Status' => $activity->is_active ? '1' : '0' 
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

                $sheet->mergeCells('A1:N1');
                $sheet->setCellValue('A1', "All Activity Master | Study Management System");
                
                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                
                $cellRange = 'A1:N1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }

    public function headings(): array {
        return [
            'Sr. No', 
            'Activity Name',
            'Days Required',
            'Next Activity',
            'Buffer Days',
            'Responsibility',
            'Activity Type',
            'Day Type',
            'Sequence No',
            'Previous Activity',
            'Milestone Activity',
            'Parent Activity',
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
