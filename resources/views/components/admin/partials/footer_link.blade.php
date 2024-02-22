<script src="{{ asset('js/module/vendor.min.js') }}"></script>
@if(\Route::is('admin.dashboard') || Route::is('admin.changeDashboardView') || Route::is('admin.teamMemberList'))
    <script src="{{ asset('js/module/elephant.min.js') }}"></script>
@endif
<script src="{{ asset('libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('libs/jqueryui/1.12.1/jquery-ui.min.js') }}" ></script>
<script src="{{ asset('libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('libs/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('js/pages/dashboard.init.js') }}"></script>


<!-- Dropify js -->
<script src="{{ asset('libs/dropify/dist/js/dropify.js') }}"></script>
<script src="{{ asset('libs/dropify/dist/js/dropify.min.js') }}"></script>

<!-- Required datatable js -->
<script src="{{ asset('js/pages/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/pages/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<!-- Buttons examples -->
<script src="{{ asset('js/pages/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('js/pages/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>

<script src="{{ asset('js/pages/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('js/pages/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('js/pages/pdfmake/build/vfs_fonts.js') }}"></script>

<script src="{{ asset('js/pages/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('js/pages/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('js/pages/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
<!-- Responsive examples -->
<script src="{{ asset('js/pages/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('js/pages/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
<!-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script> -->

<script src="{{ asset('js/pages/datatables.init.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>

<script src="{{ asset('libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>

<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/validation.js') }}"></script>

<script src="{{ asset('libs/toastr/latest/toastr.min.js') }}"></script>
<script src="{{ asset('templateEditor/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('js/pages/jquery.passwordRequirements.min.js') }}"></script>
<script src="{{ asset('libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>

<!-- Team Member js -->
@if(\Route::is('admin.addTeamMember') || Route::is('admin.editTeamMember') || Route::is('admin.teamMemberList'))
	<script src="{{ asset('js/module/team_member.js') }}"></script>
@endif

<!-- Role js -->
@if(\Route::is('admin.addRole') || Route::is('admin.editRole') || Route::is('admin.roleList'))
	<script src="{{ asset('js/module/role.js') }}"></script>
@endif

<!-- Activity Master js -->
@if (\Route::is('admin.activityMasterList') || Route::is('admin.addActivityMaster') || Route::is('admin.editActivityMaster'))
	<script type="text/javascript" src="{{ asset('js/module/activity_master.js') }}"></script>
@endif

<!-- Study js -->
@if (\Route::is('admin.studyList') || Route::is('admin.addStudy') || Route::is('admin.addCopyStudy') || Route::is('admin.editStudy'))
	<script type="text/javascript" src="{{ asset('js/module/study.js') }}"></script>
@endif

<!-- Study Schedule js -->
@if (\Route::is('admin.studyScheduleList') || Route::is('admin.addStudySchedule') || Route::is('admin.addStudyScheduleDate') || Route::is('admin.addStudyScheduleActivityStatus') || Route::is('admin.studyActivityMonitoringList') || Route::is('admin.addScheduleDelayModal') || Route::is('admin.saveScheduleDelayRemark') || Route::is('admin.studyScheduleStatus') || Route::is('admin.editStudySchedule') || Route::is('admin.addCopyActivityModal'))
	<script type="text/javascript" src="{{ asset('js/module/study_schedule.js') }}"></script>
@endif

<!-- Drug Master js -->
@if (\Route::is('admin.drugMasterList') || Route::is('admin.addDrugMaster') || Route::is('admin.editDrugMaster'))
	<script type="text/javascript" src="{{ asset('js/module/drug_master.js') }}"></script>
@endif

<!-- Dashboard js -->
@if(\Route::is('admin.dashboard') || Route::is('admin.changeDashboardView') || Route::is('admin.teamMemberList'))
	<script type="text/javascript" src="{{ asset('js/module/dashboard.js') }}"></script>
@endif

<!-- Para Master js -->
@if (\Route::is('admin.paraMasterList') || Route::is('admin.addParaMaster') || Route::is('admin.editParaMaster') || Route::is('admin.paraCodeMasterList'))
	<script type="text/javascript" src="{{ asset('js/module/para_master.js') }}"></script>
@endif

