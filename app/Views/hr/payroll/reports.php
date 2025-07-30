<!-- hr/payroll/reports.php -->
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <!-- Report Cards -->
        <div class="row clearfix">
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="body bg-pink">
                        <div class="font-bold m-b--35">MONTHLY PAYROLL REPORT</div>
                        <ul class="dashboard-stat-list">
                            <li>
                                View comprehensive monthly payroll summary
                            </li>
                            <li class="m-t-20">
                                <a href="#" onclick="openMonthlyReport()" class="btn btn-warning btn-lg waves-effect">
                                    <i class="material-icons">assessment</i> GENERATE
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="body bg-cyan">
                        <div class="font-bold m-b--35">STATUTORY REPORTS</div>
                        <ul class="dashboard-stat-list">
                            <li>
                                EPF, SOCSO, EIS & PCB Reports
                            </li>
                            <li class="m-t-20">
                                <a href="#" onclick="openStatutoryReport()" class="btn btn-warning btn-lg waves-effect">
                                    <i class="material-icons">account_balance</i> GENERATE
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="body bg-light-green">
                        <div class="font-bold m-b--35">BANK TRANSFER REPORT</div>
                        <ul class="dashboard-stat-list">
                            <li>
                                Generate bank transfer file for salary
                            </li>
                            <li class="m-t-20">
                                <a href="#" onclick="openBankReport()" class="btn btn-warning btn-lg waves-effect">
                                    <i class="material-icons">account_balance_wallet</i> GENERATE
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="body bg-orange">
                        <div class="font-bold m-b--35">YEARLY SUMMARY</div>
                        <ul class="dashboard-stat-list">
                            <li>
                                Annual payroll summary & EA forms
                            </li>
                            <li class="m-t-20">
                                <a href="#" onclick="openYearlyReport()" class="btn btn-warning btn-lg waves-effect">
                                    <i class="material-icons">date_range</i> GENERATE
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="body bg-deep-purple">
                        <div class="font-bold m-b--35">DEPARTMENT REPORT</div>
                        <ul class="dashboard-stat-list">
                            <li>
                                Department-wise payroll analysis
                            </li>
                            <li class="m-t-20">
                                <a href="#" onclick="openDepartmentReport()" class="btn btn-warning btn-lg waves-effect">
                                    <i class="material-icons">business</i> GENERATE
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="body bg-teal">
                        <div class="font-bold m-b--35">CUSTOM REPORT</div>
                        <ul class="dashboard-stat-list">
                            <li>
                                Create custom payroll reports
                            </li>
                            <li class="m-t-20">
                                <a href="#" onclick="openCustomReport()" class="btn btn-warning btn-lg waves-effect">
                                    <i class="material-icons">tune</i> GENERATE
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Reports -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>QUICK REPORTS</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="<?= base_url('payroll') ?>" class="btn btn-danger waves-effect">
                                    <i class="material-icons">arrow_back</i> Back to Payroll
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Report Type</th>
                                        <th>Description</th>
                                        <th>Format</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Borang A - KWSP</td>
                                        <td>Monthly EPF contribution statement</td>
                                        <td>PDF / CSV</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="generateReport('epf', 'pdf')">
                                                <i class="material-icons">picture_as_pdf</i> PDF
                                            </button>
                                            <button type="button" class="btn btn-sm btn-success" onclick="generateReport('epf', 'csv')">
                                                <i class="material-icons">grid_on</i> CSV
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Borang 8A - SOCSO</td>
                                        <td>Monthly SOCSO contribution statement</td>
                                        <td>PDF / CSV</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="generateReport('socso', 'pdf')">
                                                <i class="material-icons">picture_as_pdf</i> PDF
                                            </button>
                                            <button type="button" class="btn btn-sm btn-success" onclick="generateReport('socso', 'csv')">
                                                <i class="material-icons">grid_on</i> CSV
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>EIS Contribution</td>
                                        <td>Monthly EIS contribution statement</td>
                                        <td>PDF / CSV</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="generateReport('eis', 'pdf')">
                                                <i class="material-icons">picture_as_pdf</i> PDF
                                            </button>
                                            <button type="button" class="btn btn-sm btn-success" onclick="generateReport('eis', 'csv')">
                                                <i class="material-icons">grid_on</i> CSV
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>CP39 - PCB</td>
                                        <td>Monthly PCB deduction statement</td>
                                        <td>PDF / CSV</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="generateReport('pcb', 'pdf')">
                                                <i class="material-icons">picture_as_pdf</i> PDF
                                            </button>
                                            <button type="button" class="btn btn-sm btn-success" onclick="generateReport('pcb', 'csv')">
                                                <i class="material-icons">grid_on</i> CSV
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>EA Form</td>
                                        <td>Yearly employee income statement</td>
                                        <td>PDF</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="generateReport('ea', 'pdf')">
                                                <i class="material-icons">picture_as_pdf</i> Generate
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Monthly Report Modal -->
<div class="modal fade" id="monthlyReportModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Generate Monthly Payroll Report</h4>
            </div>
            <div class="modal-body">
                <form id="monthlyReportForm">
                    <div class="form-group">
                        <label>Select Month</label>
                        <input type="month" name="month" class="form-control" value="<?= date('Y-m') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Report Format</label>
                        <select name="format" class="form-control">
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect" onclick="submitMonthlyReport()">GENERATE</button>
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<!-- Statutory Report Modal -->
<div class="modal fade" id="statutoryReportModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Generate Statutory Reports</h4>
            </div>
            <div class="modal-body">
                <form id="statutoryReportForm">
                    <div class="form-group">
                        <label>Select Month</label>
                        <input type="month" name="month" class="form-control" value="<?= date('Y-m') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Report Type</label>
                        <select name="type" class="form-control">
                            <option value="all">All Statutory Reports</option>
                            <option value="epf">EPF Only</option>
                            <option value="socso">SOCSO Only</option>
                            <option value="eis">EIS Only</option>
                            <option value="pcb">PCB Only</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Report Format</label>
                        <select name="format" class="form-control">
                            <option value="pdf">PDF</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect" onclick="submitStatutoryReport()">GENERATE</button>
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<script>
function openMonthlyReport() {
    $('#monthlyReportModal').modal('show');
}

