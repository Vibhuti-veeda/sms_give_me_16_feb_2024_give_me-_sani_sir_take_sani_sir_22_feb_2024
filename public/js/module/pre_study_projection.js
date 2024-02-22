$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var employees = [];
var employee = [];

// First column freeze js
$(function() {
    
    $.ajax({
        url: "/sms-admin/pre-study-projection-data/view/get-pre-study-projection-list",
        method:'POST',
        async: false,
        dataType: "json",
        success: function(data){
            $.each(data, function (key, value) {
                employees.push({
                    "SrNo" : key,
                    "StudyNo" : value.study_no.study_no,
                    "Site" : value.study_no.cr_location_name.location_name,
                    "SponsorName" : value.study_no.sponsor_name.sponsor_name,
                    "ProjectManager" : value.study_no.project_manager.name +' - '+ value.study_no.project_manager.employee_code,
                    "Drug Name" : value.drug_name,
                    "Submission" : value.submission,
                    "Scope" : value.scope,
                    "Subjects" : value.study_no.no_of_subject,
                    "Male" : value.study_no.no_of_male_subjects,
                    "Female" : value.study_no.no_of_female_subjects,
                    "StudyType" : value.study_no.study_type.para_value,
                    "Remark" : value.remark,
                    "TentativeClinicalDate" : value.tentative_clinical_date,
                    "StudySlotted" : value.study_no.study_slotted,
                    "ActualProtocol" : value.protocol_finalization,
                    "ActualProtocolStage" : value.protocol_finalization_stage,
                    "BENOC + TL Actual Submission" : value.dcgi_submission_noc_tl,
                    "BENOCTLActualSubmissionStage" : value.dcgi_submission_noc_tl_stage,
                    "BENOC + TL Actual Approval" : value.dcgi_approval_noc_tl,
                    "BENOCTLActualApprovalStage" : value.dcgi_approval_noc_tl_stage,
                    "TL Actual Submission" : value.dcgi_submission_only_tl,
                    "TLActualSubmissionStage" : value.dcgi_submission_only_tl_stage,
                    "TL Actual Approval" : value.dcgi_approval_only_tl,
                    "TLActualApprovalStage" : value.dcgi_approval_only_tl_stage,
                    "Tentative IP Status" : value.imp_availibility,
                    "TentativeIPStatusStage" : value.imp_availibility_stage,
                    "IEC Submission" : value.iec_submission,
                    "IECSubmissionStage" : value.iec_submission_stage,
                    "IEC Approval" : value.iec_approval,
                    "IECApprovalStage" : value.iec_approval_stage,
                    "MD/MV" : value.md_mv,
                    "MDMVStage" : value.md_mv_stage,
                    "System Status" : '',
                    "ManualStatus" : value.study_no.projection_status,
                })
            });

            $('#gridContainer').dxDataGrid({
                dataSource: employees,
                paging: {
                    pageSize: 500,
                },
                filterRow: { visible: true },
                searchPanel: { visible: true },
                keyExpr: 'SrNo',
                allowColumnReordering: true,
                allowColumnResizing: true,
                columnAutoWidth: true,
                showBorders: true,
                export: {
                    enabled: true,
                },
                onExporting(e) {
                    const workbook = new ExcelJS.Workbook();
                    const worksheet = workbook.addWorksheet('Employees');

                    DevExpress.excelExporter.exportDataGrid({
                        component: e.component,
                        worksheet,
                        autoFilterEnabled: true,
                    }).then(() => {
                        workbook.xlsx.writeBuffer().then((buffer) => {
                          saveAs(new Blob([buffer], { type: 'application/octet-stream' }), 'Pre Projection Data.xlsx');
                        });
                    });
                },
                columnChooser: {
                    enabled: true,
                },
                columnFixing: {
                    enabled: true,
                },
                onCellPrepared: function(e) {
                    if (e.rowType === "data") {
                        if (e.column.dataField === "ActualProtocol" && e.data.ActualProtocolStage == 'Red') {
                            e.cellElement.css({"color":"white", "background-color":"red"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "ActualProtocol" && e.data.ActualProtocolStage == 'Yellow') {
                            e.cellElement.css({"color":"black", "background-color":"yellow"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "ActualProtocol" && e.data.ActualProtocolStage == 'Green') {
                            e.cellElement.css({"color":"white", "background-color":"green"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "BENOC + TL Actual Submission" && e.data.BENOCTLActualSubmissionStage == 'Red') {
                            e.cellElement.css({"color":"white", "background-color":"red"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "BENOC + TL Actual Submission" && e.data.BENOCTLActualSubmissionStage == 'Yellow') {
                            e.cellElement.css({"color":"black", "background-color":"yellow"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "BENOC + TL Actual Submission" && e.data.BENOCTLActualSubmissionStage == 'Green') {
                            e.cellElement.css({"color":"white", "background-color":"green"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "BENOC + TL Actual Approval" && e.data.BENOCTLActualApprovalStage == 'Red') {
                            e.cellElement.css({"color":"white", "background-color":"red"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "BENOC + TL Actual Approval" && e.data.BENOCTLActualApprovalStage == 'Yellow') {
                            e.cellElement.css({"color":"black", "background-color":"yellow"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "BENOC + TL Actual Approval" && e.data.BENOCTLActualApprovalStage == 'Green') {
                            e.cellElement.css({"color":"white", "background-color":"green"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "TL Actual Submission" && e.data.TLActualSubmissionStage == 'Red') {
                            e.cellElement.css({"color":"white", "background-color":"red"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "TL Actual Submission" && e.data.TLActualSubmissionStage == 'Yellow') {
                            e.cellElement.css({"color":"black", "background-color":"yellow"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "TL Actual Submission" && e.data.TLActualSubmissionStage == 'Green') {
                            e.cellElement.css({"color":"white", "background-color":"green"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "TL Actual Approval" && e.data.TLActualApprovalStage == 'Red') {
                            e.cellElement.css({"color":"white", "background-color":"red"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "TL Actual Approval" && e.data.TLActualApprovalStage == 'Yellow') {
                            e.cellElement.css({"color":"black", "background-color":"yellow"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "TL Actual Approval" && e.data.TLActualApprovalStage == 'Green') {
                            e.cellElement.css({"color":"white", "background-color":"green"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "Tentative IP Status" && e.data.TentativeIPStatusStage == 'Red') {
                            e.cellElement.css({"color":"white", "background-color":"red"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "Tentative IP Status" && e.data.TentativeIPStatusStage == 'Yellow') {
                            e.cellElement.css({"color":"black", "background-color":"yellow"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "Tentative IP Status" && e.data.TentativeIPStatusStage == 'Green') {
                            e.cellElement.css({"color":"white", "background-color":"green"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "IEC Submission" && e.data.IECSubmissionStage == 'Red') {
                            e.cellElement.css({"color":"white", "background-color":"red"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "IEC Submission" && e.data.IECSubmissionStage == 'Yellow') {
                            e.cellElement.css({"color":"black", "background-color":"yellow"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "IEC Submission" && e.data.IECSubmissionStage == 'Green') {
                            e.cellElement.css({"color":"white", "background-color":"green"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "IEC Approval" && e.data.IECApprovalStage == 'Red') {
                            e.cellElement.css({"color":"white", "background-color":"red"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "IEC Approval" && e.data.IECApprovalStage == 'Yellow') {
                            e.cellElement.css({"color":"black", "background-color":"yellow"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "IEC Approval" && e.data.IECApprovalStage == 'Green') {
                            e.cellElement.css({"color":"white", "background-color":"green"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "MD/MV" && e.data.MDMVStage == 'Red') {
                            e.cellElement.css({"color":"white", "background-color":"red"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "MD/MV" && e.data.MDMVStage == 'Yellow') {
                            e.cellElement.css({"color":"black", "background-color":"yellow"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "MD/MV" && e.data.MDMVStage == 'Green') {
                            e.cellElement.css({"color":"white", "background-color":"green"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "MD/MV" && e.data.MDMVStage == 'Green') {
                            e.cellElement.css({"color":"white", "background-color":"green"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "System Status" && ((e.data.ActualProtocolStage != '' && e.data.ActualProtocolStage == 'Red') || (e.data.BENOCTLActualSubmissionStage != '' && e.data.BENOCTLActualSubmissionStage == 'Red') || (e.data.BENOCTLActualApprovalStage != '' && e.data.BENOCTLActualApprovalStage == 'Red') || (e.data.TLActualSubmissionStage != '' && e.data.TLActualSubmissionStage == 'Red') || (e.data.TentativeIPStatusStage != '' && e.data.TentativeIPStatusStage == 'Red') || (e.data.IECSubmissionStage != '' && e.data.IECSubmissionStage == 'Red') || (e.data.IECApprovalStage != '' && e.data.IECApprovalStage == 'Red') || (e.data.MDMVStage != '' && e.data.MDMVStage == 'Red'))) {
                            e.cellElement.css({"color":"white", "background-color":"red"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "System Status" && ((e.data.ActualProtocolStage != '' && e.data.ActualProtocolStage == 'Yellow') || (e.data.BENOCTLActualSubmissionStage != '' && e.data.BENOCTLActualSubmissionStage == 'Yellow') || (e.data.BENOCTLActualApprovalStage != '' && e.data.BENOCTLActualApprovalStage == 'Yellow') || (e.data.TLActualSubmissionStage != '' && e.data.TLActualSubmissionStage == 'Yellow') || (e.data.TentativeIPStatusStage != '' && e.data.TentativeIPStatusStage == 'Yellow') || (e.data.IECSubmissionStage != '' && e.data.IECSubmissionStage == 'Yellow') || (e.data.IECApprovalStage != '' && e.data.IECApprovalStage == 'Yellow') || (e.data.MDMVStage != '' && e.data.MDMVStage == 'Yellow'))) {
                            e.cellElement.css({"color":"black", "background-color":"yellow"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "System Status" && ((e.data.ActualProtocolStage != '' && e.data.ActualProtocolStage == 'Green') || (e.data.BENOCTLActualSubmissionStage != '' && e.data.BENOCTLActualSubmissionStage == 'Green') || (e.data.BENOCTLActualApprovalStage != '' && e.data.BENOCTLActualApprovalStage == 'Green') || (e.data.TLActualSubmissionStage != '' && e.data.TLActualSubmissionStage == 'Green') || (e.data.TentativeIPStatusStage != '' && e.data.TentativeIPStatusStage == 'Green') || (e.data.IECSubmissionStage != '' && e.data.IECSubmissionStage == 'Green') || (e.data.IECApprovalStage != '' && e.data.IECApprovalStage == 'Green') || (e.data.MDMVStage != '' && e.data.MDMVStage == 'Green'))) {
                            e.cellElement.css({"color":"white", "background-color":"green"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "Manual Status" && e.data.ManualStatus == 'RED') {
                            e.cellElement.css({"color":"white", "background-color":"red"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "Manual Status" && e.data.ManualStatus == 'YELLOW') {
                            e.cellElement.css({"color":"black", "background-color":"yellow"});
                            e.cellElement.addClass("my-class");
                        } else if (e.column.dataField === "Manual Status" && e.data.ManualStatus == 'GREEN') {
                            e.cellElement.css({"color":"white", "background-color":"green"});
                            e.cellElement.addClass("my-class");
                        }
                    }
                },
                allowColumnReordering: true,
                groupPanel: { visible: true },
                columns: [{
                    caption: 'Study No',
                    width: 100,
                    fixed: true,
                    dataField: 'StudyNo',
                    alignment: 'left',
                }, {
                    dataField: 'Site',
                    fixed: true,
                }, {
                    dataField: 'SponsorName',
                    width: 100,
                    fixed: true,
                }, {
                    dataField: 'ProjectManager',
                    fixed: true,
                }, {
                    dataField: 'Drug Name',
                    width: 200,
                    fixed: true,
                }, {
                    dataField: 'Submission',
                    width: 100,
                }, {
                    dataField: 'Scope',
                    width: 100,
                }, {
                    dataField: 'Subjects',
                    color: 'red',
                }, {
                    dataField: 'Male',
                }, {
                    dataField: 'Female',
                }, {
                    dataField: 'StudyType',
                    width: 100,
                }, {
                    dataField: 'Remark',
                    width: 100,
                }, {
                    dataField: 'TentativeClinicalDate',
                    dataType: "date",
                    format: 'dd MMM yyyy',
                }, {
                    dataField: 'StudySlotted',
                }, {
                    dataField: 'ActualProtocol',
                }, {
                    dataField: 'BENOC + TL Actual Submission',
                }, {
                    dataField: 'BENOC + TL Actual Approval',
                }, {
                    dataField: 'TL Actual Submission',
                }, {
                    dataField: 'TL Actual Approval',
                }, {
                    dataField: 'Tentative IP Status',
                }, {
                    dataField: 'IEC Submission',
                }, {
                    dataField: 'IEC Approval',
                }, {
                    dataField: 'MD/MV',
                },{
                    dataField: 'System Status',
                },{
                    dataField: 'Manual Status',
                }],
                summary: {
                    groupItems: [{
                        summaryType: "count"
                    }],
                    totalItems: [{
                        column: 'Subjects',
                        summaryType: 'sum',
                        displayFormat: 'Total : {0}',
                    }, {
                        column: 'Male',
                        summaryType: 'sum',
                        displayFormat: '{0}',
                    }, {
                        column: 'Female',
                        summaryType: 'sum',
                        displayFormat: '{0}',
                    },]
                }
            });
        }
    });
});

$(document).on('click','#accordionExample',function(){
    
    $.ajax({
        url: "/sms-admin/pre-study-projection-data/view/get-post-study-projection-list",
        method:'POST',
        async: false,
        dataType: "json",
        success: function(result){
            $.each(result, function (k, val) {
                employee.push({
                    "SrNo" : k,
                    "Study No" : val.study_no.study_no,
                    "Site Name" : val.study_no.cr_location_name.location_name,
                    "Sponsor Name" : val.study_no.sponsor_name.sponsor_name,
                    "Project Manager" : val.study_no.project_manager.name +' - '+ val.study_no.project_manager.employee_code,
                    "Drug Name" : val.drug_name,
                    "Regulatory" : val.submission,
                    "Scope" : val.scope,
                    "Subject" : val.study_no.no_of_subject,
                    "Males" : val.study_no.no_of_male_subjects,
                    "Females" : val.study_no.no_of_female_subjects,
                    "Type" : val.study_no.study_type.para_value,
                    "Check In" : val.check_in,
                    "Remarks" : val.remark,
                })
            });

            $('#postStudyGridContainer').dxDataGrid({
                dataSource: employee,
                paging: {
                    pageSize: 10,
                },
                height: '100%',
                filterRow: { visible: true },
                searchPanel: { visible: true },
                keyExpr: 'SrNo',
                allowColumnReordering: true,
                allowColumnResizing: true,
                columnAutoWidth: true,
                showBorders: true,
                export: {
                    enabled: true,
                },
                onExporting(e) {
                    const workbook = new ExcelJS.Workbook();
                    const worksheet = workbook.addWorksheet('Employees');

                    DevExpress.excelExporter.exportDataGrid({
                        component: e.component,
                        worksheet,
                        autoFilterEnabled: true,
                    }).then(() => {
                        workbook.xlsx.writeBuffer().then((buffer) => {
                          saveAs(new Blob([buffer], { type: 'application/octet-stream' }), 'Post Projection Data.xlsx');
                        });
                    });
                },
                columnChooser: {
                    enabled: true,
                },
                columnFixing: {
                    enabled: true,
                },
                allowColumnReordering: true,
                groupPanel: { visible: true },
                columns: [{
                    caption: 'Study No',
                    width: 100,
                    fixed: true,
                    dataField: 'Study No',
                    alignment: 'left',
                }, {
                    dataField: 'Site Name',
                }, {
                    dataField: 'Sponsor Name',
                    width: 100,
                }, {
                    dataField: 'Project Manager',
                }, {
                    dataField: 'Drug Name',
                    width: 150,
                }, {
                    dataField: 'Regulatory',
                }, {
                    dataField: 'Scope',
                }, {
                    dataField: 'Subject',
                    color: 'red',
                    width: 100,
                }, {
                    dataField: 'Males',
                }, {
                    dataField: 'Females',
                }, {
                    dataField: 'Type',
                }, {
                    dataField: 'Check In',
                    dataType: 'date',
                    format: 'dd MMM yyyy',
                    width: 120,
                }, {
                    dataField: 'Remarks',
                }],
                summary: {
                    groupItems: [{
                        summaryType: "count"
                    }],
                    totalItems: [{
                        column: 'Subject',
                        summaryType: 'sum',
                        displayFormat: 'Total : {0}',
                    }, {
                        column: 'Males',
                        summaryType: 'sum',
                        displayFormat: '{0}',
                    }, {
                        column: 'Females',
                        summaryType: 'sum',
                        displayFormat: '{0}',
                    },]
                }
            });
        }
    });
});
