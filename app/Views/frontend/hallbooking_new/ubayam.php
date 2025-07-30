<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/typicons/typicons.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/css/vertical-layout-light/style.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/mdi/css/materialdesignicons.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/style.css">
<link rel="shortcut icon" href="<?php echo base_url(); ?>/assets/archanai/images/favicon.png" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/demo.css">
<link href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css" rel="stylesheet">
<style>
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
        .modal-backdrop.show {
            opacity: 0;
            z-index: -1;
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
        .itemcountrr .value-button {
    display: inline-block;
        border: 1px solid #ddd;
        margin: 0px;
        width: 25px;
        height: 25px;
        text-align: center;
        vertical-align: middle;
        padding: 0px 0px 8px 0px;
        background: #eee;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    }

    .itemcountrr .value-button:hover {
    cursor: pointer;
    }

    .itemcountrr #decrease {
    margin-right: 0px;
    border-radius: 4px 0 0 4px;
    margin-top:-2px;
    }

    .itemcountrr #increase {
    margin-left: 0px;
    border-radius: 0 4px 4px 0;
    margin-top:-2px;
    }

    .itemcountrr #input-wrap {
    margin: 0px;
    padding: 0px;
    }

    .itemcountrr input.number {
        text-align: center;
        border: none;
        border-top: 1px solid #ddd;
        border-bottom: 1px solid #ddd;
        margin: 0px;
        width: 35px;
        height: 25px;
    }

    .itemcountrr input[type=number]::-webkit-inner-spin-button,
    .itemcountrr input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
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
        padding: 13px;
        color: white;
        min-height: 120px;
    }

    .form-control:focus {
        color: #495057;
        background-color: #fff;
        border-color: #F44336 !important;
        outline: 0;
        box-shadow: none;
    }

    select.form-control:focus {
        outline: 1px solid #F44336;
    }

    .error-input {
        border-color: #F44336 !important;
    }

    .back h5 {
        min-height: 80px;
        font-size: 15px;
        font-weight: bold;
        color: #FFFFFF;
    }

    #error_msg,
    .form_error {
        color: red;
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




    #filters {
        margin: 0 0 10px;
        padding: 0;
        list-style: none;
        width: 100%;
        overflow: auto;
        display: inherit;
    }

    #filters li:first-child {
        margin-left: 0;
    }

    #filters li {
        float: left;
        background: white;
        margin: 0 7px;
        /*width:100px;
        max-height:95px;
        min-width:95px;*/
    }

    #filters li span {
        display: block;
        padding: 10px;
        text-decoration: none;
        color: #000;
        cursor: pointer;
        transition: color 300ms ease-in-out;
        text-align: center;
        line-height: 1.5em;
        font-size: 14px;
        text-transform: uppercase;
        font-weight: bold;
    }

    #filters li span:hover {
        color: #d4aa00;
    }

    #filters li span.active {
        /*background: #d4aa00;*/
        background: linear-gradient(179deg, rgb(212 170 0) 0%, rgb(197 191 16) 35%, rgb(252 245 6) 100%);
        color: #000;
    }



    #portfoliolist .portfolio {
        display: none;
        float: left;
        overflow: hidden;
        width: 20%;
        padding: 10px;
    }

    .portfolio-wrapper {
        overflow: hidden;
        position: relative !important;
        cursor: pointer;
    }

    .portfolio img {
        max-width: 100%;
        position: relative;
        top: 0;
    }

    .portfolio .label {
        position: absolute;
        width: 100%;
        height: 40px;
        bottom: -40px;
    }

    .portfolio .label-bg {
        background: #222;
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
    }

    .portfolio .label-text {
        color: #fff;
        position: relative;
        z-index: 500;
        padding: 5px 8px;
    }

    .portfolio .text-category {
        display: block;
        font-size: 9px;
    }
    .pay-label:before { content:none !important; }

    ul.payment {
        list-style-type: none;
        width: 100%;
        display: flex;
        justify-content: flex-start;
        margin-bottom:0;
        padding-left:0;
        -webkit-column-count: 3;
        column-count: 3;
        flex-wrap: wrap;
        height:300px;
        overflow:auto;
    }

    .payment li {
        display: inline-block;
        width: 20%;
    }

    input[type="radio"][id^="pay_for"] {
    display: none;
    }
    input[type="radio"][name="payment_mode"] {
    display: none;
    }
    input[type="radio"] {
    /* display: none; */
    }


    .payment li label {
        border: 1px solid #CCC;
        border-radius: 5px;
        line-height: 1.5;
        display: block;
        position: relative;
        margin:10px 10px;
        font-family: inherit;
        min-height: 120px;
        background: #fff;
        cursor: pointer;
        color: #6d5804;
        font-weight: bold;
    }
    .payment li label p {
        font-size:18px;
        margin-bottom:0;
    }

    label:before {
    background-color: white;
    color: white;
    content: " ";
    display: block;
    border-radius: 50%;
    border: 1px solid grey;
    position: absolute;
    top: -10px;
    left: -5px;
    width: 30px;
    height: 30px;
    text-align: center;
    line-height: 28px;
    transition-duration: 0.4s;
    transform: scale(0);
    }

    label i.mdi {
    transition-duration: 0.2s;
    transform-origin: 50% 50%;
    font-size:18px;
    color:#0d2f95;
    }

    :checked + label {
    background:#f6ef08;
    transition-duration: 0.4s;
    }

    /* :checked + label:before {
        content: "✓";
        background-color: green;
        transform: scale(1);
    } */

    :checked + i.mdi{
    transform: scale(0.9);
    }

    ul.payment1 {
        list-style-type: none;
        width: 100%;
        display: flex;
        justify-content: space-between;
        margin-bottom:0;
        padding-left:0;
    }

    .payment1 li {
        display: inline-block;
        text-align:center;
        width:50%;
    }

    .payment1 li label {
    border: 1px solid #CCC;
    border-radius: 5px;
    line-height: 1;
    padding: 15px 12px;
    display: block;
    position: relative;
    margin: 15px 15px;
    cursor: pointer;
    font-weight:bold;
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
    font-size:18px;
    color:#0d2f95;
    }

    .payment1 li :checked + label {
    background:#f6ef08;
    }

    .payment1 li :checked + label:before {
    content: "✓";
    background-color: green;
    transform: scale(1);
    }

    .payment1 li label :checked + i.mdi{
    transform: scale(0.9);
    }
    .prod_img { width:90px; min-width:90px; margin:0 auto; border-radius: 50%; min-height:90px; max-height:90px;
        background: #e1e1d68a;
        padding: 5px; }
    .text-muted.arch { 
        color:#000000 !important; 
        font-size:14px;
        text-align:center; padding:10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis; 
        max-height:50px;
        min-height:50px;
        text-transform:uppercase;
    }
    .ar_btn {
        background: linear-gradient(179deg, rgb(0 126 212) 0%, rgb(16 197 180) 35%, rgb(59 134 209) 100%);
        border-radius: 15px;
        font-weight: bold;
        height: 1.75em;
    }
    .btn { padding: 0.25rem 0.5rem; height: 2rem; }
    .show-cart1 { max-height:350px; overflow:auto; }
    .show-cart1 tr { border-radius:10px; }
    .show-cart1 td { font-size:13px; padding:3px 10px; }
    .total { margin-top:15px; padding-bottom:10px; } 
    .total p { font-size: 24px; font-weight: bold; }
    .tot_amt_txt {
        display: inline;
        width: 126px;
        text-align: right;
        font-size: 26px;
        font-weight: bold;
        border: 0;
        background: white;
        color: black;
    }
    .modal { background: #00000070; }
    @media (max-width: 960px) {
    .prod_img { width:50px; min-width:50px; margin:0 auto; border-radius: 50%; min-height:50px; max-height:50px;}
    .payment li { width: 25%; }
    .payment1 li label { padding: 15px 1px; margin: 15px 5px; }
    }
    .cal_head {
            background: #f34c22;
            color: #FFF;
            padding: 2px 5px;
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
        padding: 7px 10px;
        cursor: pointer;
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        transition: background-color 0.3s, color 0.3s;
        display: inline-block;
        border-radius: 5px;
        margin-bottom:0;
    }
    .modal.show .modal-dialog { margin-top: 4em; }

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
    .head_sec {
        text-align: left;
        color: white;
        background: #00add4;
        padding: 5px;
    }
    .head_sec:after {
        content: "";
        position: absolute;
        height: 0;
        width: 0;
        left: 96%;
        top: 0;
        border: 20px solid transparent;
        border-left: 20px solid #00add4;
    }
    .text {
        background-color:#ff0000;
        color:#fff;
        display:inline-block;
        padding-left:4px;
    }
    .arrow {
        border-style: dashed;
        border-color: transparent;
        border-width: 0.20em;
        display: -moz-inline-box;
        display: inline-block;
        /* Use font-size to control the size of the arrow. */
        font-size: 100px;
        height: 0;
        line-height: 0;
        position: relative;
        vertical-align: middle;
        width: 0;
        background-color:#fff; /* change background color acc to bg color */ 
        border-left-width: 0.2em;
        border-left-style: solid;
        border-left-color: #ff0000;
        left:0.25em;
    }
    #venue_select {
        display: block;
        position: absolute;
        bottom: 20px;
    }
    .clear-cart i, .total_cart i { font-size:28px; color:#000000bd;  }
    .cl_btn {
        background: linear-gradient(179deg, rgb(212 0 0) 0%, rgb(242 105 105) 35%, rgb(209 59 59) 100%);
        border-radius: 15px;
        font-weight: bold;
    }
</style>
</head>

<body class="sidebar-icon-only">
	<?php if($_SESSION['succ'] != '') { ?>
	  <div class="row" style="padding: 0 30%;margin: 0px 0 15px 0;" id="content_alert">
		  <div class="suc-alert" style="width: 100%;">
			  <span class="suc-closebtn" onClick="this.parentElement.style.display='none';">&times;</span> 
			  <p><?php echo $_SESSION['succ']; ?></p> 
		  </div>
	  </div>
  <?php } ?>
	<?php if($_SESSION['fail'] != '') { ?>
	  <div class="row" style="padding: 0 30%;margin: 0px 0 15px 0;" id="content_alert">
		  <div class="alert" style="width: 100%;">
			  <span class="closebtn" onClick="this.parentElement.style.display='none';">&times;</span> 
			  <p><?php echo $_SESSION['fail']; ?></p>
		  </div>
	  </div>
  <?php } ?>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">

            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="content w-100">
                                <div class="calendar-container">
                                    <div class="calendar">
                                        <div class="year-header">
                                            <span class="left-button fa fa-chevron-left" id="prev"></span>
                                            <span class="year" id="label"></span>
                                            <span class="right-button fa fa-chevron-right" id="next"></span>
                                        </div>
                                        <table class="months-table w-100">
                                            <tbody>
                                                <tr class="months-row">
                                                    <td class="month">Jan</td>
                                                    <td class="month">Feb</td>
                                                    <td class="month">Mar</td>
                                                    <td class="month">Apr</td>
                                                    <td class="month">May</td>
                                                    <td class="month">Jun</td>
                                                    <td class="month">Jul</td>
                                                    <td class="month">Aug</td>
                                                    <td class="month">Sep</td>
                                                    <td class="month">Oct</td>
                                                    <td class="month">Nov</td>
                                                    <td class="month">Dec</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <table class="days-table w-100">
                                            <td class="day">Sun</td>
                                            <td class="day">Mon</td>
                                            <td class="day">Tue</td>
                                            <td class="day">Wed</td>
                                            <td class="day">Thu</td>
                                            <td class="day">Fri</td>
                                            <td class="day">Sat</td>
                                        </table>
                                        <div class="frame">
                                            <table class="dates-table w-100">
                                                <tbody class="tbody">
                                                </tbody>
                                            </table>
                                        </div>
                                        <button class="button" disabled id="add-button">Add Event</button>
                                        <a class="button" id="venue_select">Change Venue</a>
                                    </div>
                                </div>
                                <div class="events-container"></div>
                                    <div class="dialog prod" id="dialog"> 
                                        <!--h4 class="dialog-header" style=" background:#d4aa00;color:#FFFFFF;"> Add New Event </h4-->
                                        <form class="form" id="form" method="post">
                                            <input type="hidden" id="ubhayam_date" name="ubhayam_date" class="form-control" value="<?php echo date("Y-m-d"); ?>">
                                            <input type="hidden" id="booking_date" name="booking_date" class="form-control" value="<?php echo date("Y-m-d"); ?>">
                                            <input type="hidden" id="booking_type" name="booking_type" value="1">
                                            <input type="hidden" id="save_booking" name="save_booking" value="1">
                                            <input type="hidden" id="venue" name="venue" value="1">
                                            <input type="hidden" id="day_check" name="day_check" value="">
                                            <!-- <input type="hidden" id="payment_type" name="payment_type" value="full"> -->
                                            <input type="hidden" id="booking_through" name="booking_through" value="COUNTER">
                                            <input type="hidden" id="advance_amount" name="advance_amount">
                                            <?php $user_id = $_SESSION['log_id_frend']; ?>
                                            <input type="hidden" id="user_id" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                                             <input type="hidden" id="print_method" name="print_method" value="<?php echo $setting['print_method']; ?>">
                                            <input class="input1" type="hidden" id="dt" name="dt" value="<?php echo date('Y-m-d'); ?>">
                                            <div class="form-container card-body" align="center">
                                                <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <h4 id="venue-name">Hall Booking</h4>
                                                            <h4 style="margin-bottom:5px; margin-top:5px; color:#FFFFFF; background:#d4aa00;">Choose Slot</h4>
                                                            <table class="table table-bordered " id="booking_slot">
                                                                <tbody>
                                                                    <tr>
                                                                        <?php // echo print_r($time_list);
                                                                        $i=0; foreach($time_list as $row) { 
                                                                            // if (in_array($row['id'], $data_time)) { $disabled = ""; $t_name = $time_name[$row['id']];}
                                                                            // else  { $disabled = ""; $t_name = ''; };
                                                                        
                                                                        ?>
                                                                        <td>
                                                                            <input style="left: 2%; opacity: 1;position: inherit;" type="radio" class="booking_slot" name="booking_slot[]" value="<?php echo $row['id']; ?>">
                                                                            <?php echo $row['slot_name'];?>
                                                                        </td>
                                                                        <?php } ?>
                                                                    </tr>
                                                                    
                                                                </tbody>
                                                            </table>
                                                            <h4 style="margin-bottom:5px; margin-top:5px; color:#FFFFFF; background:#d4aa00;">Choose Any Package</h4>

                                                            <div class="row prod product" id="add_one">
                                                                <?php foreach($package as $row) { ?>    
                                                                    <div class="col-xl-3 col-sm-6 col-lg-3 col-md-4 portfolio" data-cat="">
                                                                        <input type="radio" class="ubayam_slot" name="pay_for" value="<?php echo $row['id']; ?>" id="pay_for<?php echo $row['id']; ?>" />
                                                                        <label class="card" for="pay_for<?php echo $row['id']; ?>" onclick="payfor(<?php echo $row['id']; ?>)">
                                                                            <img class="img-fluid prod_img" src="<?php  echo base_url(); ?>/uploads/package/<?php  echo $row['image']; ?>">
                                                                            <div class="d-flex justify-content-between align-items-center mb-2 mt-2" style="flex-direction: column;">
                                                                                <p class="mb-0 text-muted arch" id="pay_name<?php echo $row['id']; ?>"><?php echo $row['name']; ?></p>
                                                                            </div>
                                                                        </labe>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="scroll products row">
                                                                <div class="col-sm-12">
                                                                    <h3 style="margin-bottom:5px; margin-top:5px;">Add-on Details</h3>
                                                                </div>
                                                                <div class="col-sm-6 ">
                                                                    <div class="form-group form-float">
                                                                        <div class="form-line">
                                                                            <!--<label class="form-lable">Package Name</label>-->
                                                                            <select class="form-control" id="add_one_addon">
                                                                                <option value="">Select From</option> 
                                                                                <?php foreach($package_addon as $row) { ?>
                                                                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" id="pack_amount" name="pack_amount" class="form-control">
                                                                <div class="col-sm-3 ">
                                                                    <div class="form-group form-float">
                                                                    
                                                                        <div class="form-line focused">
                                                                            <input type="hidden" id="pack_name_addon">
                                                                            <input type="number" class="form-control" id="get_pack_amt_addon" placeholder="0.00" >
                                                                            <!-- <label class="form-label">RM</label> -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 ">
                                                                    <div class="form-group form-float">
                                                                        <div class="form-line" style="border: none;">
                                                                            <label id="pack_add_addon" class="btn btn-success" style="padding: 5px 12px !important;">Add</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row scroll" style="overflow-y:scroll; overflow-x:hidden; height: 200px;">
                                                                <div class="col-sm-12">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered" style="width:100%" id="package_table_addon" style="height: 150px;">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th width="20%">Name</th>
                                                                                    <th width="15%">Description</th>
                                                                                    <th width="20%">Qty</th>
                                                                                    <th width="15%">Amount RM</th>
                                                                                    <th width="20%">Total RM</th>
                                                                                    <th width="10%">Action</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <input type="hidden" id="pack_row_count_addon" value="0">
                                                                </div>
                                                            </div>

                                                            <div class="scroll extra-charges row">
                                                                <div class="col-sm-12">
                                                                    <h3 style="margin-bottom:5px; margin-top:5px;">Extra Charges</h3>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group form-float">
                                                                        <div class="form-line">
                                                                            <input type="text" id="extra_desc" class="form-control" placeholder="Description">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <div class="form-group form-float">
                                                                        <div class="form-line">
                                                                            <input type="number" id="extra_amount" class="form-control" placeholder="Amount">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <div class="form-group form-float">
                                                                        <label id="add_extra_charge" class="btn btn-success" style="padding: 5px 12px !important;">Add</label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Table to display the added extra charges -->
                                                            <table id="extra_charges_table" class="table" style="overflow-y:scroll; overflow-x:hidden; height: 200px;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Description</th>
                                                                        <th>Amount RM</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <!-- Dynamically added rows will appear here -->
                                                                </tbody>
                                                                <input type="hidden" name="extra_charges" id="extra_charges" value="0">
                                                            </table>

                                                            

                                                            <div class="pack" style="display:none;">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <h4
                                                                            style="margin-bottom:5px; margin-top:5px; color:#FFFFFF; background:#d4aa00;">
                                                                            Family Details</h4>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <label class="form-label">Name</label>
                                                                        <input type="text" class="form-control" id="family_name">
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <label class="form-label">Relationship</label>
                                                                        <input type="text" class="form-control"
                                                                            id="family_relationship">
                                                                    </div>
                                                                    <div class="col-sm-2 smal_marg">
                                                                        <div class="form-group form-float">
                                                                            <div class="form-line" style="border: none;">
                                                                                <br><label id="family_add" class="btn btn-warning"
                                                                                    style="padding: 6px 12px !important;height: 2.35rem; margin-top: 6px;">Add</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12 table-responsive">
                                                                        <table class="table table-bordered"
                                                                            style="width:100%; height: 150px;" id="family_table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th width="40%">Name</th>
                                                                                    <th width="25%">Relationship</th>
                                                                                    <th width="15%">Action</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody class="prod"
                                                                                style="overflow-y:scroll; overflow-x:hidden; max-height: 200px;">
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <input type="hidden" id="family_row_count" value="0">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">

                                                            <div class="row">
                                                              
                                                                <div class="col-md-4" style="text-align:left;">
                                                                    <p style="margin-top:10px;">
                                                                        <button type="button" class="btn ar_btn btn-info btn-lg"
                                                                            onClick="userModalOpen();">Add Detail</button>
                                                                    </p>
                                                                </div>
                                                                  <div class="col-md-4" style="text-align:left;">
                                                                    <p style=" margin-top:10px;">
                                                                        <button type="button" class="btn btn-danger btn-lg ar_btn"
                                                                            onClick="rePrint();"
                                                                            style="background: #f44336;border: 1px solid #f44336;color: #fff;">Reprint</button>
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-4" style="text-align:right;">
                                                                    <p style="margin-top:10px;">
                                                                        <button type="button" class="btn btn-danger btn-lg cl_btn clear-cart" id="clear-all">Clear All</button>
                                                                    </p>
                                                                </div>
                                                                
                                                            </div>
                                                            
                                                            <table class="show-cart table table-bordered" style="width:100%;display:none;"></table>
                                                                <table class="table table-bordered" style="width:100%;" id="package_name">
                                                                    <tr><th style='padding: 5px 10px;line-height: 20px;width:10%'>Package Name </th><td style='background: #fff;border: 1px solid #dee2e6;padding: 5px 10px;line-height: 20px;'><span id="packname"></span></td></tr> 
                                                                </table>

                                                            <table class="table table-bordered" id="servicesTable" style="width:100%;">
                                                                <thead>
                                                                    <tr><th colspan="3" style="text-align: center; font-weight: bold;">Services</th></tr>
                                                                    <tr id="serviceDetailsHeader" style="display: none; text-align: center; font-weight: bold;">
                                                                        <th>Name</th>
                                                                        <th>Description</th>
                                                                        <th>Quantity</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="servicesList">
                                                                </tbody>
                                                            </table>

                                                            <!-- <div<?php //if(!empty($setting['hall_discount'])){ echo ' style="display: block;"'; }else echo ' style="display: none;"'; ?>>
                                                                <h5 style="margin-bottom:5px; margin-top:5px; color:#FFFFFF; background:#d4aa00;">Package Amount</h5>
													            <input type="number" min="0" step="any" id="sub_total"class="form-control" name="sub_total" value="0" readonly>
													            <h5 style="margin-bottom:5px; margin-top:5px; color:#FFFFFF; background:#d4aa00;">Dicount</h5>
													            <input type="number" min="0" step="any" id="discount_amount"class="form-control" name="discount_amount" value="0">
												            </div> -->

                                                            <div <?php if(!empty($setting['hall_discount'])){ echo ' style="display: block;"'; }else echo ' style="display: none;"'; ?>>
                                                                <div style="display: flex; gap: 20px; align-items: center;">
                                                                    <div>
                                                                        <h5 style="margin-bottom:5px; margin-top:5px; color:#FFFFFF; background:#d4aa00;">Package Amount</h5>
                                                                        <input style="text-align: center" type="number" min="0" step="any" id="sub_total" class="form-control" name="sub_total" value="0" readonly>
                                                                    </div>

                                                                    <div>
                                                                        <h5 style="margin-bottom:5px; margin-top:5px; color:#FFFFFF; background:#d4aa00;">Discount</h5>
                                                                        <input style="text-align: center" type="number" min="0" step="any" id="discount_amount" class="form-control" name="discount_amount" value="0">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="row" style="margin-top:10px;">
                                                            <div class="col-md-6"><h4 style="margin-bottom:5px; margin-top:5px; color:#FFFFFF; background:#d4aa00;">Deposit Amount</h4>  
                                                            <input type="number" min="0" step="any" id="deposit_amt" class="form-control" name="deposit_amt" value=""
                                                                style="margin-top:10px;font-weight:bold;font-size: 23px;text-align: center;">
                                                            </div><div class="col-md-6">
                                                            <h4 style="margin-bottom:5px; margin-top:5px; color:#FFFFFF; background:#d4aa00;">Total Amount</h4>  
                                                            <input type="number" min="0" step="any" id="total_amt" class="form-control" name="total_amt" value="0"
                                                                style="margin-top:10px;font-weight:bold;font-size: 26px;text-align: center;" readonly>
                                                            </div></div>
                                                            <div class="row clearfix" style="width:105%; border-bottom:1px dashed #CCC; display: flex; justify-content: center; align-items: center;">
                                                                <div class="payment-options" style="flex-grow: 1; display: flex; justify-content: space-between;">
                                                                    <div class="form-group" style="margin-bottom:0;">
                                                                        <input type="radio" name="payment_type" id="payment_type_full" class="payment_type" value="full" 
                                                                            <?php echo (empty($data['payment_type']) || $data['payment_type'] == 'full') ? 'checked' : ''; ?>>
                                                                        <label for="payment_type_full" class="pay-label btn-payment">Full Payment</label>
                                                                    </div>
                                                                    <div class="form-group" style="margin-bottom:0;">
                                                                        <input type="radio" name="payment_type" id="payment_type_partial" class="payment_type" value="partial" 
                                                                            <?php echo ($data['payment_type'] == 'partial') ? 'checked' : ''; ?>>
                                                                        <label for="payment_type_partial" class="pay-label btn-payment">Partial Payment</label>
                                                                    </div>
                                                                    <!-- <div class="form-group" style="margin-bottom:0;">
                                                                        <input type="radio" name="payment_type" id="payment_type_only_booking" class="payment_type" value="only_booking" 
                                                                            <?php echo ($data['payment_type'] == 'only_booking') ? 'checked' : ''; ?>>
                                                                        <label for="payment_type_only_booking" class="pay-label btn-payment">Only Booking</label>
                                                                    </div> -->
                                                                </div>
                                                                <div class="col-sm-6 partial_paid_sec" align="center" style="<?php echo (!empty($data['payment_type']) && $data['payment_type'] == 'partial') ? '' : 'display: none;'; ?>">
                                                                    <label class="form-label" align="center">Pay Amount</label>
                                                                    <input type="number" name="pay_amt" id="pay_amt" step=".01" class="form-control" value="<?php echo $data['paid_amount'] ?? '0.00'; ?>">
                                                                </div>
                                                            </div>

                                                            <ul class="payment1">
                                                                <?php foreach ($payment_mode as $key => $pay) { ?>
                                                                    <li>
                                                                        <input type="radio" name="payment_mode" id="cb<?php echo $pay['id']; ?>" value="<?php echo $pay['id']; ?>" data-name="<?php echo $pay['name']; ?>" />
                                                                        <label for="cb<?php echo $pay['id']; ?>">
                                                                            <?php echo $pay['name']; ?>
                                                                        </label>
                                                                    </li>
                                                                <?php } ?>
                                                            </ul>
                                                            <div class="col-sm-12" style="text-align: left;color: blue;">
                                                                <div class="form-group">
                                                                    <label for="brides_detail" id="brides_detail" style="cursor: pointer;">
                                                                        <i class="fa fa-plus-circle" style="color:blue"></i>
                                                                        Add Bride and Groom Details
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div<?php if (!empty($setting['enable_terms'])) echo 'style="display: block;"'; else echo ' style="display: none;"'; ?>>
                                                                <div class="col-sm-12" style="text-align: left;color: #f44336;">
                                                                    <div class="form-group">
                                                                        <label for="termsLink" id="termsLabel" style="cursor: pointer;"><i class="fa fa-check-square-o" style="color:red"></i>Terms and conditions</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <input type="button" value="OK" class="button button-white greensubmit" id="submit">
                                                                
                                                        </div>

                                                        <div class="col-md-12">
                                                            
                                                            <h4 style="margin-bottom:5px; margin-top:5px; color:#FFFFFF; background:#d4aa00;">Prasadam</h4><br>
                                                            <div class="row">
                                                                <div class="col-md-5"> 
                                                                    <label style="margin-bottom: 0rem; text-align: center;" align="center"><strong> Select Slot</strong></label><br>
                                                                    <input  type="radio" id="breakfast" name="time" value="Breakfast" class="check_time" >
                                                                    <label for ='breakfast'> Breakfast &nbsp;&nbsp; </label>
                                                                    <input  type="radio" id="lunch" name="time" value="Lunch" class="check_time" >
                                                                    <label for ='lunch'> Lunch &nbsp;&nbsp; </label>
                                                                    <input  type="radio" id="dinner" name="time" value="Dinner" class="check_time" >
                                                                    <label for ='dinner'> Dinner &nbsp;&nbsp; </label>
                                                                </div>
                                                                <div class="col-md-5" id="time-picker-container" style="margin-bottom: 15px;">
                                                                    <label for="hour"><strong>Select Time: </strong></label>
                                                                    <div style="display: flex; gap: 10px;">
                                                                        <select id="hour" name="hour" class="form-control" style="display:inline-block;">
                                                                            
                                                                        </select>
                                                                        :
                                                                        <select id="minute" name="minute" class="form-control" style="display:inline-block;">
                                                                        
                                                                        </select>
                                                                        <select id="ampm" name="ampm" class="form-control" style="display:inline-block;" disabled>
                                                                            <option value="AM">AM</option>
                                                                            <option value="PM">PM</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div><br>

                                                            <div class="scroll products row">
                                                                <div class="col-sm-5 ">
                                                                    <div class="form-group form-float">
                                                                        <div class="form-line">
                                                                            <select class="form-control" id="add_one_prasadam">
                                                                                <option value="">Select From</option>
                                                                                <?php foreach ($prasadam as $row) { ?>
                                                                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name_eng']; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                    
                                                                <div class="col-sm-2 ">
                                                                    <div class="form-group form-float">
                                                                        <div class="form-line focused">
                                                                            <input type="hidden" id="prasadam_name">
                                                                            <input type="number" class="form-control" id="get_prasadam_amt" placeholder="0.00">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2 ">
                                                                    <div class="form-group form-float">
                                                                        <div class="form-line" style="border: none;">
                                                                            <label id="prasadam_add" class="btn btn-success" style="padding: 5px 12px !important;">Add</label>       
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row scroll" style="overflow-y:scroll; overflow-x:hidden; height: 200px;">
                                                                <div class="col-sm-12">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered" style="width:100%" id="prasadam_table" style="height: 150px;">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th style="text-align: center;" width="5%">S.No</th>
                                                                                    <th style="text-align: center;" width="35%">Name</th>
                                                                                    <th style="text-align: center;" width="10%">Qty</th>
                                                                                    <th style="text-align: center;" width="20%">Amount(S$)</th>
                                                                                    <th style="text-align: center;" width="20%">Total Amount(S$)</th>
                                                                                    <th style="text-align: center;" width="10%">Action</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>                
                                                                </div>
                                                            </div><br><br>

                                                            <h4 style="margin-bottom:5px; margin-top:5px; color:#FFFFFF; background:#d4aa00;">Annathanam</h4><br>
                                                            <div class="row">
                                                                <div class="col-sm-5">
                                                                    <div class="form-group form-float">
                                                                        <div class="form-line">
                                                                            <select class="form-control" name="annathanam_package_select" id="annathanam_package_select">
                                                                                <option value="">Select meals Types</option>
                                                                                <?php foreach ($annathanam_packages as $row) { ?>
                                                                                <option value="<?php echo $row['id']; ?>" data-amount="<?php echo $row['amount']; ?>" data-name="<?php echo $row['name_eng'] . ' / ' . $row['name_tamil']; ?>">
                                                                                    <?php echo $row['name_eng'] . ' / ' . $row['name_tamil']; ?>
                                                                                </option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-1 ">
                                                                    <div class="form-group form-float">
                                                                        <div class="form-line" style="border: none;">
                                                                            <label id="annathanam_add" class="btn btn-success" style="padding: 5px 12px !important;">+</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3" style="text-align:left">
                                                                    <label style="margin-bottom: 0rem; text-align: center;" align="center"><strong> Select Slot</strong></label><br>
                                                                    <input  type="radio" id="breakfast1" name="time1" value="Breakfast" class="check_time1" >
                                                                    <label for ='breakfast'> Breakfast &nbsp;&nbsp; </label>
                                                                    <input  type="radio" id="lunch1" name="time1" value="Lunch" class="check_time1" >
                                                                    <label for ='lunch1'> Lunch &nbsp;&nbsp; </label>
                                                                    <input  type="radio" id="dinner1" name="time1" value="Dinner" class="check_time1" >
                                                                    <label for ='dinner1'> Dinner &nbsp;&nbsp; </label>
                                                                </div>
                                                                <div class="col-md-3" id="time-picker-container1" style="margin-bottom: 15px;">
                                                                    <label for="hour1"><strong>Select Time: </strong></label>
                                                                    <div style="display: flex; gap: 10px;">
                                                                        <select id="hour1" name="hour1" class="form-control" style="display:inline-block;">
                                                                            
                                                                        </select>
                                                                        :
                                                                        <select id="minute1" name="minute1" class="form-control" style="display:inline-block;">
                                                                        
                                                                        </select>
                                                                        <select id="ampm1" name="ampm1" class="form-control" style="display:inline-block;" disabled>
                                                                            <option value="AM">AM</option>
                                                                            <option value="PM">PM</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="table-responsive col-sm-12" style="overflow-y:scroll; overflow-x:hidden; height: 100px;">
                                                                    <table class="table table-bordered" id="annathanam_table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th width="6%">S.no</th>
                                                                                <th width="20%" class="wrap-text">Package</th>
                                                                                <th width="36%" class="wrap-text">Items</th>
                                                                                <th width="8%">Qty</th>
                                                                                <th width="11%">Amount</th>
                                                                                <th width="8%">Total</th>
                                                                                <th width="5%">Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div><br><br>

                                                            <div class="row">
                                                                <div class="col-sm-5">
                                                                    <div class="form-group form-float">
                                                                        <div class="form-line">
                                                                        <select class="form-control" id="annathanam_addon_select">
                                                                            <option value="">Select addon</option>
                                                                        </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <div class="form-group form-float">
                                                                        <div class="form-line focused">
                                                                            <input type="hidden" id="anna_addon_name" value=""> 
                                                                            <input type="number" class="form-control" id="get_anna_addon_amt" placeholder="0.00" readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-1 ">
                                                                    <div class="form-group form-float">
                                                                        <div class="form-line" style="border: none;">               
                                                                            <label id="addon_add" class="btn btn-success" style="padding: 5px 12px !important;">+</label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="table-responsive col-sm-12" style="overflow-y:scroll; overflow-x:hidden; height: 200px;">
                                                                    <table class="table table-bordered" id="annathanam_addon_table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th width="5%">S.no</th>
                                                                                <th width="35%">Addon</th>
                                                                                <th width="20%">Qty</th>
                                                                                <th width="15%">Amount(S$)</th>
                                                                                <th width="15%">Total(S$)</th>
                                                                                <th width="10%">Action</th>
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
                                            </div>
                                        <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                            
                                            <div class="modal-dialog modal-md">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Bride and Groom Information</h5>
                                                        <button type="button" class="close"
                                                            data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <!-- <form id="infoForm"> -->
                                                    <div class="modal-body p-4" style="padding-bottom:10px;">
                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <label class="form-label">Bride Name </label>
                                                                <input type="text" class="form-control "
                                                                    name="brideName" id="brideName" value="">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6"><div class="form-group form-float">
                                                            <div class="form-line">
                                                                <label class="form-label">Bride DOB </label>
                                                                <input type="date" class="form-control" name="brideDOB"
                                                                    id="brideDOB" value="">

                                                                <small id="brideAge"
                                                                    style="display: block; margin-top: 5px;"></small>
                                                            </div>
                                                        </div></div>

                                                        <div class="col-md-6"><div class="form-group form-float">
                                                            <div class="form-line">
                                                                <label class="form-label">Bride IC Number </label>
                                                                <input type="text" class="form-control " name="brideIC"
                                                                    id="brideIC" value="">

                                                            </div>
                                                        </div></div>
                                                        </div>

                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <label class="form-label">Groom Name </label>
                                                                <input type="text" class="form-control "
                                                                    name="groomName" id="groomName" value="">

                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6"><div class="form-group form-float">
                                                            <div class="form-line">
                                                                <label class="form-label">Groom DOB </label>
                                                                <input type="date" class="form-control " name="groomDOB"
                                                                    id="groomDOB" value="">
                                                                <small id="groomAge"
                                                                    style="display: block; margin-top: 5px;"></small>
                                                            </div>
                                                        </div></div>

                                                        <div class="col-md-6"><div class="form-group form-float">
                                                            <div class="form-line">
                                                                <label class="form-label">Groom IC Number </label>
                                                                <input type="text" class="form-control " name="groomIC"
                                                                    id="groomIC" value="">
                                                            </div>
                                                        </div></div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                            <!-- <button type="button" class="btn btn-primary"
                                                                data-dismiss="modal">Save</button> -->
                                                                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="saveBrideGroomDetails()">Save</button>
                                                        </div>
                                                        <!-- </form> -->
                                                    </div>
                                                </div>
                                            </div>
                                    </form>
                                </div>  
                                        </form>
                                                                        
                                    </div>
                                 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- partial -->
        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
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
                <div class="modal-body">
                    <!-- Terms and conditions will be populated here by JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

	<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close"
						data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body p-4" style="padding-bottom:10px;">
					<div class="text-center">
						<div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="mobile">Mobile No<span style="color:red;">*</span></label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select class="form-control" name="mobile_code" id="phonecode">
                                                <option value="">Dialing code</option>
                                                <?php
                                                if (!empty($phone_codes)) {
                                                    foreach ($phone_codes as $phone_code) {
                                                        ?>
                                                        <option value="<?php echo $phone_code['dailing_code']; ?>" <?php if ($phone_code['dailing_code'] == "+60") {
                                                               echo "selected";
                                                           } ?>>
                                                            <?php echo $phone_code['dailing_code']; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <input class="form-control" type="number" id="mobile" name="mobile_no" min="0" autocomplete="off">
                                            <small id="error-message" style="color: red; display: none;">Invalid phone number format.</small>
                                            <span id="error_msg"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-label" for="name">Name <span style="color:red;">*</span></label>											
									<input class="input1 form-control" type="text" id="name" name="name" autocomplete="off">										
									<span id="error_msg"></span>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="form-label" for="email_id">Email Address</label>									
									<input class="form-control" type="email" id="email_id" name="email" autocomplete="off">
									<span class="form_error" id="invalid_email">This email is not valid</span>										
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-label" for="ic_number">Ic No / Passport No</label>										
									<input class="form-control" type="text" id="ic_number" name="ic_number" autocomplete="off">										
									<span id="error_msg"></span>
								</div>
							</div>

							<!-- <div class="col-md-6">
								<div class="form-group">
									<label class="form-label" for="rasi_id">Rasi <span style="color:red;"></span></label>
									    <select class="form-control" name="rasi_id" id="rasi_id">
										    <option value="">Select Rasi</option>
										    <?php foreach ($rasi as $row) { ?>
											    <option value="<?php echo $row['id']; ?>">
												    <?php echo $row['name_eng']; ?>
											    </option>
										    <?php } ?>
									    </select>
									<span id="error_msg"></span>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-label" for="natchathra_id">Natchathram <span style="color:red;"></span></label>		
									<input type="hidden" id="natchathram_id" name="natchathram_id" class="form-control">
									<select class="form-control" name="natchathra_id" id="natchathra_id">
										<option value="">Select Natchiram</option>
									</select>
									<span id="error_msg"></span>
								</div>
							</div> -->

							<div class="col-md-6">
								<div class="form-group">
									<label class="form-label" for="address">Address</label>
									<textarea class="form-control" id="address"
										name="address" style="width:100%;"
										autocomplete="off"></textarea>
									<span id="error_msg"></span>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-label"
										for="description">Remarks</label>
									<textarea class="form-control" id="description"
										name="description" style="width:100%;"
										autocomplete="off"></textarea>
									<span id="error_msg"></span>
								</div>
							</div>
						</div>


						<button type="button" name="ar_add_btn" id="ar_add_btn"
							class="btn btn-info my-3"
							style="width:100%; font-size:24px; height:auto;margin-bottom: 0 !important;">Submit</button>
					</div>
				</div>
			</div>
		</div>
	</div>

    <div id="alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" style="width: 127%;">
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="dripicons-information h1 text-info"></i>
                        <table>
                            <tr><span id="spndeddelid"><b></b></span>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-info my-3" data-dismiss="modal"> &times;</button></tr>
                        </table>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div>
    </div>

    <div id="alertModal" class="modal fade" tabindex="-1" rele="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!--div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div-->
                <div class="modal-body">
                    <p style="text-align:center;"><br><i class="mdi mdi-alert-circle-outline"
                            style="font-size:42px; color:red;"></i></p>
                    <h5 style="text-align:center;" id="modalMsg"></h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!--REPRINT SECTION START-->
    <div id="myModal_reprint" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"> 
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4" style="padding-bottom:10px;">
                    <div class="text-center">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered" style="width:100%">
                                    <thead>
                                        <tr style="font-size: 13px;text-align: left;background: #3F51B5;color: #fff;">
                                            <th style="width: 10%;padding: 5px 10px;text-align:center;">S.No</th>
                                            <th style="width: 40%;padding: 5px 10px;text-align:center;">Invoice No</th>
                                            <th style="width: 40%;padding: 5px 10px;text-align:center;">Amount</th>
                                            <th style="width: 10%;padding: 5px 10px;text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="height:auto; margin-bottom:30px;">
                                        <?php
                                        if (count($reprintlists) > 0) {
                                            $ire = 1;
                                            foreach ($reprintlists as $reprintlist) {
                                                ?>
                                                <tr>
                                                    <td style="width: 10%;padding: 5px 0px!important;text-align:center;">
                                                        <?php echo $ire; ?>
                                                    </td>
                                                    <td style="width: 40%;padding: 5px 0px!important;text-align:center;">
                                                        <?php echo $reprintlist['ref_no']; ?>
                                                    </td>
                                                    <td style="width: 40%;padding: 5px 0px!important;text-align:center;">
                                                        <?php echo $reprintlist['paidamount']; ?>
                                                    </td>
                                                    <td style="width: 10%;padding: 5px 0px!important;text-align:center;">
                                                        <a class='btn btn-primary'
                                                            style='font-size: 13px;font-weight: bold;padding: 6px 10px;background: #2196F3;border: 1px solid #2196F3;'
                                                            title='Print'
                                                            href='<?php echo base_url(); ?>/ubayam_online/reprint_booking/<?php echo $reprintlist['id']; ?>'
                                                            target='_blank'>Print</a>
                                                    </td>
                                                </tr>
                                                <?php
                                                $ire++;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Venue Modal -->
    <div id="venueModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                   <h4 class="modal-title">Select a Venue</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <select id="venueSelect" class="form-control">
                    <?php foreach($venues as $venue): ?>
                        <option value="<?= $venue['id'] ?>"><?= $venue['name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal" id="loadButton">Load Calendar</button>
                </div>
            </div>

        </div>
    </div>

    <!-- Payment Alert Modal -->
    <div class="modal fade" id="paymentAlertModal" tabindex="-1" aria-labelledby="paymentAlertModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentAlertModalLabel" style="color: red; font-size: 24px;">Payment Alert!!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="paymentAlertMessage" style="font-size: 18px;"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    <div id="alert-modal-print" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p style="text-align:center;">
                        <br><i class="mdi mdi-checkbox-marked-circle-outline" style="font-size:42px; color:green;"></i>
                    </p>
                    <h4 style="text-align:center;" id="spndeddelid1"></h4>
                </div>
                <div class="modal-body text-center">
                    <h5>Choose your preferred Print Method</h5>
                    <br>
                    <button type="button" class="btn btn-primary m-2" id="print-imin">Print Imin</button>
                    <button type="button" class="btn btn-warning m-2" id="print-a4">Print A4</button>
                    <button type="button" class="btn btn-success m-2" id="print-a5">Print A5</button>
                </div>
                <div class="modal-footer text-center">
                    <button type="button" class="btn btn-info" id="okay" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="alert-modal2" class="modal fade" tabindex="-1" rele="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p style="text-align:center;"><br><i class="mdi mdi-thumbs-up" style="font-size:42px; color:blue;"></i></p>
                    <h5 style="text-align:center;" id="spndeddelid2"></h5>
                </div>
            </div>
        </div>
    </div>



    <div id="prin_page"></div>
    <!-- container-scroller -->
    <script src="<?php echo base_url(); ?>/assets/archanai/js/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> 
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
    <script src="<?php echo base_url(); ?>/assets/archanai/js/dashboard.js"></script>
    <script  src="<?php echo base_url(); ?>/assets/archanai/script.js"></script>

    <script src="<?php echo base_url(); ?>/assets/archanai/js/popper.js"></script>
    
    <link href="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
    <script src="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/ui_jquery/jquery-ui.css">
    <script src="<?php echo base_url(); ?>/assets/ui_jquery/jquery-ui.js"></script>
    <script src="<?php echo base_url(); ?>/assets/ui_jquery/moment.min.js"></script>

    <script>
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

        $('.check_time1').change(function() {
            $('#time-picker-container1').hide();
            
            $('#hour1').empty();
            $('#minute1').empty();

            if ($('#breakfast1').is(':checked')) {
                for (let i = 6; i <= 11; i++) {
                    $('#hour1').append(`<option value="${i < 10 ? '0' + i : i}">${i < 10 ? '0' + i : i}</option>`); // Format hour with leading zero
                }
                for (let i = 0; i < 60; i += 5) {
                    $('#minute1').append(`<option value="${i < 10 ? '0' + i : i}">${i < 10 ? '0' + i : i}</option>`); // Format minute with leading zero
                }
                $('#ampm1').val('AM');
                $('#time-picker-container1').show();  // Show the time picker container
            } else if ($('#lunch1').is(':checked')) {

                for (let i = 12; i <= 15; i++) {
                    var time_val = i > 12 ? '0' + (i - 12) : i;
                    $('#hour1').append(`<option value="${time_val}">${time_val}</option>`); // 12-hour format
                }
                for (let i = 0; i < 60; i += 5) {
                    $('#minute1').append(`<option value="${i < 10 ? '0' + i : i}">${i < 10 ? '0' + i : i}</option>`); // Format minute with leading zero
                }
                $('#ampm1').val('PM');
                $('#time-picker-container1').show();  // Show the time picker container
            } else if ($('#dinner1').is(':checked')) {
                
                for (let i = 6; i <= 9; i++) {
                    $('#hour1').append(`<option value="${i < 10 ? '0' + i : i}">${i < 10 ? '0' + i : i}</option>`);// 12-hour format
                }
                for (let i = 0; i < 60; i += 5) {
                    $('#minute1').append(`<option value="${i < 10 ? '0' + i : i}">${i < 10 ? '0' + i : i}</option>`); // Format minute with leading zero
                }
                $('#ampm1').val('PM');
                $('#time-picker-container1').show();  // Show the time picker container
            }
        });
    </script>

    <!-- Phone validations and Auto fill -->
    <script>
        updatePhoneInputInstructions();
        document.getElementById('mobile').addEventListener('input', function() {
            validatePhoneNumber();
        });

        document.getElementById('phonecode').addEventListener('change', function() {
            updatePhoneInputInstructions();
            validatePhoneNumber();
        });

        function updatePhoneInputInstructions() {
            const phoneCode = document.getElementById('phonecode').value;
            const phoneInput = document.getElementById('mobile');

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
            const phoneCode = document.getElementById('phonecode').value;
            const phoneNumber = document.getElementById('mobile').value;
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
            var phone_code = $('#phonecode').val();
            var phone_number = $('#mobile').val();
            $.ajax({
                url: "<?php echo base_url(); ?>/hallbooking_online/get_devotee_details", 
                method: "POST",
                data: {code: phone_code, number:  phone_number},
                datatype: "json",
                success: function(response) {
                    response = JSON.parse(response);
                    console.log(response);
                    
                    $('#name').val(response.name);
                    $('#ic_number').val(response.ic_no);
                    $('#email_id').val(response.email);
                    $('#address').val(response.address);
                    // $('select[name="rasi_id"]').val(response.rasi_id);
                    // $("#rasi_id").change();
                    // setTimeout(function() {
                    //     $('select[name="natchathra_id"]').val(response.natchathra_id);
                    // }, 500);
                },
                error: function() {
                    alert("Failed to load user data.");
                }
            });
        }

        $('#mobile').on('blur', function() {
            loadUserData();
        });

    </script>

    <script>
        $(document).ready(function() {
            $('#venue_select').click(function() {
                $("#venueModal").modal("show");
            });
		
            function updatePaymentSections() {
                var paymentType = $('.payment_type:checked').val(); 

                if (paymentType === 'partial') {
                    $('.partial_paid_sec').show();  
                    $('.payment1').show();          
                    $('#full_paid_amount').prop('disabled', true);  
                } else if (paymentType === 'full') {
                    $('.partial_paid_sec').hide();  
                    $('.payment1').show();         
                    $('#full_paid_amount').prop('disabled', false); 
                } else if (paymentType === 'only_booking') {
                    $('.partial_paid_sec').hide();  
                    $('.payment1').hide();          
                    $('#full_paid_amount').prop('disabled', false); 
                }
            }

            $(document).on('change', '.payment_type', function() {
                updatePaymentSections();  
            });

            updatePaymentSections();  
        });

         $("#add_extra_charge").click(function () {
            var desc = $('#extra_desc').val().trim();
            var amount = $('#extra_amount').val().trim();

            if (desc === '') {
                alert('Provide a description to add extra charges.');
                return;
            }
            if (amount === '' || isNaN(parseFloat(amount))) {
                alert('Please enter a valid amount.');
                return;
            }

            let cnt = $("#extra_charges_table tr").length;
            var html_e = '<tr id="rmv_extra_row' + cnt + '">';
            html_e += '<td style="width: 20%;"><input type="hidden" readonly name="extra_charges[' + cnt + '][desc]" value="' + desc + '"><input type="text" style="border: none;width: 100%;" readonly id="extra_desc_' + cnt + '" value="' + desc + '"></td>';
            html_e += '<td align="center" style="width: 25%;"><input type="hidden" name="extra_charges[' + cnt + '][amount]" value="' + Number(amount).toFixed(2) + '"><input type="text" style="border: none;width: 100%;" readonly class="extra_amt" id="extra_amount_' + cnt + '" value="' + Number(amount).toFixed(2) + '"></td>';
            html_e += '<td align="center" style="width: 10%;"><a class="btn btn-danger btn-rad" onclick="rmv_extra_charges(' + cnt + ')" style="width:auto;padding: 0px 3px !important;"><span>X</span></a></td>';
            html_e += '</tr>';

            $("#extra_charges_table").append(html_e);
            $("#extra_desc").val('');
            $('#extra_amount').val('');
            sum_amount();            
        });

        function rmv_extra_charges(id){
            $("#rmv_extra_row"+id).remove();
            sum_amount();
        }
    </script>

    <!-- Prasadam -->
    <script>
        $("#add_one_prasadam").change(function () {
            var id = $("#add_one_prasadam").val();
            if (id != '') {
                $.ajax({
                    url: "<?php echo base_url(); ?>/templeubayam_online/get_prasadam_amt",
                    type: "post",
                    data: { id: id },
                    dataType: "json",
                    success: function (data) {
                        console.log(data)
                        $("#get_prasadam_amt").val(Number(data['amt']).toFixed(2));
                        $("#prasadam_name").val(data['name']);
                    }
                });
            } else {
                $("#get_prasadam_amt").val(0);
            }
            sum_amount();
        });

        function get_prasadam_name(id, cmlp) {
            if (id != '') {
                $.ajax({
                    url: "<?php echo base_url(); ?>/templeubayam_online/get_prasadam_name",
                    type: "post",
                    data: { id: id },
                    dataType: "json",
                    success: function (data) {
                        console.log('name response:', data);
                        $("#prasadam_name_" + cmlp).val(data['name']);
                    }
                });
            }
        }

        var cnt = 1;
        $("#prasadam_add").click(function () {
            var id = $("#add_one_prasadam option:selected").val();
            var amt = $("#get_prasadam_amt").val();
            var min_qty = 1;

            if (id != '' && parseFloat(amt) > 0) {
                var status_check = 0;
                var rowId;

                $(".prasadam_category").each(function () {
                    var arcat = parseInt($(this).val());
                    if (arcat == id) {
                        status_check++;
                        rowId = $(this).closest('tr').attr('id').replace('rmv_packrow_addon', '');
                    }
                });

                if (status_check > 0) {
                    alert('Product already added to cart');
                } else {
                    $.ajax({
                        url: "<?php echo base_url(); ?>/templeubayam_online/get_prasadam_list",
                        type: "post",
                        data: { id: id },
                        success: function (response) {
                            response = JSON.parse(response);
                            if (response.length > 0) {
                                $.each(response, function (key, value) {
                                    var countid = value.id;
                                    var serviceid = value.id;
                                    var serviceamount = value.amount;
                                    get_prasadam_name(serviceid, countid);
                                    var html_p = '<tr id="rmv_pack_prasadam1' + countid + '">';
                                    html_p += '<td style="width: 5%; text-align: center;">' + cnt + '</td>';
                                    html_p += '<td style="width: 35%; text-align: center;"><input type="hidden" readonly name="prasadam[' + countid + '][id]" value="' + serviceid + '"><input type="text" style="border: none;width: 100%;" readonly id="prasadam_name_' + countid + '" data-amount1="' + serviceamount + '"></td>';
                                    html_p += '<td style="width: 10%; text-align: center;"><div class="itemcountrr"><div class="value-button" id="decrease" onclick="decreaseValue1(' + countid + ')" value="Decrease Value" style="font-weight: bold;font-size: 16px; cursor: pointer;">-</div><input type="number" name="prasadam[' + countid + '][quantity]" min="1" id="quantity1' + countid + '" value="1" pattern="[0-9]*" oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" class="qty_amt" style="text-align: center;border: none;border-top: 1px solid #ddd;border-bottom: 1px solid #ddd;margin: 0px;width: 35px;height: 25px;" onkeyup="qtykeyup1(' + countid + ')" /><div class="value-button" id="increase" onclick="increaseValue1(' + countid + ')" value="Increase Value" style="font-weight: bold;font-size: 16px; cursor: pointer;">+</div></div></td>';
                                    html_p += '<td style="width: 20%; text-align: center;"><input type="text" style="border: none;width: 100%; text-align: center;" class="editable_amt_prasadam" id="editable_amt_prasadam_' + countid + '" name="prasadam[' + countid + '][amount]" value="' + Number(serviceamount).toFixed(2) + '" onkeyup="editableAmountKeyUp1(' + countid + ')"></td>';
                                    html_p += '<td style="width: 20%; text-align: center;"><input type="text" style="border: none;width: 100%;" class="package_amt_prasadam" id="package_amt_prasadam_' + countid + '" name="prasadam[' + countid + '][total_amount]" value="' + (Number(serviceamount) * Number(min_qty)).toFixed(2) + '" onkeyup="serviceamount()"></td>';
                                    html_p += '<td style="width: 10%; text-align: center;"><a class="btn btn-danger btn-rad" onclick="rmv_pack_prasadam1(' + countid + ')" style="width:auto;padding: 0px 3px !important;"><i class="material-icons">X</i></a><input type="hidden" class="prasadam_category" value=' + id + '></td>';
                                    html_p += '</tr>';
                                    $("#prasadam_table").append(html_p);
                                });
                                sum_amount();
                                updateSerialNumbers1();
                            }
                        }
                    });
                    $("#get_prasadam_amt").val('');
                    $('#add_one_prasadam').prop('selectedIndex', 0);
                }
            }
        });

        function increaseValue1(cnt) {
            var quantity = $("#quantity1" + cnt);
            var currentVal = parseInt(quantity.val());
            if (!isNaN(currentVal)) {
                quantity.val(currentVal + 1);
                updateAmount1(cnt);
            } else {
                quantity.val(1);
            }
            sum_amount();
        }

        function decreaseValue1(cnt) {
            var quantity = $("#quantity1" + cnt);
            var currentVal = parseInt(quantity.val());
            if (!isNaN(currentVal) && currentVal > 1) {
                quantity.val(currentVal - 1);
                updateAmount1(cnt);
            } else {
                quantity.val(1);
            }
            sum_amount();
        }

        function qtykeyup1(cnt) {
            var quantity = $("#quantity1" + cnt);
            var currentVal = parseInt(quantity.val());
            console.log(currentVal);
            if (!isNaN(currentVal) && currentVal >= 0) {
                if (currentVal > quantity.attr('max')) quantity.val(quantity.attr('max'));
            } else {
                quantity.val(1);
            }
            updateAmount1(cnt);
            sum_amount();
        }

        function editableAmountKeyUp1(cnt) {
            var amountInput = $("#editable_amt_prasadam_" + cnt);
            var val = parseFloat(amountInput.val()) || 0;
            if (val < 0.01) {
                amountInput.val("0.01");
            }
            updateAmount1(cnt, true);
        }

        function updateAmount1(cnt, fromAmountField = false) {
            var quantity = parseInt($("#quantity1" + cnt).val()) || 1;
            if(quantity < 1) {
                quantity = 1;
                $("#quantity1" + cnt).val(quantity);
            }

            var unitPrice = parseFloat($("#editable_amt_prasadam_" + cnt).val()) || 0.01;
            if(unitPrice < 0.01) {
                unitPrice = 0.01;
                $("#editable_amt_prasadam_" + cnt).val(unitPrice.toFixed(2));
            }

            var totalPrice = quantity * unitPrice;
            $("#package_amt_prasadam_" + cnt).val(Number(totalPrice).toFixed(2));
            
            if (!fromAmountField) {
                $("#editable_amt_prasadam_" + cnt).val(Number(unitPrice).toFixed(2));
            }
            sum_amount();
        }

        function rmv_pack_prasadam1(id) {
            $("#rmv_pack_prasadam1" + id).remove();
            sum_amount();
            updateSerialNumbers1();
        }

        function updateSerialNumbers1() {
            $(".prasadam_category").each(function(index) {
                var countId = index + 1;
                $(this).closest('tr').find('td:first').text(countId);
            });
        }
    </script>

    <!-- Annathanam-->
    <script>
        $('#annathanam_add').click(function() {
            var id = $('#annathanam_package_select option:selected').val();
            var package_name = $('#annathanam_package_select option:selected').data('name');
            var amount = $('#annathanam_package_select option:selected').data('amount');
            var qty = 50;

            if ($("#annathanam_table tbody tr").length > 0) {
                alert("Only one Annathanam package can be added.");
                return;
            }

            if (id !== "" && id !== null && id !== undefined) {
                $.ajax({
                    url: "<?php echo base_url(); ?>/templeubayam_online/get_annathanam_items",
                    type: "post",
                    data: { id: id },
                    success: function(response) {
                        response = JSON.parse(response);
                        if (response.items.length > 0) {
                            var countid = id;
                            var serviceid = id;
                            var package_items = response.items;

                            var html_a = '<tr id="rmv_annathanam' + countid + '">';
                            html_a += '<td style="width: 6%;">1</td>';
                            html_a += '<td style="width: 20%;"><input type="hidden" readonly name="annathanam[' + countid + '][id]" value="' + serviceid + '">' + package_name + '</td>';
                            html_a += '<td style="width: 36%;">' + package_items + '</td>';
                            html_a += '<td style="width: 8%;"><div class="itemcountrr"><div class="value-button" id="decrease" onclick="decreaseValue_an(' + countid + ')" value="Decrease Value" style="font-weight: bold;font-size: 16px; cursor: pointer;">-</div><input type="number" name="annathanam[' + countid + '][quantity]" min="1" id="quantity_an" value="'+ qty +'" pattern="[0-9]*" oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" class="qty_amt" style="text-align: center;border: none;border-top: 1px solid #ddd;border-bottom: 1px solid #ddd;margin: 0px;width: 35px;height: 25px;" onkeyup="qtykeyup_an(' + countid + ')" /><div class="value-button" id="increase" onclick="increaseValue_an(' + countid + ')" value="Increase Value" style="font-weight: bold;font-size: 16px; cursor: pointer;">+</div></div></td>';
                            html_a += '<td style="width: 11%;"><input type="text" style="border: none;width: 100%; text-align: center;" class="editable_amt_annathanam" id="editable_amt_annathanam_' + countid + '" name="annathanam[' + countid + '][amount]" value="' + Number(amount).toFixed(2) + '" onkeyup="editableAmountKeyUp_an(' + countid + ')"></td>';
                            html_a += '<td style="width: 8%;"> <input type="text" style="border: none;width: 100%;" class="package_amt_annathanam" id="package_amt_annathanam_' + countid + '" name="annathanam[' + countid + '][total_amount]" value="' + (Number(amount) * qty).toFixed(2) + '"></td>';
                            html_a += '<td style="width: 5%;"><a class="btn btn-danger btn-rad" onclick="rmv_annathanam(' + countid + ')" style="width:auto;padding: 0px 3px !important;"><i class="material-icons">X</i></a><input type="hidden" class="annathanam_category" value=' + id + '></td>';
                            html_a += '</tr>';

                            $("#annathanam_table tbody").append(html_a);
                        }

                        var a_html = '';
                        if (response.addons.length > 0) {
                            a_html = '<option value="">Select Annathanam Addons</option>';
                            response.addons.forEach(function(value) {
                                a_html += '<option data-amount="' + value.amount + '" value="' + value.id + '">' + value.name_eng + '</option>';
                            });
                        } else {
                            a_html = '<option value="">No Addons Found</option>';
                        }
                        $('#annathanam_addon_select').html(a_html);
                    }
                });
            }
        });

        function increaseValue_an(cnt) {
            var quantity = $("#quantity_an");
            var currentVal = parseInt(quantity.val());
            if (!isNaN(currentVal)) {
                quantity.val(currentVal + 1);
            } else {
                quantity.val(1);
            }
            updateAmount_an(cnt);
        }

        function decreaseValue_an(cnt) {
            var quantity = $("#quantity_an");
            var currentVal = parseInt(quantity.val());
            if (!isNaN(currentVal) && currentVal > 1) {
                quantity.val(currentVal - 1);
            } else {
                quantity.val(1);
            }
            updateAmount_an(cnt);
        }

        function qtykeyup_an(cnt) {
            var quantity = $("#quantity_an");
            var currentVal = parseInt(quantity.val());
            console.log(currentVal);
            if (!isNaN(currentVal) && currentVal >= 0) {
                if (currentVal > quantity.attr('max')) quantity.val(quantity.attr('max'));
            } else {
                quantity.val(1);
            }
            updateAmount_an(cnt);
        }

        function editableAmountKeyUp_an(cnt) {
            var amountInput = $("#editable_amt_annathanam_" + cnt);
            var val = parseFloat(amountInput.val()) || 0;
            if (val < 0.01) {
                amountInput.val("0.01");
            }
            updateAmount_an(cnt, true);
        }

        function updateAmount_an(cnt, fromAmountField = false) {
            var quantity = parseInt($("#quantity_an").val());
            console.log('quantity:', quantity);
            if(quantity < 1) {
                quantity = 1;
                $("#quantity_an").val(quantity);
            }
            var unitPrice = parseFloat($("#editable_amt_annathanam_" + cnt).val());
            console.log('amount:', unitPrice);
            if(unitPrice < 0.01) {
                unitPrice = 0.01;
                $("#editable_amt_annathanam_" + cnt).val(unitPrice.toFixed(2));
            }
            var totalPrice = quantity * unitPrice;
            $("#package_amt_annathanam_" + cnt).val(Number(totalPrice).toFixed(2));
            
            if (!fromAmountField) {
                $("#editable_amt_annathanam_" + cnt).val(Number(unitPrice).toFixed(2));
            }
            sum_amount();
        }

        function rmv_annathanam(id) {
            $("#rmv_annathanam" + id).remove();
        }

        $("#annathanam_addon_select").change(function () {
            var id = $("#annathanam_addon_select").val();
            if (id != '') {
                $.ajax({
                    url: "<?php echo base_url(); ?>/templeubayam_online/get_anna_addon_amt",
                    type: "post",
                    data: { id: id },
                    dataType: "json",
                    success: function (data) {
                        $("#get_anna_addon_amt").val(Number(data['amt']).toFixed(2));
                        $("#anna_addon_name").val(data['name']);
                    }
                });
            } else {
                $("#get_anna_addon_amt").val(0);
            }
            sum_amount();
        });

        var cnt = 1;
        $("#addon_add").click(function () {
            var id = $("#annathanam_addon_select option:selected").val();
            var amt = $("#get_anna_addon_amt").val();
            var addon_name = $("#anna_addon_name").val();
            var package_id = $('.package_id').val(); quantity_an
            var quantity = $('#quantity_an').val() || 1;
            console.log('quantity:', quantity);

            if (id != '' && parseFloat(amt) > 0) {
                var status_check = 0;
                var rowId;

                $(".anna_addon_category").each(function () {
                    var arcat = parseInt($(this).val());
                    if (arcat == id) {
                        status_check++;
                        rowId = $(this).closest('tr').attr('id').replace('rmv_anna_addon', '');
                    }
                });

                if (status_check > 0) {
                    alert('This addon is already added to the cart');
                } else {
                    var countid = id;
                    var serviceid = id;
                    var quantity = quantity;
                    var serviceamount = amt;
                    
                    var html = '<tr id="rmv_anna_addon' + countid + '">';
                    html += '<td style="width: 5%; text-align: center;">' + cnt + '</td>';
                    html += '<td style="width: 35%; text-align: center;"><input type="hidden" readonly name="anna_addon[' + countid + '][id]" value="' + serviceid + '">' + addon_name + '</td>';
                    html += '<td style="width: 20%; text-align: center;"><div class="itemcountrr"><div class="value-button" id="decrease" onclick="decreaseValue_an1(' + countid + ')" value="Decrease Value" style="font-weight: bold;font-size: 16px; cursor: pointer;">-</div><input type="number" name="anna_addon[' + countid + '][quantity]" min="1" id="quantity_ad' + countid + '" value="'+quantity+'" pattern="[0-9]*" oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" class="qty_amt" style="text-align: center;border: none;border-top: 1px solid #ddd;border-bottom: 1px solid #ddd;margin: 0px;width: 35px;height: 25px;" max="' + quantity + '" onkeyup="qtykeyup_an1(' + countid + ')" /><div class="value-button" id="increase" onclick="increaseValue_an1(' + countid + ')" value="Increase Value" style="font-weight: bold;font-size: 16px; cursor: pointer;">+</div></div></td>';
                    html += '<td style="width: 15%; text-align: center;"><input type="text" style="border: none;width: 100%; text-align: center;" class="editable_amt_anna_addon" id="editable_amt_anna_addon_' + countid + '" name="anna_addon[' + countid + '][amount]" value="' + Number(serviceamount).toFixed(2) + '" onkeyup="editableAmountKeyUp_an1(' + countid + ')"></td>';
                    html += '<td style="width: 15%; text-align: center;"><input type="text" style="border: none;width: 100%;" class="package_amt_anna_addon" id="package_amt_anna_addon_' + countid + '" name="anna_addon[' + countid + '][total_amount]" value="' + (Number(serviceamount) * Number(quantity)).toFixed(2) + '"onkeyup="serviceamount_an1()"></td>';
                    html += '<td style="width: 10%; text-align: center;"><a class="btn btn-danger btn-rad" onclick="rmv_anna_addon(' + countid + ')" style="width:auto;padding: 1px 6px !important;height: auto;font-size: 14px;color: #FFF;">X</a><input type="hidden" class="anna_addon_category" value=' + id + '></td>';
                    html += '</tr>';
                    $("#annathanam_addon_table").append(html);
                
                    sum_amount();
                    updateSerialNumbers()
                            
                    $("#get_anna_addon_amt").val('');
                    $('#annathanam_addon_select').prop('selectedIndex', 0);
                }
            }
        });

        function increaseValue_an1(cnt) {
            var quantity = $("#quantity_ad" + cnt);
            var currentVal = parseInt(quantity.val());
            if (!isNaN(currentVal)) {
                quantity.val(currentVal + 1);
            } else {
                quantity.val(1);
            }
            updateAmount_an1(cnt);
        }

        function decreaseValue_an1(cnt) {
            var quantity = $("#quantity_ad" + cnt);
            var currentVal = parseInt(quantity.val());
            if (!isNaN(currentVal) && currentVal > 1) {
                quantity.val(currentVal - 1);
            } else {
                quantity.val(1);
            }
            updateAmount_an1(cnt);
        }

        function qtykeyup_an1(cnt) {
            var quantity = $("#quantity_ad" + cnt);
            var currentVal = parseInt(quantity.val());
            if (!isNaN(currentVal) && currentVal >= 0) {
                if (currentVal > quantity.attr('max')) quantity.val(quantity.attr('max'));
            } else {
                quantity.val(1);
            }
            updateAmount_an1(cnt);
        }

        function editableAmountKeyUp_an1(cnt) {
            var amountInput = $("#editable_amt_anna_addon_" + cnt);
            var val = parseFloat(amountInput.val()) || 0;
            if (val < 0.01) {
                amountInput.val("0.01");
            }
            updateAmount_an1(cnt, true);
        }

        function updateAmount_an1(cnt, fromAmountField = false) {
            var quantity = parseInt($("#quantity_ad" + cnt).val()) || 1;
            if(quantity < 1) {
                quantity = 1;
                $("#quantity_ad" + cnt).val(quantity);
            }

            var unitPrice = parseFloat($("#editable_amt_anna_addon_" + cnt).val());
            if(unitPrice < 0.01) {
                unitPrice = 0.01;
                $("#editable_amt_anna_addon_" + cnt).val(unitPrice.toFixed(2));
            }

            var totalPrice = quantity * unitPrice;
            $("#package_amt_anna_addon_" + cnt).val(Number(totalPrice).toFixed(2));
            
            if (!fromAmountField) {
                $("#editable_amt_anna_addon_" + cnt).val(Number(unitPrice).toFixed(2));
            }
            sum_amount();
        }

        function rmv_anna_addon(id) {
            $("#rmv_anna_addon" + id).remove();
            updateSerialNumbers();
            sum_amount();
        }

        function updateSerialNumbers() {
            $(".anna_addon_category").each(function(index) {
                var countId = index + 1;
                $(this).closest('tr').find('td:first').text(countId);
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#deposit_amt').on('input', function() {
                sum_amount();
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#rasi_id').change(function() {
                var rasiId = $(this).val();  // Get the selected rasi id

                if (rasiId != "") {
                    $.ajax({
                        url: '<?php echo base_url(); ?>/hallbooking_online/get_natchathram',
                        type: 'post',
                        data: { rasi_id: rasiId },
                        dataType: 'json',
                        success: function(response) {
                            console.log('natchathiram:', response);
                            var natchathramDropdown = $('#natchathra_id');
                            natchathramDropdown.empty();  // Clear existing options
                            natchathramDropdown.append('<option value="">Select Natchathiram</option>');

                            if (response.natchathra_id) {
                                var str = response.natchathra_id;

                                $.each(str.split(','), function(key, value) {
                                    $.ajax({
                                        url: '<?php echo base_url(); ?>/hallbooking_online/get_natchathram_name',
                                        type: 'post',
                                        data: { id: value },
                                        dataType: 'json',
                                        success: function(natchathraResponse) {
                                            natchathramDropdown.append('<option value="' + natchathraResponse.id + '">' + natchathraResponse.name_eng + '</option>');
                                        }
                                    });
                                });
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                        }
                    });
                } else {
                    $('#natchathra_id').empty().append('<option value="">Select Natchathiram</option>');
                }
            });
        });
    </script>

   <script>
        //$(function() {
        //var filterList = {
        //   init: function() {
            // MixItUp plugin
            // http://mixitup.io
        //     $('#portfoliolist').mixItUp({
        //       selectors: {
        //         target: '.portfolio',
        //         filter: '.filter'
        //       },
        //       load: {
        //         filter: '.<?php /*echo $default;*/ ?>'
        //       }
        //     });

        //   }

        //};
        // Run the show!
        //filterList.init();
        //});


    function rePrint()
    {
        $("#myModal_reprint").modal("show");
    }
    var userDetail = (function() {
        user = [];
        // Constructor
        function Item(date,name,email_id,phonecode,mobile,ic_number,rasi_id,rasi_text,natchathra_id,natchathra_text,address,description) {
            this.date = date;
            this.name = name;
            this.email_id = email_id;
            this.phonecode = phonecode;
            this.mobile = mobile;
            this.ic_number = ic_number;
            this.rasi_id = rasi_id;
            this.rasi_text = rasi_text;
            this.natchathra_id = natchathra_id;
            this.natchathra_text = natchathra_text;
            this.address = address;
            this.description = description;
        }
        // Save user
        function saveUser() {
            sessionStorage.setItem('ubayam_userdetails', JSON.stringify(user));
        }
            // Load user
        function loadUser() {
            user = JSON.parse(sessionStorage.getItem('ubayam_userdetails'));
        }
        if (sessionStorage.getItem("ubayam_userdetails") != null) {
            loadUser();
        }
        var obj = {};
        // Add to user
        obj.addUserToCart = function(dt,name,email_id,phonecode,mobile,ic_number,rasi_id,rasi_text,natchathra_id,natchathra_text,address,description) {
            var item = new Item(dt,name,email_id,phonecode,mobile,ic_number,rasi_id,rasi_text,natchathra_id,natchathra_text,address,description);
            user.push(item);
            saveUser();
        }
        // clear user
        obj.clearUser = function() {
            user = [];
            saveUser();
        }
        // List user
        obj.listUser = function() {
            return user;
        }
        return obj;
    })();

    function userModalOpen() {
        $("#myModal").modal("show");
        var cartArray = userDetail.listUser();
        if (cartArray.length > 0) {
            $('#name').val(cartArray[0].name);
            $('#email_id').val(cartArray[0].email_id);
            $('#phonecode').val(cartArray[0].phonecode);
            $('#mobile').val(cartArray[0].mobile);
            $('#ic_number').val(cartArray[0].ic_number);
            $('#rasi_id').val(cartArray[0].rasi_id);
            $('#natchathra_id').val(cartArray[0].natchathra_id);
            $('#address').val(cartArray[0].address);
            $('#description').val(cartArray[0].description);
            $('.show-cart').show();
        } else {
            $('#name').val("");
            $('#email_id').val("");
            $('#phonecode').val("+60");
            $('#mobile').val("");
            $('#ic_number').val("");
            $('#rasi_id').val("");
            $('#natchathra_id').val("");
            $('#address').val("");
            $('#description').val("");
            $('.show-cart').empty();
            $('.show-cart').hide();
        }
    }

    $('.form_error').hide();

    $('#ar_add_btn').click(function (event) {
        userDetail.clearUser();
        event.preventDefault();

        var name = $('#name').val();
        var mobile = $('#mobile').val();

        // Validate required fields
        if (name == "") {
            $('#name').siblings('#error_msg').text('Name is required');
        } else {
            $('#name').siblings('#error_msg').text('');
        }

        if (mobile == "") {
            $('#mobile').siblings('#error_msg').text('Mobile is required');
        } else {
            $('#mobile').siblings('#error_msg').text('');
        }

        // Validate optional fields
        $('.form-control').not('#name, #mobile').each(function () {
            if ($(this).val() != "") {
                $(this).siblings('#error_msg').text('');
            }
        });

        // Check if any required field is empty
        if (name == "" || mobile == "") {
            return; // Stop form submission if required fields are not filled
        }

        var email_id = $('#email_id').val();
        var address = $('#address').val();
        var rasi_id = $('#rasi_id').val();
        var rasi_text = $("#rasi_id option:selected").text().trim();
        var natchathra_id = $('#natchathra_id').val();
        var natchathra_text = $("#natchathra_id option:selected").text().trim();
        var phonecode = $('#phonecode').val();
        var description = $('#description').val();
        console.log('Before Adding to Cart - Rasi ID:', rasi_id, ' Rasi Text:', rasi_text);
        console.log('Before Adding to Cart - Natchathra ID:', natchathra_id, ' Natchathra Text:', natchathra_text);


        //if(name == "") { $(this).siblings("#error_msg").html("Field needs filling"); }
        //if(email_id == "") { $(this).siblings("#error_msg").html("Field needs filling"); }


        // $('.form-control').each(function () {
        //     if ($(this).val() == "") {
        //         $(this).siblings('#error_msg').text('Field needs to Fill');
        //     } else {
        //         $(this).siblings('#error_msg').text('');
        //     }
        // });

        // if(email_id != "") {
        //     if(IsEmail(email_id)==false){
        //         $('#invalid_email').show();
        //         return false;
        //     }
        // }

        function IsEmail(email_id) {
            var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (!regex.test(email_id)) {
                return false;
            } else {
                return true;
            }
        }


        if (name != "" && mobile != "") {
            $("#myModal").modal("hide");
            userDetail.addUserToCart(dt, name, email_id, phonecode, mobile, rasi_id, rasi_text, natchathra_id, natchathra_text, address, description);
            displayCart();
        }
        // userDetail.addUserToCart({
        //     rasi_id: rasi_id,
        //     rasi_text: rasi_text,
        //     natchathra_id: natchathra_id,
        //     natchathra_text: natchathra_text,
        //     // other data fields
        // });
        console.log('Cart Contents:', userDetail.listUser());
    });


    function saveBrideGroomDetails() {
        const brideName = $('#brideName').val();
        const brideDOB = $('#brideDOB').val();
        const brideIC = $('#brideIC').val();
        const groomName = $('#groomName').val();
        const groomDOB = $('#groomDOB').val();
        const groomIC = $('#groomIC').val();

        sessionStorage.setItem('brideName', brideName);
        sessionStorage.setItem('brideDOB', brideDOB);
        sessionStorage.setItem('brideIC', brideIC);
        sessionStorage.setItem('groomName', groomName);
        sessionStorage.setItem('groomDOB', groomDOB);
        sessionStorage.setItem('groomIC', groomIC);

        displayCart(); // Refresh the cart display
    }

    function displayCart() {
        var cartArray = userDetail.listUser();
        if (cartArray.length > 0) {
            console.log(cartArray);
            var output = "";
            output += "<tr>"
                + "<th style='padding: 5px 10px;line-height: 20px;width:10%'>Name </th><td style='background: #fff;border: 1px solid #dee2e6;padding: 5px 10px;line-height: 20px;'>" + cartArray[0].name + "</td>"
                + "</tr>";
            output += "<tr>"
                + "<th style='padding: 5px 10px;line-height: 20px;width:10%'>Email ID</th><td style='background: #fff;border: 1px solid #dee2e6;padding: 5px 10px;line-height: 20px;'>" + cartArray[0].email_id + "</td>"
                + "</tr>";
            output += "<tr>"
                + "<th style='padding: 5px 10px;line-height: 20px;width:10%'>Mobile No </th><td style='background: #fff;border: 1px solid #dee2e6;padding: 5px 10px;line-height: 20px;'>" + cartArray[0].phonecode + " " + cartArray[0].mobile + "</td>"
                + "</tr>";
            // output += "<tr>"
            //     + "<th style='padding: 5px 10px;line-height: 20px;width:10%'>Rasi </th><td style='background: #fff;border: 1px solid #dee2e6;padding: 5px 10px;line-height: 20px;'>" + cartArray[0].rasi_text + "</td>"
            //     + "</tr>";
            // output += "<tr>"
            //     + "<th style='padding: 5px 10px;line-height: 20px;width:10%'>Natchathiram</th><td style='background: #fff;border: 1px solid #dee2e6;padding: 5px 10px;line-height: 20px;'>" + cartArray[0].natchathra_text + "</td>"
            //     + "</tr>";
            //output += "<tr>"
            // + "<th style='padding: 5px 10px;line-height: 20px;width:10%'>Address </th><td style='background: #fff;border: 1px solid #dee2e6;padding: 5px 10px;line-height: 20px;'>"+cartArray[0].address+"</td>"
            //+ "<th style='padding: 5px 10px;line-height: 20px;width:10%'>Remarks</th><td style='background: #fff;border: 1px solid #dee2e6;padding: 5px 10px;line-height: 20px;'>"+cartArray[0].description+"</td>"
            //+ "</tr>";
            output += '<input type="hidden" name="name" value="' + cartArray[0].name + '" />\
                <input type="hidden" name="email" value="' + cartArray[0].email_id + '" />\
                <input type="hidden" name="mobile_code" value="' + cartArray[0].phonecode + '" />\
                <input type="hidden" name="mobile_no" value="' + cartArray[0].mobile + '" />\
                <input type="hidden" name="mobile_code" value="' + cartArray[0].phonecode + '" />\
                <input type="hidden" name="rasi_id" value="' + cartArray[0].rasi_id + '" />\
                <input type="hidden" name="natchathra_id" value="' + cartArray[0].natchathra_id + '" />\
                <input type="hidden" name="address" value="' + cartArray[0].address + '" />\
                <input type="hidden" name="description" value="' + cartArray[0].description + '" />';

                    // Retrieve and display bride & groom details
                const brideName = sessionStorage.getItem('brideName');
                const brideDOB = sessionStorage.getItem('brideDOB');
                const brideIC = sessionStorage.getItem('brideIC');
                const groomName = sessionStorage.getItem('groomName');
                const groomDOB = sessionStorage.getItem('groomDOB');
                const groomIC = sessionStorage.getItem('groomIC');

                if (brideName) output += "<tr><th>Bride Name</th><td>" + brideName + "</td></tr>";
                if (brideDOB) output += "<tr><th>Bride DOB</th><td>" + brideDOB + "</td></tr>";
                if (brideIC) output += "<tr><th>Bride IC</th><td>" + brideIC + "</td></tr>";
                if (groomName) output += "<tr><th>Groom Name</th><td>" + groomName + "</td></tr>";
                if (groomDOB) output += "<tr><th>Groom DOB</th><td>" + groomDOB + "</td></tr>";
                if (groomIC) output += "<tr><th>Groom IC</th><td>" + groomIC + "</td></tr>";


            $('.show-cart').html(output);
            $('.show-cart').show();
        } else {
            clearCart();
        }

    }

    function clearCart() {
        $('#name').val("");
        $('#email_id').val("");
        $('#phonecode').val("+60");
        $('#mobile').val("");
        $('#ic_number').val("");
        $('#rasi_id').val("");
        $('#natchathra_id').val("");
        $('#address').val("");
        $('#description').val("");
        $('.show-cart').empty();
        $('.show-cart').hide();
    }
    displayCart();

    function clearGroomDetails() {
        // Bride details
        $('#brideName').val('');  // Clear bride name
        $('#brideDOB').val('');   // Clear bride date of birth
        $('#brideIC').val('');    // Clear bride IC number
        $('#brideAge').text('');  // Clear bride age display

        // Groom details
        $('#groomName').val('');  // Clear groom name
        $('#groomDOB').val('');   // Clear groom date of birth
        $('#groomIC').val('');    // Clear groom IC number
        $('#groomAge').text('');
    }

    $(document).on('click', '.clear-cart', function () {
        clearCart();
    });

    $('.clear-cart').click(function () {
        userDetail.clearUser();
        clearGroomDetails();
    });

    function formatDate(dateString) {
        var options = { day: '2-digit', month: '2-digit', year: 'numeric' };
        var date = new Date(dateString);
        return date.toLocaleDateString('en-GB', options);
    }

    $(document).ready(function() {
        $('#brideName').val(sessionStorage.getItem('brideName'));
        $('#brideDOB').val(sessionStorage.getItem('brideDOB'));
        $('#brideIC').val(sessionStorage.getItem('brideIC'));
        $('#groomName').val(sessionStorage.getItem('groomName'));
        $('#groomDOB').val(sessionStorage.getItem('groomDOB'));
        $('#groomIC').val(sessionStorage.getItem('groomIC'));
        displayCart(); // Initial display
    });

    function get_booking_ubhayam(date) {
        // ubhayam_date = $("#ubhayam_date").val(date);
        console.log('ubhayam_date');
        console.log(date);
        if (date != '') {
            $.ajax({
                url: "<?php echo base_url(); ?>/ubayam_online/get_booking_ubhayam",
                    type: "post",
                    data: { ubhayamdate: date },
                    success: function (data) {
                        console.log('ubhayam_date');
                        console.log(date);
                        $("#pay_for").html(data);
                    }
                });
        }
    }

    function payfor(pay_id) {
        var is_weekend = $('#day_check').val();
        $.ajax({
            url: "<?php echo base_url() ?>/hallbooking_online/get_payfor_collection",
            type: "POST",
            data: { id: pay_id },
            dataType: "json",
            success: function (data) {
                var discount_amount = $('#discount_amount').val();
                var total_amt = Number(data.amt);
                var sub_total = Number(data.amt);
                var max_discount = 0;
                if(discount_amount){
                    discount_amount = Number(discount_amount);
                    max_discount = total_amt - 1;
                    if(discount_amount > max_discount){
                        discount_amount = max_discount;
                        $('#discount_amount').val(discount_amount.toFixed(2))
                    }
                    total_amt = total_amt - discount_amount;
                }
                $("#total_amt").val(total_amt.toFixed(2));
                $("#sub_total").val(sub_total.toFixed(2));
                $("#pack_amount").val(Number(data.amt).toFixed(2));
                $("#packname").text(data.name);
                $("#total_amt").prop("max", total_amt.toFixed(2));
                $("#discount_amount").prop("max", max_discount.toFixed(2));
                $("#pay_for" + pay_id).prop("checked", true);
                $("input[name='pay_for']").removeClass("error-input");
                $(".payment li label").removeClass("error-input");

                var servicesList = $('#servicesList');
                servicesList.empty(); 
                if (Array.isArray(data.services) && data.services.length > 0) {
                    $("#serviceDetailsHeader").show();
                    data.services.forEach(function(service) {
                        var row = `<tr>
                                        <td>${service.name}</td>
                                        <td>${service.description}</td>
                                        <td>${service.quantity}</td>
                                    </tr>`;
                        servicesList.append(row);
                    });
                } else {
                    $("#serviceDetailsHeader").hide();
                    servicesList.append('<tr><td colspan="3">No services found</td></tr>');
                }

                //$("#display_deposit_amt").text(Number(data.deposit_amount).toFixed(2));
                $("#deposit_amt").val(Number(data.deposit_amount).toFixed(2));

                $("#advance_amount").val(Number(data.advance_amount).toFixed(2));
                
                if (data.addons.length > 0) {
                    var a_html = '<option value="">Select From</option>';
                    data.addons.forEach(function(value, key) {
                        a_html += '<option value="' + value.id + '">' + value.name + '</option>';
                    });
                } else {
                    var a_html = '<option value="">No Addons Found</option>';
                }
                $('#add_one_addon').html(a_html);  

                $("#package_table_addon tbody").empty();
                $("#pack_row_count_addon").val(0);
                sum_amount();
                if (data.terms.length > 0) {
                    var t_html = '';
                    data.terms.forEach(function(value, key) {
                        t_html += '<div class="form-group">' +
                                '<label class="custom-checkbox">' +
                                value +
                                '<input type="checkbox" class="term-checkbox" name="terms[]" value="' + value + '">' +
                                '<span class="checkmark"></span>' +
                                '</label>' +
                                '</div>';
                    });
                    $('#termsModal .modal-body').html(t_html);
                    // $('#termsModal').modal('show');
                }
            }
        });
    }

    $("#family_add").click(function () {
        var family_name = $("#family_name").val();
        var family_relationship = $("#family_relationship").val();
        var cnt_fmy = parseInt($("#family_row_count").val());
        if (family_name != '' && family_relationship != '') {
            var html = '<tr id="rmv_familyrow' + cnt_fmy + '">';
            html += '<td style="width: 33%;"><input type="text" style="border: none;" readonly name="familly[' + cnt_fmy + '][name]" value="' + family_name + '"></td>';
            html += '<td style="width: 33%;"><input type="text" style="border: none;" readonly name="familly[' + cnt_fmy + '][relationship]" value="' + family_relationship + '"></td>';
            html += '<td style="width: 33%;"><a class="btn btn-danger btn-rad" onclick="rmv_family(' + cnt_fmy + ')" style="width:auto;padding: 0px 3px !important; color:#fff;"><i class="fa fa-remove"></i></a></td>';
            html += '</tr>';
            $("#family_table").append(html);
            var ct_fmy = parseInt(cnt_fmy + 1);
            $("#family_row_count").val(ct_fmy);
            $("#family_name").val('');
            $("#family_relationship").val('');
        }
    });
    function rmv_family(id) {
        $("#rmv_familyrow" + id).remove();
    }
                // $(document).ready(function () {
                //     $("#rasi_id").change(function () {
                //         var rasi = $("#rasi_id").val();
                //         if (rasi != "") {
                //             $.ajax({
                //                 url: '<?php echo base_url(); ?>/ubayam_online/get_natchathram',
                //                 type: 'post',
                //                 data: { rasi_id: rasi },
                //                 dataType: 'json',
                //                 success: function (response) {
                //                     $('#natchathram_id').val(response.natchathra_id);

                //                     var str = response.natchathra_id;
                //                     console.log(str);
                //                     //return;
                //                     if (str != "") {
                //                         $("#natchathra_id").empty();

                //                         $('#natchathra_id').append('<option value="">Select Natchiram</option>');
                //                         $.each(str.split(','), function (key, value) {
                //                             //$('#natchathra_id').append('<option value="' + value + '">' + value + '</option>');
                //                             $.ajax({
                //                                 url: '<?php echo base_url(); ?>/ubayam_online/get_natchathram_name',
                //                                 type: 'post',
                //                                 data: { id: value },
                //                                 dataType: 'json',
                //                                 success: function (response) {
                //                                     $('#natchathra_id').append('<option value="' + response.id + '">' + response.name_eng + '</option>');
                //                                     //$('#natchathra_id').prop('selectedIndex',0);
                //                                     //$("#natchathra_id").selectpicker("refresh");
                //                                 }
                //                             });
                //                         });
                //                     }
                //                 }
                //             });
                //         }
                //     });
                // });

                // $("#submit").click(function () {
                //     var total_amt = parseFloat($("#total_amt").val());

                //     var pack_row_count = parseInt($("#pack_row_count").val());
                //     var pay_for = $('.ubayam_slot').filter(':checked').length;
                //     var name = $("#name").val();
                //     //var email_id = $("#email_id").val();
                //     var mobile = $("#mobile").val();
                //     //var rasi_id = $("#rasi_id").val();
                //     //var natchathra_id = $("#natchathra_id").val();
                //     var dt = $("#dt").val();
                //     if (pay_for === 0) {
                //         $("input[name='pay_for']").addClass("error-input");
                //         $(".payment li label").addClass("error-input");
                //         $('html, body').animate({
                //             scrollTop: $("input[name='pay_for']").focus().offset().top - 25
                //         }, 500);
                //     }
                //     else if (total_amt.length === 0 || total_amt == '') {
                //         $("#total_amt").addClass("error-input");
                //         $('html, body').animate({
                //             scrollTop: $("#total_amt").focus().offset().top - 25
                //         }, 500);
                //     }
                //     // else if (dt == "") {
                //     //     //alert("Please enter user details.");
                //     //     $("#modalMsg").text('Please enter user details.');
                //     //     $('#alertModal').modal();
                //     // }
                //     else if (name == "") {
                //         //alert("Please enter user details.");
                //         $("#modalMsg").text('Please enter user details.');
                //         $('#alertModal').modal();
                //     }
                //     // else if(email_id == "")
                //     // {
                //     //     //alert("Please enter user details.");
                //     //     $("#modalMsg").text('Please enter user details.');
                //     //     $('#alertModal').modal();
                //     // }
                //     else if (mobile == "") {
                //         //alert("Please enter user details.");
                //         $("#modalMsg").text('Please enter user details.');
                //         $('#alertModal').modal();
                //     }
                //     // else if(rasi_id == "")
                //     // {
                //     //     //alert("Please enter user details.");
                //     //     $("#modalMsg").text('Please enter user details.');
                //     //     $('#alertModal').modal();
                //     // }
                //     // else if(natchathra_id == "")
                //     // {
                //     //     //alert("Please enter user details.");
                //     //     $("#modalMsg").text('Please enter user details.');
                //     //     $('#alertModal').modal();
                //     // }
                //     else {
                //         $.ajax
                //             ({
                //                 type: "POST",
                //                 url: "<?php echo base_url(); ?>/ubayam_online/save",
                //                 data: $("form").serialize(),
                //                 beforeSend: function () {
                //                     $("#loader").show();
                //                 },
                //                 success: function (data) {
                //                     userDetail.clearUser();
                //                     //location.reload();
                //                     obj = jQuery.parseJSON(data);
                //                     if (obj.err != '') {
                //                         $('#alert-modal').modal('show', { backdrop: 'static' });
                //                         $("#spndeddelid").text(obj.err);
                //                     } else {
                //                         window.open("<?php echo base_url(); ?>/ubayam_online/payment_process/" + obj.id, "_blank", "width=680,height=500");
                //                         window.location.reload(true);
                //                     }
                //                 },
                //                 complete: function (data) {
                //                     // Hide image container
                //                     $("#loader").hide();
                //                 }
                //             });
                //     }
                // });

                
    $("#submit").click(function(){

        var date = $("#dt").val();
        var amount = $("#pay_amt").val();
        var paymentMode = $('input[name="payment_mode"]:checked').val();
        var paymentType = $('input[name="payment_type"]:checked').val();
        var advanceAmount = parseFloat($("#advance_amount").val()) || 0;
        var deposit_amt = parseFloat($("#deposit_amt").val()) || 0;

        if (paymentType === 'partial') {
            if (advanceAmount > 0 && amount < advanceAmount) {
            $("#paymentAlertMessage").text(`Please pay at least ${advanceAmount} as an advance amount.`);
            $('#paymentAlertModal').modal('show');
            return false;
            }
        
            if (deposit_amt > 0 && amount < deposit_amt) {
            $("#paymentAlertMessage").text(`Please pay at least ${deposit_amt} as a deposit amount.`);
            $('#paymentAlertModal').modal('show');
            return false;
            }

            var cnt = $("#paymentForm input[type='hidden']").length;  
            var hiddenInputs = `<input type="hidden" name="payment_details[${cnt}][paid_date]" value="${date}">
                                <input type="hidden" name="payment_details[${cnt}][amount]" value="${Number(amount).toFixed(2)}">
                                <input type="hidden" name="payment_details[${cnt}][payment_mode]" value="${paymentMode}">`;
            
            $("#form").append(hiddenInputs);
        }
        var brideName = document.getElementById('brideName').value.trim();
        var brideDOB = document.getElementById('brideDOB').value.trim();
        var brideIC = document.getElementById('brideIC').value.trim();
        var groomName = document.getElementById('groomName').value.trim();
        var groomDOB = document.getElementById('groomDOB').value.trim();
        var groomIC = document.getElementById('groomIC').value.trim();
        // if (brideName === '' || brideDOB === '' || brideIC === '' || groomName === '' || groomDOB === '' || groomIC === '') {
        //     event.preventDefault();
        //     alert('Please fill out all Bride\'s details before submitting.');
        //     return;
        // }

        var terms = "<?php $setting['enable_terms']; ?>"
        if (terms){
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

        var print_method = $("#print_method").val();
        var pack_row_count = parseInt($("#pack_row_count").val());
        var pay_for = $('.ubayam_slot').filter(':checked').length;
        var name = $("#name").val();
        var email_id = $("#email_id").val();
        var mobile = $("#mobile").val();
        //var rasi_id = $("#rasi_id").val();
        //var natchathra_id = $("#natchathra_id").val();
        var dt = $("#dt").val();
        var total_amt       = parseFloat($("#total_amt").val());
        var check_dep       = parseFloat((total_amt / 100) * 30).toFixed(2);
        console.log(check_dep);
                
        if (name == "") {
            //alert("Please enter user details.");
            $("#modalMsg").text('Please enter user details.');
            $('#alertModal').modal();
        } else if (mobile == "") {
            //alert("Please enter user details.");
            $("#modalMsg").text('Please enter user details.');
            $('#alertModal').modal();
        } else {
               
            $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>/ajax/save_booking",
                data: $("form").serialize(),
                beforeSend: function() {    
                    $("#loader").show();
                },
                success: function(data) {
                    console.log(data);
                    if (typeof data === 'string') {
                        try {
                            data = JSON.parse(data);
                        } catch (e) {
                            console.error("Failed to parse JSON response: ", e);
                            $('#alert-modal').modal('show', { backdrop: 'static' });
                            $("#spndeddelid").text("An error occurred while processing the response.");
                            $("#spndeddelid").css("color", "red");
                            return;
                        }
                    }

                    if (data.success) {
                        if (data.data.status) {
                            $('#alert-modal-print').modal('show', { backdrop: 'static', keyboard: false });
                            $("#spndeddelid1").text(data.data.message);
                            $("#spndeddelid1").css("color", "green");
                            

                            var bookingId = data.data.booking_id;
                            $('#print-imin').click(function () {
                                window.open("<?php echo base_url(); ?>/hallbooking_online/print_page_hall_imin/" + bookingId, '_blank');
                            });

                            $('#print-a4').click(function () {
                                window.open("<?php echo base_url(); ?>/hallbooking_online/print_page_hall/" + bookingId, '_blank');
                            });

                            $('#print-a5').click(function () {
                                window.open("<?php echo base_url(); ?>/hallbooking_online/print_page_hall_a5/" + bookingId, '_blank');
                            });

                            $('#okay').click(function () {
                                $('#alert-modal2').modal('show', { backdrop: 'static' });
                                $("#spndeddelid2").text("Thanks!");
                                $("#spndeddelid2").css("color", "green");
                                setTimeout(function () {
                                    if (typeof userDetail !== 'undefined') {
                                        userDetail.clearUser();
                                    }
                                    window.location.reload();
                                }, 2000);
                            });
                        } else {
                            $('#alert-modal').modal('show', { backdrop: 'static' });
                            $("#spndeddelid").text(data.data.message);
                            $("#spndeddelid").css("color", "red");
                        }
                        ClearBrideGroomDetails();
                    } else {
                        $('#alert-modal').modal('show', { backdrop: 'static' });
                        $("#spndeddelid").text("An error occurred. Please try again later");
                        $("#spndeddelid").css("color", "red");
                    }
                },
                error: function () {
                    $('#alert-modal').modal('show', { backdrop: 'static' });
                    $("#spndeddelid").text("An error occurred. Please try again later");
                    $("#spndeddelid").css("color", "red");
                },
                complete: function () {
                    $("#loader").hide();
                }
            });

        }
    });   

    function ClearBrideGroomDetails() {
        sessionStorage.removeItem('brideName');
        sessionStorage.removeItem('brideDOB');
        sessionStorage.removeItem('brideIC');
        sessionStorage.removeItem('groomName');
        sessionStorage.removeItem('groomDOB');
        sessionStorage.removeItem('groomIC');
    }


    window.onbeforeunload = () => {
        userDetail.clearUser();  // Function to clear user details from session
    };

    function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test(email)) {
            return 0;
        } else {
            return 1;
        }
    }
    $("#cancel-button").click(function () {
        $("#name").removeClass("error-input");
        $("#address").removeClass("error-input");
        $("#mobile").removeClass("error-input");
        $("#ic_number").removeClass("error-input");
        $("#email_id").removeClass("error-input");
        $("#total_amt").removeClass("error-input");
        $("input[name='pay_for']").removeClass("error-input");
        $(".payment li label").removeClass("error-input");
    });
    $('#name').keyup(function () {
        $("#name").removeClass("error-input");
    });
    $('#address').keyup(function () {
        $("#address").removeClass("error-input");
    });
    $('#mobile').keyup(function () {
        $("#mobile").removeClass("error-input");
    });
    $('#ic_number').keyup(function () {
        $("#ic_number").removeClass("error-input");
    });
    $('#email_id').keyup(function () {
        $("#email_id").removeClass("error-input");
    });
    $('#total_amt').keyup(function () {
        $("#total_amt").removeClass("error-input");
    });
</script>


<script>
    $(document).ready(function() {
        $('#venueModal').modal('show');
    });

    $('#venueSelect').change(function() {
        var selectedVenueId = $(this).val(); // Get the selected venue ID
        var selectedVenueName = $(this).find("option:selected").text(); // Get the selected venue name

        // Update the hidden input and the header
        $('#venue').val(selectedVenueId);
        $('h4#venue-name').text(selectedVenueName + " Booking"); // Make sure to have an h4 with id="venue-name"
    });

    // $(document).ready(function () {
    //     $("#clear").on("click", function (e) {
    //         // e.preventDefault(); // Prevent default behavior if the button is a link
    //         // location.reload(); // Reload the page
    //         window.location.replace("<?php echo base_url();?>/hallbooking_online/hall");
    //     });
    // });
</script>

<script>
// MAIN JS FILE START
(function($) {

	"use strict";
    $(document).ready(function(){
        var date = new Date();
        var today = date.getDate();
        // Set click handlers for DOM elements
        $(".right-button").click({date: date}, next_year);
        $(".left-button").click({date: date}, prev_year);
        $(".month").click({date: date}, month_click);
        $("#add-button").click({date: date}, new_event);
        $("#clear-all").click(function () {
            $("#add-button").trigger("click"); // Simulates a click on the #add-button
        });
        // Set current month as active
        $(".months-row").children().eq(date.getMonth()).addClass("active-month");
        //init_calendar(date);
        var events = check_events(today, date.getMonth()+1, date.getFullYear());
        show_events(events, months[date.getMonth()], today);
    });

function init_calendar(date, venueId) {
    $(".tbody").empty();
    $(".events-container").empty();
    var venueId = $('#venueSelect').val();
    var calendar_days = $(".tbody");
    var month = date.getMonth();
    var year = date.getFullYear();
    var day_count = days_in_month(month, year);
    var row = $("<tr class='table-row'></tr>");
    // Set date to 1 to find the first day of the month
    date.setDate(1);
    var first_day = date.getDay();
    // 35+firstDay is the number of date elements to be added to the dates table
    // 35 is from (7 days in a week) * (up to 5 rows of dates in a month)
    for(var i=0; i<35+first_day; i++) {
        // Since some of the elements will be blank, 
        // need to calculate actual date from index
        var day = i-first_day+1;
        // If it is a sunday, make a new row
        if(i%7===0) {
            calendar_days.append(row);
            row = $("<tr class='table-row'></tr>");
        }
        // if current index isn't a day in this month, make it blank
        if(i < first_day || day > day_count) {
            var curr_date = $("<td class='table-date nil'>"+"</td>");
            row.append(curr_date);
        } else {
            var curr_date = $("<td class='table-date'>"+day+"</td>");
            // Assuming check_events can take venueId as a parameter to filter events
            var events = check_events(day, month+1, year, venueId);
            console.log('events by me:', events);
            // If this date has any events, style it with .event-date
            if(events.length!==0) {
                curr_date.addClass("event-date");
            }
            // Set onClick handler for clicking a date
            curr_date.click({events: events, month: months[month], day:day, year: year}, date_click);
            row.append(curr_date);
        }
    }
    calendar_days.append(row);
    $(".year").text(year);
}

function loadCalendarWithVenue() {
    var selectedVenue = $('#venueSelect').val();
    init_calendar(new Date(), selectedVenue);
}

document.addEventListener('DOMContentLoaded', function() {
    var loadButton = document.getElementById('loadButton'); // Ensure your button has this ID
    if (loadButton) {
        loadButton.addEventListener('click', function() {
            loadCalendarWithVenue();
        });
    }
});

// Get the number of days in a given month/year
function days_in_month(month, year) {
    var monthStart = new Date(year, month, 1);
    var monthEnd = new Date(year, month + 1, 1);
    return (monthEnd - monthStart) / (1000 * 60 * 60 * 24);    
}

// Event handler for when a date is clicked
function date_click(event) {
	var today = new Date();
    var cur_day = new Date(event.data.year + '-' + event.data.month + '-' + event.data.day);

    var threeDaysAfterToday = new Date(today);
    //threeDaysAfterToday.setDate(today.getDate() + 30); // Add 3 days to today
    threeDaysAfterToday.setDate(today.getDate()); // Add 3 days to today

    if (cur_day <= threeDaysAfterToday) {
        $('#add-button').prop('disabled', true);
            // alert("You can book 3 days after today's date");
    } else {
        $('#add-button').prop('disabled', false);
    }
    console.log(cur_day);
    // console.log(threeDaysAfterCurDay);

    $(".events-container").show(250);
    $("#dialog").hide(250);
    $(".active-date").removeClass("active-date");
    $(this).addClass("active-date");
	console.log(event);
    show_events(event.data.events, event.data.month, event.data.day);
};

// Event handler for when a month is clicked
function month_click(event) {
    $(".events-container").show(250);
    $("#dialog").hide(250);
    var date = event.data.date;
    $(".active-month").removeClass("active-month");
    $(this).addClass("active-month");
    var new_month = $(".month").index(this);
    date.setMonth(new_month);
    init_calendar(date);
}

// Event handler for when the year right-button is clicked
function next_year(event) {
    $("#dialog").hide(250);
    var date = event.data.date;
    var new_year = date.getFullYear()+1;
    $("year").html(new_year);
    date.setFullYear(new_year);
    init_calendar(date);
}

// Event handler for when the year left-button is clicked
function prev_year(event) {
    $("#dialog").hide(250);
    var date = event.data.date;
    var new_year = date.getFullYear()-1;
    $("year").html(new_year);
    date.setFullYear(new_year);
    init_calendar(date);
}

// Event handler for clicking the new event button
function new_event(event) {
    // if a date isn't selected then do nothing
    if($(".active-date").length===0)
        return;
    // remove red error input on click
    $("input").click(function(){
        $(this).removeClass("error-input");
    });
    $(function() {
        var filterList = {
        init: function() {
        // MixItUp plugin
        // http://mixitup.io
        $('#portfoliolist').mixItUp({
        selectors: {
            target: '.portfolio',
            filter: '.filter'
        },
        load: {
        filter: '.<?php echo $default; ?>'
                    }
                });
            }
        };
        // Run the show!
        filterList.init();
    });
	console.log('event.data.date');
	console.log(event.data);
	var curdate = event.data.date;
	var curday = parseInt($(".active-date").html());
	var event_date = curdate.getFullYear() + "-" + (curdate.getMonth() + 1) + "-" + curday;
    console.log('event date:', event_date);
	/* $('#dt').val(event_date); */
	$("#ubhayam_date").val(event_date);
	$("#booking_date").val(event_date);

    var actualDate = new Date(event_date);
    var dayOfWeek = actualDate.getDay(); // Now this is correct as actualDate is a Date object
    console.log('day of week', dayOfWeek);
    if (dayOfWeek === 0 || dayOfWeek === 6) {
        $("#day_check").val(1); // Weekend
    } else {
        $("#day_check").val(0); // Weekday
    }

	get_booking_ubhayam(event_date);
    loadbookingslots(event_date);
    // empty inputs and hide events
    $("#dialog input[type=text]").val('');
    $("#dialog input[type=number]").val('');
    $(".events-container").hide(250);
    $("#dialog").show(250);
    // Event handler for cancel button
    $("#cancel-button").click(function() {
        $("#name").removeClass("error-input");
        $("#count").removeClass("error-input");
        $("#dialog").hide(250);
        $(".events-container").show(250);
    });
    // Event handler for ok button
    $("#ok-button").unbind().click({date: event.data.date}, function() {
        var date = event.data.date;
        var name = $("#name").val().trim();
        var count = parseInt($("#count").val().trim());
        var day = parseInt($(".active-date").html());
        // Basic form validation
        if(name.length === 0) {
            $("#name").addClass("error-input");
        }
        else if(isNaN(count)) {
            $("#count").addClass("error-input");
        }
        else {
            $("#dialog").hide(250);
            console.log("new event");
            new_event_json(name, count, date, day);
            date.setDate(day);
            init_calendar(date);
        }
    });
}

function loadbookingslots(date)
{
    $.ajax({
        type:"POST",
        url: "<?php echo base_url(); ?>/hallbooking_online/loadbookingslots",
        data: {bookeddate:date},
        success:function(data)
        {
			console.log(data);
			var result = JSON.parse(data);
			if(result.status){
				if(result.html != ''){
					$("#booking_slot").html(result.html);
				} else {
					alert("Selected date is not available, Kindly select various date!");
					window.location.replace("<?php echo base_url();?>/hallbooking_online/hall");
				}
			}else{
				alert(result.error_msg);
				window.location.replace("<?php echo base_url();?>/hallbooking_online/hall");
			}
        },
		error: function(err){
			console.log('err');
			console.log(err);
		}
		
    });
}
	
// Adds a json event to event_data
function new_event_json(name, count, date, day) {
    var event = {
        "occasion": name,
        "invited_count": count,
        "year": date.getFullYear(),
        "month": date.getMonth()+1,
        "day": day
    };
    event_data["events"].push(event);
}

// Display all events of the selected date in card views
function show_events(events, month, day) {
    // Clear the dates container
    $(".events-container").empty();
    $(".events-container").show(250);
    console.log(event_data["events"]);
    // If there are no events for this date, notify the user
    if(events.length===0) {
        var event_card = $("<div class='event-card'></div>");
        var event_name = $("<div class='event-name'>There are no events planned for "+month+" "+day+".</div>");
        $(event_card).css({ "border-left": "10px solid #FF1744" });
        $(event_card).append(event_name);
        $(".events-container").append(event_card);
    }
    else {
        // Go through and add each event as a card to the events container
        for(var i=0; i<events.length; i++) {
            var event_card = $("<div class='event-card'></div>");
            var event_name = $("<div class='event-name'>Event Detail: "+events[i]["event_name"]+":</div>");
            var event_count = $("<div class='cal_head'>Booked By: "+events[i]["name"]+"</div>");
            var slot = $("<div class='cal_head'>Slot Time: " + events[i]["slot"] + "</div>");
            $(event_card).append(event_name).append(event_count).append(slot);
            $(".events-container").append(event_card);
        }
    }
}

function check_events(day, month, year, venueId) {
    var events = [];
    for(var i = 0; i < event_data["events"].length; i++) {
        var event = event_data["events"][i];
        if(event["day"] === day &&
            event["month"] === month &&
            event["year"] === year &&
            event["venue_id"] === venueId) {
                events.push(event);
            }
    }
    return events;
}

// Given data for events in JSON format
var event_data = <?php echo $ubayams; ?>;

const months = [ 
    "January", 
    "February", 
    "March", 
    "April", 
    "May", 
    "June", 
    "July", 
    "August", 
    "September", 
    "October", 
    "November", 
    "December" 
];

})(jQuery);


    function increaseValue(cnt) {
        adjustQuantity(cnt, 1);
    }

    function decreaseValue(cnt) {
        adjustQuantity(cnt, -1);
    }

    function adjustQuantity(cnt, adjustment) {
        var quantity = $("#quantity" + cnt);
        var currentVal = parseInt(quantity.val());
        var maxQuantity = parseInt(quantity.attr('max'));
        var minQuantity = 1;  // Set minQuantity as 1 (can be changed if needed)

        if (!isNaN(currentVal)) {
            var newQuantity = currentVal + adjustment;

            if (newQuantity > maxQuantity) {
                newQuantity = maxQuantity;
            } else if (newQuantity < minQuantity) {
                newQuantity = minQuantity;
            }

            quantity.val(newQuantity);
            updateAmount(cnt);
        }
    }

    function qtykeyup(cnt) {
        var quantity = $("#quantity" + cnt);
        var currentVal = parseInt(quantity.val());
        var maxQuantity = parseInt(quantity.attr('max'));
        var minQuantity = 1;  // Set minQuantity as 1

        if (currentVal > maxQuantity) {
            quantity.val(maxQuantity);
        } else if (currentVal < minQuantity) {
            quantity.val(minQuantity);
        }

        updateAmount(cnt);
    }

    function editableAmountKeyUp(cnt) {
        updateAmount(cnt, true);
    }

    function updateAmount(cnt, fromAmountField = false) {
        var quantity = parseInt($("#quantity" + cnt).val());
        var unitPrice = parseFloat($("#editable_amt_addon_" + cnt).val()); // Always use the current value from the editable amount input
        var totalPrice = quantity * unitPrice;
        $("#package_amt_addon_" + cnt).val(Number(totalPrice).toFixed(2));
        
        // Only update the editable amount field if the change was not triggered by it
        if (!fromAmountField) {
            $("#editable_amt_addon_" + cnt).val(Number(unitPrice).toFixed(2));
        }
        sum_amount();
    }

    function rmv_pack_addon(id) {
        $("#rmv_packrow_addon" + id).remove();
        sum_amount();
    }

    function serviceamount() {
        sum_amount();
    }

    function sum_amount() {
        var total = 0;
        $(".package_amt_addon").each(function() {
            var amount = parseFloat($(this).val());
            if (!isNaN(amount)) {
                total += amount;
            }
        });

        $(".package_amt_prasadam").each(function () {
            var amount = parseFloat($(this).val());
            if (!isNaN(amount)) {
                total += amount;
            }
        }); 

        $(".package_amt_annathanam").each(function () {
            var amount = parseFloat($(this).val());
            if (!isNaN(amount)) {
                total += amount;
            }
        }); 

        $(".package_amt_anna_addon").each(function () {
            var amount = parseFloat($(this).val());
            if (!isNaN(amount)) {
                total += amount;
            }
        }); 

        $(".extra_amt").each(function () {
            var amount = parseFloat($(this).val());
            if (!isNaN(amount)) {
                total += amount;
            }
        });
        
        var deposit_amt = parseFloat($("#deposit_amt").val()) || 0;
        var pack_amount = parseFloat($("#pack_amount").val());
        var total_val = total + pack_amount + deposit_amt;

        $("#sub_total").val(Number(total_val).toFixed(2));
        
        var discount_amount = $('#discount_amount').val();
        var max_discount = 0;
        if(discount_amount){
            discount_amount = Number(discount_amount);
            max_discount = total_val - 1;
            if(max_discount < 0) max_discount = 0;
            if(discount_amount > max_discount){
                discount_amount = max_discount;
                $('#discount_amount').val(discount_amount.toFixed(2))
            }
            total_val = total_val - discount_amount;
        }
        
        $("#total_amt").val(Number(total_val).toFixed(2));
        console.log("Total Amount: " + total);
    } 

    $(".clear-cart").click(function () {
        // Hide the entire package name table
        $("#package_name").hide();
    });

        function get_service_name_addon(id, cmlp) {
            console.log("get_service_name_addon called with id: " + id + " and cmlp: " + cmlp);
            if (id != '') {
                $.ajax({
                    url: "<?php echo base_url();?>/hallbooking_online/get_service_name_addon",
                    type: "post",
                    data: {id: id},
                    dataType: "json",
                    success: function(data){
                        $("#service_name_addon_" + cmlp).val(data['name']);
                        $("#service_description_addon_" + cmlp).val(data['description']);
                    }
                });
            }
        }
        $('#discount_amount').on('blur change', function(){
			sum_amount();
		});
        $("#add_one_addon").change(function(){
            var id = $("#add_one_addon").val();
            if(id != ''){
                $.ajax({
                    url: "<?php echo base_url();?>/hallbooking_online/getpack_amt_addon",
                    type: "post",
                    data: {id: id},
                    dataType: "json",
                    success: function(data){
                        console.log(data)
                        $("#get_pack_amt_addon").val(Number(data['amt']).toFixed(2));
                        $("#pack_name_addon").val(data['name']);
                    }
                });
            }else{
                $("#get_pack_amt_addon").val(0);
            }
            sum_amount();
        });
        $("#pack_add_addon").click(function(){
            var id = $("#add_one_addon option:selected").val();
            var cnt = parseInt($("#pack_row_count_addon").val());
            var amt = $("#get_pack_amt_addon").val();
            var package_id = $('.package_id').val();
            console.log('id:', id);
            console.log('package_id:', package_id);

            if (id != '' && parseFloat(amt) > 0) {
                var status_check = 0;
                var rowId;

                $(".package_category_addon").each(function () {
                    var existingId = parseInt($(this).val());
                    if (existingId === parseInt(id)) {
                        status_check++;
                        rowId = $(this).closest('tr').attr('id').replace('rmv_packrow_addon', '');
                    }
                });

                if (status_check > 0) {
                    alert('Addon already selected');
                    // var quantity = $("#quantity" + rowId);
                    // var currentVal = parseInt(quantity.val());
                    // quantity.val(currentVal + 1);
                    // updateAmount(rowId);
                    // sum_amount();
                    $("#get_pack_amt_addon").val('');
                    $('#add_one_addon').prop('selectedIndex', 0);
                    // $("#add_one_addon").selectpicker("refresh");
                } else {
                    $.ajax({
                        url: "<?php echo base_url();?>/hallbooking_online/get_service_list_addon",
                        type: "post",
                        data: {id: id, package_id: package_id},
                        success: function(response) {
                            response = JSON.parse(response);
                            console.log('addon response data:', response);
                            if (response.length > 0) {
                                $.each(response, function(key, value) {
                                    var countid = value.id;
                                    var serviceid = value.id;
                                    var quantity = value.quantity;
                                    console.log('Addon quantity:', quantity);
                                    var serviceamount = value.amount;
                                    get_service_name_addon(serviceid, countid);
                                    var html = '<tr id="rmv_packrow_addon' + countid + '">';
                                    html += '<td style="width: 20%;"><input type="hidden" readonly name="add_on[' + countid + '][id]" value="' + serviceid + '"><input type="text" style="border: none;width: 100%;" readonly id="service_name_addon_' + countid + '" data-amount="' + serviceamount + '"></td>';
                                    html += '<td style="width: 45%;"><input type="text" style="border: none;width: 100%;" id="service_description_addon_' + countid + '"></td>';
                                    html += '<td><div class="itemcountrr"><div class="value-button" id="decrease" onclick="decreaseValue(' + countid + ')" value="Decrease Value" style="font-weight: bold;font-size: 16px; cursor: pointer;">-</div><input type="number" name="add_on[' + countid + '][quantity]" id="quantity' + countid + '" value="1" pattern="[0-9]*" class="qty_amt" style="text-align: center;border: none;border-top: 1px solid #ddd;border-bottom: 1px solid #ddd;margin: 0px;width: 35px;height: 25px;" min="1" max="' + quantity + '" onkeyup="qtykeyup(' + countid + ')" /><div class="value-button" id="increase" onclick="increaseValue(' + countid + ')" value="Increase Value" style="font-weight: bold;font-size: 16px; cursor: pointer;">+</div></div></td>';
                                    html += '<td style="width: 20%;"><input type="text" style="border: none;width: 100%;" class="editable_amt_addon" id="editable_amt_addon_' + countid + '" name="add_on[' + countid + '][amount]" value="' + Number(serviceamount).toFixed(2) + '" onkeyup="editableAmountKeyUp(' + countid + ')"></td>';
                                    html += '<td style="width: 25%;"><input type="text" style="border: none;width: 100%;" class="package_amt_addon" id="package_amt_addon_' + countid + '" name="add_on[' + countid + '][total_amount]" value="' + (Number(serviceamount).toFixed(2) * 1).toFixed(2) + '"></td>';
                                    html += '<td style="width: 10%;"><a class="btn btn-danger btn-rad" onclick="rmv_pack_addon(' + countid + ')" style="width:auto;padding: 0px 3px !important;"><i class="material-icons">X</i></a><input type="hidden" class="package_category_addon" value=' + id + '></td>';
                                    html += '</tr>';
                                    $("#package_table_addon").append(html);

                                });
                                sum_amount();
                            }
                        }
                    });
                    $("#get_pack_amt_addon").val('');
                    $('#add_one_addon').prop('selectedIndex', 0);
                    // $("#add_one_addon").selectpicker("refresh");
                }
            }
        });
        $(document).on('click', '.booking_slot', function(){
            var booking_slot = this.value;
            var booking_date = $('#ubhayam_date').val();
            var venue_id = $('#venueSelect').val();
            var is_weekend = $('#day_check').val();
            console.log(booking_slot);
            $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>/ajax/get_hallpackages_list",
                data: {slot_id: booking_slot, booking_date: booking_date, package_type: 1, venue_id: venue_id},
                dataType: 'json',
                beforeSend: function() {    
                    // $("#loader").show();
                },
                success: function(data) {
                    console.log('packages from back:', data);
                    if (data.success) {
                        // Generate HTML for packages
                        var packageHtml = '';
                        if(data.data.packages.length > 0){
                            data.data.packages.forEach(function(value, key){
                                packageHtml += '<div class="col-xl-3 col-sm-6 col-lg-3 col-md-4 portfolio" data-cat="">';
                                packageHtml += '<input type="radio" class="ubayam_slot" name="pay_for" value="' + value.id + '" id="pay_for' + value.id + '" onclick="createHiddenInput(' + value.id + ')"/>';
                                packageHtml += '<label class="card" style="margin: 10px;" for="pay_for' + value.id + '" onclick="payfor(' + value.id + ')">';
                                packageHtml += '<img class="img-fluid prod_img" src="<?php echo base_url(); ?>/uploads/package/' + value.image + '">';
                                packageHtml += '<div class="d-flex justify-content-between align-items-center mb-2 mt-2" style="flex-direction: column;">';
                                packageHtml += '<p class="mb-0 text-muted arch" id="pay_name' + value.id + '">' + value.name + '</p>';
                                packageHtml += '</div></label></div>';
                            });
                        } else {
                            packageHtml = '<p>No Packages Found</p>';
                            alert("This slot was not available for booking. Please choose another slot.");
                        }
                        $('#add_one').html(packageHtml);
                        // $("#add_one").selectpicker("refresh");

                        // Generate HTML for addons
                        var addonHtml = '<option value="">Select From</option>';
                        if(data.data.addons.length > 0){
                            data.data.addons.forEach(function(value, key){
                                addonHtml += '<option value="' + value.id + '">' + value.name + '</option>';
                            });
                        } else {
                            addonHtml += '<option value="">No Addons Found</option>';
                        }
                        $('#add_one_addon').html(addonHtml);
                        // $("#add_one_addon").selectpicker("refresh");
                        
                        $("#pack_amount").val(0);
                        $("#package_table_addon tbody").empty();
                        $("#pack_row_count_addon").val(0);
                        sum_amount();
                    }
                },
                error: function() {
                    // $('#alert-modal').modal('show', { backdrop: 'static' });
                    // $("#spndeddelid").text("An error occurred. Please try again later");
                    // $("#spndeddelid").css("color", "red");
                    // var packageHtml = '<p>No Packages Found</p>';
                    // $('#add_one').html(packageHtml);
                    // $("#add_one").selectpicker("refresh");
                    // var addonHtml = '<option value="">No Addons Found</option>';
                    // $('#add_one_addon').html(addonHtml);
                    // $("#add_one_addon").selectpicker("refresh");
                },
                complete: function() {
                    // $("#loader").hide();
                }
            });
        });

function createHiddenInput(packageId) {
    // Remove any existing hidden input
    $('input[name^="packages["]').remove();

    // Create a new hidden input with the selected package ID
    var hiddenInput = '<input type="hidden" name="packages[' + packageId + '][id]" class="package_id" value="' + packageId + '">';
    $('form').append(hiddenInput);
}
 $('#termsLabel').on('click', function(event) {
    event.preventDefault();
    var name = $("#name").val();
    var today = new Date();
    var formattedDate = today.getFullYear() + '-' 
                    + ('0' + (today.getMonth() + 1)).slice(-2) + '-' 
                    + ('0' + today.getDate()).slice(-2);
    var ic_number = $("#ic_number").val();
    var address = $("#address").val();
    var booking_date = $("#ubhayam_date").val();
    var booking_slot = $('input[name="booking_slot[]"]:checked').closest('td').text().trim();
    var package_id = $('.package_id').val();
    var pay_id = $("input[name='pay_for']:checked").val();
    if(name && ic_number){
        $.ajax({
        url: "<?php echo base_url() ?>/hallbooking_online/get_payfor_collection",
        type: "POST",
        data: { id: pay_id },
        dataType: "json",
        success: function (data) {
           
            if (data.terms.length > 0) {
                var t_html = '';
                data.terms.forEach(function(value, key) {
                    var replacedValue = value.replace(/\[person_name\]/g, name).replace(/\[ic_number\]/g, ic_number).replace(/\[Address\]/g, address).replace(/\[booking_date\]/g, booking_date).replace(/\[booking_slot\]/g, booking_slot).replace(/\[entry_date\]/g, formattedDate);
                    t_html += '<div class="form-group">' +
                            '<label class="custom-checkbox">' +
                            replacedValue +
                            '<input type="checkbox" class="term-checkbox" name="terms[]" value="' + replacedValue + '">' +
                            '<span class="checkmark"></span>' +
                            '</label>' +
                            '</div>';
                });
                $('#termsModal .modal-body').html(t_html);
                $('#termsModal').modal('show');
            }
        }
    });
    } else{
        alert("Kindly fill all details");
    }
    });
    </script>

<script>
    $(document).on('keypress', function (e) {
        if (e.which === 13) { // Check for Enter key
            e.preventDefault(); // Prevent the default behavior (form submission)
        }
    });
    $(document).ready(function(){    
        console.log("Script loaded successfully");
        
    });
    $('#brides_detail').on('click', function (event) {
            event.preventDefault();
            $('#infoModal').modal('show');

        });
    // Setup the calendar with the current date
// $(document).ready(function(){
//     var date = new Date();
//     var today = date.getDate();
//     // Set click handlers for DOM elements
//     $(".right-button").click({date: date}, next_year);
//     $(".left-button").click({date: date}, prev_year);
//     $(".month").click({date: date}, month_click);
//     $("#add-button").click({date: date}, new_event);
//     // Set current month as active
//     $(".months-row").children().eq(date.getMonth()).addClass("active-month");
//     init_calendar(date);
//     var events = check_events(today, date.getMonth()+1, date.getFullYear());
//     show_events(events, months[date.getMonth()], today);
// });

// // Initialize the calendar by appending the HTML dates
// function init_calendar(date) {
//     $(".tbody").empty();
//     $(".events-container").empty();
//     var calendar_days = $(".tbody");
//     var month = date.getMonth();
//     var year = date.getFullYear();
//     var day_count = days_in_month(month, year);
//     var row = $("<tr class='table-row'></tr>");
//     var today = date.getDate();
//     // Set date to 1 to find the first day of the month
//     date.setDate(1);
// 	get_booking_ubhayam(today);
//     var first_day = date.getDay();
//     // 35+firstDay is the number of date elements to be added to the dates table
//     // 35 is from (7 days in a week) * (up to 5 rows of dates in a month)
//     for(var i=0; i<35+first_day; i++) {
//         // Since some of the elements will be blank, 
//         // need to calculate actual date from index
//         var day = i-first_day+1;
//         // If it is a sunday, make a new row
//         if(i%7===0) {
//             calendar_days.append(row);
//             row = $("<tr class='table-row'></tr>");
//         }
//         // if current index isn't a day in this month, make it blank
//         if(i < first_day || day > day_count) {
//             var curr_date = $("<td class='table-date nil'>"+"</td>");
//             row.append(curr_date);
//         }   
//         else {
//             var curr_date = $("<td class='table-date'>"+day+"</td>");
//             var events = check_events(day, month+1, year);
//             if(today===day && $(".active-date").length===0) {
//                 curr_date.addClass("active-date");
//                 show_events(events, months[month], day);
//             }
//             // If this date has any events, style it with .event-date
//             if(events.length!==0) {
//                 curr_date.addClass("event-date");
//             }
//             // Set onClick handler for clicking a date
//             curr_date.click({events: events, month: months[month], day:day, year: year}, date_click);
//             row.append(curr_date);
//         }
//     }
//     // Append the last row and set the current year
//     calendar_days.append(row);
//     $(".year").text(year);
// }

// // Get the number of days in a given month/year
// function days_in_month(month, year) {
//     var monthStart = new Date(year, month, 1);
//     var monthEnd = new Date(year, month + 1, 1);
//     return (monthEnd - monthStart) / (1000 * 60 * 60 * 24);    
// }

// // Event handler for when a date is clicked
// function date_click(event) {
// 	var today = new Date();
//     var cur_day = new Date(event.data.year + '-' + event.data.month + '-' + event.data.day);

//     var threeDaysAfterToday = new Date(today);
//     threeDaysAfterToday.setDate(today.getDate() + 30); // Add 3 days to today

//     if (cur_day <= threeDaysAfterToday) {
//         $('#add-button').prop('disabled', true);
//         // alert("You can book 3 days after today's date");
//     } else {
//         $('#add-button').prop('disabled', false);
//     }
//     console.log(cur_day);
//     // console.log(threeDaysAfterCurDay);

//     $(".events-container").show(250);
//     $("#dialog").hide(250);
//     $(".active-date").removeClass("active-date");
//     $(this).addClass("active-date");
// 	console.log(event);
//     show_events(event.data.events, event.data.month, event.data.day);
// };

// // Event handler for when a month is clicked
// function month_click(event) {
//     $(".events-container").show(250);
//     $("#dialog").hide(250);
//     var date = event.data.date;
//     $(".active-month").removeClass("active-month");
//     $(this).addClass("active-month");
//     var new_month = $(".month").index(this);
//     date.setMonth(new_month);
//     init_calendar(date);
// }

// // Event handler for when the year right-button is clicked
// function next_year(event) {
//     $("#dialog").hide(250);
//     var date = event.data.date;
//     var new_year = date.getFullYear()+1;
//     $("year").html(new_year);
//     date.setFullYear(new_year);
//     init_calendar(date);
// }

// // Event handler for when the year left-button is clicked
// function prev_year(event) {
//     $("#dialog").hide(250);
//     var date = event.data.date;
//     var new_year = date.getFullYear()-1;
//     $("year").html(new_year);
//     date.setFullYear(new_year);
//     init_calendar(date);
// }

// // Event handler for clicking the new event button
// function new_event(event) {
//     // if a date isn't selected then do nothing
//     if($(".active-date").length===0)
//         return;
//     // remove red error input on click
//     $("input").click(function(){
//         $(this).removeClass("error-input");
//     });
//     $(function() {
// var filterList = {
//   init: function() {
//     // MixItUp plugin
//     // http://mixitup.io
//     $('#portfoliolist').mixItUp({
//       selectors: {
//         target: '.portfolio',
//         filter: '.filter'
//       },
//       load: {
//         filter: '.<?php echo $default; ?>'
//                     }
//                 });

//             }

//         };
//         // Run the show!
//         filterList.init();
//     });
// 	console.log('event.data.date');
// 	console.log(event.data);
// 	var curdate = event.data.date;
// 	var curday = parseInt($(".active-date").html());
// 	var event_date = curdate.getFullYear() + "-" + (curdate.getMonth() + 1) + "-" + curday;
// 	/* $('#dt').val(event_date); */
// 	$("#ubhayam_date").val(event_date);
// 	$("#booking_date").val(event_date);
// 	get_booking_ubhayam(event_date);
//     loadbookingslots(event_date);
//     // empty inputs and hide events
//     $("#dialog input[type=text]").val('');
//     $("#dialog input[type=number]").val('');
//     $(".events-container").hide(250);
//     $("#dialog").show(250);
//     // Event handler for cancel button
//     $("#cancel-button").click(function() {
//         $("#name").removeClass("error-input");
//         $("#count").removeClass("error-input");
//         $("#dialog").hide(250);
//         $(".events-container").show(250);
//     });
//     // Event handler for ok button
//     $("#ok-button").unbind().click({date: event.data.date}, function() {
//         var date = event.data.date;
//         var name = $("#name").val().trim();
//         var count = parseInt($("#count").val().trim());
//         var day = parseInt($(".active-date").html());
//         // Basic form validation
//         if(name.length === 0) {
//             $("#name").addClass("error-input");
//         }
//         else if(isNaN(count)) {
//             $("#count").addClass("error-input");
//         }
//         else {
//             $("#dialog").hide(250);
//             console.log("new event");
//             new_event_json(name, count, date, day);
//             date.setDate(day);
//             init_calendar(date);
//         }
//     });
// }
// function loadbookingslots(date)
// {
//     $.ajax({
//         type:"POST",
//         url: "<?php echo base_url(); ?>/hallbooking_online/loadbookingslots",
//         data: {bookeddate:date},
//         success:function(data)
//         {
//           // console.log(data);
//           if(data){
//                 $("#booking_slot").html(data);
//             } else {
//                 alert("Selected date is not available, Kindly select various date!");
//                 window.location.replace("<?php echo base_url();?>/hallbooking_online/hall");


//             }
//         }
//     });
// }
</script>
  <script>
        document.addEventListener('DOMContentLoaded', function () {
            const today = new Date();
            const maxDate = new Date(today.getFullYear() - 21, today.getMonth(), today.getDate());
            const maxDateString = maxDate.toISOString().split('T')[0];
            document.getElementById('brideDOB').setAttribute('max', maxDateString);
            document.getElementById('groomDOB').setAttribute('max', maxDateString);
        });
        document.getElementById('brideDOB').addEventListener('change', function () {
            var dob = new Date(this.value);
            var today = new Date();
            var age = today.getFullYear() - dob.getFullYear();
            var monthDifference = today.getMonth() - dob.getMonth();
            if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            document.getElementById('brideAge').textContent = "Bride Age: " + age + " years";
        });
        document.getElementById('groomDOB').addEventListener('change', function () {
            var dob = new Date(this.value);
            var today = new Date();
            var age = today.getFullYear() - dob.getFullYear();
            var monthDifference = today.getMonth() - dob.getMonth();
            if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            document.getElementById('groomAge').textContent = "Groom Age: " + age + " years";
        });
    </script>
</body>

</html>