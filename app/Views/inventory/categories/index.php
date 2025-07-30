<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= $title ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
                        <li class="breadcrumb-item">Inventory</li>
                        <li class="breadcrumb-item active">Categories</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Product Categories</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" id="btnAdd">
                            <i class="fas fa-plus"></i> Add Category
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="categoriesTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="10%">Code</th>
                                <th width="25%">Category Name</th>
                                <th width="20%">Parent Category</th>
                                <th width="25%">Description</th>
                                <th width="10%">Status</th>
                                <th width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Category Form</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Form will be loaded here -->
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
var categoriesTable;

$(document).ready(function() {
    // Initialize DataTable
    categoriesTable = $('#categoriesTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?= base_url('inventory/categories/datatables') ?>",
            "type": "POST"
        },
        "columns": [
            { "data": "category_code" },
            { "data": "category_name" },
            { 
                "data": "parent_name",
                "render": function(data) {
                    return data || '-';
                }
            },
            { 
                "data": "description",
                "render": function(data) {
                    return data || '-';
                }
            },
            {
                "data": "is_active",
                "render": function(data, type, row) {
                    var badge = data == 1 
                        ? '<span class="badge badge-success">Active</span>' 
                        : '<span class="badge badge-danger">Inactive</span>';
                    return badge;
                }
            },
            {
                "data": null,
                "orderable": false,
                "render": function(data, type, row) {
                    var buttons = '<div class="btn-group btn-group-sm">';
                    buttons += '<button class="btn btn-info btn-edit" data-id="' + row.id + '" title="Edit">';
                    buttons += '<i class="fas fa-edit"></i></button>';
                    buttons += '<button class="btn btn-warning btn-toggle" data-id="' + row.id + '" title="Toggle Status">';
                    buttons += '<i class="fas fa-power-off"></i></button>';
                    buttons += '<button class="btn btn-danger btn-delete" data-id="' + row.id + '" title="Delete">';
                    buttons += '<i class="fas fa-trash"></i></button>';
                    buttons += '</div>';
                    return buttons;
                }
            }
        ],
        "order": [[0, 'asc']],
        "pageLength": 10,
        "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]]
    });

    // Add button click
    $('#btnAdd').on('click', function() {
        $.ajax({
            url: '<?= base_url('inventory/categories/create') ?>',
            type: 'GET',
            success: function(response) {
                $('#modalContent').html(response);
                $('#categoryModal').modal('show');
            }
        });
    });

    // Edit button click
    $(document).on('click', '.btn-edit', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '<?= base_url('inventory/categories/edit') ?>/' + id,
            type: 'GET',
            success: function(response) {
                $('#modalContent').html(response);
                $('#categoryModal').modal('show');
            }
        });
    });

    // Delete button click
    $(document).on('click', '.btn-delete', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('inventory/categories/delete') ?>/' + id,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            Swal.fire('Deleted!', response.message, 'success');
                            categoriesTable.ajax.reload();
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    }
                });
            }
        });
    });

    // Toggle status button click
    $(document).on('click', '.btn-toggle', function() {
        var id = $(this).data('id');
        
        $.ajax({
            url: '<?= base_url('inventory/categories/toggle') ?>/' + id,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    toastr.success(response.message);
                    categoriesTable.ajax.reload();
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });

    // Form submission
    $(document).on('submit', '#categoryForm', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var url = form.attr('action');
        var formData = form.serialize();
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    $('#categoryModal').modal('hide');
                    toastr.success(response.message);
                    categoriesTable.ajax.reload();
                } else {
                    if (response.errors) {
                        $.each(response.errors, function(field, error) {
                            $('#' + field).addClass('is-invalid');
                            $('#' + field + '_error').text(error);
                        });
                    } else {
                        toastr.error(response.message);
                    }
                }
            }
        });
    });

    // Clear validation errors on input
    $(document).on('keyup change', '.form-control', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').text('');
    });
});
</script>
<?= $this->endSection() ?>
