<div>
    
    <div class="form-group">
        <p>Hello <?php echo $data['name']; ?>,</p>
    </div>

    <div class="form-group">
        <p style="color: blue;">
            <b>
                Kindly note that below study's scheduled have been created. Please update milestone activities.
            </b>
        </p>
    </div>

    <table style="border-collapse: collapse; border-spacing: 0; width: 100%; border: 1px solid;">
        
        <tr style="border: 1px solid;">
            <center>
                <th style="border: 1px solid;">Study No</th>
                <th style="border: 1px solid;">Project Manager</th>
            </center>
        </tr>
    
        
        <center>
            <tr style="border: 1px solid;">
                <td style="border: 1px solid;"><?php echo $data['studyNo']; ?></td>
                <td style="border: 1px solid;"><?php echo $data['projectManager']; ?></td>
            </tr>
        </center>

    </table>

    <p>
        <b>Note:</b> Please do not reply to this email, this is system generated email from Study Management System.
    </p>
    
    <div class="form-group">
        <h4>Study Management System</h4>
    </div>
        
</div>