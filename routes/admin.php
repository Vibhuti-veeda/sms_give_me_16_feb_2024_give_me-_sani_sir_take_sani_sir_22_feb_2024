<?php

	// Authentication admin Login Routes
	Route::get('login', 'Auth\LoginController@showLoginForm')->name('admin.login');
	Route::post('post-login', 'Auth\LoginController@postlogin')->name('admin.postlogin');
	Route::get('logout/{id?}', 'Auth\LoginController@logout')->name('admin.logout');

	//forget and reset password
	Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('admin.auth.password.reset');
	Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('admin.passwordemail');
	Route::get('password/reset/{token?}', 'Auth\ResetPasswordController@showResetForm')->name('admin.auth.password.reset');
	Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('admin.resetpassword');

	//Dashboard Route....
	Route::get('/', 'AdminController@index')->name('admin.dashboard');
	Route::post('/dashboard/view/change-dashboard-view', 'AdminController@changeDashboardView')->name('admin.changeDashboardView');

	// Change Password Route
	Route::get('/change-admin-password', 'AdminController@changeAdminPassword')->name('admin.changeAdminPassword');
	Route::post('/update-admin-password', 'AdminController@updateAdminPassword')->name('admin.updateAdminPassword');

	// Profile Route
	Route::get('/editprofile', 'AdminController@editProfile')->name('admin.editProfile');
	Route::post('/update-profile', 'AdminController@updateProfile')->name('admin.updateProfile');

	// Role Route
	Route::group(['prefix' => 'role'], function () {
		Route::get('/view/role-list', 'RoleController@roleList')->name('admin.roleList');
		Route::get('/add/add-role', 'RoleController@addRole')->name('admin.addRole');
		Route::post('/add/save-role', 'RoleController@saveRole')->name('admin.saveRole');
		Route::get('/edit/edit-role/{id}', 'RoleController@editRole')->name('admin.editRole');
		Route::post('/edit/update-role', 'RoleController@updateRole')->name('admin.updateRole');
		Route::get('/delete/delete-role/{id}', 'RoleController@deleteRole')->name('admin.deleteRole');
		Route::post('/view/change-role-status', 'RoleController@changeRoleStatus')->name('admin.changeRoleStatus');
		Route::post('/view/check-role-exists', 'RoleController@checkRoleExist')->name('admin.checkRoleExist');
		Route::post('/view/module-access-change', 'RoleController@moduleAccessChange')->name('admin.moduleAccessChange');
		Route::post('/view/session-store', 'RoleController@sessionStore')->name('admin.sessionStore');
		Route::post('/view/get-removed-temp-array', 'RoleController@removeTempArray')->name('admin.removeTempArray');
		Route::get('/view/export-roles', 'RoleController@exportRoles')->name('admin.exportRoles');
	});

	// Team Member Route
	Route::group(['prefix' => 'team-member'], function () {
		Route::match(['get', 'post'],'/view/team-member-list', 'TeamMemberController@teamMemberList')->name('admin.teamMemberList');
		Route::get('/add/add-team-member', 'TeamMemberController@addTeamMember')->name('admin.addTeamMember');
		Route::post('/add/save-team-member', 'TeamMemberController@saveTeamMember')->name('admin.saveTeamMember');
		Route::get('/edit/edit-team-member/{id}', 'TeamMemberController@editTeamMember')->name('admin.editTeamMember');
		Route::post('/edit/update-team-member', 'TeamMemberController@updateTeamMember')->name('admin.updateTeamMember');
		Route::get('/delete/delete-team-member/{id}', 'TeamMemberController@deleteTeamMember')->name('admin.deleteTeamMember');
		Route::post('/view/change-team-member-status', 'TeamMemberController@changeTeamMemberStatus')->name('admin.changeTeamMemberStatus');
		Route::post('/view/check-team-member-email-exists', 'TeamMemberController@checkTeamMemberEmailExist')->name('admin.checkTeamMemberEmailExist');
		Route::get('/view/export-team-members', 'TeamMemberController@exportTeamMembers')->name('admin.exportTeamMembers');
	});

	// Activity Master Route
	Route::group(['prefix' => 'activity-master'], function () {
		Route::get('/view/activity-master-list', 'Master\ActivityMasterController@activityMasterList')->name('admin.activityMasterList');
		Route::get('/add/add-activity-master', 'Master\ActivityMasterController@addActivityMaster')->name('admin.addActivityMaster');
		Route::post('/add/save-activity-master', 'Master\ActivityMasterController@saveActivityMaster')->name('admin.saveActivityMaster');
		Route::get('/edit/edit-activity-master/{id}', 'Master\ActivityMasterController@editActivityMaster')->name('admin.editActivityMaster');
		Route::post('/edit/update-activity-master', 'Master\ActivityMasterController@updateActivityMaster')->name('admin.updateActivityMaster');
		Route::get('/delete/delete-activity-master/{id}', 'Master\ActivityMasterController@deleteActivityMaster')->name('admin.deleteActivityMaster');
		Route::post('/view/change-activity-master-status', 'Master\ActivityMasterController@changeActivityMasterStatus')->name('admin.changeActivityMasterStatus');
		Route::get('/view/export-activity-master', 'Master\ActivityMasterController@exportActivityMaster')->name('admin.exportActivityMaster');
	});

	// Study master Route
	Route::group(['prefix' => 'study-master'], function () {
		Route::match(['get', 'post'],'/view/study-list', 'Study\StudyController@studyList')->name('admin.studyList');
		Route::get('/add/add-study', 'Study\StudyController@addStudy')->name('admin.addStudy');
		Route::post('/add/save-study', 'Study\StudyController@saveStudy')->name('admin.saveStudy');
		Route::get('/edit/edit-study/{id}', 'Study\StudyController@editStudy')->name('admin.editStudy');
		Route::post('/edit/update-study', 'Study\StudyController@updateStudy')->name('admin.updateStudy');
		Route::post('/delete/delete-study', 'Study\StudyController@deleteStudy')->name('admin.deleteStudy');
		Route::post('/view/change-study-status', 'Study\StudyController@changeStudyStatus')->name('admin.changeStudyStatus');
		Route::post('/view/select-drug-details', 'Study\StudyController@selectDrugDetails')->name('admin.selectDrugDetails');
		Route::post('/view/select-clinical-ward-location', 'Study\StudyController@selectClinicalWardLocation')->name('admin.selectClinicalWardLocation');
		Route::get('/add/copy-study/{id}', 'Study\StudyController@copyStudy')->name('admin.copyStudy');
		Route::get('/add/add-copy-study/{id}', 'Study\StudyController@addCopyStudy')->name('admin.addCopyStudy');
		Route::post('/view/check-study-no-exist', 'Study\StudyController@checkStudyNoExist')->name('admin.checkStudyNoExist');
		Route::post('/view/study-result', 'Study\StudyController@studyResult')->name('admin.studyResult');
		Route::post('/view/study-status', 'Study\StudyController@studyStatus')->name('admin.studyStatus');
		Route::post('/view/study-projected', 'Study\StudyController@studyProjected')->name('admin.studyProjected');
		Route::post('/view/study-tentative-clinical-date', 'Study\StudyController@studyTentativeClinicalDate')->name('admin.studyTentativeClinicalDate');
		Route::post('/view/pre-study-projection-status', 'Study\StudyController@preStudyProjectionStatus')->name('admin.preStudyProjectionStatus');
		Route::get('/view/export-study', 'Study\StudyController@exportStudy')->name('admin.exportStudy');
	});

	// Study Schedule Route
    Route::group(['prefix' => 'study-schedule'], function () {
        Route::get('/view/study-schedule-list', 'Study\StudyScheduleController@studyScheduleList')->name('admin.studyScheduleList');
        Route::get('/add/add-study-schedule', 'Study\StudyScheduleController@addStudySchedule')->name('admin.addStudySchedule');
        Route::post('/add/save-study-schedule', 'Study\StudyScheduleController@saveStudySchedule')->name('admin.saveStudySchedule');
        Route::post('/view/change-study-schedule-status', 'Study\StudyScheduleController@changeStudyScheduleStatus')->name('admin.changeStudyScheduleStatus');
        Route::get('/edit/edit-study-schedule/{id}', 'Study\StudyScheduleController@editStudySchedule')->name('admin.editStudySchedule');
		Route::post('/edit/update-study-schedule', 'Study\StudyScheduleController@updateStudySchedule')->name('admin.updateStudySchedule'); 
        Route::get('/delete/delete-study-schedule/{id}', 'Study\StudyScheduleController@deleteStudySchedule')->name('admin.deleteStudySchedule');
        Route::get('/edit/add-study-schedule-date/{id}', 'Study\StudyScheduleController@addStudyScheduleDate')->name('admin.addStudyScheduleDate');
        Route::post('/view/change-schedule-date', 'Study\StudyScheduleController@changeScheduleDate')->name('admin.changeScheduleDate');
        Route::post('/add/save-study-schedule-date', 'Study\StudyScheduleController@saveStudyScheduleDate')->name('admin.saveStudyScheduleDate');
        Route::post('/add/save-schedule-delay-remark', 'Study\StudyScheduleController@saveScheduleDelayRemark')->name('admin.saveScheduleDelayRemark');
        Route::post('/view/change-schedule-update-date','Study\StudyScheduleController@addScheduleDelayModal')->name('admin.addScheduleDelayModal');
        Route::post('/view/change-required-days', 'Study\StudyScheduleController@changeRequiredDays')->name('admin.changeRequiredDays');
        Route::post('/view/add-schedule-remark-modal', 'Study\StudyScheduleController@addScheduleRemarkModal')->name('admin.addScheduleRemarkModal');
        Route::post('/view/change-milestone-activity', 'Study\StudyScheduleController@changeMilestoneActivity')->name('admin.changeMilestoneActivity');
        Route::post('/view/start-milestone-activity', 'Study\StudyScheduleController@startMilestoneActivity')->name('admin.startMilestoneActivity');
        Route::post('/view/end-milestone-activity', 'Study\StudyScheduleController@endMilestoneActivity')->name('admin.endMilestoneActivity');
        Route::post('/view/copy-study-activity', 'Study\StudyScheduleController@copyStudyActivity')->name('admin.copyStudyActivity');
        Route::post('/view/change-schedule-version-date', 'Study\StudyScheduleController@changeScheduleVersionDate')->name('admin.changeScheduleVersionDate');
        Route::post('/view/add-copy-activity-modal', 'Study\StudyScheduleController@addCopyActivityModal')->name('admin.addCopyActivityModal');
		Route::get('/view/export-study-schedule', 'Study\StudyScheduleController@exportStudySchedule')->name('admin.exportStudySchedule');

    });

    // Study Schedule Monitoring Route
    Route::group(['prefix' => 'study-schedule-monitoring'], function () {
        Route::match(['get', 'post'],'/view/study-schedule-monitoring-list', 'Study\StudyScheduleMonitoringController@studyScheduleMonitoringList')->name('admin.studyScheduleMonitoringList');  
        Route::get('/add/study-schedule-status/{id}', 'Study\StudyScheduleMonitoringController@studyScheduleStatus')->name('admin.studyScheduleStatus');
        Route::get('/view/study-schedule-activity-status', 'Study\StudyScheduleMonitoringController@studyScheduleActivityStatus')->name('admin.studyScheduleActivityStatus');
		Route::get('/view/add-study-schedule-activity-status/{id}', 'Study\StudyScheduleMonitoringController@addStudyScheduleActivityStatus')->name('admin.addStudyScheduleActivityStatus');
        Route::post('/view/save-study-schedule-activity-status', 'Study\StudyScheduleMonitoringController@saveStudyScheduleActivityStatus')->name('admin.saveStudyScheduleActivityStatus');
        Route::match(['get', 'post'],'/view/study-activity-monitoring-list', 'Study\StudyScheduleMonitoringController@studyActivityMonitoringList')->name('admin.studyActivityMonitoringList');
        Route::post('/view/study-details-modal', 'Study\StudyScheduleMonitoringController@studyDetailsModal')->name('admin.studyDetailsModal');
        Route::get('/view/study-schedule-actual-start-date-modal/{id}', 'Study\StudyScheduleMonitoringController@studyScheduleActualStartDateModal')->name('admin.studyScheduleActualStartDateModal');
        Route::post('/add/save-study-schedule-actual-start-date-modal', 'Study\StudyScheduleMonitoringController@saveStudyScheduleActualStartDateModal')->name('admin.saveStudyScheduleActualStartDateModal');
        Route::get('/view/study-schedule-actual-end-date-modal/{id}', 'Study\StudyScheduleMonitoringController@studyScheduleActualEndDateModal')->name('admin.studyScheduleActualEndDateModal');
        Route::post('/add/save-study-schedule-actual-end-date-modal', 'Study\StudyScheduleMonitoringController@saveStudyScheduleActualEndDateModal')->name('admin.saveStudyScheduleActualEndDateModal');
    });

	// Drug Master Route
	Route::group(['prefix' => 'drug-master'], function () {
		Route::get('/view/drug-master-list', 'Master\DrugMasterController@drugMasterList')->name('admin.drugMasterList');
		Route::get('/add/add-drug-master', 'Master\DrugMasterController@addDrugMaster')->name('admin.addDrugMaster');
		Route::post('/add/save-drug-master', 'Master\DrugMasterController@saveDrugMaster')->name('admin.saveDrugMaster');
		Route::get('/edit/edit-drug-master/{id}', 'Master\DrugMasterController@editDrugMaster')->name('admin.editDrugMaster');
		Route::post('/edit/update-drug-master', 'Master\DrugMasterController@updateDrugMaster')->name('admin.updateDrugMaster');
		Route::get('/delete/delete-drug-master/{id}', 'Master\DrugMasterController@deleteDrugMaster')->name('admin.deleteDrugMaster');
		Route::post('/view/change-drug-master-status', 'Master\DrugMasterController@changeDrugMasterStatus')->name('admin.changeDrugMasterStatus');
		Route::get('/view/export-drug-master', 'Master\DrugMasterController@exportDrugMaster')->name('admin.exportDrugMaster');

	});

	// Para Master Route
	Route::group(['prefix' => 'para-master'], function () {
		Route::get('/view/para-master-list', 'Master\ParaMasterController@paraMasterList')->name('admin.paraMasterList');
		Route::get('/add/add-para-master', 'Master\ParaMasterController@addParaMaster')->name('admin.addParaMaster');
		Route::post('/add/save-para-master', 'Master\ParaMasterController@saveParaMaster')->name('admin.saveParaMaster');
		Route::get('/edit/edit-para-master/{id}', 'Master\ParaMasterController@editParaMaster')->name('admin.editParaMaster');
		Route::post('/edit/update-para-master', 'Master\ParaMasterController@updateParaMaster')->name('admin.updateParaMaster');
		Route::get('/delete/delete-para-master/{id}', 'Master\ParaMasterController@deleteParaMaster')->name('admin.deleteParaMaster');
		Route::post('/view/change-para-master-status', 'Master\ParaMasterController@changeParaMasterStatus')->name('admin.changeParaMasterStatus');
		
		Route::get('/view/para-code-master-list/{id}', 'Master\ParaMasterController@paraCodeMasterList')->name('admin.paraCodeMasterList');
		Route::get('/add/add-para-code-master/{id}', 'Master\ParaMasterController@addParaCodeMaster')->name('admin.addParaCodeMaster');
		Route::post('/add/save-para-code-master', 'Master\ParaMasterController@saveParaCodeMaster')->name('admin.saveParaCodeMaster');
		Route::get('/edit/edit-para-code-master/{id}', 'Master\ParaMasterController@editParaCodeMaster')->name('admin.editParaCodeMaster');
		Route::post('/edit/update-para-code-master', 'Master\ParaMasterController@updateParaCodeMaster')->name('admin.updateParaCodeMaster');
		Route::get('/delete/delete-para-code-master/{para_id}/{id}', 'Master\ParaMasterController@deleteParaCodeMaster')->name('admin.deleteParaCodeMaster');
		Route::post('/view/change-para-code-master-status', 'Master\ParaMasterController@changeParaCodeMasterStatus')->name('admin.changeParaCodeMasterStatus');
		Route::get('/view/export-para-master', 'Master\ParaMasterController@exportParaMaster')->name('admin.exportParaMaster');
		Route::get('/view/export-para-code/{id}', 'Master\ParaMasterController@exportParaCode')->name('admin.exportParaCode');
	});

	// Sponsor Master Route
	Route::group(['prefix' => 'sponsor-master'], function () {
		Route::get('/view/sponsor-master-list', 'Master\SponsorMasterController@sponsorMasterList')->name('admin.sponsorMasterList');
		Route::get('/add/add-sponsor-master', 'Master\SponsorMasterController@addSponsorMaster')->name('admin.addSponsorMaster');
		Route::post('/add/save-sponsor-master', 'Master\SponsorMasterController@saveSponsorMaster')->name('admin.saveSponsorMaster');
		Route::get('/edit/edit-sponsor-master/{id}', 'Master\SponsorMasterController@editSponsorMaster')->name('admin.editSponsorMaster');
		Route::post('/edit/update-sponsor-master', 'Master\SponsorMasterController@updateSponsorMaster')->name('admin.updateSponsorMaster');
		Route::get('/delete/delete-sponsor-master/{id}', 'Master\SponsorMasterController@deleteSponsorMaster')->name('admin.deleteSponsorMaster');
		Route::post('/view/change-sponsor-master-status', 'Master\SponsorMasterController@changeSponsorMasterStatus')->name('admin.changeSponsorMasterStatus');
		Route::get('/view/export-sponsor-master', 'Master\SponsorMasterController@exportSponsorMaster')->name('admin.exportSponsorMaster');
	});

	// Holiday Master Route
	Route::group(['prefix' => 'holiday-master'], function () {
        Route::get('/view/holiday-master-list','Master\HolidayController@holidayMasterList')->name('admin.holidayMasterList');
        Route::get('/add/add-holiday-master','Master\HolidayController@addHolidayMaster')->name('admin.addHolidayMaster');
        Route::post('/add/save-holiday-master-list', 'Master\HolidayController@saveHolidayMaster')->name('admin.saveHolidayMaster');
        Route::get('/edit/edit-holiday-master/{id}', 'Master\HolidayController@editHolidayMaster')->name('admin.editHolidayMaster');
        Route::post('/edit/update-holiday-master', 'Master\HolidayController@updateHolidayMaster')->name('admin.updateHolidayMaster');
        Route::get('/delete/delete-holiday-master/{id}', 'Master\HolidayController@deleteHolidayMaster')->name('admin.deleteHolidayMaster');
        Route::post('/view/change-holiday-master-status', 'Master\HolidayController@changeHolidayMasterStatus')->name('admin.changeHolidayMasterStatus');
        Route::post('/view/check-holiday-master-date-exist', 'Master\HolidayController@checkHolidayMasterDateExist')->name('admin.checkHolidayMasterDateExist');
		Route::get('/view/export-holiday-master', 'Master\HolidayController@exportHolidayMaster')->name('admin.exportHolidayMaster');
	});

	// Location Master Route
	Route::group(['prefix' => 'location-master'], function () {      
        Route::get('/view/location-master-list','Master\LocationMasterController@locationMasterList')->name('admin.locationMasterList');
        Route::get('/add/add-location-master','Master\LocationMasterController@addLocationMaster')->name('admin.addLocationMaster');
        Route::post('/add/save-location-master-list', 'Master\LocationMasterController@saveLocationMaster')->name('admin.saveLocationMaster');
        Route::get('/edit/edit-location-master/{id}', 'Master\LocationMasterController@editLocationMaster')->name('admin.editLocationMaster');
        Route::post('/edit/update-location-master', 'Master\LocationMasterController@updateLocationMaster')->name('admin.updateLocationMaster');
        Route::get('/delete/delete-location-master/{id}', 'Master\LocationMasterController@deleteLocationMaster')->name('admin.deleteLocationMaster');
        Route::post('/view/change-location-master-status', 'Master\LocationMasterController@changeLocationMasterStatus')->name('admin.changeLocationMasterStatus');
		Route::get('/view/export-location-master', 'Master\LocationMasterController@exportLocationMaster')->name('admin.exportLocationMaster');
	});

	// Study master Route
	Route::group(['prefix' => 'study-master-data'], function () {
		Route::match(['get', 'post'],'/view/study-master-list', 'Study\StudyMasterController@studyMasterList')->name('admin.studyMasterList');
		Route::get('/view/export-study-master', 'Study\StudyMasterController@exportStudyMaster')->name('admin.exportStudyMaster');
	});

	Route::group(['prefix' => 'sla-activity-master'], function () {
	    Route::get('/view/sla-activity-master-list', 'Master\SlaActivityMasterController@slaActivityMasterList')->name('admin.slaActivityMasterList');
	    Route::get('/add/add-sla-activity-master', 'Master\SlaActivityMasterController@addSlaActivityMaster')->name('admin.addSlaActivityMaster');
	    Route::post('/add/save-sla-activity-master', 'Master\SlaActivityMasterController@saveSlaActivityMaster')->name('admin.saveSlaActivityMaster');
	    Route::get('/edit/edit-sla-activity-master/{id}', 'Master\SlaActivityMasterController@editSlaActivityMaster')->name('admin.editSlaActivityMaster');
	    Route::post('/edit/update-sla-activity-master', 'Master\SlaActivityMasterController@updateSlaActivityMaster')->name('admin.updateSlaActivityMaster');
	    Route::get('/delete/delete-sla-activity-master/{id}', 'Master\SlaActivityMasterController@deleteSlaActivityMaster')->name('admin.deleteSlaActivityMaster');
	    Route::post('/view/change-sla-activity-master-status', 'Master\SlaActivityMasterController@changeSlaActivityMasterStatus')->name('admin.changeSlaActivityMasterStatus');
		Route::get('/view/export-sla-activity-master', 'Master\SlaActivityMasterController@exportSlaActivityMaster')->name('admin.exportSlaActivityMaster');
	});

	// Reason master Route
    Route::group(['prefix' => 'reason-master'], function (){
        Route::get('/view/reason-master-list', 'Master\ReasonMasterController@reasonMasterList')->name('admin.reasonMasterList');
        Route::get('/add/add-reason-master', 'Master\ReasonMasterController@addReasonMaster')->name('admin.addReasonMaster');
        Route::post('/add/save-reason-master', 'Master\ReasonMasterController@saveReasonMaster')->name('admin.saveReasonMaster');
        Route::get('/edit/edit-reason-master/{id}', 'Master\ReasonMasterController@editReasonMaster')->name('admin.editReasonMaster');
        Route::post('/edit/update-reason-master', 'Master\ReasonMasterController@updateReasonMaster')->name('admin.updateReasonMaster');
        Route::get('/delete/delete-reason-master/{id}', 'Master\ReasonMasterController@deleteReasonMaster')->name('admin.deleteReasonMaster');
        Route::post('/view/change-reason-master-status', 'Master\ReasonMasterController@changeReasonMasterStatus')->name('admin.changeReasonMasterStatus');
		Route::get('/view/export-reason-master', 'Master\ReasonMasterController@exportReasonMaster')->name('admin.exportReasonMaster');
    });

    Route::group(['prefix' => 'pre-study-projection-data'], function () {
		Route::match(['get', 'post'],'/view/pre-study-projection-list', 'Study\PreStudyController@preStudyProjectionList')->name('admin.preStudyProjectionList');
		Route::post('/view/get-pre-study-projection-list', 'Study\PreStudyController@getPreStudyProjectionList')->name('admin.getPreStudyProjectionList');
		Route::post('/view/get-post-study-projection-list', 'Study\PreStudyController@getPostStudyProjectionList')->name('admin.getPostStudyProjectionList');
	});

	// Activity MetaData Routes
	Route::group(['prefix' => 'activity-metadata'], function () {
		Route::get('/view/activity-metadata-list', 'Master\ActivityMetadataController@activityMetadataList')->name('admin.activityMetadataList');
		Route::get('/add/add-activity-metadata', 'Master\ActivityMetadataController@addActivityMetadata')->name('admin.addActivityMetadata');
		Route::post('/add/save-activity-metadata', 'Master\ActivityMetadataController@saveActivityMetadata')->name('admin.saveActivityMetadata');
		Route::get('/delete/delete-activity-metadata/{id}', 'Master\ActivityMetadataController@deleteActivityMetadata')->name('admin.deleteActivityMetadata');
		Route::post('/view/change-activity-metadata-status', 'Master\ActivityMetadataController@changeActivityMetadataStatus')->name('admin.changeActivityMetadataStatus');
		Route::get('/view/export-activity-metadata-master', 'Master\ActivityMetadataController@exportActivityMetadata')->name('admin.exportActivityMetadata');
	});

	Route::group(['prefix' => 'study-metadata'], function () {
		Route::match(['get', 'post'], '/view/all-studies-activity-metadata-list', 'Study\StudyActivityMetadataController@allStudiesActivityMetadataList')->name('admin.allStudiesActivityMetadataList');
		Route::get('/view/export-study-metadata', 'Study\StudyActivityMetadataController@exportStudyMetaData')->name('admin.exportStudyMetaData');
	});