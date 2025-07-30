<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>BULK COMMISSION UPLOAD</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="<?= base_url('commissionhr') ?>" class="btn btn-danger waves-effect">
                                    <i class="material-icons">arrow_back</i> Back
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <form method="POST" action="<?= base_url('commissionhr/process-bulk') ?>" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            
                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <strong>CSV Format Instructions:</strong>
                                        <ul>
                                            <li>Column 1: Staff Code (Required)</li>
                                            <li>Column 2: Commission Type (Required)</li>
                                            <li>Column 3: Base Amount (Required)</li>
                                            <li>Column 4: Commission Date (Optional, format: YYYY-MM-DD)</li>
                                            <li>Column 5: Reference No (Optional)</li>
                                            <li>Column 6: Remarks (Optional)</li>
                                        </ul>
                                        <p>Note: Commission will be calculated based on staff's commission settings</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Select CSV File <span class="text-danger">*</span></label>
                                        <input type="file" name="commission_file" class="form-control" accept=".csv" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Download Sample Template</label><br>
                                        <a href="<?= base_url('assets/templates/commission_bulk_template.csv') ?>" class="btn btn-info waves-effect">
                                            <i class="material-icons">file_download</i> Download Template
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <h4>Sample Data Preview</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Staff Code</th>
                                                    <th>Commission Type</th>
                                                    <th>Base Amount</th>
                                                    <th>Commission Date</th>
                                                    <th>Reference No</th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>EMP001</td>
                                                    <td>Sales Commission</td>
                                                    <td>5000.00</td>
                                                    <td>2024-01-15</td>
                                                    <td>INV-2024-001</td>
                                                    <td>January sales</td>
                                                </tr>
                                                <tr>
                                                    <td>EMP002</td>
                                                    <td>Project Commission</td>
                                                    <td>10000.00</td>
                                                    <td>2024-01-20</td>
                                                    <td>PRJ-2024-005</td>
                                                    <td>Website project completion</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary waves-effect" onclick="return confirm('Are you sure you want to upload this file?')">
                                        <i class="material-icons">cloud_upload</i> UPLOAD & PROCESS
                                    </button>
                                    <button type="reset" class="btn btn-danger waves-effect">
                                        <i class="material-icons">clear</i> RESET
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if(isset($upload_results)): ?>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>UPLOAD RESULTS</h2>
                    </div>
                    <div class="body">
                        <div class="alert alert-<?= $upload_results['status'] == 'success' ? 'success' : 'danger' ?>">
                            <strong><?= $upload_results['message'] ?></strong>
                        </div>
                        <?php if(isset($upload_results['errors']) && count($upload_results['errors']) > 0): ?>
                        <h4>Errors:</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Row</th>
                                        <th>Staff Code</th>
                                        <th>Error</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($upload_results['errors'] as $error): ?>
                                    <tr>
                                        <td><?= $error['row'] ?></td>
                                        <td><?= $error['staff_code'] ?></td>
                                        <td><?= $error['message'] ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>