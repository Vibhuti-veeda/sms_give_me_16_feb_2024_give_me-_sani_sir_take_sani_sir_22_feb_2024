<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\StudySchedule;
use App\Models\Study;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use \Maatwebsite\Excel\Sheet;
use Auth;
use App\User;
use App\Exports\StudyExport;
use Illuminate\Foundation\Auth\User as Authenticatable;

class StudyScheduleExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection() {
        $studyNo = Study::where('is_delete', 0)
                        ->whereHas('projectManager', function($q){
                            $q->where('is_active',1);
                        })
                        ->pluck('id');
        if (Auth::guard('admin')->user()->role_id == 3) {

            $studies = StudySchedule::where('is_delete', 0)
                                    ->select('id', 'study_id')
                                    ->whereHas(
                                        'studyNo', function($q) { 
                                            $q->select('id','study_no', 'sponsor')
                                              ->where('project_manager',Auth::guard('admin')->user()->id);
                                        }
                                    )
                                    ->with([
                                        'studyNo' => function ($q) {
                                            $q->select('id','study_no', 'sponsor')
                                              ->with([
                                                'sponsorName' => function($q){
                                                    $q->select('id','sponsor_name');
                                                }
                                            ]);
                                        },
                                        'drugDetails' => function($q) {
                                            $q->with([
                                                'drugName' => function($q){
                                                    $q->select('id','drug_name');
                                                },
                                                'drugDosageName' => function($q){
                                                    $q->select('id','para_value');
                                                },
                                                'drugUom' => function($q){
                                                    $q->select('id','para_value');
                                                },
                                                'drugType'
                                            ]);
                                        }
                                    ])
                                    ->whereIn('study_id',$studyNo)
                                    ->groupBy('study_id')
                                    ->orderBy('id', 'DESC')
                                    ->get();
            $data = $studies->map(function ($study, $index){
                $drug = '---'; // Default value if drug details are not available

                if ($study->drugDetails) {
                    foreach ($study->drugDetails as $dk => $dv) {
                        if ($dv->drugName && $dv->drugDosageName && $dv->dosage && $dv->drugUom && $dv->drugType && $dv->drugType->type == 'TEST') {
                            $drug = $dv->drugName->drug_name . '-' . $dv->drugDosageName->para_value . '-' . $dv->dosage . '-' . $dv->drugUom->para_value;
                            break;
                        }
                    }
                }

                return [
                    'Sr. No' => $index + 1,
                    'Study No' => $study->studyNo ? $study->studyNo->study_no : '---',
                    'Sponsor' => ($study->studyNo && $study->studyNo->sponsorName) ? $study->studyNo->sponsorName->sponsor_name : '---',
                    'Drug' => $drug,
                ];
            });
            return $data;

        } else {
            $studies = StudySchedule::where('is_delete', 0)
                                    ->select('id', 'study_id')
                                    ->with([
                                        'studyNo' => function ($q) {
                                            $q->select('id','study_no', 'sponsor')
                                              ->with([
                                                'sponsorName' => function($q){
                                                    $q->select('id','sponsor_name');
                                                }
                                            ]);
                                        },
                                        'drugDetails' => function($q) {
                                            $q->with([
                                                'drugName' => function($q){
                                                    $q->select('id','drug_name');
                                                },
                                                'drugDosageName' => function($q){
                                                    $q->select('id','para_value');
                                                },
                                                'drugUom' => function($q){
                                                    $q->select('id','para_value');
                                                },
                                                'drugType'
                                            ]);
                                        }
                                    ])
                                    ->whereIn('study_id',$studyNo)
                                    ->groupBy('study_id')
                                    ->orderBy('id', 'DESC')
                                    ->get();

            $data = $studies->map(function ($study, $index){
               $drug = '---'; // Default value if drug details are not available

                if ($study->drugDetails) {
                    foreach ($study->drugDetails as $dk => $dv) {
                        if ($dv->drugName && $dv->drugDosageName && $dv->dosage && $dv->drugUom && $dv->drugType && $dv->drugType->type == 'TEST') {
                            $drug = $dv->drugName->drug_name . '-' . $dv->drugDosageName->para_value . '-' . $dv->dosage . '-' . $dv->drugUom->para_value;
                            break;
                        }
                    }
                }

                return [
                    'Sr. No' => $index + 1,
                    'Study No' => $study->studyNo ? $study->studyNo->study_no : '---',
                    'Sponsor' => ($study->studyNo && $study->studyNo->sponsorName) ? $study->studyNo->sponsorName->sponsor_name : '---',
                    'Drug' => $drug,
                ];
            });
            return $data;
        }
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
                $sheet->setCellValue('A1', "All Schedule Study | Study Management System");
                
                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                
                $cellRange = 'A1:d1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }

    public function headings(): array {
        return [
            'Sr. No',
            'Study No',
            'Sponsor',
            'Drug',
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