<!-- Sponsor Master js -->
@if (\Route::is('admin.sponsorMasterList') || Route::is('admin.addSponsorMaster') || Route::is('admin.editSponsorMaster'))
	<script type="text/javascript" src="{{ asset('js/module/sponsor_master.js') }}"></script>
@endif

<!-- Holiday Master js -->
@if (\Route::is('admin.holidayMasterList') || Route::is('admin.addHolidayMaster') || Route::is('admin.editHolidayMaster'))
	<script type="text/javascript" src="{{ asset('js/module/holiday_master.js') }}"></script>
@endif

<!-- Location Master js -->
@if (\Route::is('admin.locationMasterList') || Route::is('admin.addLocationMaster') || Route::is('admin.editLocationMaster') )
	<script type="text/javascript" src="{{ asset('js/module/location_master.js') }}"></script>
@endif

<!-- Activity Slotting Master js -->
@if (\Route::is('admin.slaActivityMasterList') || Route::is('admin.addSlaActivityMaster') || Route::is('admin.editSlaActivityMaster'))
    <script type="text/javascript" src="{{ asset('js/module/sla_activity_master.js') }}"></script>
@endif

<!-- Activity Slotting Master js -->
@if (\Route::is('admin.reasonMasterList') || Route::is('admin.addReasonMaster') || Route::is('admin.editReasonMaster'))
    <script type="text/javascript" src="{{ asset('js/module/reason_master.js') }}"></script>
@endif

<!-- Study Master js -->

    <script type="text/javascript" src="{{ asset('js/module/study_master.js') }}"></script>

<!-- Dropify & Select2 js -->
<script type="text/javascript">
	$('.dropify').dropify();
	$('.select2').select2();
	$("#description").maxlength({ warningClass: "badge bg-info", limitReachedClass: "badge bg-warning" });
</script>

<!-- <script src="https://code.jquery.com/jquery-1.10.2.js"></script> -->
<!-- <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script> -->
<script type="text/javascript" src="{{ asset('js/pages/jquery-ui.js') }}"></script>

<!-- Pre study projection js -->
@if (\Route::is('admin.preStudyProjectionList') || Route::is('admin.preStudyProjectionStatus') || Route::is('admin.getPreStudyProjectionList'))
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->
    <script type="text/javascript" src="{{ asset('js/pages/jquery351.min.js') }}"></script>
    <!-- Dev express data grid js -->
    <script type="text/javascript" src="{{ asset('js/pages/devexpress/js/dx.all.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/pages/devexpress/js/exceljs.min.js') }}"></script>
    <!-- <script type="text/javascript" src="https://cdn3.devexpress.com/jslib/23.1.5/js/dx.all.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.0.1/exceljs.min.js"></script> -->
    <script type="text/javascript" src="{{ asset('js/module/pre_study_projection.js') }}"></script>
@endif

<!-- Activity Metadata js -->
@if (\Route::is('admin.activityMetadataList') || Route::is('admin.addActivityMetadata') || Route::is('admin.allActivityMetadataList'))
    <script type="text/javascript" src="{{ asset('js/module/activity_metadata.js') }}"></script>
@endif

<script type="text/javascript">
	$(".datepickerStyle").datepicker({
        dateFormat: "dd-mm-yy",
        showOtherMonths: true,
        selectOtherMonths: true,
        autoclose: true,
        changeMonth: true,
        changeYear: true,
    });

    $(".scheduleDatePickerStyle").datepicker({
        dateFormat: "dd M yy",
        showOtherMonths: true,
        selectOtherMonths: true,
        autoclose: true,
        changeMonth: true,
        changeYear: true,
    });

    $(document).ready(function(){
        $.each($('.scheduleDatepicker'), function() {
            var mindate = $(this).attr('data-startdate');
            $(this).datepicker({
                dateFormat: "dd M yy",
                minDate: new Date(mindate),
                maxDate: new Date(),
            });
        });
    });

    /*$(".scheduleDatepicker").datepicker({
        dateFormat: "yy-mm-dd",
        showOtherMonths: true,
        selectOtherMonths: true,
        autoclose: true,
        changeMonth: true,
        changeYear: true,
    });*/
</script>

<!-- <script>
    $(function() {
        $(".preload").fadeOut(2000, function() {
            $(".page-content").fadeIn(1);        
        });
    });
</script> -->