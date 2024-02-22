<div>
    
    <div class="form-group">
        <p>Hello <?php echo $name; ?>,</p>
    </div>

    <div class="form-group">
        <p>
            <b>
                Below are the list of
            </b> 
            <b style="color: blue;"> 
                Planned 
            </b>
            <b> 
                & 
            </b>
            <b style="color: red;">
                Delay
            </b>
            <b>
                activities, kindly take necessary action.
            </b>
        </p>
    </div>

    @if(count($daily)>0)
        <table style="border-collapse: collapse; border-spacing: 0; width: 85%; border: 1px solid;">
            <tr>
                <th style="color: blue; column-span:5;">
                    List of planned activities
                </th>
            </tr>
            <tr style="border: 1px solid;">
                <center>
                    <th style="border: 1px solid;">Sr.No</th>
                    <th style="border: 1px solid;">Study No</th>
                    <th style="border: 1px solid;">Activity Name</th>
                    <th style="border: 1px solid;">Schedule Start Date</th>
                    <th style="border: 1px solid;">Schedule End Date</th>
                </center>
            </tr>
        
            @if(!is_null($daily))
                @foreach($daily as $dk => $dv)
                    <center>
                        <tr style="border: 1px solid;">
                            <td style="border: 1px solid;"><?php echo $loop->iteration; ?></td>
                            <td style="border: 1px solid;"><?php echo $dv['study_no']; ?></td>
                            <td style="border: 1px solid;"><?php echo $dv['activity_name']; ?></td>
                            <td style="border: 1px solid;"><?php echo date('d M Y', strtotime($dv['scheduled_start_date'])); ?></td>
                            <td style="border: 1px solid;"><?php echo date('d M Y', strtotime($dv['scheduled_end_date'])); ?></td>
                        </tr>
                    </center>
                @endforeach
            @endif
            
        </table>
        <br>
        <br>
    @else
        <table style="border-collapse: collapse; border-spacing: 0; width: 85%; border: 1px solid;">
            <tr>
                <th style="color: blue;">
                    No planned activities today
                </th>
            </tr>
        </table>
    @endif<br><br>

    @if(count($data)>0)
        <table style="border-collapse: collapse; border-spacing: 0; width: 85%; border: 1px solid;">
            <tr>
                <th style="color: red; column-span:5;">
                    List of delay activities
                </th>
            </tr>
            <tr style="border: 1px solid;">
                <center>
                    <th style="border: 1px solid;">Sr.No</th>
                    <th style="border: 1px solid;">Study No</th>
                    <th style="border: 1px solid;">Activity Name</th>
                    <th style="border: 1px solid;">Schedule Start Date</th>
                    <th style="border: 1px solid;">Schedule End Date</th>
                </center>
            </tr>
        
            @if(!is_null($data))
                @foreach($data as $dk => $dv)
                    <center>
                        <tr style="border: 1px solid;">
                            <td style="border: 1px solid;"><?php echo $loop->iteration; ?></td>
                            <td style="border: 1px solid;"><?php echo $dv['study_no']; ?></td>
                            <td style="border: 1px solid;"><?php echo $dv['activity_name']; ?></td>
                            <td style="border: 1px solid;"><?php echo date('d M Y', strtotime($dv['scheduled_start_date'])); ?></td>
                            <td style="border: 1px solid;"><?php echo date('d M Y', strtotime($dv['scheduled_end_date'])); ?></td>
                        </tr>
                    </center>
                @endforeach
            @endif
            
        </table>
    @else 
        <table style="border-collapse: collapse; border-spacing: 0; width: 85%; border: 1px solid;">
            <tr>
                <th style="color: red;">
                    No delay activities
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