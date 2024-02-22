<div>
    
    <div class="form-group">
        <p>Hello <?php echo $name; ?>,</p>
    </div>

    <div class="form-group">
        <p>
            <b>
                Below are the lists of Current & Next week planned studies
            </b> 
        </p>
    </div>

    
    @if(!is_null($currentWeek))
        <table style="border-collapse: collapse; border-spacing: 0; width: 100%; border: 1px solid;">
            <tr>
                <th style="color: blue; width: 100%; column-span:13;">
                    <b>
                        List of studies planned for current week
                    </b>
                </th>
            </tr>
            <tr style="border: 1px solid;">
                <center>
                    <th style="border: 1px solid;">#</th>
                    <th style="border: 1px solid;">Site</th>
                    <th style="border: 1px solid;">Study No</th>
                    <th style="border: 1px solid;">Drug name</th>
                    <th style="border: 1px solid;">Sponsor</th>
                    <th style="border: 1px solid;">Subjects</th>
                    <th style="border: 1px solid;">Male</th>
                    <th style="border: 1px solid;">Female</th>
                    <th style="border: 1px solid;">Tentative Clinical Date</th>
                    <th style="border: 1px solid;">BENOC Actual Approval</th>
                    <th style="border: 1px solid;">Tentative IP Status</th>
                    <th style="border: 1px solid;">IEC Approval</th>
                    <th style="border: 1px solid;">System Generated Status</th>
                </center>
            </tr>
        
            @if(!is_null($currentWeek))
                @foreach($currentWeek as $pwk => $pcv)
                    <center>
                        <tr style="border: 1px solid;">
                            <td style="border: 1px solid; width: 2%;">
                                <center>
                                    <?php echo $loop->iteration; ?>
                                </center>
                            </td>
                            <td style="border: 1px solid; width: 5%;">
                                <?php echo $pcv->studyNo->crLocationName->location_name; ?>
                            </td>
                            <td style="border: 1px solid; width: 10%;">
                                <?php echo $pcv->studyNo->study_no; ?>
                            </td>

                            <td style="border: 1px solid; width: 24%;">
                                @if(!is_null($pcv->studyNo) && ($pcv->studyNo->drugDetails)) 
                                    @php $drug = ''; @endphp
                                    @foreach($pcv->studyNo->drugDetails as $ddk => $ddv)
                                        @if(!is_null($ddv->drugName) && !is_null($ddv->drugDosageName) && !is_null($ddv->dosage) && !is_null($ddv->drugUom) && !is_null($ddv->drugType) && $ddv->drugType->type == 'TEST')
                                            @php 
                                                $drug = $ddv->drugName->drug_name.' - '.$ddv->drugDosageName->para_value .' - '.$ddv->dosage .''.$ddv->drugUom->para_value;
                                            @endphp
                                        @endif    
                                    @endforeach
                                    <p>{{ $drug != '' ? $drug : '---' }}</p>
                                @endif
                            </td>
                            <td style="border: 1px solid; width: 5%;">
                                <?php echo $pcv->studyNo->sponsorName->sponsor_name; ?>
                            </td>
                            <td style="border: 1px solid; width: 2%;">
                                <center>
                                    <?php echo $pcv->studyNo->no_of_subject; ?>
                                </center>
                            </td>
                            <td style="border: 1px solid; width: 2%;">
                                <center>
                                    <?php echo $pcv->studyNo->no_of_male_subjects; ?>
                                </center>
                            </td>
                            <td style="border: 1px solid; width: 2%;">
                                <center>
                                    <?php echo $pcv->studyNo->no_of_female_subjects; ?>
                                </center>
                            </td>
                            <td style="border: 1px solid; width: 8%;">
                                <center>
                                    <?php echo ((!is_null($pcv->studyNo)) && ($pcv->studyNo->tentative_clinical_date != '')) ? date('d M Y', strtotime($pcv->studyNo->tentative_clinical_date)) : '---' ;?>
                                </center>
                            </td>
                            @if($pcv->dcgi_approval_noc_tl != '')
                                <td style="border: 1px solid; width: 10%;">
                                    <center>
                                        <?php echo (($pcv->dcgi_approval_noc_tl != '')) ? $pcv->dcgi_approval_noc_tl : '---' ;?>
                                    </center>
                                </td>
                            @else
                                <td style="border: 1px solid; width: 10%;">
                                    <center>
                                        <?php echo (($pcv->dcgi_approval_only_tl != '')) ? $pcv->dcgi_approval_only_tl : '---' ;?>
                                    </center>
                                </td>
                            @endif
                            <td style="border: 1px solid; width: 10%;">
                                <center>
                                    <?php echo (($pcv->imp_availibility != '')) ? $pcv->imp_availibility : '---' ;?>
                                </center>
                            </td>
                            <td style="border: 1px solid; width: 10%;">
                                <center>
                                    <?php echo (($pcv->iec_approval != '')) ? $pcv->iec_approval : '---' ;?>
                                </center>
                            </td>

                            @if(($pcv->dcgi_submission_only_tl_stage != '' && $pcv->dcgi_submission_only_tl_stage == 'Red') || ($pcv->dcgi_approval_only_tl_stage != '' && $pcv->dcgi_approval_only_tl_stage == 'Red') || ($pcv->protocol_finalization_stage == 'Red') || ($pcv->md_mv_stage != '' && $pcv->md_mv_stage == 'Red') || ($pcv->iec_submission_stage == 'Red') || ($pcv->iec_approval_stage == 'Red') || ($pcv->imp_availibility_stage == 'Red') || ($pcv->md_mv_stage == 'Red'))
                                <td style="border: 1px solid; width: 10%;">
                                    <center>
                                        <?php echo 'Red';?>
                                    </center>
                                </td>
                           @elseif(($pcv->dcgi_submission_noc_tl_stage != '' && $pcv->dcgi_submission_noc_tl_stage == 'Red') || ($pcv->dcgi_approval_noc_tl_stage != '' && $pcv->dcgi_approval_noc_tl_stage == 'Red') || ($pcv->md_mv_stage != '' && $pcv->md_mv_stage == 'Red') || ($pcv->protocol_finalization_stage == 'Red') || ($pcv->iec_submission_stage == 'Red') || ($pcv->iec_approval_stage == 'Red') || ($pcv->imp_availibility_stage == 'Red'))
                                <td style="border: 1px solid; width: 10%;">
                                    <center>
                                        <?php echo 'Red';?>
                                    </center>
                                </td>
                            @elseif(($pcv->dcgi_submission_only_tl_stage != '' && $pcv->dcgi_submission_only_tl_stage == 'Yellow') || ($pcv->dcgi_approval_only_tl_stage != '' && $pcv->dcgi_approval_only_tl_stage == 'Yellow') || ($pcv->protocol_finalization_stage == 'Yellow') || ($pcv->md_mv_stage != '' && $pcv->md_mv_stage == 'Yellow') || ($pcv->iec_submission_stage == 'Yellow') || ($pcv->iec_approval_stage == 'Yellow') || ($pcv->imp_availibility_stage == 'Yellow') || ($pcv->md_mv_stage == 'Yellow'))
                                <td style="border: 1px solid; width: 10%;">\
                                    <center>
                                        <?php echo 'Yellow';?>
                                    </center>
                                </td>
                            @elseif(($pcv->dcgi_submission_noc_tl_stage != '' && $pcv->dcgi_submission_noc_tl_stage == 'Yellow') || ($pcv->dcgi_approval_noc_tl_stage != '' && $pcv->dcgi_approval_noc_tl_stage == 'Yellow') || ($pcv->md_mv_stage != '' && $pcv->md_mv_stage == 'Yellow') || ($pcv->protocol_finalization_stage == 'Yellow') || ($pcv->iec_submission_stage == 'Yellow') || ($pcv->iec_approval_stage == 'Yellow') || ($pcv->imp_availibility_stage == 'Yellow'))
                                <td style="border: 1px solid; width: 10%;">
                                    <center>
                                        <?php echo 'Yellow' ;?>
                                    </center>
                                </td>
                            @else
                                <td style="border: 1px solid; width: 10%;">
                                    <center>
                                        <?php echo 'Green';?>
                                    </center>
                                </td>
                            @endif

                        </tr>
                    </center>
                @endforeach
            @endif
            <tr>
                <th style="width: 41%; column-span:5; border: 1px solid;">
                    <b>
                        Total
                    </b>
                </th>
                <th style="width: 2%; column-span:1; border: 1px solid;">
                    <b>
                        <?php echo $crtWeekTotal; ?>
                    </b>
                </th>
                <th style="width: 2%; column-span:1; border: 1px solid;">
                    <b>
                        <?php echo $crtWeekMale; ?>
                    </b>
                </th>
                <th style="width: 2%; column-span:1; border: 1px solid;">
                    <b>
                        <?php echo $crtWeekFemale; ?>
                    </b>
                </th>
                <th style="color: blue; width: 8%; column-span:1; border: 1px solid;">
                    <b>
                        
                    </b>
                </th>
                <th style="color: blue; width: 10%; column-span:1; border: 1px solid;">
                    <b>
                        
                    </b>
                </th>
                <th style="color: blue; width: 10%; column-span:1; border: 1px solid;">
                    <b>
                        
                    </b>
                </th>
                <th style="color: blue; width: 10%; column-span:1; border: 1px solid;">
                    <b>
                        
                    </b>
                </th>
                <th style="color: blue; width: 10%; column-span:1; border: 1px solid;">
                    <b>
                        
                    </b>
                </th>
            </tr>
            
        </table>
        <br>
        <br>
    @else
        <table style="border-collapse: collapse; border-spacing: 0; width: 100%; border: 1px solid;">
            <tr>
                <th style="color: blue;">
                    No planned studies on current week
                </th>
            </tr>
        </table>
    @endif

    @if(!is_null($nextWeek))
        <table style="border-collapse: collapse; border-spacing: 0; width: 100%; border: 1px solid;">
            <tr>
                <th style="color: blue; width: 100%; column-span:13;">
                    <b>
                        List of studies planned for next week
                    </b>
                </th>
            </tr>
            <tr style="border: 1px solid;">
                <center>
                    <th style="border: 1px solid;">#</th>
                    <th style="border: 1px solid;">Site</th>
                    <th style="border: 1px solid;">Study No</th>
                    <th style="border: 1px solid;">Drug name</th>
                    <th style="border: 1px solid;">Sponsor</th>
                    <th style="border: 1px solid;">Subjects</th>
                    <th style="border: 1px solid;">Male</th>
                    <th style="border: 1px solid;">Female</th>
                    <th style="border: 1px solid;">Tentative Clinical Date</th>
                    <th style="border: 1px solid;">BENOC Actual Approval</th>
                    <th style="border: 1px solid;">Tentative IP Status</th>
                    <th style="border: 1px solid;">IEC Approval</th>
                    <th style="border: 1px solid;">System Generated Status</th>
                </center>
            </tr>
        
            @if(!is_null($nextWeek))
                @foreach($nextWeek as $pnk => $pnv)
                    <center>
                        <tr style="border: 1px solid;">
                            <td style="border: 1px solid; width: 2%;">
                                <center>
                                    <?php echo $loop->iteration; ?>
                                </center>
                            </td>
                            <td style="border: 1px solid; width: 5%;">
                                <?php echo $pnv->studyNo->crLocationName->location_name; ?>
                            </td>
                            <td style="border: 1px solid; width: 10%;">
                                <?php echo $pnv->studyNo->study_no; ?>
                            </td>

                            <td style="border: 1px solid; width: 24%;">
                                @if(!is_null($pnv->studyNo) && ($pnv->studyNo->drugDetails)) 
                                    @php $drug = ''; @endphp
                                    @foreach($pnv->studyNo->drugDetails as $ddk => $ddv)
                                        @if(!is_null($ddv->drugName) && !is_null($ddv->drugDosageName) && !is_null($ddv->dosage) && !is_null($ddv->drugUom) && !is_null($ddv->drugType) && $ddv->drugType->type == 'TEST')
                                            @php 
                                                $drug = $ddv->drugName->drug_name.' - '.$ddv->drugDosageName->para_value .' - '.$ddv->dosage .''.$ddv->drugUom->para_value;
                                            @endphp
                                        @endif    
                                    @endforeach
                                    <p>{{ $drug != '' ? $drug : '---' }}</p>
                                @endif
                            </td>
                            <td style="border: 1px solid; width: 5%;">
                                <?php echo $pnv->studyNo->sponsorName->sponsor_name; ?>
                            </td>
                            <td style="border: 1px solid; width: 2%;">
                                <center>
                                    <?php echo $pnv->studyNo->no_of_subject; ?>
                                </center>
                            </td>
                            <td style="border: 1px solid; width: 2%;">
                                <center>
                                    <?php echo $pnv->studyNo->no_of_male_subjects; ?>
                                </center>
                            </td>
                            <td style="border: 1px solid; width: 2%;">
                                <center>
                                    <?php echo $pnv->studyNo->no_of_female_subjects; ?>
                                </center>
                            </td>
                            <td style="border: 1px solid; width: 8%;">
                                <center>
                                    <?php echo ((!is_null($pnv->studyNo)) && ($pnv->studyNo->tentative_clinical_date != '')) ? date('d M Y', strtotime($pnv->studyNo->tentative_clinical_date)) : '---' ;
                                    ?>
                                </center>
                            </td>
                            @if($pnv->dcgi_approval_noc_tl != '')
                                <td style="border: 1px solid; width: 10%;">
                                    <center>
                                        <?php echo (($pnv->dcgi_approval_noc_tl != '')) ? $pnv->dcgi_approval_noc_tl : '---' ;?>
                                    </center>
                                </td>
                            @else
                                <td style="border: 1px solid; width: 10%;">
                                    <center>
                                        <?php echo (($pnv->dcgi_approval_only_tl != '')) ? $pnv->dcgi_approval_only_tl : '---' ;?>
                                    </center>
                                </td>
                            @endif
                            <td style="border: 1px solid; width: 10%;">
                                <center>
                                    <?php echo (($pnv->imp_availibility != '')) ? $pnv->imp_availibility : '---' ;?>
                                </center>
                            </td>
                            <td style="border: 1px solid; width: 10%;">
                                <center>
                                    <?php echo (($pnv->iec_approval != '')) ? $pnv->iec_approval : '---' ;?>
                                </center>
                            </td>

                            @if(($pnv->dcgi_submission_only_tl_stage != '' && $pnv->dcgi_submission_only_tl_stage == 'Red') || ($pnv->dcgi_approval_only_tl_stage != '' && $pnv->dcgi_approval_only_tl_stage == 'Red') || ($pnv->protocol_finalization_stage == 'Red') || ($pnv->md_mv_stage != '' && $pnv->md_mv_stage == 'Red') || ($pnv->iec_submission_stage == 'Red') || ($pnv->iec_approval_stage == 'Red') || ($pnv->imp_availibility_stage == 'Red') || ($pnv->md_mv_stage == 'Red'))
                                <td style="border: 1px solid; width: 10%;">
                                    <center>
                                        <?php echo 'Red';?>
                                    </center>
                                </td>
                            @elseif(($pnv->dcgi_submission_noc_tl_stage != '' && $pnv->dcgi_submission_noc_tl_stage == 'Red') || ($pnv->dcgi_approval_noc_tl_stage != '' && $pnv->dcgi_approval_noc_tl_stage == 'Red') || ($pnv->md_mv_stage != '' && $pnv->md_mv_stage == 'Red') || ($pnv->protocol_finalization_stage == 'Red') || ($pnv->iec_submission_stage == 'Red') || ($pnv->iec_approval_stage == 'Red') || ($pnv->imp_availibility_stage == 'Red'))
                                <td style="border: 1px solid; width: 10%;">
                                    <center>
                                        <?php echo 'Red';?>
                                    </center>
                                </td>
                            @elseif(($pnv->dcgi_submission_only_tl_stage != '' && $pnv->dcgi_submission_only_tl_stage == 'Yellow') || ($pnv->dcgi_approval_only_tl_stage != '' && $pnv->dcgi_approval_only_tl_stage == 'Yellow') || ($pnv->protocol_finalization_stage == 'Yellow') || ($pnv->md_mv_stage != '' && $pnv->md_mv_stage == 'Yellow') || ($pnv->iec_submission_stage == 'Yellow') || ($pnv->iec_approval_stage == 'Yellow') || ($pnv->imp_availibility_stage == 'Yellow') || ($pnv->md_mv_stage == 'Yellow'))
                                <td style="border: 1px solid; width: 10%;">
                                    <center>
                                        <?php echo 'Yellow';?>
                                    </center>
                                </td>
                            @elseif(($pnv->dcgi_submission_noc_tl_stage != '' && $pnv->dcgi_submission_noc_tl_stage == 'Yellow') || ($pnv->dcgi_approval_noc_tl_stage != '' && $pnv->dcgi_approval_noc_tl_stage == 'Yellow') || ($pnv->md_mv_stage != '' && $pnv->md_mv_stage == 'Yellow') || ($pnv->protocol_finalization_stage == 'Yellow') || ($pnv->iec_submission_stage == 'Yellow') || ($pnv->iec_approval_stage == 'Yellow') || ($pnv->imp_availibility_stage == 'Yellow'))
                                <td style="border: 1px solid; width: 10%;">
                                    <center>
                                        <?php echo 'Yellow' ;?>
                                    </center>
                                </td>
                            @else
                                <td style="border: 1px solid; width: 10%;">
                                    <center>
                                        <?php echo 'Green';?>
                                    </center>
                                </td>
                            @endif

                        </tr>
                    </center>
                @endforeach
            @endif
            <tr>
                <th style="width: 41%; column-span:5; border: 1px solid;">
                    <b>
                        Total
                    </b>
                </th>
                <th style="width: 2%; column-span:1; border: 1px solid;">
                    <b>
                        <?php echo $nxtWeekTotal; ?>
                    </b>
                </th>
                <th style="width: 2%; column-span:1; border: 1px solid;">
                    <b>
                        <?php echo $nxtWeekMale; ?>
                    </b>
                </th>
                <th style="width: 2%; column-span:1; border: 1px solid;">
                    <b>
                        <?php echo $nxtWeekFemale; ?>
                    </b>
                </th>
                <th style="color: blue; width: 8%; column-span:1; border: 1px solid;">
                    <b>
                        
                    </b>
                </th>
                <th style="color: blue; width: 10%; column-span:1; border: 1px solid;">
                    <b>
                        
                    </b>
                </th>
                <th style="color: blue; width: 10%; column-span:1; border: 1px solid;">
                    <b>
                        
                    </b>
                </th>
                <th style="color: blue; width: 10%; column-span:1; border: 1px solid;">
                    <b>
                        
                    </b>
                </th>
                <th style="color: blue; width: 10%; column-span:1; border: 1px solid;">
                    <b>
                        
                    </b>
                </th>
            </tr>
            
        </table>
        <br>
        <br>
    @else
        <table style="border-collapse: collapse; border-spacing: 0; width: 100%; border: 1px solid;">
            <tr>
                <th style="color: blue;">
                    No planned studies on next week
                </th>
            </tr>
        </table>
    @endif

    <p>
        <b>Note:</b> Please do not reply to this email, this is system generated email from Study Management System.
    </p>
    
    <div class="form-group">
        <h4>Study Management System</h4>
    </div>
        
</div>
