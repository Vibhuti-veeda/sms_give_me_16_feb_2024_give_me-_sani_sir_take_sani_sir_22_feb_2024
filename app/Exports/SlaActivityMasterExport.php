<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\SlaActivityMaster;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use \Maatwebsite\Excel\Sheet;

class SlaActivityMasterExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $slaActivities = SlaActivityMaster::select('id','activity_id', 'study_design', 'no_from_subject', 'no_to_subject', 'is_cdisc', 'is_active')
                                        ->where('is_delete', 0)
                                        ->with([
                                            'activityName',
                                            'studyDesign'
                                        ])
                                        ->get();

        $data = $slaActivities->map(function ($slaActivity, $index){
            return[
                'Sr no.' => $index +1,
                'Activity Name' => $slaActivity->activityName ? $slaActivity->activityName->activity_name : '---',
                'Study Design' => $slaActivity->studyDesign ? $slaActivity->studyDesign->para_value : '----',
                'No of from subject' => $slaActivity->no_from_subject ? $slaActivity->no_from_subject : '---',
                'No of to subject' => $slaActivity->no_to_subject ? $slaActivity->no_to_subject : '---',
                'No of days' => $slaActivity->no_of_days ? $slaActivity->no_of_days : '---',
                'CDISC' => $slaActivity->is_cdisc == '0' ? "No" : "Yes",
                'Status' => $slaActivity->is_active ? '1' : '0'
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

                $sheet->mergeCells('A1:H1');
                $sheet->setCellValue('A1', "All Team Members | Study Management System");
                
                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                
                $cellRange = 'A1:H1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }

    public function headings(): array
    {
        return [
            'Sr no.',
            'Activity Name',
            'Study Design',
            'No of from subject',
            'No of to subject',
            'No of days',
            'CDISC',
            'Status'
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Define custom styles for the first row (heading row)
        return [
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['bold' => true]],
        ];
    }
}
