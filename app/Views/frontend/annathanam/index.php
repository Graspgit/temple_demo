<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/typicons/typicons.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/css/vertical-layout-light/style.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/style.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/mdi/css/materialdesignicons.min.css" />
<link rel="shortcut icon" href="<?php echo base_url(); ?>/assets/archanai/images/favicon.png" />
  <link href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css" rel="stylesheet">
<!-- Load jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Load Bootstrap and Bootstrap-Select -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<!-- Bootstrap-Select CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>

<style>
    .error{
        color:red;
    }
    body {
        height: 100vh;
        width: 100%;
    }

    .prod::-webkit-scrollbar {
        width: 3px;
    }

    .prod::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .prod::-webkit-scrollbar-thumb {
        background: #d4aa00;
    }

    .prod::-webkit-scrollbar-thumb:hover {
        background: #e91e63;
    }

    a {
        text-decoration: none !important;
    }

    .table tr th {
        border: 1px solid #f7e086;
        font-size: 14px;
        background: #f7ebbb;
        color: #333232;
    }

    .pack,
    .pay {
        margin-bottom: 15px;
    }

    .form-label {
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 1px;
        color: #333333;
        text-align: left;
        width: 100%;
    }

    .input {
        width: 100%;
        text-align: left;
    }

    select.input {
        color: #000;
    }

    .sidebar-icon-only .sidebar .nav .nav-item .nav-link .menu-title {
        display: block !important;
        font-size: 11px;
        color: #FFFFFF;
    }

    .sidebar .nav .nav-item.active>.nav-link i.menu-icon {
        background: #edc10f;
        padding: 1px;
        list-style: outside;
        border-radius: 5px;
        box-shadow: 2px 5px 15px #00000017;
    }

    .sidebar-icon-only .sidebar .nav .nav-item .nav-link {
        display: block;
        padding-left: 0.25rem;
        padding-right: 0.25rem;
        text-align: center;
        position: static;
    }

    .sidebar-icon-only .sidebar .nav .nav-item .nav-link[aria-expanded] .menu-title {
        padding-top: 7px;
    }

    .sidebar-icon-only .main-panel {
        width: calc(100% - 0px);
    }

    .back {
        background: #00000087;
        padding: 15px;
        color: white;
        min-height: 120px;
    }

    .back h5 {
        min-height: 80px;
        font-size: 15px;
        font-weight: bold;
        color: #FFFFFF;
    }

    .greensubmit {
        background: #ab8a04 !important;
        font-weight: bold !important;
        color: #ffffff !important;
        box-shadow: -1px 10px 20px #ab8a04;
        background: #ab8a04 !important;
        background: -moz-linear-gradient(left, #ab8a04 0%, #ab8a04 80%, #ab8a04 100%) !important;
        background: -webkit-linear-gradient(left, #ab8a04 0%, #ab8a04 80%, #ab8a04 100%) !important;
        background: linear-gradient(to right, #ab8a04 0%, #ab8a04 80%, #ab8a04 100%) !important;
    }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0; 
    }
    .annathanam_container {
        width: 100%;
        margin: 0 auto;
        background-color: #ffffff;
        padding: 20px;
        /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
    }

    h3 {
        margin-top: 20px;
        margin-bottom: 20px;
        color: #333;
    }

    .row {
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .btn-info {
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-info:hover {
        background-color: #0056b3;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th, .table td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .table th {
        background-color: #f2f2f2;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    ul.payment1 {
      list-style-type: none;
      width: 100%;
      display: flex;
      justify-content: space-between;
      margin-bottom: 0;
      padding-left: 0;
    }

    .payment1 li {
      display: inline-block;
      text-align: center;
      width: 50%;
    }

    .payment1 li label {
      border: 1px solid #CCC;
      border-radius: 5px;
      line-height: 1;
      padding: 15px 20px;
      display: block;
      position: relative;
      margin: 15px 15px;
      cursor: pointer;
      font-weight: bold;
    }

    .payment1 li label:before {
      background-color: white;
      color: white;
      content: " ";
      display: block;
      border-radius: 50%;
      border: 1px solid grey;
      position: absolute;
      top: -5px;
      left: -5px;
      width: 18px;
      height: 18px;
      text-align: center;
      line-height: 18px;
      transition-duration: 0.4s;
      transform: scale(0);
    }

    .payment1 li label i.mdi {
      transition-duration: 0.2s;
      transform-origin: 50% 50%;
      font-size: 18px;
      color: #0d2f95;
    }

    .payment1 li :checked+label {
      background: #f6ef08;
    }

    .payment1 li :checked+label:before {
      content: "✓";
      background-color: green;
      transform: scale(1);
    }

    .payment1 li label :checked+i.mdi {
      transform: scale(0.9);
    }

    input[type="radio"][id^="cb"] {
      display: none;
    }

    input[type="checkbox"][class^="package_amt"] {
      display: none;
    }

    /* Basic reset and box sizing */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Style the container */
    .payment-options {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px; /* space between buttons */
    }

    /* Hide the actual radio input */
    .payment-options .payment_type {
        display: none;
    }

    /* Style labels to look like buttons */
    .payment-options .btn-payment {
        padding: 10px 20px;
        cursor: pointer;
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        transition: background-color 0.3s, color 0.3s;
        display: inline-block;
        border-radius: 5px;
    }

    /* Change style when radio is checked */
    .payment-options .payment_type:checked + .btn-payment {
        background-color: #008000; /* Green */
        color: white;
    }

    /* Hover effect for the buttons */
    .payment-options .btn-payment:hover {
        background-color: #45a049;
    }
    .row.clearfix {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        border-bottom: 1px dashed #CCC;
        padding: 10px;
    }

    .payment-options {
        flex-grow: 1; /* Takes up the full width of the container */
        display: flex;
        justify-content: center; /* Centers the payment options */
        padding: 10px; /* Additional padding for better spacing */
        background-color: #f9f9f9; /* Optional: for better visibility of padding */
    }

    .partial_paid_sec {
        flex-grow: 1; /* Optional: Allows this section to take equal space */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .pay-label {
        margin: 0 10px; /* Adds some space between the buttons */
    }
    .button-container {
        display: flex;
        justify-content: center;  /* Centers the content horizontally */
        align-items: center;      /* Centers the content vertically if needed */
        padding: 10px;            /* Adds some padding around the button for spacing */
    }

    .custom-checkbox {
        position: relative;
        padding-left: 25px;
        cursor: pointer;
        font-size: 16px;
        user-select: none;
    }

    .custom-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .custom-checkbox input:checked ~ .checkmark {
        background-color: #2196F3;
    }

    .custom-checkbox input:checked ~ .checkmark:after {
        display: block;
    }

    .custom-checkbox .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 20px;
        width: 20px;
        background-color: #eee;
        border: 1px solid #ddd;
    }

    .custom-checkbox .checkmark:after {
        content: "";
        position: absolute;
        display: none;
        left: 7px;
        top: 3px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        transform: rotate(45deg);
    }
</style>
</head>

<body class="sidebar-icon-only">
  <div class="container-scroller">

    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->

      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <?php if ($_SESSION['succ'] != '') { ?>
            <div class="row" style="padding: 0 30%;margin: 0px 0 15px 0;" id="content_alert">
              <div class="suc-alert" style="width: 100%;">
                <span class="suc-closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <p>
                  <?php echo $_SESSION['succ']; ?>
                </p>
              </div>
            </div>
          <?php } ?>
          <?php if ($_SESSION['fail'] != '') { ?>
            <div class="row" style="padding: 0 30%;margin: 0px 0 15px 0;" id="content_alert">
              <div class="alert" style="width: 100%;">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <p>
                  <?php echo $_SESSION['fail']; ?>
                </p>
              </div>
            </div>
          <?php } ?>
          <div class="row">
            <div class="col-md-12 card" style="padding:20px;">
              <form id="form_validation" action="<?php echo base_url(); ?>/annathanam_counter/save_annathanam" method="POST">
                <div class="form-container card-body" align="center">
                  <div class="container-fluid">
                    <div class="row">

                      <div class="body">
                        <div class="container-fluid">
                        <div class="row clearfix">
                            <input type="hidden" name="date" id="date" class="form-control" value="<?php echo date('Y-m-d'); ?>"  >
                            <input type="hidden" name="term_setting" id="term_setting" class="form-control" value="<?php echo $setting['enable_terms']; ?>">
                            <div class="col-sm-4">
                              <div class="form-group"><label class="form-label">Event Date <span
                                    style="color: red;">*</span></label>
                                <input type="date" name="event_date" id="event_date" class="form-control" autocomplete="off" value="<?php echo date("d-m-Y");?>"
                                  required>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group"><label class="form-label">Bill No</label>
                                <input type="text" class="form-control" name="billno" id="billno" value="<?php echo $bill_no; ?>"
                                  readonly>
                              </div>
                            </div>
                            <div class="col-sm-1">
                              <div class="form-group">
                                <label class="form-label">&nbsp;</label>
                                <select class="form-control" id="phone_code" name="phone_code">
                                  <option value="">code</option>
                                  <?php
                                  if (count($phone_codes) > 0) {
                                    foreach ($phone_codes as $phone_code) {
                                      ?>
                                      <option value="<?php echo $phone_code['dailing_code']; ?>" <?php if($phone_code['dailing_code'] == "+60"){ echo "selected";}?>>
                                          <?php echo $phone_code['dailing_code']; ?>
                                      </option>
                                      <?php
                                    }
                                  }
                                  ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="form-group"><label class="form-label">Mobile Number <span style="color: red;">*</span></label>
                                <input type="number" min="0" class="form-control" name="phone_no" id="phone_no" required>
                                <small id="error-message" style="color: red; display: none;">Invalid phone number format.</small>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group"><label class="form-label">Name <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" name="name" id="name" required>
                              </div>
                            </div>
                            
                            <div class="col-sm-2">
                              <div class="form-group"><label class="form-label">DOB </label>
                                <input type="date" class="form-control" name="dob" id="dob" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                            </div>
                            <!-- <div class="col-md-1" style="margin: 30px 0;text-align:right">
                                <b>Time :</b> 
                            </div> -->
                            <div class="col-md-3" style="margin: 30px 0;text-align:left">
                                <p style="margin-bottom: 0rem; text-align: center;"><strong> Select Slot</strong></p><br>
                                <input  type="checkbox" id="breakfast" name="time" value="Breakfast" class="check_time" >
                                <label for ='breakfast'> Breakfast &nbsp;&nbsp; </label>
                                <input  type="checkbox" id="lunch" name="time" value="Lunch" class="check_time" >
                                <label for ='lunch'> Lunch &nbsp;&nbsp; </label>
                                <input  type="checkbox" id="dinner" name="time" value="Dinner" class="check_time" >
                                <label for ='dinner'> Dinner &nbsp;&nbsp; </label>
                            </div>
                            <div class="col-md-3" id="time-picker-container" style="margin: 20px 0;">
                                <label for="hour">Select Time:</label>
                                <div style="display: flex; gap: 10px;">
                                    <select id="hour" name="hour" class="form-control" style="display:inline-block;"> </select>
                                    :
                                    <select id="minute" name="minute" class="form-control" style="display:inline-block;"> </select>
                                    <select id="ampm" name="ampm" class="form-control" style="display:inline-block;" disabled>
                                        <option value="AM">AM</option>
                                        <option value="PM">PM</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group"><label class="form-label">Annathanam Package </label>
                                    <select class="form-control" name="package_id" id="package_id" required>
                                        <option value="">--select Annathanam Package--</option>
                                        <?php if (count($packages) > 0):
                                            foreach ($packages as $pack): ?>
                                                <option value="<?php echo $pack['id']; ?>" data-amount="<?php echo $pack['amount']; ?>" > 
                                                    <?php echo $pack['name_eng'] . ' / ' . $pack['name_tamil']; ?>
                                                </option>
                                            <?php endforeach;
                                        endif; ?>
                                    </select>
                                </div>
                                <input type="hidden" name="package_name" id="package_name" value="">
                            </div>
                            
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-label">Package amount per pax</label>
                                    <input type="number" name="amount" id="amount" min="0" step=".01" class="form-control" value="0.00" readonly>
                                </div>
                            </div>
                            
                            <div class="col-sm-4">
                                <div class="form-group form-float">
                                    <div class="form-line focused">
                                        <label class="form-label">No of Pax (*Minimum <?php echo $setting['annathanam_min_pax']; ?> pax)<span style="color: red;">*</span></label>
                                        <input type="number" id="no_of_pax" min="<?php echo $setting['annathanam_min_pax']; ?>" name="no_of_pax" class="form-control" value="<?php echo !empty($data['no_of_pax']) ? $data['no_of_pax'] : ""; ?>" <?php echo $readonly; ?> required placeholder="0" >
                                    </div>
                                </div>
                            </div><br>

                            <div class="col-sm-12">
                              <div class="row">
                                <h3 style="text-align:center;">Annathanam Items</h3>
                                    <div class="table-responsive">
                                        <table class="table" id="annathanam_items_table">
                                            <thead>
                                                <tr>
                                                    <th width="10%">S.no</th>
                                                    <th width="60%">Service</th>
                                                    <th width="30%">Description</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Items will be dynamically added here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12"> 
                                <div class="annathanam_special_items" style="display: none;">
                                    <h3 style="text-align:center;">Annathanam Special Items<small><b></b></small></h3>
                                    <div class="row clearfix">
                                        <div class="col-sm-5">
                                            <div class="form-group form-float">
                                                <select class="form-control" id="special_dropdown">
                                                    <option value="">-- Select Service --</option> 
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2" align="left">
                                            <div class="form-group form-float">
                                                <button class="btn btn-success" id="add_special_item" type="button">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table" id="annathanam_special_table">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align: center" width="5%">S.no</th>
                                                            <th style="text-align: center" width="20%">Type</th>
                                                            <th style="text-align: center" width="25%">Service</th>
                                                            <th style="text-align: center" width="15%">Amount</th>
                                                            <th style="text-align: center" width="10%">Quantity</th>
                                                            <th style="text-align: center" width="15%">Total</th>
                                                            <th style="text-align: center" width="10%">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Items will be dynamically added here -->
                                                    </tbody>
                                                </table>
                                            </div>
                                            <input type="hidden" id="pack_row_count_special" value="1">
                                        </div>
                                    </div>
                                </div>
                            </div> <br>

                            <div class="col-md-12"> 
                                <h3 style="text-align:center;">Annathanam Add-on Items<small><b></b></small></h3><br>
                                <div class="row clearfix">
                                    <div class="col-sm-5">
                                        <div class="form-group form-float">
                                            <select class="form-control" id="annathanamAddonDropdown">
                                                <option value="">-- Select Service --</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group form-float">
                                            <div class="form-line focused">
                                                <input type="hidden" id="pack_name_addon">
                                                <input type="number" class="form-control" id="get_pack_amt_addon" placeholder="0.00" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2" align="left">
                                        <div class="form-group form-float">
                                            <button class="btn btn-success" id="add_annathanam_addon" type="button">Add</button>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table class="table" id="annathanam_addon_items_table">
                                                <thead>
                                                    <tr>
                                                        <th width="10%">S.no</th>
                                                        <th width="30%">Addon</th>
                                                        <th width="15%">Quantity</th>
                                                        <th width="15%">Item Amount</th>
                                                        <th width="15%">Item Total</th>
                                                        <th width="15%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                        <input type="hidden" id="pack_row_count_addon" value="1">
                                    </div>
                                </div>
                            </div><br>
                            <hr>
                            <br>
                            <div class="col-md-12">
                                <div class="addi_hide" style="display: none">
                                    <h3 style="text-align:center;">Additional Items<small><b></b></small></h3><br>
                                    <div class="scroll row">
                                        <div class="col-sm-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" id="addi_item_name" class="form-control" placeholder="Item Name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="number" id="addi_item_amount" class="form-control" placeholder="Amount">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group form-float">
                                                <label id="add_addi_item" class="btn btn-success" style="padding: 5px 12px !important;">Add</label>
                                            </div>
                                        </div>
                                    </div>

                                    <table id="addi_item_table" class="table">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Item Name</th>
                                                <th>Quantity</th>
                                                <th>Amount(S$)</th>
                                                <th>Total Amount(S$)</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Dynamically added rows will appear here -->
                                        </tbody>
                                        <input type="hidden" id="pack_row_count_addi" value="1">
                                    </table>
                                </div><br><br>
                            </div><br><br>

                            <div class="row clearfix">
                                <div class="col-sm-3" <?php if(!empty($setting['annathanam_discount'])){ echo ' style="display: block;"'; }else echo ' style="display: none;"'; ?>>
                                    <div class="form-group">
                                        <label class="form-label" style="text-align: center">Discount</label>
                                        <input type="number" name="discount_amount" id="discount_amount" min="0" step=".01" class="form-control" value="0">
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label class="form-label" style="text-align: center">Total Amount</label>
                                        <input type="number" name="total_amount" id="total_amount" min="0" step=".01" class="form-control" value="0.00" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix" style="width:105%; border-bottom:1px dashed #CCC; display: flex; justify-content: center; align-items: center;">
                              <div class="col-sm-10">
                                <div class="payment-options" style="flex-grow: 1; display: flex; justify-content: center;">
                                    <div class="form-group">
                                        <input type="radio" name="payment_type" id="payment_type_full" class="payment_type" value="full" 
                                            <?php echo (empty($data['payment_type']) || $data['payment_type'] == 'full') ? 'checked' : ''; ?>>
                                        <label for="payment_type_full" class="pay-label btn-payment">Full Payment</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="radio" name="payment_type" id="payment_type_partial" class="payment_type" value="partial" 
                                            <?php echo ($data['payment_type'] == 'partial') ? 'checked' : ''; ?>>
                                        <label for="payment_type_partial" class="pay-label btn-payment">Partial Payment</label>
                                    </div>
                                </div>
                              </div>
                              <div class="col-sm-3 partial_paid_sec" align="center" style="<?php echo (!empty($data['payment_type']) && $data['payment_type'] == 'partial') ? '' : 'display: none;'; ?>">
                                  <label class="form-label" style="text-align: center">Pay Amount</label>
                                  <input type="number" name="paid_amount" id="paid_amount" step=".01" class="form-control" value="<?php echo $data['paid_amount'] ?? '0.00'; ?>">
                              </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6"> 
                    <ul class="payment1">
                        <?php foreach ($payment_mode as $key => $pay) { ?>
                        <li>
                            <input type="radio" name="pay_method" id="cb<?php echo $pay['id']; ?>" value="<?php echo $pay['id']; ?>" data-name="<?php echo $pay['name']; ?>"/>
                            <label for="cb<?php echo $pay['id']; ?>">
                                <?php echo $pay['name']; ?>
                            </label>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="col-sm-12" <?php if(!empty($setting['enable_terms'])){ echo ' style="display: block;"'; }else echo ' style="display: none;"'; ?>>
                    <div class="col-sm-12" style="text-align:center; color: #f44336;">
                        <div class="form-group">
                            <label for="termsLink" id="termsLabel" style="cursor: pointer;"><i class="fa fa-check-square-o" style="color:red"></i>Terms and conditions</label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" align="center" style="margin:0 auto;">
                    <input type="submit" value="SAVE" id="saveButton" class="button button-white greensubmit">
                </div>
            </div>
        </div>

        </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

</div>

<div id="alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <p style="text-align:center;"><br><i class="mdi mdi-alert-circle-outline" style="font-size:42px; color:red;"></i></p>
                <h5 style="text-align:center;" id="spndeddelid"></h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">OK</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>

<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width:fit-content;">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body-terms">
            <?php foreach ($terms as $term): ?>
                <div class="form-group">
                    <label class="custom-checkbox">
                        <?php echo htmlspecialchars($term); ?>
                        <input type="checkbox" class="term-checkbox" name="terms[]" value="<?php echo htmlspecialchars($term); ?>">
                        <span class="checkmark"></span>
                    </label>
                </div>
            <?php endforeach; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="validationModal" tabindex="-1" role="dialog" aria-labelledby="validationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="validationModalLabel" style="Text-align:center">Attention here!!</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="validationModalBody" style="Text-align:center; font-size: 16px;">
                <!-- Validation messages will be inserted here -->
            </div>
        </div>
    </div>
</div>


<!-- container-scroller -->
<script src="<?php echo base_url(); ?>/assets/archanai/js/jquery.min.js"></script>
<!-- base:js -->
<script src="<?php echo base_url(); ?>/assets/archanai/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page-->
<script src="<?php echo base_url(); ?>/assets/archanai/vendors/chart.js/Chart.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/archanai/js/jquery.cookie.js" type="text/javascript"></script>
<!-- End plugin js for this page-->
<!-- inject:js -->
<script src="<?php echo base_url(); ?>/assets/archanai/js/off-canvas.js"></script>
<script src="<?php echo base_url(); ?>/assets/archanai/js/hoverable-collapse.js"></script>
<script src="<?php echo base_url(); ?>/assets/archanai/js/template.js"></script>
<script src="<?php echo base_url(); ?>/assets/archanai/js/settings.js"></script>
<script src="<?php echo base_url(); ?>/assets/archanai/js/todolist.js"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<!-- <script src="<?php echo base_url(); ?>/assets/archanai/js/dashboard.js"></script> -->
<script src="<?php echo base_url(); ?>/assets/jquery.validate.js"></script>

<link href="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>

<!-- Phone number validation and User Autofill -->
<script>
    updatePhoneInputInstructions();
    document.getElementById('phone_no').addEventListener('input', function() {
        validatePhoneNumber();
    });

    document.getElementById('phone_code').addEventListener('change', function() {
        updatePhoneInputInstructions();
        validatePhoneNumber();
    });

    function updatePhoneInputInstructions() {
        const phoneCode = document.getElementById('phone_code').value;
        const phoneInput = document.getElementById('phone_no');

        switch (phoneCode) {
            case "+60":
                phoneInput.placeholder = "Enter a valid 8-9 digit number starting with 1.";
                break;
            case "+65":
                phoneInput.placeholder = "Enter a valid 8-digit number starting with 8 or 9.";
                break;
            case "+91":
                phoneInput.placeholder = "Enter a 10-digit number starting with 7, 8, or 9.";
                break;
            default:
                phoneInput.placeholder = "Enter a valid phone number.";
                break;
        }
    }

    function validatePhoneNumber() {
        const phoneCode = document.getElementById('phone_code').value;
        const phoneNumber = document.getElementById('phone_no').value;
        const errorMessage = document.getElementById('error-message');
        let isValid = false;

        const phoneValidationPatterns = {
            "+60": /^1[1-9]\d{7,8}$/, // Malaysia: Starts with 1, followed by 8 or 9 digits (without leading 0)
            "+65": /^[89]\d{7}$/,     // Singapore: Starts with 8 or 9, followed by 7 digits
            "+91": /^[6789]\d{9}$/,    // India: Starts with 7, 8, or 9, followed by 9 digits
            "default": /^[0-9]{7,15}$/ // General validation for any number (7 to 15 digits)
        };

        if (phoneCode === "+60" && phoneNumber.startsWith('0')) {
            errorMessage.textContent = "Invalid phone number format: Cannot start with 0 for Malaysia (+60).";
            errorMessage.style.display = 'block';
            return;
        }

        const pattern = phoneValidationPatterns[phoneCode] || phoneValidationPatterns['default'];

        if (pattern) {
            isValid = pattern.test(phoneNumber);
        }

        if (isValid) {
            errorMessage.style.display = 'none';  // Hide error message if valid
        } else {
            errorMessage.style.display = 'block'; // Show error message if invalid
        }
    }

    function loadUserData() {
        var phone_code = $('#phone_code').val();
        var phone_number = $('#phone_no').val();
        $.ajax({
            url: "<?php echo base_url(); ?>/annathanam_counter/get_devotee_details", 
            method: "POST",
            data: {code: phone_code, number:  phone_number},
            datatype: "json",
            success: function(response) {
                response = JSON.parse(response);
                console.log(response);
                
                $('#name').val(response.name);
                $('#dob').val(response.dob);
            },
            error: function() {
                alert("Failed to load user data.");
            }
        });
    }

    $('#phone_no').on('blur', function() {
        loadUserData();
    });
</script>

<!-- Add Package and Items -->
<script>
    $("#package_id").change(function(){
        var id = $("#package_id").val();
        var amount = $("#package_id option:selected").data('amount'); 
        $("#amount").val(Number(amount).toFixed(2));

        if(id == 1) {
            $('#no_of_pax').closest('.col-sm-4').hide();
            $('#amount').closest('.col-sm-4').hide();
            $('#annathanam_items_table').closest('.col-sm-12').hide();
            $('.annathanam_special_items').show();
            var min_pax = <?php echo $setting['annathanam_min_pax']; ?>;
            $.ajax({
                url: '<?php echo base_url(); ?>/annathanam_counter/get_special_items',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log('special data:', data);
                    var $dropdown = $('#special_dropdown');
                    $dropdown.empty().append('<option value="">-- Select Service --</option>'); 

                    $.each(data.special, function(typeName, typeData) {
                        var $group = $('<optgroup>', {
                            label: typeName,
                            style: "font-weight: bold"
                        });

                        $.each(typeData.items, function(index, item) {
                            $group.append($('<option>', {
                                value: item.id,
                                text: item.name_eng + ' / ' + item.name_tamil,
                                'data-amount': item.amount,
                                'data-type-id': item.type_id
                            }));
                        });

                        $dropdown.append($group);
                    });

                    $('#annathanamAddonDropdown').empty();
                    if (data.addons.length > 0) {
                        var a_html = '<option value="">Select From</option>';
                        data.addons.forEach(function(value, key) {
                            a_html += '<option data-addon="' + value.add_on + '" data-amount="' + value.amount + '" value="' + value.id + '">' + value.name_eng + ' / ' + value.name_tamil + '</option>';
                        });
                    } else {
                        var a_html = '<option value="">No Addons Found</option>';
                    }
                    $('#annathanamAddonDropdown').html(a_html);  

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error fetching special items: ' + textStatus);
                }
            });
                
        } else {
            $('#no_of_pax').closest('.col-sm-4').show();
            $('#amount').closest('.col-sm-4').show();
            $('#annathanam_items_table').closest('.col-sm-12').show();
            $('.annathanam_special_items').hide();

            $.ajax({
                url: "<?php echo base_url();?>/annathanam_counter/get_items_by_package_id",
                type: "post",
                data: {id: id},
                dataType: "json",
                success: function(response){
                    $("#annathanam_items_table tbody").empty();
                    var count = 1; 
                    if (response.items.length > 0) {
                        response.items.forEach(function(item) {
                            if(item.add_on == 0) { 
                                var row = '<tr>';
                                row += '<td>' + count + '</td>'; 
                                row += '<td>' + item.name_eng + ' / ' + item.name_tamil + '</td>'; 
                                row += '<td>' + item.description + '</td>'; 
                                row += '</tr>';
                                $("#annathanam_items_table tbody").append(row); 
                                count++; 
                            } 
                        });
                    } else {
                        var row = '<tr>';
                        row += '<td> No Package items found for the selected Package</td>'; 
                        row += '</tr>';
                        $("#annathanam_items_table tbody").append(row); 
                    }

                    $('#annathanamAddonDropdown').empty();
                    if (response.addons.length > 0) {
                        var a_html = '<option value="">Select From</option>';
                        response.addons.forEach(function(value, key) {
                            a_html += '<option data-addon="' + value.add_on + '" data-amount="' + value.amount + '" value="' + value.id + '">' + value.name_eng + ' / ' + value.name_tamil + '</option>';
                        });
                    } else {
                        var a_html = '<option value="">No Addons Found</option>';
                    }
                    $('#annathanamAddonDropdown').html(a_html);  
                }
            });
        }
        sum_amount();  
    });

    $("#annathanamAddonDropdown").change(function(){
        var addon_value = $("#annathanamAddonDropdown option:selected").data('addon'); 
        if (addon_value == 2) {
            $('.addi_hide').show();
        } else {
            var amount = $("#annathanamAddonDropdown option:selected").data('amount'); 
            $("#get_pack_amt_addon").val(Number(amount).toFixed(2));
        }
    });

    $("#no_of_pax").change(function(){
        sum_amount();
    });
</script>

<!-- Add Addon Item -->
<script>
    var cnt = 1;
    $("#add_annathanam_addon").click(function(){
        var validationMessage = '';
        var addon_value = $("#annathanamAddonDropdown option:selected").data('addon'); 
        if (addon_value == 2) {
            validationMessage += 'You have choosen Additional Items, Kindly provide details about the Additional Item below.<br>';
        } else {
            var id = $("#annathanamAddonDropdown option:selected").val();
            var name = $("#annathanamAddonDropdown option:selected").text();
            //var cnt = parseInt($("#pack_row_count_addon").val());
            var amt = $("#annathanamAddonDropdown option:selected").data('amount'); 
            var package_id = $('#package_id').val();
            if (package_id == 1){
                var pack_pax = <?php echo !empty($setting['annathanam_min_pax']) ? $setting['annathanam_min_pax'] : 1; ?>;
            } else {
                var pack_pax = $('#no_of_pax').val();
            }

            if (pack_pax != 0) {
                if (id != '' && parseFloat(amt) > 0) {
                    var status_check = 0;
                    var rowId;

                    $(".package_category_addon").each(function() {
                        var arcat = parseInt($(this).val());
                        if (arcat == id) {
                            status_check++;
                            rowId = $(this).closest('tr').attr('id').replace('rmv_packrow_addon', '');
                        }
                    });

                    if (status_check > 0) {
                        var quantity = $("#quantity" + rowId);
                        var currentVal = parseInt(quantity.val());
                        quantity.val(currentVal + 1);
                        updateAmount(rowId);
                        sum_amount();
                        $("#get_pack_amt_addon").val('');
                        $('#annathanamAddonDropdown').prop('selectedIndex', 0);
                    } else {
                        
                        var countid = id;
                        var serviceid = id;
                        var quantity = 1;
                        var serviceamount = amt;
            
                        var html = '<tr id="rmv_packrow_addon' + countid + '">';
                        html += '<td style="width: 10%; text-align: center;">' + cnt + '</td>';
                        html += '<td style="width: 20%; text-align: center;"><input type="hidden" readonly name="add_on[' + countid + '][id]" value="' + serviceid + '"><input type="text" style="border: none;width: 100%; text-align: center;" readonly id="service_name_addon_' + countid + '" data-amount="' + serviceamount + '"></td>';
                        html += '<td style="width: 20%; text-align: center;">';
                        html += '<div class="itemcountrr" style="display: flex; justify-content: center; align-items: center;">';
                        html += '<div class="value-button" id="decrease" onclick="decreaseValue(' + countid + ')" value="Decrease Value" style="font-weight: bold;font-size: 16px; cursor: pointer; padding: 5px;">-</div>';
                        html += '<input type="number" name="add_on[' + countid + '][quantity]" id="quantity' + countid + '" value="' + pack_pax + '" pattern="[0-9]*" class="qty_amt" style="text-align: center;border: none;border-top: 1px solid #ddd;border-bottom: 1px solid #ddd;margin: 0px;width: 35px;height: 25px;" min="1" onkeyup="qtykeyup(' + countid + ')" />';
                        html += '<div class="value-button" id="increase" onclick="increaseValue(' + countid + ')" value="Increase Value" style="font-weight: bold;font-size: 16px; cursor: pointer; padding: 5px;">+</div></div></td>';
                        html += '<td style="width: 20%; text-align: center;"><input type="text" style="border: none;width: 100%; text-align: center;" class="editable_amt_addon" id="editable_amt_addon_' + countid + '" name="add_on[' + countid + '][amount]" value="' + Number(serviceamount).toFixed(2) + '" onkeyup="editableAmountKeyUp(' + countid + ')"></td>';
                        html += '<td style="width: 25%; text-align: center;"><input type="text" style="border: none;width: 100%; text-align: center;" class="package_amt_addon" id="package_amt_addon_' + countid + '" name="add_on[' + countid + '][total_amount]" value="' + (Number(serviceamount).toFixed(2) * pack_pax).toFixed(2) + '"></td>';
                        html += '<td style="width: 10%; text-align: center;"><a class="btn btn-danger btn-rad" onclick="rmv_pack_addon(' + countid + ')" style="width:auto;padding: 0px 3px !important;"><i class="material-icons">X</i></a><input type="hidden" class="package_category_addon" value=' + id + '></td>';
                        html += '</tr>';
                        $("#annathanam_addon_items_table").append(html);
                        sum_amount();
                        $("#service_name_addon_" + countid).val(name);

                        $("#get_pack_amt_addon").val('');
                        $('#annathanamAddonDropdown').prop('selectedIndex', 0);
                        updateSerialNumbers();
                        cnt++;
                    }
                }
            } else {
                var validationMessage = '';

                if (!pack_pax || pack_pax <= 0) {
                    validationMessage += 'The number of Pax is invalid, Please ensure it is greater than 0.<br>';
                }
                
                if (id == '' || parseFloat(amt) <= 0) {
                    validationMessage += 'The selected item is invalid. Please select a valid item.<br>';
                }
            }
        }
        if (validationMessage != ''){
            $("#validationModalBody").html(validationMessage);
            $("#validationModal").modal('show');
        }
        
    });

    function increaseValue(cnt) {
        adjustQuantity(cnt, 1);
    }

    function decreaseValue(cnt) {
        adjustQuantity(cnt, -1);
    }

    function adjustQuantity(cnt, adjustment) {
        var quantity = $("#quantity" + cnt);
        var currentVal = parseInt(quantity.val());
        if (!isNaN(currentVal)) {
            var newQuantity = currentVal + adjustment;
            if(newQuantity > parseInt(quantity.attr('max'))) {
                newQuantity = parseInt(quantity.attr('max'));
            } else if (newQuantity < 1) {
                newQuantity = 1;
            }
            quantity.val(newQuantity);
            updateAmount(cnt);
        }
    }

    function qtykeyup(cnt) {
        updateAmount(cnt);
    }

    function editableAmountKeyUp(cnt) {
        updateAmount(cnt, true);
    }

    function updateAmount(cnt, fromAmountField = false) {
        var quantity = parseInt($("#quantity" + cnt).val()) || 0;
        var unitPrice = parseFloat($("#editable_amt_addon_" + cnt).val()) || 0; 
        var totalPrice = quantity * unitPrice;
        $("#package_amt_addon_" + cnt).val(Number(totalPrice).toFixed(2));
        
        if (!fromAmountField) {
            $("#editable_amt_addon_" + cnt).val(Number(unitPrice).toFixed(2));
        }
        sum_amount();
    }

    function rmv_pack_addon(id) {
        $("#rmv_packrow_addon" + id).remove();
        updateSerialNumbers();
        sum_amount();
    }

    function updateSerialNumbers() {
        $(".package_category_addon").each(function(index) {
            var countId = index + 1;
            $(this).closest('tr').find('td:first').text(countId);
        });
    }

    function serviceamount() {
        sum_amount();
    }
</script>

<!-- Add special Item -->
<script>
    var cnt = 1;
    $("#add_special_item").click(function(){
        var selectedOption = $('#special_dropdown option:selected');
        var id = selectedOption.val();
        var name = selectedOption.text();
        var type_name = selectedOption.closest('optgroup').attr('label');
        var type_id = selectedOption.data('type-id'); 
        var amount = parseFloat(selectedOption.data('amount')); 
        // var cnt = parseInt($("#pack_row_count_special").val());
        var package_id = $('#package_id').val();
        var min_pax = <?php echo $setting['annathanam_min_pax']; ?>;
        

        if (id != '' && parseFloat(amount) > 0) {
            var status_check = 0;
            var rowId;

            $(".package_category_special").each(function() {
                var arcat = parseInt($(this).val());
                if (arcat == id) {
                    status_check++;
                    rowId = $(this).closest('tr').attr('id').replace('rmv_packrow_special', '');
                }
            });

            if (status_check > 0) {
                var quantity = $("#special_quantity" + rowId);
                var currentVal = parseInt(quantity.val());
                quantity.val(currentVal + 1);
                updateAmount1(rowId);
                sum_amount();
                $('#special_dropdown').prop('selectedIndex', 0);
            } else {
                            
                var countid = id;
                var serviceid = id;
                var quantity = min_pax;
                var serviceamount = amount;
    
                var html = '<tr id="rmv_packrow_special' + countid + '">';
                html += '<td style="width: 5%; text-align: center;">' + cnt + '</td>';
                html += '<td style="width: 20%; text-align: center;"><input type="hidden" readonly name="special[' + countid + '][type_id]" value="' + type_id + '"><input type="text" style="border: none;width: 100%; text-align: center;" readonly id="service_type_special_' + countid + '"></td>';
                html += '<td style="width: 25%; text-align: center;"><input type="hidden" readonly name="special[' + countid + '][id]" value="' + serviceid + '"><input type="text" style="border: none;width: 100%; text-align: center;" readonly id="service_name_special_' + countid + '" data-amount="' + serviceamount + '"></td>';
                html += '<td style="width: 15%; text-align: center;"><div class="itemcountrr" style="display: flex; justify-content: center; align-items: center;">';
                html += '<div class="value-button" id="decrease" onclick="decreaseValue1(' + countid + ')" value="Decrease Value" style="font-weight: bold;font-size: 16px; cursor: pointer; padding: 5px;">-</div>';
                html += '<input type="number" name="special[' + countid + '][quantity]" id="special_quantity' + countid + '" value="' + min_pax + '" pattern="[0-9]*" class="qty_amt" style="text-align: center;border: none;border-top: 1px solid #ddd;border-bottom: 1px solid #ddd;margin: 0px;width: 35px;height: 25px;" min="1" onkeyup="qtykeyup1(' + countid + ')" />';
                html += '<div class="value-button" id="increase" onclick="increaseValue1(' + countid + ')" value="Increase Value" style="font-weight: bold;font-size: 16px; cursor: pointer; padding: 5px;">+</div></div></td>';
                html += '<td style="width: 10%; text-align: center;"><input type="text" style="border: none;width: 100%; text-align: center;" class="editable_amt_special" id="editable_amt_special_' + countid + '" name="special[' + countid + '][amount]" value="' + Number(serviceamount).toFixed(2) + '" onkeyup="editableAmountKeyUp1(' + countid + ')"></td>';
                html += '<td style="width: 15%; text-align: center;"><input type="text" style="border: none;width: 100%; text-align: center;" class="package_amt_special" id="package_amt_special_' + countid + '" name="special[' + countid + '][total_amount]" value="' + (Number(serviceamount).toFixed(2) * min_pax).toFixed(2) + '"></td>';
                html += '<td style="width: 10%; text-align: center;"><a class="btn btn-danger btn-rad" onclick="rmv_pack_special(' + countid + ')" style="width:auto;padding: 0px 3px !important;"><i class="material-icons">X</i></a><input type="hidden" class="package_category_special" value=' + id + '></td>';
                html += '</tr>';
                $("#annathanam_special_table").append(html);
                sum_amount();

                $("#service_name_special_" + countid).val(name);
                $("#service_type_special_" + countid).val(type_name);
                
                $('#special_dropdown').prop('selectedIndex', 0);
                updateSerialNumbers1();
                cnt++;
            }   
        }
    });

    function increaseValue1(cnt) {
        adjustQuantity1(cnt, 1);
    }

    function decreaseValue1(cnt) {
        adjustQuantity1(cnt, -1);
    }

    function adjustQuantity1(cnt, adjustment) {
        var quantity = $("#special_quantity" + cnt);
        var currentVal = parseInt(quantity.val());
        if (!isNaN(currentVal)) {
            var newQuantity = currentVal + adjustment;
            if(newQuantity > parseInt(quantity.attr('max'))) {
                newQuantity = parseInt(quantity.attr('max'));
            } else if (newQuantity < 1) {
                newQuantity = 1;
            }
            quantity.val(newQuantity);
            updateAmount1(cnt);
        }
    }

    function qtykeyup1(cnt) {
        updateAmount1(cnt);
    }

    function editableAmountKeyUp1(cnt) {
        updateAmount1(cnt, true);
    }

    function updateAmount1(cnt, fromAmountField = false) {
        var quantity = parseInt($("#special_quantity" + cnt).val()) || 0;
        var unitPrice = parseFloat($("#editable_amt_special_" + cnt).val()) || 0; // Always use the current value from the editable amount input
        var totalPrice = quantity * unitPrice;
        $("#package_amt_special_" + cnt).val(Number(totalPrice).toFixed(2));
        
        if (!fromAmountField) {
            $("#editable_amt_special_" + cnt).val(Number(unitPrice).toFixed(2));
        }
        sum_amount();
    }

    function rmv_pack_special(id) {
        $("#rmv_packrow_special" + id).remove();
        updateSerialNumbers1();
        sum_amount();
    }

    function updateSerialNumbers1() {
        $(".package_category_special").each(function(index) {
            var countId = index + 1;
            $(this).closest('tr').find('td:first').text(countId);
        });
    }

    function serviceamount() {
        sum_amount();
    }
</script>

<!-- Add Additional Item -->
<script>
    var cnt = 1;
    $("#add_addi_item").click(function(){
        var name = $("#addi_item_name").val();
        // var cnt = parseInt($("#pack_row_count_addi").val());
        var amt = $("#addi_item_amount").val(); 
        var package_id = $('#package_id').val();

        if (package_id == 1){
            var pack_pax = <?php echo !empty($setting['annathanam_min_pax']) ? $setting['annathanam_min_pax'] : 1; ?>;
        } else {
            var pack_pax = $('#no_of_pax').val();
        }

        if (name != '' && parseFloat(amt) > 0) {
            var status_check = 0;
            var rowId;

            $(".package_category_addi").each(function() {
                var arcat = parseInt($(this).val());
                if (arcat == cnt) {
                    status_check++;
                    rowId = $(this).closest('tr').attr('id').replace('rmv_packrow_addi', '');
                }
            });

            if (status_check > 0) {
                var quantity = $("#addi_quantity" + rowId);
                var currentVal = parseInt(quantity.val());
                quantity.val(currentVal + 1);
                updateAmount2(rowId);
                sum_amount();
                $("#get_pack_amt_addi").val('');
                $("#addi_item_name").text('');
            } else {
                
                var countid = cnt;
                var serviceid = cnt;
                var quantity = 1;
                var serviceamount = amt;
    
                var html = '<tr id="rmv_packrow_addi' + countid + '">';
                html += '<td style="width: 10%; text-align: center;">' + cnt + '</td>';
                html += '<td style="width: 20%; text-align: center;"><input type="text" style="border: none;width: 100%; text-align: center;" readonly id="service_name_addi_' + countid + '" data-amount="' + serviceamount + '" name="addi_item[' + countid + '][name]" value="'+ name +'"></td>';
                html += '<td style="width: 20%; text-align: center;">';
                html += '<div class="itemcountrr" style="display: flex; justify-content: center; align-items: center;">';
                html += '<div class="value-button" id="decrease" onclick="decreaseValue2(' + countid + ')" value="Decrease Value" style="font-weight: bold;font-size: 16px; cursor: pointer; padding: 5px;">-</div>';
                html += '<input type="number" name="addi_item[' + countid + '][quantity]" id="addi_quantity' + countid + '" value="' + pack_pax + '" pattern="[0-9]*" class="qty_amt" style="text-align: center;border: none;border-top: 1px solid #ddd;border-bottom: 1px solid #ddd;margin: 0px;width: 35px;height: 25px;" min="1" onkeyup="qtykeyup2(' + countid + ')" />';
                html += '<div class="value-button" id="increase" onclick="increaseValue2(' + countid + ')" value="Increase Value" style="font-weight: bold;font-size: 16px; cursor: pointer; padding: 5px;">+</div></div></td>';
                html += '<td style="width: 20%; text-align: center;"><input type="text" style="border: none;width: 100%; text-align: center;" class="editable_amt_addi" id="editable_amt_addi_' + countid + '" name="addi_item[' + countid + '][amount]" value="' + Number(serviceamount).toFixed(2) + '" onkeyup="editableAmountKeyUp2(' + countid + ')"></td>';
                html += '<td style="width: 25%; text-align: center;"><input type="text" style="border: none;width: 100%; text-align: center;" class="package_amt_addi" id="package_amt_addi_' + countid + '" name="addi_item[' + countid + '][total_amount]" value="' + (Number(serviceamount).toFixed(2) * pack_pax).toFixed(2) + '"></td>';
                html += '<td style="width: 10%; text-align: center;"><a class="btn btn-danger btn-rad" onclick="rmv_pack_addi2(' + countid + ')" style="width:auto;padding: 0px 3px !important;"><i class="material-icons">X</i></a><input type="hidden" class="package_category_addi" value=' + cnt + '></td>';
                html += '</tr>';
                $("#addi_item_table").append(html);
                sum_amount();
                $("#service_name_addi_" + countid).val(name);

                $("#addi_item_amount").val('');
                $("#addi_item_name").val('');
                updateSerialNumbers2();
                cnt++;
            }
        } else {
            var validationMessage = '';

            if (!pack_pax || pack_pax <= 0) {
                validationMessage += 'The number of Pax is invalid, Please ensure it is greater than 0.<br>';
            }
            
            if (id == '' || parseFloat(amt) <= 0) {
                validationMessage += 'Entered Item or Amount is Invalid.<br>';
            }
            $("#validationModalBody").html(validationMessage);
            $("#validationModal").modal('show');
        }
    });

    function increaseValue2(cnt) {
        adjustQuantity2(cnt, 1);
    }

    function decreaseValue2(cnt) {
        adjustQuantity2(cnt, -1);
    }

    function adjustQuantity2(cnt, adjustment) {
        var quantity = $("#addi_quantity" + cnt);
        var currentVal = parseInt(quantity.val());
        if (!isNaN(currentVal)) {
            var newQuantity = currentVal + adjustment;
            if(newQuantity > parseInt(quantity.attr('max'))) {
                newQuantity = parseInt(quantity.attr('max'));
            } else if (newQuantity < 1) {
                newQuantity = 1;
            }
            quantity.val(newQuantity);
            updateAmount2(cnt);
        }
    }

    function qtykeyup2(cnt) {
        updateAmount2(cnt);
    }

    function editableAmountKeyUp2(cnt) {
        updateAmount2(cnt, true);
    }

    function updateAmount2(cnt, fromAmountField = false) {
        var quantity = parseInt($("#addi_quantity" + cnt).val()) || 0;
        var unitPrice = parseFloat($("#editable_amt_addi_" + cnt).val()) || 0; // Always use the current value from the editable amount input
        var totalPrice = quantity * unitPrice;
        $("#package_amt_addi_" + cnt).val(Number(totalPrice).toFixed(2));
        
        if (!fromAmountField) {
            $("#editable_amt_addi_" + cnt).val(Number(unitPrice).toFixed(2));
        }
        sum_amount();
    }

    function rmv_pack_addi2(cnt) {
        $("#rmv_packrow_addi" + cnt).remove();
        updateSerialNumbers2();
        sum_amount();
    }

    function updateSerialNumbers2() {
        $(".package_category_addi").each(function(index) {
            var countId = index + 1;
            $(this).closest('tr').find('td:first').text(countId);
        });
    }

    function serviceamount2() {
        sum_amount();
    }
</script>

<!-- Time and slot picker -->
<script>
    $(document).ready(function() {
        $('.check_time').change(function() {
            $('#time-picker-container').hide();
            
            $('#hour').empty();
            $('#minute').empty();

            if ($('#breakfast').is(':checked')) {
                for (let i = 6; i <= 11; i++) {
                    $('#hour').append(`<option value="${i < 10 ? '0' + i : i}">${i < 10 ? '0' + i : i}</option>`); // Format hour with leading zero
                }
                for (let i = 0; i < 60; i += 5) {
                    $('#minute').append(`<option value="${i < 10 ? '0' + i : i}">${i < 10 ? '0' + i : i}</option>`); // Format minute with leading zero
                }
                $('#ampm').val('AM');
                $('#time-picker-container').show();  // Show the time picker container
            } else if ($('#lunch').is(':checked')) {

                for (let i = 12; i <= 15; i++) {
                    var time_val = i > 12 ? '0' + (i - 12) : i;
                    $('#hour').append(`<option value="${time_val}">${time_val}</option>`); // 12-hour format
                }
                for (let i = 0; i < 60; i += 5) {
                    $('#minute').append(`<option value="${i < 10 ? '0' + i : i}">${i < 10 ? '0' + i : i}</option>`); // Format minute with leading zero
                }
                $('#ampm').val('PM');
                $('#time-picker-container').show();  // Show the time picker container
            } else if ($('#dinner').is(':checked')) {
                
                for (let i = 6; i <= 9; i++) {
                    $('#hour').append(`<option value="${i < 10 ? '0' + i : i}">${i < 10 ? '0' + i : i}</option>`);// 12-hour format
                }
                for (let i = 0; i < 60; i += 5) {
                    $('#minute').append(`<option value="${i < 10 ? '0' + i : i}">${i < 10 ? '0' + i : i}</option>`); // Format minute with leading zero
                }
                $('#ampm').val('PM');
                $('#time-picker-container').show();  // Show the time picker container
            }
        });
    });


    $("#clear").click(function(){
        $("input").val("");
    });
    $(document).ready(function(){
        $('.check_time').click(function() {
            $('.check_time').not(this).prop('checked', false);
        });
    });

    $(document).on('change', '.payment_type', function(){
        if(this.value == 'partial'){
            $('.partial_paid_sec').show();
            $('#full_paid_amount').prop('disabled', true);
        }else{
            $('.partial_paid_sec').hide();
            $('#full_paid_amount').prop('disabled', false);
        }
    });

    $('#termsLabel').on('click', function(event) {
        event.preventDefault();
        var name = $("#name").val();
        var booking_date = $("#event_date").val();
        var booking_slot = $("#time").val();
        
        if (name) {
            $.ajax({
                url: '<?php echo base_url(); ?>/annathanam_counter/get_terms',
                method: 'POST',
                data: { name: name },
                success: function(response) {
                    if (response.success) {
                        $('.modal-body-terms').html(response.terms);
                        $('#termsModal').modal('show');
                    } else {
                        alert('Failed to load terms. Please try again.');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        } else {
            alert('Please Add your Name to view Terms and Conditions.');
        }
    });
</script>

<!-- Sum amount -->
<script>
    $('#discount_amount').on('blur change', function () {
        sum_amount();
    });

    function sum_amount() {
        var total = 0;

        var package_id = $('#package_id').val();
        if (package_id != 1){
            var amount = $('#amount').val();
            var no_of_pax = $('#no_of_pax').val();
            total += amount * no_of_pax;
        } else {
            $(".package_amt_special").each(function () {
                total += parseFloat($(this).val());
            });
        }

        $(".package_amt_addon").each(function () {
            total += parseFloat($(this).val());
        });

        $(".package_amt_addi").each(function () {
            total += parseFloat($(this).val());
        });

        var discount_amount = $('#discount_amount').val();
        var max_discount = 0;
        if (discount_amount) {
            discount_amount = Number(discount_amount);
            max_discount = total - 1;
            if (max_discount < 0) max_discount = 0;
            if (discount_amount > max_discount) {
                discount_amount = max_discount;
                $('#discount_amount').val(discount_amount.toFixed(2))
            }
            total = total - discount_amount;
        }

        $("#total_amount").val(Number(total).toFixed(2));

    }
</script>

<!-- Save Function -->
<script>
    $(document).ready(function() {
        $('#saveButton').click(function(event) {
            event.preventDefault();  // Prevent the default form submission

            var packageSelect = document.getElementById('package_id');
            var selectedPackageId = packageSelect.value;

            var errors = [];
            var highlightClass = 'highlight'; // Class to add for highlighting fields

            if (!selectedPackageId) {
                errors.push("Please select a package.");
                packageSelect.classList.add(highlightClass);
            } else {
                packageSelect.classList.remove(highlightClass);
            }

            if(selectedPackageId != 1){
                var noOfPaxInput = document.getElementById('no_of_pax');
                var noOfPax = parseInt(noOfPaxInput.value);
                if (!noOfPax || noOfPax < <?php echo $setting['annathanam_min_pax']; ?>) {
                    errors.push("Please enter the number of pax (minimum <?php echo $setting['annathanam_min_pax']; ?>).");
                    noOfPaxInput.classList.add(highlightClass);
                } else {
                    noOfPaxInput.classList.remove(highlightClass);
                }
            }

            var eventSelect = document.getElementById('event_date');
            var selectedEventId = eventSelect.value;

            if (!selectedEventId) {
                errors.push("Please select a package.");
                eventSelect.classList.add(highlightClass);
            } else {
                eventSelect.classList.remove(highlightClass);
            }

            var timeSlots = document.querySelectorAll('.check_time');
            var timeSelected = Array.from(timeSlots).some(slot => slot.checked);
            
            if (!timeSelected) {
                errors.push("Please select any time slot.");
                timeSlots.forEach(slot => slot.classList.add(highlightClass));
            } else {
                timeSlots.forEach(slot => slot.classList.remove(highlightClass));
            }

            var payMethods = document.querySelectorAll('input[name="pay_method"]');
            var payMethodSelected = Array.from(payMethods).some(radio => radio.checked);
            if (!payMethodSelected) {
                errors.push("Please select a payment method.");
                document.querySelector('.payment1').classList.add(highlightClass);
            } else {
                document.querySelector('.payment1').classList.remove(highlightClass);
            }

            if (errors.length > 0) {
                showValidationModal(errors);
                return;
            }

            var totalAmount = parseFloat($("#total_amount").val());
            var paidAmount = parseFloat($("#paid_amount").val());

            if (paidAmount > totalAmount) {
                $('#alert-modal').modal('show', { backdrop: 'static' });
                $("#spndeddelid").text("Pay Amount should be less than the Total Amount.");
                return;
            }

            var term_setting = $("#term_setting").val();
            if (term_setting == 1) {
                var checkboxes = document.querySelectorAll('.term-checkbox');
                var allChecked = true;

                checkboxes.forEach(function(checkbox) {
                    if (!checkbox.checked) {
                        allChecked = false;
                    }
                });

                if (!allChecked) {
                    event.preventDefault();
                    alert('Please check all Terms & Conditions before saving.');
                    exit();
                }
            }

            $.ajax({
                url: '<?php echo base_url(); ?>/annathanam_counter/save_annathanam',
                type: 'post',
                data: $('#form_validation').serialize(),  // Serialize the data in the form
                success: function(response) {
                    console.log("Success response:", response);
                    try {
                        var obj = jQuery.parseJSON(response);
                        if (obj.err) {
                            $('#alert-modal').modal('show', { backdrop: 'static' });
                            $("#spndeddelid").text(obj.err);
                        } else {
                            window.open("<?php echo base_url(); ?>/annathanam_counter/print_annathanam/" + obj.id);
                            window.location.replace("<?php echo base_url(); ?>/annathanam_counter");
                        }
                    } catch (e) {
                        console.error("Response parsing error:", e);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX error:", status, error);
                }
            });
        });
    });

    function showValidationModal(messages) {
        var modalBody = document.getElementById('validationModalBody');
        modalBody.innerHTML = messages.join('<br>');
        $('#validationModal').modal('show');
    }
</script>

<!-- Payment type and clear -->
<script>
    $("#clear").click(function(){
        $("input").val("");
    });
    $(document).ready(function(){
        $('.check_time').click(function() {
            $('.check_time').not(this).prop('checked', false);
        });
		$(document).on('change', '.payment_type', function(){
			if(this.value == 'partial'){
				$('.partial_paid_sec').show();
				$('#full_paid_amount').prop('disabled', true);
			}else{
				$('.partial_paid_sec').hide();
				$('#full_paid_amount').prop('disabled', false);
			}
		});
    });
</script>

</body>

</html>