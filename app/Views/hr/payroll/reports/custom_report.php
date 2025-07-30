<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <!-- Custom Report Builder -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>CUSTOM REPORT BUILDER</h2>
                    </div>
                    <div class="body">
                        <form method="POST" action="<?= base_url('payroll/customReport') ?>" id="customReportForm">
                            <?= csrf_field() ?>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Date Range</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>From Month</label>
                                                <input type="month" name="month_from" class="form-control" value="<?= date('Y-01') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>To Month</label>
                                                <input type="month" name="month_to" class="form-control" value="<?= date('Y-m') ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <h4>Filters</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Department</label>
                                                <select name="department_id" class="form-control show-tick">
                                                    <option value="">All Departments</option>
                                                    <?php foreach($departments as $dept): ?>
                                                        <option value="<?= $dept['id'] ?>"><?= $dept['department_name'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Designation</label>
                                                <select name="designation_id" class="form-control show-tick">
                                                    <option value="">All Designations</option>
                                                    <?php foreach($designations as $desig): ?>
                                                        <option value="<?= $desig['id'] ?>"><?= $desig['designation_name'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Staff Type</label>
                                                <select name="staff_type" class="form-control show-tick">
                                                    <option value="">All Types</option>
                                                    <option value="local">Local</option>
                                                    <option value="foreigner">Foreigner</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Payment Status</label>
                                                <select name="payment_status" class="form-control show-tick">
                                                    <option value="">All Status</option>
                                                    <option value="paid">Paid</option>
                                                    <option value="pending">Pending</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Report Columns</h4>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input type="checkbox" name="columns[]" value="staff_code" id="col_staff_code" checked>
                                                <label for="col_staff_code">Staff Code</label>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="columns[]" value="name" id="col_name" checked>
                                                <label for="col_name">Name</label>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="columns[]" value="department" id="col_department" checked>
                                                <label for="col_department">Department</label>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="columns[]" value="designation" id="col_designation" checked>
                                                <label for="col_designation">Designation</label>
                                            </div>
                                        </div>
                                        <div class="row m-t-10">
                                            <div class="col-md-3">
                                                <input type="checkbox" name="columns[]" value="basic_salary" id="col_basic" checked>
                                                <label for="col_basic">Basic Salary</label>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="columns[]" value="allowances" id="col_allowances" checked>
                                                <label for="col_allowances">Allowances</label>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="columns[]" value="gross_salary" id="col_gross" checked>
                                                <label for="col_gross">Gross Salary</label>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="columns[]" value="net_salary" id="col_net" checked>
                                                <label for="col_net">Net Salary</label>
                                            </div>
                                        </div>
                                        <div class="row m-t-10">
                                            <div class="col-md-3">
                                                <input type="checkbox" name="columns[]" value="epf" id="col_epf">
                                                <label for="col_epf">EPF</label>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="columns[]" value="socso" id="col_socso">
                                                <label for="col_socso">SOCSO</label>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="columns[]" value="pcb" id="col_pcb">
                                                <label for="col_pcb">PCB</label>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="columns[]" value="payment_date" id="col_payment_date">
                                                <label for="col_payment_date">Payment Date</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-lg waves-effect">
                                        <i class="material-icons">search</i> GENERATE REPORT
                                    </button>
                                    <button type="reset" class="btn btn-default btn-lg waves-effect">
                                        <i class="material-icons">clear</i> RESET
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Results -->
        <?php if(isset($report_data) && !empty($report_data)): ?>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            REPORT RESULTS
                            <small>Found <?= count($report_data) ?> records</small>
                        </h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <button type="button" class="btn btn-success waves-effect" onclick="exportReport('excel')">
                                    <i class="material-icons">grid_on</i> EXPORT EXCEL
                                </button>
                            </li>
                            <li>
                                <button type="button" class="btn btn-primary waves-effect" onclick="exportReport('pdf')">
                                    <i class="material-icons">picture_as_pdf</i> EXPORT PDF
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-exportable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <?php 
                                        $columns = $_POST['columns'] ?? ['staff_code', 'name', 'gross_salary', 'net_salary'];
                                        $columnLabels = [
                                            'staff_code' => 'Staff Code',
                                            'name' => 'Name',
                                            'department' => 'Department',
                                            'designation' => 'Designation',
                                            'basic_salary' => 'Basic Salary',
                                            'allowances' => 'Allowances',
                                            'gross_salary' => 'Gross Salary',
                                            'net_salary' => 'Net Salary',
                                            'epf' => 'EPF',
                                            'socso' => 'SOCSO',
                                            'pcb' => 'PCB',
                                            'payment_date' => 'Payment Date'
                                        ];
                                        foreach($columns as $col): ?>
                                            <th><?= $columnLabels[$col] ?? $col ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($report_data as $row): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <?php foreach($columns as $col): ?>
                                            <td>
                                                <?php
                                                switch($col) {
                                                    case 'name':
                                                        echo $row['first_name'] . ' ' . $row['last_name'];
                                                        break;
                                                    case 'basic_salary':
                                                    case 'allowances':
                                                    case 'gross_salary':
                                                    case 'net_salary':
                                                        echo 'RM ' . number_format($row[$col == 'allowances' ? 'total_allowances' : $col], 2);
                                                        break;
                                                    case 'epf':
                                                        echo 'RM ' . number_format($row['epf_employee'], 2);
                                                        break;
                                                    case 'socso':
                                                        echo 'RM ' . number_format($row['socso_employee'], 2);
                                                        break;
                                                    case 'pcb':
                                                        echo 'RM ' . number_format($row['pcb'], 2);
                                                        break;
                                                    case 'payment_date':
                                                        echo $row['payment_date'] ? date('d-M-Y', strtotime($row['payment_date'])) : 'Pending';
                                                        break;
                                                    default:
                                                        echo $row[$col] ?? '-';
                                                }
                                                ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
function exportReport(format) {
    var form = document.getElementById('customReportForm');
    var formData = new FormData(form);
    formData.append('export_format', format);
    
    // Create a temporary form for export
    var exportForm = document.createElement('form');
    exportForm.method = 'POST';
    exportForm.action = '<?= base_url("payroll/export-custom-report") ?>';
    
    for (var pair of formData.entries()) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = pair[0];
        input.value = pair[1];
        exportForm.appendChild(input);
    }
    
    document.body.appendChild(exportForm);
    exportForm.submit();
    document.body.removeChild(exportForm);
}
</script>