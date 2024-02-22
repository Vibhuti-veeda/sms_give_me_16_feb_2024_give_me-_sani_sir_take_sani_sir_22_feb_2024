<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
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
use Illuminate\Foundation\Auth\User as Authenticatable;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class StudyExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents, WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection() {
        $query = Study::select('id', 'study_result', 'study_status', 'study_slotted', 'tentative_clinical_date', 'projection_status', 'study_no', 'sponsor', 'project_manager', 'is_active')
                        ->where('is_delete', 0)
                        ->whereHas('projectManager', function($q){
                            $q->where('is_active',1);
                        })
                        ->orderBy('id', 'DESC');
        if (Auth::guard('admin')->user()->role_id == 3) {
            $studies = $query->where('project_manager', Auth::guard('admin')->user()->id)
                            ->with([
                                    'sponsorName',
                                    'studyType',
                                    'priorityName',
                                    'studyDesignName',
                                    'studySubTypeName',
                                    'subjectTypeName',
                                    'blindingStatusName',
                                    'crLocationName',
                                    'wardName',
                                    'complexityName',
                                    'studyConditionName',
                                    'brLocationName',
                                    'projectManager',
                                    'specialNotesName',
                                    'principleInvestigator',
                                    'bioanalyticalInvestigator',
                                    'studyScope' => function($q){
                                        $q->with([
                                            'scopeName'
                                        ]);
                                    },
                                    'drugDetails' => function($q) {
                                        $q->with([
                                            'drugName',
                                            'drugDosageName',
                                            'drugUom',
                                            'drugType'
                                        ]);
                                    }
                                ])
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
                return[
                    'Sr. No' => $index +1,
                    'Study Result' => $study->study_result ? $study->study_result : '---',
                    'Study Status' => $study->study_status ? $study->study_status : '---',
                    'Study Slotted' => $study->study_slotted ? $study->study_slotted : '---',
                    'Tentative Clinical Date' => $study->tentative_clinical_date ? Carbon::parse($study->tentative_clinical_date)->format('d M Y') : '---',
                    'Study No' => $study->study_no ? $study->study_no : '---',
                    'Drug' => $drug,
                    'Sponsor' => $study->sponsorName ? $study->sponsorName->sponsor_name : '---',
                    'projectManager' => $study->projectManager ? $study->projectManager->name : '---', 
                    'Status' => $study->is_active ? '1' : '0' 
                ];
            });
            return $data;

        } elseif(Auth::guard('admin')->user()->role_id == 10) {
            $studies = $query->with([
                                    'sponsorName',
                                    'studyType',
                                    'priorityName',
                                    'studyDesignName',
                                    'studySubTypeName',
                                    'subjectTypeName',
                                    'blindingStatusName',
                                    'crLocationName',
                                    'wardName',
                                    'complexityName',
                                    'studyConditionName',
                                    'brLocationName',
                                    'projectManager',
                                    'specialNotesName',
                                    'principleInvestigator',
                                    'bioanalyticalInvestigator',
                                    'studyScope' => function($q){
                                        $q->with([
                                            'scopeName'
                                        ]);
                                    },
                                    'drugDetails' => function($q) {
                                        $q->with([
                                            'drugName',
                                            'drugDosageName',
                                            'drugUom',
                                            'drugType'
                                        ]);
                                    }
                                ])
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
                return[
                    'Sr. No' => $index +1,
                    'Study Result' => $study->study_result ? $study->study_result : '---',
                    'Study Status' => $study->study_status ? $study->study_status : '---',
                    'Study Slotted' => $study->study_slotted ? $study->study_slotted : '---',
                    'Tentative Clinical Date' => $study->tentative_clinical_date ? Carbon::parse($study->tentative_clinical_date)->format('d M Y') : '---',
                    'Study No' => $study->study_no ? $study->study_no : '---',
                    'Drug' => $drug,
                    'Sponsor' => $study->sponsorName ? $study->sponsorName->sponsor_name : '---',
                    'projectManager' => $study->projectManager ? $study->projectManager->name : '---', 
                    'Status' => $study->is_active ? '1' : '0' 
                ];
            });
            return $data;           
        } else {
            $studies = $query->with([
                                    'sponsorName',
                                    'studyType',
                                    'priorityName',
                                    'studyDesignName',
                                    'studySubTypeName',
                                    'subjectTypeName',
                                    'blindingStatusName',
                                    'crLocationName',
                                    'wardName',
                                    'complexityName',
                                    'studyConditionName',
                                    'brLocationName',
                                    'projectManager',
                                    'specialNotesName',
                                    'principleInvestigator',
                                    'bioanalyticalInvestigator',
                                    'studyScope' => function($q){
                                        $q->with([
                                            'scopeName'
                                        ]);
                                    },
                                    'drugDetails' => function($q) {
                                        $q->with([
                                            'drugName',
                                            'drugDosageName',
                                            'drugUom',
                                            'drugType'
                                        ]);
                                    }
                                ])
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
                return[
                    'Sr. No' => $index +1,
                    'Study Result' => $study->study_result ? $study->study_result : '---',
                    'Study Status' => $study->study_status ? $study->study_status : '---',
                    'Study Slotted' => $study->study_slotted ? $study->study_slotted : '---',
                    'Tentative Clinical Date' => $study->tentative_clinical_date ? Carbon::parse($study->tentative_clinical_date)->format('d M Y') : '---',
                    'Projection Status' => $study->projection_status ? $study->projection_status : '---',
                    'Study No' => $study->study_no ? $study->study_no : '---',
                    'Drug' => $drug,
                    'Sponsor' => $study->sponsorName ? $study->sponsorName->sponsor_name : '---',
                    'projectManager' => $study->projectManager ? $study->projectManager->name : '---', 
                    'Status' => $study->is_active ? '1' : '0' 
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

                $sheet->mergeCells('A1:J1');
                $sheet->setCellValue('A1', "All Studies | Study Management System");
                
                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                
                $cellRange = 'A1:J1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }

    public function headings(): array {

        $headings = [
            'Sr. No',
            'Study Result',
            'Study Status',
            'Study Slotted',
            'Tentative Clinical Date',
            'Projection Status',
            'Study No',
            'Drug',
            'Sponsor',
            'projectManager', 
            'Status' 
        ];

        if ((Auth::guard('admin')->user()->role_id == 3) || (Auth::guard('admin')->user()->role_id == 10)) {
            unset($headings[5]); // Remove 'Projection Status' heading
        }

        return $headings ;   
    }

    public function styles(Worksheet $sheet): array {
        // Define custom styles for the first row (heading row)
        return [
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['bold' => true]],
        ];
    }

    public function columnFormats(): array {
        return[
            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY
        ];
    }
}
