<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?= $title ?></h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-red">
                        <h2>DOCUMENTS EXPIRING WITHIN 30 DAYS</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="<?= base_url('staff') ?>" class="btn btn-danger waves-effect">
                                    <i class="material-icons">arrow_back</i> Back
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <?php if(!empty($expiring_documents)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Staff Code</th>
                                        <th>Name</th>
                                        <th>Document Type</th>
                                        <th>Document Number</th>
                                        <th>Expiry Date</th>
                                        <th>Days Remaining</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($expiring_documents as $doc): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= $doc['staff_code'] ?></td>
                                        <td><?= $doc['first_name'] . ' ' . $doc['last_name'] ?></td>
                                        <td>
                                            <?php if($doc['passport_expiry'] <= date('Y-m-d', strtotime('+30 days'))): ?>
                                                <span class="label label-danger">Passport</span>
                                            <?php endif; ?>
                                            <?php if($doc['visa_expiry'] <= date('Y-m-d', strtotime('+30 days'))): ?>
                                                <span class="label label-warning">Visa</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($doc['passport_expiry'] <= date('Y-m-d', strtotime('+30 days'))): ?>
                                                <?= $doc['passport_number'] ?><br>
                                            <?php endif; ?>
                                            <?php if($doc['visa_expiry'] <= date('Y-m-d', strtotime('+30 days'))): ?>
                                                <?= $doc['visa_number'] ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($doc['passport_expiry'] <= date('Y-m-d', strtotime('+30 days'))): ?>
                                                <?= date('d-M-Y', strtotime($doc['passport_expiry'])) ?><br>
                                            <?php endif; ?>
                                            <?php if($doc['visa_expiry'] <= date('Y-m-d', strtotime('+30 days'))): ?>
                                                <?= date('d-M-Y', strtotime($doc['visa_expiry'])) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $days = 30;
                                            if($doc['passport_expiry'] <= date('Y-m-d', strtotime('+30 days'))) {
                                                $days = ceil((strtotime($doc['passport_expiry']) - time()) / (60*60*24));
                                            } elseif($doc['visa_expiry'] <= date('Y-m-d', strtotime('+30 days'))) {
                                                $days = ceil((strtotime($doc['visa_expiry']) - time()) / (60*60*24));
                                            }
                                            ?>
                                            <span class="label <?= $days <= 7 ? 'label-danger' : ($days <= 15 ? 'label-warning' : 'label-info') ?>">
                                                <?= $days ?> days
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('staff/view/'.$doc['staff_id']) ?>" class="btn btn-xs btn-info">
                                                <i class="material-icons">visibility</i> View
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-success">
                            <strong>Good News!</strong> No documents are expiring within the next 30 days.
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>