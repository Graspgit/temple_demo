<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>ALLOWANCE SETTINGS</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <button type="button" class="btn btn-primary waves-effect" data-toggle="modal" data-target="#allowanceModal">
                                    <i class="material-icons">add</i> Add Allowance
                                </button>
                            </li>
                            <li>
                                <a href="<?= base_url('statutorysettings/statutory') ?>" class="btn btn-danger waves-effect">
                                    <i class="material-icons">arrow_back</i> Back
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Allowance Name</th>
                                        <th>Type</th>
                                        <th>Taxable</th>
                                        <th>EPF Eligible</th>
                                        <th>SOCSO Eligible</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($allowances as $allowance): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= $allowance['allowance_name'] ?></td>
                                        <td><span class="label label-info"><?= ucfirst($allowance['allowance_type']) ?></span></td>
                                        <td><?= $allowance['is_taxable'] ? '<span class="label label-success">Yes</span>' : '<span class="label label-default">No</span>' ?></td>
                                        <td><?= $allowance['is_epf_eligible'] ? '<span class="label label-success">Yes</span>' : '<span class="label label-default">No</span>' ?></td>
                                        <td><?= $allowance['is_socso_eligible'] ? '<span class="label label-success">Yes</span>' : '<span class="label label-default">No</span>' ?></td>
                                        <td>
                                            <span class="label <?= $allowance['status'] == 1 ? 'label-success' : 'label-danger' ?>">
                                                <?= $allowance['status'] == 1 ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-primary edit-allowance" 
                                                data-id="<?= $allowance['id'] ?>"
                                                data-name="<?= $allowance['allowance_name'] ?>"
                                                data-type="<?= $allowance['allowance_type'] ?>"
                                                data-taxable="<?= $allowance['is_taxable'] ?>"
                                                data-epf="<?= $allowance['is_epf_eligible'] ?>"
                                                data-socso="<?= $allowance['is_socso_eligible'] ?>"
                                                data-status="<?= $allowance['status'] ?>">
                                                <i class="material-icons">edit</i>
                                            </button>
                                            <a href="<?= base_url('statutorysettings/delete/allowances/'.$allowance['id']) ?>" 
                                               class="btn btn-xs btn-danger" 
                                               onclick="return confirm('Are you sure?')">
                                                <i class="material-icons">delete</i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Allowance Modal -->
<div class="modal fade" id="allowanceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="<?= base_url('statutorysettings/saveAllowance') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="allowance_id">
                <div class="modal-header">
                    <h4 class="modal-title">Add/Edit Allowance</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" name="allowance_name" id="allowance_name" class="form-control" required>
                            <label class="form-label">Allowance Name</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Allowance Type</label>
                        <select name="allowance_type" id="allowance_type" class="form-control show-tick">
                            <option value="fixed">Fixed Amount</option>
                            <option value="percentage">Percentage of Basic</option>
                        </select>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-4">
                            <input type="checkbox" name="is_taxable" id="is_taxable" value="1" class="filled-in">
                            <label for="is_taxable">Is Taxable</label>
                        </div>
                        <div class="col-md-4">
                            <input type="checkbox" name="is_epf_eligible" id="is_epf_eligible" value="1" class="filled-in">
                            <label for="is_epf_eligible">EPF Eligible</label>
                        </div>
                        <div class="col-md-4">
                            <input type="checkbox" name="is_socso_eligible" id="is_socso_eligible" value="1" class="filled-in">
                            <label for="is_socso_eligible">SOCSO Eligible</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="allowance_status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary waves-effect">SAVE</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
$(function () {
    // Edit allowance
    $('.edit-allowance').on('click', function() {
        var data = $(this).data();
        $('#allowance_id').val(data.id);
        $('#allowance_name').val(data.name);
        $('#allowance_type').val(data.type).selectpicker('refresh');
        $('#is_taxable').prop('checked', data.taxable == 1);
        $('#is_epf_eligible').prop('checked', data.epf == 1);
        $('#is_socso_eligible').prop('checked', data.socso == 1);
        $('#allowance_status').val(data.status);
        
        $('#allowanceModal').modal('show');
    });

    $('.show-tick').selectpicker();
});
</script>