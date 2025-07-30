<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>VENUE SETTINGS</h2>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="suc-alert">
                <?= session()->getFlashdata('success') ?>
                <span class="suc-closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            </div>
        <?php endif; ?>

        <!-- Add/Edit Venue -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            ADD/EDIT VENUE
                            <small>Configure available venues for marriage registrations</small>
                        </h2>
                    </div>
                    <div class="body">
                        <form method="post" action="<?= base_url('rom/saveVenue') ?>" id="venueForm">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" id="venue_id">
                            <div class="row clearfix">
                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="venue_name" id="venue_name" class="form-control" required>
                                            <label class="form-label">Venue Name *</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Venue Type *</label>
                                        <select name="venue_type" id="venue_type" class="form-control show-tick" required>
                                            <option value="">-- Select Type --</option>
                                            <option value="Simple">Simple</option>
                                            <option value="Classic">Classic</option>
                                            <option value="Premium">Premium</option>
                                            <option value="Outdoor">Outdoor</option>
                                            <option value="Special">Special</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="number" name="capacity" id="capacity" class="form-control" min="1">
                                            <label class="form-label">Capacity</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="number" name="price" id="price" class="form-control" 
                                                   step="0.01" min="0" required>
                                            <label class="form-label">Price (RM) *</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                                               class="filled-in chk-col-green" checked>
                                        <label for="is_active">Active</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-11">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                                            <label class="form-label">Description</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-primary waves-effect">
                                        <i class="material-icons">save</i> SAVE
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Venues Grid -->
        <div class="row clearfix">
            <?php foreach($venues as $venue): ?>
            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header <?= 
                        $venue['venue_type'] == 'Premium' ? 'bg-amber' : 
                        ($venue['venue_type'] == 'Classic' ? 'bg-blue' : 
                        ($venue['venue_type'] == 'Outdoor' ? 'bg-teal' : 'bg-green')) ?>">
                        <h2 style="color: white;">
                            <?= $venue['venue_name'] ?>
                            <small style="color: white;"><?= $venue['venue_type'] ?> Venue</small>
                        </h2>
                        <ul class="header-dropdown m-r--5">
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" 
                                   role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons" style="color: white;">more_vert</i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li><a href="javascript:void(0);" onclick="editVenue(<?= htmlspecialchars(json_encode($venue)) ?>)">
                                        <i class="material-icons">edit</i> Edit</a></li>
                                    <li><a href="javascript:void(0);" onclick="deleteVenue(<?= $venue['id'] ?>)">
                                        <i class="material-icons">delete</i> Delete</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="venue-details">
                            <p><strong>Capacity:</strong> <?= $venue['capacity'] ?: 'Not specified' ?> persons</p>
                            <p><strong>Price:</strong> <span class="text-success">RM <?= number_format($venue['price'], 2) ?></span></p>
                            <p><strong>Status:</strong> 
                                <span class="label label-<?= $venue['is_active'] ? 'success' : 'danger' ?>">
                                    <?= $venue['is_active'] ? 'ACTIVE' : 'INACTIVE' ?>
                                </span>
                            </p>
                            <?php if($venue['description']): ?>
                            <hr>
                            <p><small><?= $venue['description'] ?></small></p>
                            <?php endif; ?>
                        </div>
                        <div class="venue-stats">
                            <hr>
                            <div class="row text-center">
                                <div class="col-xs-4">
                                    <small>Total Bookings</small>
                                    <h5>
                                        <?php
                                        // Get booking count for this venue
                                        $bookingModel = new \App\Models\RomBookingModel();
                                        $count = $bookingModel->where('venue_id', $venue['id'])->countAllResults();
                                        echo $count;
                                        ?>
                                    </h5>
                                </div>
                                <div class="col-xs-4">
                                    <small>This Month</small>
                                    <h5>
                                        <?php
                                        $monthCount = $bookingModel->where('venue_id', $venue['id'])
                                            ->where('MONTH(booking_date)', date('m'))
                                            ->where('YEAR(booking_date)', date('Y'))
                                            ->countAllResults();
                                        echo $monthCount;
                                        ?>
                                    </h5>
                                </div>
                                <div class="col-xs-4">
                                    <small>Revenue</small>
                                    <h5>RM <?= number_format($count * $venue['price'], 0) ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Add New Venue Card -->
        <div class="row clearfix">
            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <div class="card add-venue-card" onclick="scrollToForm()">
                    <div class="body text-center" style="padding: 60px 20px;">
                        <i class="material-icons" style="font-size: 48px; color: #ccc;">add_circle_outline</i>
                        <h4 style="color: #999;">Add New Venue</h4>
                        <p style="color: #999;">Click to add a new venue</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function editVenue(venue) {
    $('#venue_id').val(venue.id);
    $('#venue_name').val(venue.venue_name);
    $('#venue_type').val(venue.venue_type).selectpicker('refresh');
    $('#capacity').val(venue.capacity);
    $('#price').val(venue.price);
    $('#description').val(venue.description);
    $('#is_active').prop('checked', venue.is_active == 1);
    
    // Update labels
    $('#venue_name').parent().addClass('focused');
    $('#capacity').parent().addClass('focused');
    $('#price').parent().addClass('focused');
    if (venue.description) {
        $('#description').parent().addClass('focused');
    }
    
    scrollToForm();
}

function deleteVenue(id) {
    if (confirm('Are you sure you want to delete this venue? This action cannot be undone.')) {
        window.location.href = '<?= base_url('rom/deleteVenue/') ?>' + id;
    }
}

function scrollToForm() {
    $('html, body').animate({
        scrollTop: $("#venueForm").offset().top - 100
    }, 500);
}

// Clear form
function clearForm() {
    $('#venueForm')[0].reset();
    $('#venue_id').val('');
    $('.selectpicker').selectpicker('refresh');
    $('.form-line').removeClass('focused');
}
</script>

<style>
.venue-details p {
    margin-bottom: 8px;
}
.venue-stats {
    margin-top: 15px;
}
.add-venue-card {
    cursor: pointer;
    border: 2px dashed #ddd;
    transition: all 0.3s ease;
}
.add-venue-card:hover {
    border-color: #2196F3;
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}
.card .header small {
    color: rgba(255,255,255,0.8);
}
</style>