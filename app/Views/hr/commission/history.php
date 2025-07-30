<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <!-- Filter Section -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>FILTER COMMISSION HISTORY</h2>
                    </div>
                    <div class="body">
                        <form method="GET" action="<?= base_url('commissionhr/history') ?>">
                            <div class="row clearfix">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Staff</label>
                                        <select name="staff_id" class="form-control show-tick" data-live-search="true">
                                            <option value="">-- All Staff --</option>
                                            <?php foreach($staff as $emp): ?>
                                                <option value="<?= $emp['id'] ?>" <?= $selected_staff == $emp['id'] ? 'selected' : '' ?>>
                                                    <?= $emp['staff_code'] ?> - <?= $emp['first_name'] . ' ' . $emp['last_name'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Month</label>
                                        <input type="month" name="month" class="form-control" value="<?= $selected_month ?>">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>&nbsp;</label><br>
                                        <button type="submit" class="btn btn-primary waves-effect">
                                            <i class="material-icons">search</i> FILTER
                                        </button>
                                        <a href="<?= base_url('commissionhr/history') ?>" class="btn btn-danger waves-effect">
                                            <i class="material-icons">clear</i> RESET
                                        </a>
                                        <a href="<?= base_url('commission') ?>" class="btn btn-info waves-effect">
                                            <i class="material-icons">arrow_back</i> BACK
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commission History Table -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            COMMISSION HISTORY
                            <?php if($selected_month): ?>
                                - <?= date('F Y', strtotime($selected_month . '-01')) ?>
                            <?php endif; ?>
                        </h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <button type="button" class="btn btn-success waves-effect" onclick="exportHistory()">
                                    <i class="material-icons">file_download</i> Export
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover data-js-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Staff Code</th>
                                        <th>Name</th>
                                        <th>Commission Type</th>
                                        <th>Base Amount</th>
                                        <th>Rate/Amount</th>
                                        <th>Commission</th>
                                        <th>Reference No</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; $totalBase = 0; $totalCommission = 0; ?>
                                    <?php foreach($commissions as $comm): ?>
                                    <?php 
                                        $totalBase += $comm['base_amount'];
                                        $totalCommission += $comm['commission_amount'];
                                    ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= date('d-M-Y', strtotime($comm['commission_date'])) ?></td>
                                        <td><?= $comm['staff_code'] ?></td>
                                        <td><?= $comm['first_name'] . ' ' . $comm['last_name'] ?></td>
                                        <td><span class="label label-info"><?= $comm['commission_type'] ?></span></td>
                                        <td>RM <?= number_format($comm['base_amount'], 2) ?></td>
                                        <td>
                                            <?php if($comm['commission_percentage']): ?>
                                                <?= $comm['commission_percentage'] ?>%
                                            <?php else: ?>
                                                Fixed
                                            <?php endif; ?>
                                        </td>
                                        <td><strong>RM <?= number_format($comm['commission_amount'], 2) ?></strong></td>
                                        <td><?= $comm['reference_no'] ?? '-' ?></td>
                                        <td><?= $comm['remarks'] ?? '-' ?></td>
                                        <td>
                                            <a href="<?= base_url('commissionhr/edit/'.$comm['id']) ?>" 
                                               class="btn btn-xs btn-primary" 
                                               title="Edit">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a href="<?= base_url('commissionhr/delete/'.$comm['id']) ?>" 
                                               class="btn btn-xs btn-danger" 
                                               onclick="return confirm('Are you sure you want to delete this commission?')" 
                                               title="Delete">
                                                <i class="material-icons">delete</i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-right">TOTAL:</th>
                                        <th>RM <?= number_format($totalBase, 2) ?></th>
                                        <th></th>
                                        <th>RM <?= number_format($totalCommission, 2) ?></th>
                                        <th colspan="3"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(function() {
    $('.data-js-table').DataTable({
        responsive: true,
        pageLength: 50,
        order: [[1, 'desc']],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
    
    $('.show-tick').selectpicker();
});

function exportHistory() {
    var staffId = '<?= $selected_staff ?>';
    var month = '<?= $selected_month ?>';
    var url = '<?= base_url("commissionhr/export-history") ?>?staff_id=' + staffId + '&month=' + month;
    window.location.href = url;
}
</script>