function openStatutoryReport() {
    $('#statutoryReportModal').modal('show');
}

function openBankReport() {
    var month = prompt("Enter month (YYYY-MM):", "<?= date('Y-m') ?>");
    if (month) {
        window.location.href = '<?= base_url("payroll/bankReport") ?>/' + month;
    }
}

function openYearlyReport() {
    var year = prompt("Enter year:", "<?= date('Y') ?>");
    if (year) {
        window.location.href = '<?= base_url("payroll/yearlyReport") ?>/' + year;
    }
}

function openDepartmentReport() {
    window.location.href = '<?= base_url("payroll/departmentReport") ?>/';
}

function openCustomReport() {
    window.location.href = '<?= base_url("payroll/customReport") ?>/';
}

function submitMonthlyReport() {
    var form = $('#monthlyReportForm');
    var month = form.find('[name="month"]').val();
    var format = form.find('[name="format"]').val();
    
    window.location.href = '<?= base_url("payroll/monthlyReport") ?>/' + month + '?format=' + format;
    $('#monthlyReportModal').modal('hide');
}

function submitStatutoryReport() {
    var form = $('#statutoryReportForm');
    var month = form.find('[name="month"]').val();
    var type = form.find('[name="type"]').val();
    var format = form.find('[name="format"]').val();
    
    window.location.href = '<?= base_url("payroll/statutoryReport") ?>/' + month + '?type=' + type + '&format=' + format;
    $('#statutoryReportModal').modal('hide');
}

function generateReport(type, format) {
    var month = prompt("Enter month (YYYY-MM):", "<?= date('Y-m') ?>");
    if (month) {
        var url = '';
        switch(type) {
            case 'epf':
                url = '<?= base_url("payroll/epfReport") ?>?month=' + month + '&format=' + format;
                break;
            case 'socso':
                url = '<?= base_url("payroll/socsoReport") ?>?month=' + month + '&format=' + format;
                break;
            case 'eis':
                url = '<?= base_url("payroll/eisReport") ?>?month=' + month + '&format=' + format;
                break;
            case 'pcb':
                url = '<?= base_url("payroll/pcbReport") ?>?month=' + month + '&format=' + format;
                break;
            case 'ea':
                var year = month.substring(0,4);
                url = '<?= base_url("payroll/eaReport") ?>?year=' + year;
                break;
        }
        window.location.href = url;
    }
}
</script>