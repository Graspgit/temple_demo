<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?> - <?= $staff['first_name'] . ' ' . $staff['last_name'] ?></h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>SALARY COMPONENT HISTORY</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="<?= base_url('salarycomponents/manage/' . $staff['id']) ?>" class="btn btn-primary waves-effect">
                                    <i class="material-icons">edit</i> Manage Components
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date Added</th>
                                        <th>Component Type</th>
                                        <th>Component Name</th>
                                        <th>Amount/Percentage</th>
                                        <th>Effective Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($history as $item): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($item['created_at'])) ?></td>
                                        <td>
                                            <span class="label <?= $item['component_type'] == 'allowance' ? 'label-success' : 'label-danger' ?>">
                                                <?= ucfirst($item['component_type']) ?>
                                            </span>
                                        </td>
                                        <td><?= $item['component_name'] ?></td>
                                        <td>
                                            <?php if($item['percentage']): ?>
                                                <?= $item['percentage'] ?>%
                                            <?php else: ?>
                                                RM <?= number_format($item['amount'], 2) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($item['effective_date'])) ?></td>
                                        <td><?= $item['end_date'] ? date('d/m/Y', strtotime($item['end_date'])) : '-' ?></td>
                                        <td>
                                            <?php if($item['status'] == 1): ?>
                                                <span class="label label-success">Active</span>
                                            <?php else: ?>
                                                <span class="label label-default">Inactive</span>
                                            <?php endif; ?>
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