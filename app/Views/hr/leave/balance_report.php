
<!-- hr/leave/balance_report.php -->
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?> - Year <?= $year ?></h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>STAFF LEAVE BALANCE REPORT</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Year: <?= $year ?> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="?year=<?= date('Y') ?>"><?= date('Y') ?></a></li>
                                        <li><a href="?year=<?= date('Y') - 1 ?>"><?= date('Y') - 1 ?></a></li>
                                        <li><a href="?year=<?= date('Y') - 2 ?>"><?= date('Y') - 2 ?></a></li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <button type="button" class="btn btn-success waves-effect" onclick="exportToExcel()">
                                    <i class="material-icons">file_download</i> Export Excel
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="leaveBalanceTable">
                                <thead>
                                    <tr>
                                        <th rowspan="2">#</th>
                                        <th rowspan="2">Staff Code</th>
                                        <th rowspan="2">Name</th>
                                        <th rowspan="2">Department</th>
                                        <?php foreach($leave_types as $type): ?>
                                        <th colspan="3" class="text-center"><?= $type['leave_name'] ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <?php foreach($leave_types as $type): ?>
                                        <th>Entitled</th>
                                        <th>Used</th>
                                        <th>Balance</th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($staff_balances as $staff): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= $staff['staff_code'] ?></td>
                                        <td><?= $staff['first_name'] . ' ' . $staff['last_name'] ?></td>
                                        <td><?= $staff['department_name'] ?? '-' ?></td>
                                        <?php foreach($leave_types as $type): 
                                            $balance = $staff['balances'][$type['id']] ?? null;
                                        ?>
                                        <td><?= $balance ? $balance['entitled_days'] : '0' ?></td>
                                        <td><?= $balance ? $balance['used_days'] : '0' ?></td>
                                        <td class="<?= ($balance && $balance['balance_days'] < 3) ? 'text-danger font-bold' : '' ?>">
                                            <?= $balance ? $balance['balance_days'] : '0' ?>
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

        <!-- Summary Cards -->
        <div class="row clearfix">
            <?php foreach($leave_types as $type): 
                $totalEntitled = 0;
                $totalUsed = 0;
                $totalBalance = 0;
                foreach($staff_balances as $staff) {
                    $balance = $staff['balances'][$type['id']] ?? null;
                    if ($balance) {
                        $totalEntitled += $balance['entitled_days'];
                        $totalUsed += $balance['used_days'];
                        $totalBalance += $balance['balance_days'];
                    }
                }
            ?>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="header bg-<?= $type['leave_code'] == 'AL' ? 'blue' : ($type['leave_code'] == 'MC' ? 'green' : 'orange') ?>">
                        <h2><?= strtoupper($type['leave_name']) ?></h2>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-xs-4 text-center">
                                <h4><?= $totalEntitled ?></h4>
                                <small>Total Entitled</small>
                            </div>
                            <div class="col-xs-4 text-center">
                                <h4><?= $totalUsed ?></h4>
                                <small>Total Used</small>
                            </div>
                            <div class="col-xs-4 text-center">
                                <h4><?= $totalBalance ?></h4>
                                <small>Total Balance</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Approve/Reject Modal -->
<div class="modal fade" id="actionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="leaveActionForm">
                <input type="hidden" id="leave_id">
                <input type="hidden" id="action_type">
                <div class="modal-header">
                    <h4 class="modal-title" id="actionModalLabel">Leave Action</h4>
                </div>
                <div class="modal-body">
                    <p id="action_message"></p>
                    <div class="form-group">
                        <div class="form-line">
                            <textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="Remarks (Optional)"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary waves-effect">CONFIRM</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CANCEL</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(function () {
    $('.js-basic-example').DataTable({
        responsive: true,
        order: [[0, 'desc']],
        pageLength: 25
    });

    // Approve leave
    $('.approve-leave').on('click', function() {
        var data = $(this).data();
        $('#leave_id').val(data.id);
        $('#action_type').val('approved');
        $('#actionModalLabel').text('Approve Leave');
        $('#action_message').text('Are you sure you want to approve ' + data.days + ' days leave for ' + data.staff + '?');
        $('#actionModal').modal('show');
    });

    // Reject leave
    $('.reject-leave').on('click', function() {
        var data = $(this).data();
        $('#leave_id').val(data.id);
        $('#action_type').val('rejected');
        $('#actionModalLabel').text('Reject Leave');
        $('#action_message').text('Are you sure you want to reject leave application for ' + data.staff + '?');
        $('#actionModal').modal('show');
    });

    // Submit action
    $('#leaveActionForm').on('submit', function(e) {
        e.preventDefault();
        
        var leaveId = $('#leave_id').val();
        var status = $('#action_type').val();
        var remarks = $('#remarks').val();
        
        $.post('<?= base_url("leave/update-status/") ?>' + leaveId, {
            status: status,
            remarks: remarks,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        }, function(response) {
            if (response.status == 'success') {
                location.reload();
            } else {
                alert(response.message);
            }
        });
    });

    // Animated Count To
    $('.count-to').countTo();
});

// Export to Excel function
function exportToExcel() {
    var table = document.getElementById('leaveBalanceTable');
    var html = table.outerHTML;
    var url = 'data:application/vnd.ms-excel,' + escape(html);
    var link = document.createElement("a");
    link.download = "leave_balance_report_<?= $year ?>.xls";
    link.href = url;
    link.click();
}
</script>