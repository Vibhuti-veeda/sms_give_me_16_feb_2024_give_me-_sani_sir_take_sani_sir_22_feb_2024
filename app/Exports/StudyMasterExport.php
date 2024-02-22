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
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class StudyMasterExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents, WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $StudyMasters = Study::where('is_active', 1)
                            ->where('is_delete', 0)
                            ->with([
                                'sponsorName' => function($q){
                                    $q->select('id', 'sponsor_name');
                                },
                                'projectManager' => function($q){
                                    $q->select('id', 'name');
                                },
                                'studyRegulatory' =>function($q){
                                    $q->select('id', 'regulatory_submission', 'project_id')
                                      ->with([
                                        'paraSubmission' => function($q){
                                            $q->select('id', 'para_value');
                                        }
                                    ]);
                                },
                                'drugDetails' => function($q) {
                                    $q->select('id', 'study_id', 'drug_id', 'dosage_form_id', 'uom_id', 'dosage', 'type')->with([
                                        'drugName' => function($q){
                                            $q->select('id', 'drug_name');
                                        },
                                        'drugDosageName' => function($q){
                                            $q->select('id', 'para_value');
                                        },
                                        'drugUom' => function($q){
                                            $q->select('id', 'para_value');
                                        },
                                        'drugType' => function($q){
                                            $q->select('id', 'manufacturedby', 'type');
                                        },
                                    ]);
                                },
                                'checkIn' => function($q){
                                    $q->select('id', 'study_id', 'scheduled_end_date', 'actual_end_date');
                                },
                                'lastSample' => function($q){
                                    $q->select('id', 'study_id', 'scheduled_end_date', 'actual_end_date');
                                },
                                'CRtoQA' => function($q){
                                    $q->select('id', 'study_id', 'scheduled_end_date', 'actual_end_date');
                                },
                                'QAtoCR' => function($q){
                                    $q->select('id', 'study_id', 'scheduled_end_date', 'actual_end_date');
                                },
                                'BRtoQA' => function($q){
                                    $q->select('id', 'study_id', 'scheduled_end_date', 'actual_end_date');
                                },
                                'QAtoBR' => function($q){
                                    $q->select('id', 'study_id', 'scheduled_end_date', 'actual_end_date');
                                },
                                'BRtoPB' => function($q){
                                    $q->select('id', 'study_id', 'scheduled_end_date', 'actual_end_date');
                                },
                                'PBtoQA' => function($q){
                                    $q->select('id', 'study_id', 'scheduled_end_date', 'actual_end_date');
                                },
                                'QAtoPB' => function($q){
                                    $q->select('id', 'study_id', 'scheduled_end_date', 'actual_end_date');
                                },
                                'darftToSponsor' => function($q){
                                    $q->select('id', 'study_id', 'scheduled_end_date', 'actual_end_date');
                                },
                                'bioanalyticalStartEnd' => function($q){
                                    $q->select('id', 'study_id', 'scheduled_start_date', 'actual_start_date', 'scheduled_end_date', 'actual_end_date');
                                },
                            ])
                        ->orderBy('global_priority_no', 'ASC')
                        ->get();

        $data = $StudyMasters->map(function ($studyMaster, $index){
            // Submiss 
            $submissions = [];
            if (!is_null($studyMaster->studyRegulatory)) {
                foreach ($studyMaster->studyRegulatory as $rk => $rv) {
                    if (!is_null($rv->paraSubmission) && $rv->paraSubmission->para_value != '') {
                        $submissions[] = $rv->paraSubmission->para_value;
                    }
                }
            }

            $drug = '---'; // Default value if drug details are not available

                if ($studyMaster->drugDetails) {
                    foreach ($studyMaster->drugDetails as $dk => $dv) {
                        if ($dv->drugName && $dv->drugDosageName && $dv->dosage && $dv->drugUom && $dv->drugType && $dv->drugType->type == 'TEST') {
                            $drug = $dv->drugName->drug_name . '-' . $dv->drugDosageName->para_value . '-' . $dv->dosage . '-' . $dv->drugUom->para_value;
                            break;
                        }
                    }
                }

            return[
                'Sr. No' => $index +1,
                'Study No' => $studyMaster->study_no ? $studyMaster->study_no : '---', 
                'Global Priority No' => $studyMaster->global_priority_no ? $studyMaster->global_priority_no : '---', 
                'Sponsor Study No' => $studyMaster->sponsor_study_no ? $studyMaster->sponsor_study_no : '---', 
                'Submission Type' => implode(' | ', $submissions),
                
                'Cdisc' => $studyMaster->cdisc_require == '1' ? 'Yes' : 'No',
                'Drug Name' => $drug,
                'PM Name' => $studyMaster->projectManager ? $studyMaster->projectManager->name : '---',
                'Sponsor Name' => $studyMaster->sponsorName ? $studyMaster->sponsorName->sponsor_name : '---',
                'No Of Subject' => $studyMaster->no_of_subject ? $studyMaster->no_of_subject : '---',
                'No Of Female' => $studyMaster->no_of_female_subjects ? $studyMaster->no_of_female_subjects : '---',
                'Period 1 Check In(S)' => $studyMaster->checkIn && $studyMaster->checkIn->scheduled_end_date ? Carbon::parse($studyMaster->checkIn->scheduled_end_date)->format('d M Y') : '---',
                'Period 1 Check In(A)' => $studyMaster->checkIn && $studyMaster->checkIn->actual_end_date ? Carbon::parse($studyMaster->checkIn->actual_end_date)->format('d M Y') : '---',
                'Last Sample(S)' => $studyMaster->lastSample && $studyMaster->lastSample->scheduled_end_date ? Carbon::parse($studyMaster->lastSample->scheduled_end_date)->format('d M Y') : '---',
                'Last Sample(A)' => $studyMaster->lastSample && $studyMaster->lastSample->actual_end_date? Carbon::parse($studyMaster->lastSample->actual_end_date)->format('d M Y') : '---',
                'CR to QA(S)' => $studyMaster->CRtoQA && $studyMaster->CRtoQA->scheduled_end_date ? Carbon::parse($studyMaster->CRtoQA->scheduled_end_date)->format('d M Y') : '---',
                'CR to QA(A)' => $studyMaster->CRtoQA && $studyMaster->CRtoQA->actual_end_date ? Carbon::parse($studyMaster->CRtoQA->actual_end_date)->format('d M Y') : '---',
                'QA to CR(S)' => $studyMaster->QAtoCR && $studyMaster->QAtoCR->scheduled_end_date ? Carbon::parse($studyMaster->QAtoCR->scheduled_end_date)->format('d M Y') : '---',
                'QA to CR(A)' => $studyMaster->QAtoCR && $studyMaster->QAtoCR->actual_end_date ? Carbon::parse($studyMaster->QAtoCR->actual_end_date) : '---',
                'Bio Analyitcal Start(S)' => $studyMaster->bioanalyticalStartEnd && $studyMaster->bioanalyticalStartEnd->scheduled_start_date? Carbon::parse($studyMaster->bioanalyticalStartEnd->scheduled_start_date)->format('d M Y') : '---',
                'Bio Analyitcal Start(A)' => $studyMaster->bioanalyticalStartEnd && $studyMaster->bioanalyticalStartEnd->actual_start_date ? Carbon::parse($studyMaster->bioanalyticalStartEnd->actual_start_date)->format('d M Y') : '---',
                'Bio Analyitcal End(S)' => $studyMaster->bioanalyticalStartEnd && $studyMaster->bioanalyticalStartEnd->scheduled_end_date? Carbon::parse($studyMaster->bioanalyticalStartEnd->scheduled_end_date)->format('d M Y') : '---',
                'Bio Analyitcal End(A)' => $studyMaster->bioanalyticalStartEnd && $studyMaster->bioanalyticalStartEnd->actual_end_date ? Carbon::parse($studyMaster->bioanalyticalStartEnd->actual_end_date)->format('d M Y') : '---',
                'BR to QA(S)' => $studyMaster->BRtoQA && $studyMaster->BRtoQA->scheduled_end_date ? Carbon::parse($studyMaster->BRtoQA->scheduled_end_date)->format('d M Y') : '---',
                'BR to QA(A)' => $studyMaster->BRtoQA && $studyMaster->BRtoQA->actual_end_date ? Carbon::parse($studyMaster->BRtoQA->actual_end_date)->format('d M Y') : '---',
                'QA to BR(S)' => $studyMaster->QAtoBR && $studyMaster->QAtoBR->scheduled_end_date ? Carbon::parse($studyMaster->QAtoBR->scheduled_end_date)->format('d M Y') : '---',
                'QA to BR(A)' => $studyMaster->QAtoBR && $studyMaster->QAtoBR->actual_end_date ? Carbon::parse($studyMaster->QAtoBR->actual_end_date)->format('d M Y') : '---',
                'BR to PB(S)' => $studyMaster->BRtoPB && $studyMaster->BRtoPB->scheduled_end_date ? Carbon::parse($studyMaster->BRtoPB->scheduled_end_date)->format('d M Y') : '---',
                'BR to PB(A)' => $studyMaster->BRtoPB && $studyMaster->BRtoPB->actual_end_date ? Carbon::parse($studyMaster->BRtoPB->actual_end_date)->format('d M Y') : '---',
                'PB to QA(S)' => $studyMaster->PBtoQA && $studyMaster->PBtoQA->scheduled_end_date ? Carbon::parse($studyMaster->PBtoQA->scheduled_end_date)->format('d M Y') : '---',
                'PB to QA(A)' => $studyMaster->PBtoQA && $studyMaster->PBtoQA->actual_end_date ? Carbon::parse($studyMaster->PBtoQA->actual_end_date)->format('d M Y') : '---',
                'QA to PB(S)' => $studyMaster->QAtoPB && $studyMaster->QAtoPB->scheduled_end_date ? Carbon::parse($studyMaster->QAtoPB->scheduled_end_date)->format('d M Y') : '---',
                'QA to PB(A)' => $studyMaster->QAtoPB && $studyMaster->QAtoPB->actual_end_date ? Carbon::parse($studyMaster->QAtoPB->actual_end_date)->format('d M Y') : '---',
                'Draft Report Date(S)' => $studyMaster->darftToSponsor && $studyMaster->darftToSponsor->scheduled_end_date ? Carbon::parse($studyMaster->darftToSponsor->scheduled_end_date)->format('d M Y') : '---',
                'Draft Report Date(A)' => $studyMaster->darftToSponsor && $studyMaster->darftToSponsor->actual_end_date ? Carbon::parse($studyMaster->darftToSponsor->actual_end_date)->format('d M Y') : '---',
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

                $sheet->mergeCells('A1:AJ1');
                $sheet->setCellValue('A1', "All Study Master Data| Study Management System");
                
                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                
                $cellRange = 'A1:AJ1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }

    public function headings(): array {
        return [
            'Sr. No',
            'Study No', 
            'Global Priority No', 
            'Sponsor Study No', 
            'Submission Type',
            'Cdisc',
            'Drug Name',
            'PM Name',
            'Sponsor Name',
            'No Of Subject',
            'No Of Female',
            'Period 1 Check In(S)',
            'Period 1 Check In(A)',
            'Last Sample(S)',
            'Last Sample(A)',
            'CR to QA(S)',
            'CR to QA(A)',
            'QA to CR(S)',
            'QA to CR(A)',
            'Bio Analyitcal Start(S)',
            'Bio Analyitcal Start(A)',
            'Bio Analyitcal End(S)',
            'Bio Analyitcal End(A)',
            'BR to QA(S)',
            'BR to QA(A)',
            'QA to BR(S)',
            'QA to BR(A)',
            'BR to PB(S)',
            'BR to PB(A)',
            'PB to QA(S)',
            'PB to QA(A)',
            'QA to PB(S)',
            'QA to PB(A)',
            'Draft Report Date(S)',
            'Draft Report Date(A)',
        ];
    }

    public function styles(Worksheet $sheet): array {
        // Define custom styles for the first row (heading row)
        return [
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['bold' => true]],
        ];
    }

    public function columnFormats(): array {
        return [
            'L' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'M' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'N' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'O' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'P' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'Q' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'R' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'S' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'T' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'U' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'V' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'W' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'X' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'Y' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'Z' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'AA' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'AB' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'AC' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'AD' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'AE' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'AF' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'AG' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'AH' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'AI' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'AJ' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
}
