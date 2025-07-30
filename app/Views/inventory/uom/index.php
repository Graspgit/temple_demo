<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Units of Measure</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('inventory') ?>">Inventory</a></li>
                        <li class="breadcrumb-item active">Units of Measure</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <h4 class="card-title">All Units</h4>
                        </div>
                        <div class="col-sm-8">
                            <div class="text-sm-end">
                                <a href="<?= base_url('inventory/uom/create') ?>" class="btn btn-success btn-rounded waves-effect waves-light mb-2">
                                    <i class="mdi mdi-plus me-1"></i> Add New Unit
                                </a>
                            </div>
                        </div>
                    </div>

                    <table id="uomTable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Abbreviation</th>
                                <th>Category</th>
                                <th>Base Unit</th>
                                <th>Conversion Factor</th>
                                <th>Fractional</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    var table = $('#uomTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url('inventory/uom/datatables') ?>',
            type: 'POST'
        },
        columns: [
            { 
                data: 'name',
                render: function(data, type, row) {
                    return '<strong>' + data + '</strong>' + 
                           (row.description ? '<br><small class="text-muted">' + row.description + '</small>' : '');
                }
            },
            { 
                data: 'abbreviation',
                render: function(data) {
                    return '<span class="badge bg-info">' + data + '</span>';
                }
            },
            { 
                data: 'category',
                render: function(data) {
                    if (!data) return '-';
                    var categoryLabels = {
                        'weight': 'Weight',
                        'volume': 'Volume',
                        'count': 'Count',
                        'length': 'Length',
                        'area': 'Area',
                        'other': 'Other'
                    };
                    return categoryLabels[data] || data;
                }
            },
            { 
                data: 'base_unit_name',
                render: function(data, type, row) {
                    if (!data) return '<span class="text-muted">Base Unit</span>';
                    return data + ' <small>(' + row.base_unit_abbr + ')</small>';
                }
            },
            { 
                data: 'conversion_factor',
                render: function(data, type, row) {
                    if (!data || !row.base_unit_id) return '-';
                    return '1 ' + row.abbreviation + ' = ' + data + ' ' + row.base_unit_abbr;
                }
            },
            { 
                data: 'is_fractional',
                render: function(data, type, row) {
                    if (data == 1) {
                        return '<span class="badge bg-success">Yes</span>' +
                               (row.decimal_places > 0 ? ' <small>(' + row.decimal_places + ' decimals)</small>' : '');
                    }
                    return '<span class="badge bg-secondary">No</span>';
                }
            },
            { 
                data: 'is_active',
                render: function(data) {
                    return inventoryCommon.renderStatus(data);
                }
            },
            { 
                data: 'id',
                render: function(data, type, row) {
                    return inventoryCommon.renderActions(data, '<?= base_url('inventory/uom') ?>');
                },
                orderable: false,
                searchable: false
            }
        ],
        order: [[0, 'asc']],
        pageLength: 25,
        responsive: true,
        drawCallback: function() {
            inventoryCommon.initializeActions('uomTable', '<?= base_url('inventory/uom') ?>');
        }
    });
});
</script>