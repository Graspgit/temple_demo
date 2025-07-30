<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/typicons/typicons.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/css/vertical-layout-light/style.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/mdi/css/materialdesignicons.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/style.css">
<link rel="shortcut icon" href="<?php echo base_url(); ?>/assets/archanai/images/favicon.png" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/demo.css">
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

    .text-muted.arch1 {
        color: #e13573 !important;
        font-size: 14px;
        font-weight: bold;
        text-align: center;
        padding: 5px;
        width: 100%;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 0;
        max-height: 35px;
        min-height: 35px;
        text-transform: uppercase;
    }

    .custom-checkbox input:checked~.checkmark {
        background-color: #2196F3;
    }

    .custom-checkbox input:checked~.checkmark:after {
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
        padding: 4px;
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
        margin-top: -3px;
    }

    .itemcountrr #increase {
        margin-left: 0px;
        border-radius: 0 4px 4px 0;
        margin-top: -3px;
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
        font-size: 15px;
        background: #f7ebbb;
        color: #000000;
        font-weight: 500;
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
        font-size
        background-color: #f2f2f2;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }


    .table-responsive {
        width: 100%;
        overflow-x: hidden; 
    }

    .table {
        table-layout: auto; 
        width: 100%; 
    }

    .wrap-text {
        white-space: normal; 
        word-wrap: break-word; 
        overflow-wrap: break-word; 
    }

    th, td {
        text-align: left;
        padding: 8px;
    }

    #annathanam_table {
        width: 100%;
        table-layout: auto; 
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

    .pay-label:before {
        content: none !important;
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
        gap: 10px;
        /* space between buttons */
    }

    /* Hide the actual radio input */
    .payment-options .payment_type {
        display: none;
    }

    /* Style labels to look like buttons */
    .payment-options .btn-payment {
        padding: 5px 12px;
        cursor: pointer;
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        transition: background-color 0.3s, color 0.3s;
        display: inline-block;
        border-radius: 5px;
        margin-bottom: 0;
    }

    /* Change style when radio is checked */
    .payment-options .payment_type:checked+.btn-payment {
        background-color: #008000;
        /* Green */
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
        flex-grow: 1;
        /* Takes up the full width of the container */
        display: flex;
        justify-content: center;
        /* Centers the payment options
        padding: 10px; */
        /* Additional padding for better spacing */
        background-color: #f9f9f9;
        /* Optional: for better visibility of padding */
    }

    .partial_paid_sec {
        flex-grow: 1;
        /* Optional: Allows this section to take equal space */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .pay-label {
        margin: 0 5px;
        /* Adds some space between the buttons */
    }

    .button-container {
        display: flex;
        justify-content: center;
        /* Centers the content horizontally */
        align-items: center;
        /* Centers the content vertically if needed */
        padding: 10px;
        /* Adds some padding around the button for spacing */
    }

    .cl_btn {
        background: linear-gradient(179deg, rgb(212 0 0) 0%, rgb(242 105 105) 35%, rgb(209 59 59) 100%);
        border-radius: 15px;
        font-weight: bold;
    }

    .booking_slots {
        padding-left: 0;
    }

    .booking_slots li {
        display: inline-block;
        width: 20%;
        padding: 5px;
        background: #ffebd1;
        border: 1px solid #f1c891;
        border-radius: 3px;
        margin: 5px;
        cursor: pointer;
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

    .form .page .title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 10px;
        margin-top: 5px;
        color: #FFFFFF;
        background: #d4aa00;

    }

    .form .page .title1 {
        font-size: 21px;
        font-weight: 500;
        margin-bottom: 10px;
        margin-top: 5px;
        color: #FFFFFF;
        background: #0e7d89de;

    }

    #annathanam_table {
            width: 100%;  /* Full width to ensure responsiveness */
            table-layout: fixed; /* Keeps the table layout consistent */
        }

    #annathanam_table th, #annathanam_table td {
        padding: 8px; /* Padding for better readability */
        overflow: hidden; /* Prevents content from spilling out */
        text-overflow: ellipsis; /* Shows ellipsis when text overflows */
        white-space: normal; /* Ensures text stays on a single line */
        cursor: pointer; /* Indicates that the cell is interactive */
    }
    .modal-backdrop.show {
        opacity: 0;
        z-index: -1;
    }
    .fade.show {
        opacity: 1;
        background: #00000075;
    }
    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 850px;
            margin: 3.75rem auto;
        }
    }
</style>
</head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<body class="sidebar-icon-only">
    <?php if ($_SESSION['succ'] != '') { ?>
        <div class="row" style="padding: 0 30%;margin: 0px 0 15px 0;" id="content_alert">
            <div class="suc-alert" style="width: 100%;">
                <span class="suc-closebtn" onClick="this.parentElement.style.display='none';">&times;</span>
                <p><?php echo $_SESSION['succ']; ?></p>
            </div>
        </div>
    <?php } ?>
    <?php if ($_SESSION['fail'] != '') { ?>
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
                                            <span class="left-button fa fa-chevron-left" id="prev">
                                            </span>
                                            <span class="year" id="label"></span>
                                            <span class="right-button fa fa-chevron-right" id="next"> </span>
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
                                    </div>
                                </div>
                                <div class="events-container"></div>
                                <div class="dialog prod" id="dialog">
                                    <!--h4 class="dialog-header" style=" background:#d4aa00;color:#FFFFFF;"> Add New Event </h4-->
                                    <form class="form" id="form" method="post">
                                        <input type="hidden" id="ubhayam_date" name="ubhayam_date" class="form-control" value="<?php echo date("Y-m-d"); ?>">                                           
                                        <input type="hidden" id="booking_date" name="booking_date" class="form-control" value="<?php echo date("Y-m-d"); ?>">                             
                                        <input type="hidden" id="booking_type" name="booking_type" value="2">
                                        <input type="hidden" id="save_booking" name="save_booking" value="1">
                                        <input type="hidden" id="payment_type" name="payment_type" value="full">
                                        <input type="hidden" id="booking_through" name="booking_through" value="COUNTER">  
                                        <input type="hidden" id="print_method" name="print_method" value="<?php echo $setting['print_method']; ?>">
                                        <?php $user_id = $_SESSION['log_id_frend']; ?>
                                        <input type="hidden" id="user_id" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                                        <input class="input1" type="hidden" id="dt" name="dt" value="<?php echo date('Y-m-d'); ?>">
                                        <input type="hidden" id="devotee_id" name="devotee_id" value="">

                                        <div class="form-container card-body" align="center">
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-md-8">

                                                        <style>
                                                            .product {
                                                                max-height: 400px;
                                                                padding-top: 10px;
                                                                overflow: auto;
                                                            }

                                                            .grid-margin,
                                                            .purchase-popup {
                                                                margin-bottom: 1.3rem;
                                                            }

                                                            .btns {
                                                                margin-top: 5px;
                                                                position: absolute;
                                                                width: 100%;
                                                                display: flex;
                                                                justify-content: space-between;
                                                            }

                                                            .next {
                                                                margin-right: 30px;
                                                            }

                                                            .btns .btn {
                                                                color: #FFF !important;
                                                                font-weight: bold;
                                                            }

                                                            .form {
                                                                margin-top: 0px;
                                                            }

                                                            .form .page {
                                                                display: none;
                                                            }

                                                            .form .page.active {
                                                                display: block;
                                                            }

                                                            .form .page .field {
                                                                width: 100%;
                                                                margin-bottom: 20px;
                                                            }

                                                            .form .page .field label {
                                                                display: block;
                                                                font-size: 14px;
                                                                font-weight: 500;
                                                                margin-bottom: 8px;
                                                                color: #666;
                                                            }

                                                            .form .page .field input,
                                                            .form .page .field select,
                                                            .form .page .field textarea {
                                                                width: 100%;
                                                                padding: 12px;
                                                                border-radius: 8px;
                                                                border: 1px solid #ddd;
                                                                outline: none;
                                                                font-size: 14px;
                                                                transition: border-color 0.3s ease;
                                                            }

                                                            .form .page .field input:focus,
                                                            .form .page .field select:focus,
                                                            .form .page .field textarea:focus {
                                                                border-color: #667eea;
                                                            }



                                                            .form .page .btns button:hover {
                                                                opacity: 0.8;
                                                            }

                                                            .progress-bar {
                                                                display: flex;
                                                                margin: 3px 0;
                                                                user-select: none;
                                                                flex-direction: row;
                                                                background: #FFF !important;
                                                            }

                                                            .progress-bar .step {
                                                                position: relative;
                                                                text-align: center;
                                                                width: 100%;
                                                            }

                                                            .progress-bar .step p {
                                                                font-size: 14px;
                                                                font-weight: 500;
                                                                color: #666;
                                                                margin-bottom: 8px;
                                                            }

                                                            .progress-bar .step .bullet {
                                                                position: relative;
                                                                height: 30px;
                                                                width: 30px;
                                                                border: 2px solid #ddd;
                                                                border-radius: 50%;
                                                                display: inline-block;
                                                                transition: 0.3s;
                                                            }

                                                            .progress-bar .step .bullet.active {
                                                                border-color: #26c915;
                                                                background: #26c915;
                                                            }

                                                            .progress-bar .step .bullet span {
                                                                position: absolute;
                                                                left: 50%;
                                                                transform: translateX(-50%);
                                                                color: #999;
                                                                line-height: 26px;
                                                            }

                                                            .progress-bar .step .bullet.active span {
                                                                color: #fff;
                                                            }

                                                            .progress-bar .step:not(:last-child) .bullet::before,
                                                            .progress-bar .step:not(:last-child) .bullet::after {
                                                                position: absolute;
                                                                content: '';
                                                                bottom: 11px;
                                                                right: -110px;
                                                                height: 3px;
                                                                width: 100px;
                                                                background: #ddd;
                                                            }

                                                            .progress-bar .step .bullet.active::after {
                                                                background: #26c915;
                                                                transform: scaleX(0);
                                                                transform-origin: left;
                                                                animation: animate 0.3s linear forwards;
                                                            }

                                                            @keyframes animate {
                                                                100% {
                                                                    transform: scaleX(1);
                                                                }
                                                            }

                                                            .alert {
                                                                padding: 12px;
                                                                border-radius: 8px;
                                                                margin-bottom: 15px;
                                                                display: none;
                                                            }

                                                            .alert.error {
                                                                background: #fee2e2;
                                                                color: #dc2626;
                                                                border: 1px solid #fecaca;
                                                                display: block;
                                                            }

                                                            .alert.success {
                                                                background: #dcfce7;
                                                                color: #16a34a;
                                                                border: 1px solid #bbf7d0;
                                                                display: block;
                                                            }

                                                            @media (max-width: 480px) {
                                                                .form .page .btns {
                                                                    flex-direction: column-reverse;
                                                                    width: 100%;
                                                                }

                                                                .form .page .btns button {
                                                                    width: 100%;
                                                                }
                                                            }

                                                            a.btn {
                                                                text-decoration: none;
                                                                color: #666;
                                                                border: 0px solid #666;
                                                                padding: 6px 15px;
                                                                display: inline-block;
                                                                margin-left: 5px;
                                                            }

                                                            .table th,
                                                            .table td {
                                                                padding: 0.35rem;
                                                            }
                                                        </style>
                                                        <div class="container1">
                                                            <div class="progress-bar">
                                                                <div class="step">
                                                                    <!--p>Usage</p-->
                                                                    <div class="bullet active">
                                                                        <span>1</span>
                                                                    </div>
                                                                </div>
                                                                <div class="step">
                                                                    <div class="bullet">
                                                                        <span>2</span>
                                                                    </div>
                                                                </div>
                                                                <div class="step">
                                                                    <div class="bullet">
                                                                        <span>3</span>
                                                                    </div>
                                                                </div>
                                                                <div class="step">
                                                                    <div class="bullet">
                                                                        <span>4</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form">
                                                                <div class="page active">
                                                                    <div class="title">Choose Slot</div>
                                                                    <div class="alert"></div>

                                                                    <style>
                                                                        .tabs {
                                                                            display: flex;
                                                                            cursor: pointer;
                                                                        }

                                                                        .tab {
                                                                            padding: 7px;
                                                                            width: 50%;
                                                                            margin-right: 5px;
                                                                        }

                                                                        .tab.active {
                                                                            background-color: #8f7405;
                                                                            color: white;
                                                                        }

                                                                        .contents {
                                                                            display: none;
                                                                            padding: 10px;
                                                                            border: 1px solid #ccc;
                                                                        }

                                                                        .contents.active {
                                                                            display: block;
                                                                        }

                                                                        .contents ul {
                                                                            padding-left: 0
                                                                        }

                                                                        ;
                                                                    </style>

                                                                    <ul class="booking_slots">
                                                                        <?php
                                                                        $morning_slots = [];
                                                                        $evening_slots = [];

                                                                        foreach ($time_list as $row) {
                                                                            $slot_parts = explode(" - ", $row['slot_name']);
                                                                            $start_time = $slot_parts[0];

                                                                            $hour = date("H", strtotime($start_time));

                                                                            if ($hour >= 0 && $hour < 12) {
                                                                                $morning_slots[] = $row;
                                                                            } else {
                                                                                $evening_slots[] = $row;
                                                                            }
                                                                        }
                                                                        ?>

                                                                        <div class="tabs">
                                                                            <div class="tab active" data-target="#morning">MORNING</div>
                                                                            <div class="tab" data-target="#evening">EVENING</div>   
                                                                        </div>

                                                                        <div id="morning" class="contents active">
                                                                            <ul>
                                                                                <?php foreach ($morning_slots as $row) { ?>
                                                                                    <li>
                                                                                        <input
                                                                                            style="left: 2%; opacity: 1; position: inherit;"
                                                                                            type="radio"
                                                                                            class="booking_slot"
                                                                                            name="booking_slot[]"
                                                                                            value="<?php echo $row['id']; ?>">
                                                                                        <?php echo $row['slot_name']; ?>
                                                                                    </li>
                                                                                <?php } ?>
                                                                            </ul>
                                                                        </div>

                                                                        <div id="evening" class="contents">
                                                                            <ul>
                                                                                <?php foreach ($evening_slots as $row) { ?>
                                                                                    <li>
                                                                                        <input
                                                                                            style="left: 2%; opacity: 1; position: inherit;"
                                                                                            type="radio"
                                                                                            class="booking_slot"
                                                                                            name="booking_slot[]"
                                                                                            value="<?php echo $row['id']; ?>">
                                                                                        <?php echo $row['slot_name']; ?>
                                                                                    </li>
                                                                                <?php } ?>
                                                                            </ul>
                                                                        </div>

                                                                        <script>
                                                                            $(document).ready(function () {
                                                                                $('.tab').click(function () {
                                                                                    $('.tab').removeClass('active');
                                                                                    $(this).addClass('active');

                                                                                    $('.contents').removeClass('active');
                                                                                    $($(this).data('target')).addClass('active');
                                                                                });
                                                                            });
                                                                        </script>
                                                                    </ul>
                                                                </div>

                                                                <div class="page">
                                                                    <div class="btns">
                                                                        <a class="btn btn-info prev">Back</a>
                                                                        <a class="btn btn-info next">Next</a>
                                                                    </div>
                                                                    <div class="title">Choose Any Deity for Ubayam</div>
                                                                    <div class="alert"></div>
                                                                    <div class="row prod deitys" id="deity"></div>

                                                                </div>

                                                                <div class="page">
                                                                    <div class="btns">
                                                                        <a class="btn btn-info prev">Back</a>
                                                                        <a class="btn btn-info next">Next</a>
                                                                    </div>

                                                                    <div class="title">Choose Any Ubayam for Pay</div>
                                                                    <div class="alert"></div>

                                                                    <div class="row prod product" id="add_one">
                                                                        <?php // print_r($package); 
                                                                        foreach ($package as $row) { ?>

                                                                            <div class="col-xl-3 col-sm-6 col-lg-3 col-md-4 grid-margin stretch-card portfolio archanai"
                                                                                data-cat="">
                                                                                <input type="radio" class="ubayam_slot"
                                                                                    name="pay_for"
                                                                                    value="<?php echo $row['id']; ?>"
                                                                                    id="pay_for<?php echo $row['id']; ?>" />
                                                                                <label class="card"
                                                                                    for="pay_for<?php echo $row['id']; ?>"
                                                                                    onclick="payfor(<?php echo $row['id']; ?>)">
                                                                                    <img class="img-fluid prod_img"
                                                                                        src="<?php echo base_url(); ?>/uploads/package/<?php echo $row['image']; ?>">
                                                                                    <div class="d-flex justify-content-between align-items-center mb-2 mt-2"
                                                                                        style="flex-direction: column;">
                                                                                        <p class="mb-0 text-muted arch"
                                                                                            id="pay_name<?php echo $row['id']; ?>">
                                                                                            <?php echo $row['name']; ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </label>
                                                                            </div>
                                                                            <?php
                                                                        } ?>  
                                                                    </div>
                                                                </div>

                                                                <div class="page">
                                                                    <div class="btns">
                                                                        <a class="btn btn-info prev">Back</a>
                                                                        <a class="btn btn-info next">Next</a>
                                                                    </div>
                                                                    <div class="title">Ubayam Services</div>
                                                                    <div class="alert"></div>

                                                                    <div class="title1">Add-on Details</div>
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group form-float">
                                                                                <div class="form-line">
                                                                                    <!--<label class="form-lable">Package Name</label>-->
                                                                                    <select class="form-control" id="add_one_addon">                                                  
                                                                                        <option value="">Select From</option>
                                                                                        <?php foreach ($package_addon as $row) { ?>
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
                                                                                    <input type="number" class="form-control" id="get_pack_amt_addon" placeholder="0.00">
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
                                                                                            <th width="30%">Description</th>                                                                                         
                                                                                            <th width="20%">Qty</th>
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

                                                                    <div <?php if(!empty($setting['enable_abishegam'])){ echo ' style="display: block;"'; }else echo ' style="display: none;"'; ?>>
                                                                        <div class="title1">Abishegam</div>
                                                                        <div class="row">
                                                                            <div class="col-sm-8">
                                                                                <div class="form-group form-float">
                                                                                    <div class="form-line">
                                                                                        <select class="form-control" id="abishegamDropdown">
                                                                                            <option value="">Select Deity</option>
                                                                                            <?php foreach ($abishegam_deities as $row) { ?>
                                                                                                <option value="<?php echo $row['id']; ?>" data-amount="<?php echo $row['abishegam_amount']; ?>">
                                                                                                    <?php echo $row['name'] . ' / ' . $row['name_tamil']; ?>
                                                                                                </option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row scroll" style="overflow-y:scroll; overflow-x:hidden; height: 200px;">
                                                                            <div class="table-responsive col-sm-12" style="margin-bottom: 40px">
                                                                                <table class="table table-bordered">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th width="10%">S.no</th>
                                                                                            <th width="35%">Deity Name</th>
                                                                                            <th width="35%">Amount</th>
                                                                                            <th width="20%">Action</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody id="selectedAbishegamTable">
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div <?php if(!empty($setting['enable_homam'])){ echo ' style="display: block;"'; }else echo ' style="display: none;"'; ?>>
                                                                        <div class="title1">Homam</div>
                                                                        <div class="row">
                                                                            <div class="col-sm-8">
                                                                                <div class="form-group form-float">
                                                                                    <div class="form-line">
                                                                                        <select class="form-control" id="homamDropdown" onChange="addHomamToTable()">
                                                                                            <option value="">Select deity</option>
                                                                                                <?php foreach ($homam_deities as $row) { ?>
                                                                                                    <option value="<?php echo $row['id']; ?>" data-amount="<?php echo $row['homam_amount']; ?>">
                                                                                                        <?php echo $row['name'] . ' / ' . $row['name_tamil']; ?>
                                                                                                    </option>
                                                                                                <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row scroll" style="overflow-y:scroll; overflow-x:hidden; height: 200px;">
                                                                            <div class="table-responsive col-sm-12" style="margin-bottom: 40px">
                                                                                <table class="table table-bordered">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th width="10%">S.no</th>
                                                                                            <th width="35%">Deity Name</th>
                                                                                            <th width="35%">Amount</th>
                                                                                            <th width="20%">Action</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody id="selectedHomamTable">
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="title1">Prasadam</div>
                                                                    <div class="row">
                                                                        <div class="col-md-5">                                                                                <label style="margin-bottom: 0rem; text-align: center;" align="center"><strong> Select Slot</strong></label><br>
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

                                                                    <div id="free_prasadam">
                                                                        <div class="col-sm-12">
                                                                            <h3 style="margin-bottom:5px; margin-top:5px; text-align: left;">Free Prasadam</h3>   
                                                                            <hr>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group form-float">
                                                                                    <div class="form-line">
                                                                                        <select class="form-control" id="add_free_prasadam">                                                                                   
                                                                                            <option value="">Select Prasadam from dropdown </option>                                                                                                                                                                                         
                                                                                            <?php foreach ($free_prasadam as $row) { ?>
                                                                                                <option  value="<?php echo $row['id']; ?>">
                                                                                                    <?php echo $row['name_eng']; ?>
                                                                                                </option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group form-float">
                                                                                    <div class="form-line" style="border: none;">                                                                                       
                                                                                        <label id="pack_add_free_prasadam" class="btn btn-success" style="padding: 5px 12px !important;">Add</label>                                                                                           
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        

                                                                        <div class="row scroll" style="overflow-y:scroll; overflow-x:hidden; height: 150px;">                                                                           
                                                                            <div class="col-sm-12">
                                                                                <div class="table-responsive">
                                                                                    <table class="table table-bordered" style="width:100%" id="package_table_free_prasadam"  style="height: 150px;">                                                                                      
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th width="50%">Name</th>                                                                                              
                                                                                                <th width="30%">Qty</th>
                                                                                                <th width="20%">Action</th>                                                                                                
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                                <input type="hidden" id="pack_row_count_prasadam" value="0">                                                                                                                                                                      
                                                                            </div>
                                                                        </div>
                                                                    </div><br>

                                                                    <div <?php if(!empty($setting['enable_prasadam'])){ echo ' style="display: block;"'; }else echo ' style="display: none;"'; ?>>
                                                                        <div class="scroll products row">
                                                                            <div class="col-sm-12">
                                                                                <h3 style="margin-bottom:5px; margin-top:5px; text-align: left;">Add-on Prasadam</h3><hr>
                                                                            </div>
                                                                            <div class="col-sm-6 ">
                                                                                <div class="form-group form-float">
                                                                                    <div class="form-line">
                                                                                        <!--<label class="form-lable">Package Name</label>-->
                                                                                        <select class="form-control" id="add_one_prasadam">
                                                                                            <option value="">Select From</option>
                                                                                            <?php foreach ($prasadam as $row) { ?>
                                                                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['name_eng']; ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <input type="hidden" id="prasadam_amount" name="prasadam_amount" class="form-control">
                                                                                
                                                                            <div class="col-sm-3 ">
                                                                                <div class="form-group form-float">
                                                                                    <div class="form-line focused">
                                                                                        <input type="hidden" id="prasadam_name">
                                                                                        <input type="number" class="form-control" id="get_prasadam_amt" placeholder="0.00">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-3 ">
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
                                                                                                <th width="50%">Name</th>
                                                                                                <th width="20%">Qty</th>
                                                                                                <th width="20%">Total RM</th>
                                                                                                <th width="10%">Action</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                                <input type="hidden" id="prasadam_row_count" value="0">
                                                                                    
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                    <div class="pack" style="display:none;">
                                                                        <div class="row">
                                                                            <div class="col-sm-12">
                                                                                <h4
                                                                                    style="margin-bottom:5px; margin-top:5px; color:#FFFFFF; background:#d4aa00;">
                                                                                    Family Details</h4>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <label class="form-label">Name</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="family_name_old">
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <label
                                                                                    class="form-label">Relationship</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="family_relationship_old">
                                                                            </div>
                                                                            <div class="col-sm-2 smal_marg">
                                                                                <div class="form-group form-float">
                                                                                    <div class="form-line"
                                                                                        style="border: none;">
                                                                                        <br><label id="family_add"
                                                                                            class="btn btn-warning"
                                                                                            style="padding: 6px 12px !important;height: 2.35rem; margin-top: 6px;">Add</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12 table-responsive">
                                                                                <table class="table table-bordered"
                                                                                    style="width:100%; height: 150px;"
                                                                                    id="family_table">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th width="40%">Name</th>
                                                                                            <th width="25%">Relationship
                                                                                            </th>
                                                                                            <th width="15%">Action</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody class="prod"
                                                                                        style="overflow-y:scroll; overflow-x:hidden; max-height: 200px;">
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                            <input type="hidden" id="family_row_count"
                                                                                value="0">
                                                                        </div>
                                                                    </div>

                                                                    <!--div style="margin-top:20px;">
                                                                        <a class="btn btn-success submit">Submit</a>
                                                                    </div-->
                                                                </div>
                                                            </div><br><br>
                                                        </div>

                                                        <script>
                                                            const pages = document.querySelectorAll('.page');
                                                            const dis = document.querySelectorAll('.dis');
                                                            const progressSteps = document.querySelectorAll('.step');

                                                            let currentPage = 0;

                                                            function showPage(pageIndex) {
                                                                pages.forEach((page, index) => {
                                                                    page.classList.remove('active');
                                                                    if (index === pageIndex) {
                                                                        page.classList.add('active');
                                                                    }
                                                                });

                                                                progressSteps.forEach((step, index) => {
                                                                    const bullet = step.querySelector('.bullet');
                                                                    if (index <= pageIndex) {
                                                                        bullet.classList.add('active');
                                                                    } else {
                                                                        bullet.classList.remove('active');
                                                                    }
                                                                });
                                                                const specialDiv = document.querySelector('.final-div');
                                                                if (specialDiv) {
                                                                    if (pageIndex === 3) {
                                                                        specialDiv.style.display = 'block';
                                                                    } else {
                                                                        specialDiv.style.display = 'none';
                                                                    }
                                                                }
                                                            }

                                                            function validatePage(page) {
                                                                const fields = page.querySelectorAll('[required]');
                                                                const alert = page.querySelector('.alert');
                                                                let isValid = true;

                                                                fields.forEach(field => {
                                                                    if (!field.value.trim()) {
                                                                        isValid = false;
                                                                    }
                                                                });

                                                                if (!isValid) {
                                                                    alert.textContent = 'Please fill in all required fields.';
                                                                    alert.className = 'alert error';
                                                                    return false;
                                                                }

                                                                if (alert) {
                                                                    alert.style.display = 'none';
                                                                }
                                                                return true;
                                                            }



                                                            document.querySelectorAll('input.booking_slot').forEach(radio => {
                                                                radio.addEventListener('click', () => {
                                                                    if (validatePage(pages[currentPage])) {
                                                                        currentPage++;
                                                                        showPage(currentPage);
                                                                    }
                                                                });
                                                            });



                                                            document.querySelectorAll('.next').forEach(button => {
                                                                button.addEventListener('click', () => {
                                                                    if (validatePage(pages[currentPage])) {
                                                                        currentPage++;
                                                                        showPage(currentPage);
                                                                    }
                                                                });
                                                            });


                                                            document.querySelectorAll('.prev').forEach(button => {
                                                                button.addEventListener('click', () => {
                                                                    currentPage--;
                                                                    showPage(currentPage);
                                                                });
                                                            });
                                                            

                                                            document.querySelector('.submit').addEventListener('click', () => {
                                                                if (validatePage(pages[currentPage])) {
                                                                    const formData = new FormData(document.querySelector('.form'));
                                                                    const data = Object.fromEntries(formData);

                                                                    // Here you would typically send the data to your server
                                                                    console.log('Form submitted:', data);

                                                                    const alert = pages[currentPage].querySelector('.alert');
                                                                    alert.textContent = 'Form submitted successfully!';
                                                                    alert.className = 'alert success';

                                                                    // Disable all form inputs and buttons after submission
                                                                    document.querySelectorAll('input, select, textarea, button').forEach(element => {
                                                                        element.disabled = true;
                                                                    });
                                                                }
                                                            });
                                                            
                                                        </script>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="row">
                                                            <div class="col-md-4" style="text-align:left;">
                                                                <p style=" margin-top:10px;">
                                                                    <button type="button" class="btn btn-danger btn-lg ar_btn" onClick="rePrint();" style="background: #f44336;border: 1px solid #f44336;color: #fff;">Reprint</button>     
                                                                </p>
                                                            </div>
                                                            <div class="col-md-4 mt-2"> 
                                                                <p style=" margin-top:10px;">
                                                                    <button type="button" class="btn btn-danger btn-lg cl_btn clear-cart">Clear All</button>     
                                                                </p>
                                                            </div>
                                                            <div class="col-md-4" style="text-align:right;">
                                                                <p style="margin-top:10px;">
                                                                    <button type="button" class="btn ar_btn btn-info btn-lg" onClick="userModalOpen();">Add Detail</button>      
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <table class="show-cart table table-bordered" style="width:100%;display:none;"></table>
                                                        <span id="packname" style="font-weight:bold;color:#d40087;"></span>
                                                            

                                                        <table class="table table-bordered" id="servicesTable" style="width:100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="3" style="text-align: center; font-weight: bold;"> Services</th>     
                                                                </tr>
                                                                <tr id="serviceDetailsHeader" style="display: none; text-align: center; font-weight: bold;">   
                                                                    <th>Name</th>
                                                                    <th>Description</th>
                                                                    <th>Quantity</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="servicesList">
                                                            </tbody>
                                                        </table>

                                                        <div <?php if (!empty($setting['ubayam_discount'])) { echo ' style="display: block;"'; } else echo ' style="display: none;"'; ?>>  
                                                            <div style="display: flex; gap: 20px; align-items: center;">
                                                                <div>
                                                                    <h5 style="margin-bottom:5px; margin-top:5px; color:#FFFFFF; background:#d4aa00;">Package Amount</h5>
                                                                    <input style="text-align: center" type="number" min="0" step="any" id="sub_total" class="form-control" name="sub_total" value="0" readonly>              
                                                                </div>
                                                                <div> 
                                                                    <h5 style="margin-bottom:5px; margin-top:5px; color:#FFFFFF; background:#d4aa00;"> Discount</h5>
                                                                    <input style="text-align: center" type="number" min="0" step="any" id="discount_amount" class="form-control" name="discount_amount" value="0">        
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h4 style="margin-bottom:5px; margin-top:5px; color:#FFFFFF; background:#d4aa00;">Total Amount</h4>      
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="number" min="0" step="any" id="total_amt" class="form-control" name="total_amt" value="0" style="margin-top:5px; height:35px;font-weight:bold;font-size: 30px;text-align: center;" readonly>
                                                            </div>        
                                                        </div>

                                                        <div class="row clearfix" style="width:105%; border-bottom:1px dashed #CCC; display: flex; justify-content: center; align-items: center;">                                                       
                                                            <div class="payment-options" style="flex-grow: 1; display: flex; justify-content: space-between;">                                                        
                                                                <div class="form-group" style="margin-bottom:0;">
                                                                    <input type="radio" name="payment_type" id="payment_type_full" class="payment_type" value="full" <?php echo (empty($data['payment_type']) || $data['payment_type'] == 'full') ? 'checked' : ''; ?>>                                              
                                                                    <label for="payment_type_full" class="pay-label btn-payment">Full Payment</label>                                                           
                                                                </div>
                                                                <div class="form-group" style="margin-bottom:0;">
                                                                    <input type="radio" name="payment_type"  id="payment_type_partial" class="payment_type"  value="partial" <?php echo ($data['payment_type'] == 'partial') ? 'checked' : ''; ?>>                                                                                                                                     
                                                                    <label for="payment_type_partial" class="pay-label btn-payment">Partial Payment</label>                                                  
                                                                </div>
                                                                <div class="form-group" style="margin-bottom:0;">
                                                                    <input type="radio" name="payment_type" id="payment_type_only_booking" class="payment_type" value="only_booking" <?php echo ($data['payment_type'] == 'only_booking') ? 'checked' : ''; ?>>                                                                                                                                       
                                                                    <label for="payment_type_only_booking" class="pay-label btn-payment">Only Booking</label>                                                                
                                                                </div>
                                                            </div>
                                            
                                                            <div class="col-sm-6 partial_paid_sec" align="center" style="<?php echo (!empty($data['payment_type']) && $data['payment_type'] == 'partial') ? '' : 'display: none;'; ?>margin-top: 15px;">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <label class="form-label"align="center">Pay Amount</label>
                                                                    </div>       
                                                                    <div class="col-sm-6">
                                                                        <input type="number" name="pay_amt" id="pay_amt" step=".01" class="form-control" value="<?php echo $data['paid_amount'] ?? '0.00'; ?>">      
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                
                                                        <ul class="payment1">
                                                            <?php foreach ($payment_mode as $key => $pay) { ?>
                                                                <li>
                                                                    <input type="radio" name="payment_mode" id="cb<?php echo $pay['id']; ?>" value="<?php echo $pay['id']; ?>" data-name="<?php echo $pay['name']; ?>" <?php echo $key === 0 ? 'checked' : ''; ?> />
                                                                    <label for="cb<?php echo $pay['id']; ?>"><?php echo $pay['name']; ?></label>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>

                                                        <div class="col-sm-12" style="text-align: left;color: blue;">
                                                            <div class="form-group">
                                                                <label for="family_detail" id="family_detail" style="cursor: pointer;" data-toggle="modal" data-target="#familyDetailsModal">
                                                                    <i class="fa fa-plus-circle" style="color:blue"></i> Add Family Details
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

                                                    <div class="col-md-12 final-div" style="display:none">
                                                        <style>
                                                            .title1 {
                                                                font-size: 21px;
                                                                font-weight: 500;
                                                                margin-bottom: 10px;
                                                                margin-top: 5px;
                                                                color: #FFFFFF;
                                                                background: #0e7d89de;
                                                            }
                                                        </style>

                                                        <div class="title1">Annathanam</div>
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

                                                        <div <?php if(!empty($setting['enable_extra_charges'])){ echo ' style="display: block;"'; }else echo ' style="display: none;"'; ?>>
                                                            <div class="scroll extra-charges row">
                                                                <div class="col-sm-12">
                                                                    <h4 style="margin-bottom:20px; margin-top:5px; color:#FFFFFF; background:#d4aa00;">--Extra Charges--</h4>
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

                                                            <table id="extra_charges_table" class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Description</th>
                                                                        <th>Amount</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <!-- Dynamically added rows will appear here -->
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- Modal Content for Family Details -->
                                            <div class="modal fade" id="familyDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"> 
                                                <div class="modal-dialog" role="document" style="max-width: 80%;">
                                                    <div class="modal-content" style="width: 100%;"> <!-- This ensures that modal content takes the full width -->
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Add Family Details</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form id="familyDetailsForm">
                                                                <div class="row" id="inputRow">
                                                                    <div class="col-sm-3">
                                                                        <div class="form-group form-float">
                                                                            <div class="form-line">
                                                                                <label class="form-label">Name</label>
                                                                                <input type="text" class="form-control" id="family_name" value=" ">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-2">
                                                                        <div class="form-group">
                                                                            <label class="form-label">DOB</label>
                                                                            <input class="form-control" type="date" id="family_dob" max="<?php echo date('Y-m-d'); ?>">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-sm-2">
                                                                        <div class="form-group form-float">
                                                                            <div class="form-line focused">
                                                                                <label class="form-label">Rasi</label>
                                                                                <select class="form-control" id="family_rasi_id">
                                                                                    <option value="">--Select--</option>
                                                                                    <?php foreach ($rasi as $row) { ?>
                                                                                        <option value="<?php echo $row['id']; ?>" data-name="<?php echo $row['name_eng']; ?>"><?php echo $row['name_eng']; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-sm-2">
                                                                        <div class="form-group form-float">
                                                                            <div class="form-line focused">
                                                                                <label class="form-label">Natchathiram</label>
                                                                                <select class="form-control" id="family_natchathra_id">
                                                                                    <option value="">--Select--</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-sm-3">
                                                                        <div class="form-group form-float">
                                                                            <div class="form-line focused">
                                                                                <label class="form-label">Relationship</label>
                                                                                <select class="form-control" id="family_relationship">
                                                                                    <option value="">Select Relationship</option>
                                                                                    <option value="Husband">Husband</option>
                                                                                    <option value="Wife">Wife</option>
                                                                                    <option value="Father">Father</option>
                                                                                    <option value="Mother">Mother</option>
                                                                                    <option value="Son">Son</option>
                                                                                    <option value="Daughter">Daughter</option>
                                                                                    <option value="Brother">Brother</option>
                                                                                    <option value="Sister">Sister</option>
                                                                                    <option value="Uncle">Uncle</option>
                                                                                    <option value="Aunt">Aunt</option>
                                                                                    <option value="Grand Father">Grand Father</option>
                                                                                    <option value="Grand Mother">Grand Mother</option>
                                                                                    <option value="Grand Son">Grand Son</option>
                                                                                    <option value="Grand Daughter">Grand Daughter</option>
                                                                                    <option value="Cousin">Cousin</option>
                                                                                    <option value="Co-Brother">Co-Brother</option>
                                                                                    <option value="Co-Sister">Co-Sister</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12" align="center">
                                                                    <button type="button" class="btn btn-success" id="addFamilyMember">Add</button>
                                                                </div>
                                                                <br>
                                                            </form>
                                                            
                                                            <h3 style="text-align: center">Family Details</h3>
                                                            <table id="familyDetailsTable" class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width: 30%; text-align: center">Name</th>
                                                                        <th style="width: 15%; text-align: center">DOB</th>
                                                                        <th style="width: 15%; text-align: center">Rasi</th>
                                                                        <th style="width: 15%; text-align: center">Natchathiram</th>
                                                                        <th style="width: 15%; text-align: center">Relationship</th>
                                                                        <th style="width: 10%; text-align: center">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <!-- Dynamically added rows will appear here -->
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
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
        <!-- partial -->
    </div>
    <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
    </div>
    <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel"
        aria-hidden="true">
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
                                <input type="checkbox" class="term-checkbox" name="terms[]"
                                    value="<?php echo htmlspecialchars($term); ?>">
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

    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
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

                            <div class="col-md-6">
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
                                    <select class="form-control" name="natchathra_id" id="natchathra_id">                           
                                        <option value="">Select Natchiram</option>
                                    </select>
                                    <span id="error_msg"></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="address">Address</label>
                                    <textarea class="form-control" id="address" name="address" style="width:100%;" autocomplete="off"></textarea>   
                                    <span id="error_msg"></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="description">Remarks</label>
                                    <textarea class="form-control" id="description" name="description" style="width:100%;" autocomplete="off"></textarea>   
                                    <span id="error_msg"></span>
                                </div>
                            </div>
                        </div>


                        <button type="button" name="ar_add_btn" id="ar_add_btn" class="btn btn-info my-3"
                            style="width:100%; font-size:24px; height:auto;margin-bottom: 0 !important;">Submit</button>
                    </div>
                </div>
            </div>
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
                                                        <?php echo $reprintlist['paid_amount']; ?>
                                                    </td>
                                                    <td style="width: 10%;padding: 5px 0px!important;text-align:center;">
                                                        <a class='btn btn-primary'
                                                            style='font-size: 13px;font-weight: bold;padding: 6px 10px;background: #2196F3;border: 1px solid #2196F3;'
                                                            title='Print'
                                                            href='<?php echo base_url(); ?>/templeubayam_online/print_page_ubayam_imin/<?php echo $reprintlist['id']; ?>'
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

    <div id="alert-modal" class="modal fade" tabindex="-1" rele="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!--div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div-->
                <div class="modal-body">
                    <p style="text-align:center;"><br><i class="mdi mdi-alert-circle-outline"
                            style="font-size:42px; color:red;"></i></p>
                    <h5 style="text-align:center;" id="spndeddelid"></h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">OK</button>
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

    <div id="alert-modal-print" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" style=" max-width: 500px;">
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
                    <!-- <button type="button" class="btn btn-success m-2" id="print-a5">Print A5</button> -->
                </div>
                <div class="modal-footer text-center">
                    <button type="button" class="btn btn-info" id="okay" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Family Member Selection Modal -->
    <div class="modal fade" id="familySelectionModal" tabindex="-1" role="dialog" aria-labelledby="familySelectLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document" style="max-width: 90%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Family Members</h5><br>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <h6>Please select any 4 of your family members below to add in this Ubayam:</h6>
                    <table class="table" id="selectFamilyTable">
                        <thead>
                            <tr>
                            <th>Name</th>
                            <th>DOB</th>
                            <th>Rasi</th>
                            <th>Natchathiram</th>
                            <th>Relationship</th>
                            <th>Select</th>
                            </tr>
                        </thead>
                        <tbody>
                    
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" onclick="addSelectedFamily()">Add Selected</button>
                    <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
    <script src="<?php echo base_url(); ?>/assets/archanai/script.js"></script>

    <script src="<?php echo base_url(); ?>/assets/archanai/js/popper.js"></script>

    <link href="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
    <script src="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/ui_jquery/jquery-ui.css">
    <script src="<?php echo base_url(); ?>/assets/ui_jquery/jquery-ui.js"></script>
    <script src="<?php echo base_url(); ?>/assets/ui_jquery/moment.min.js"></script>

    <style>
        ul.payment {
            list-style-type: none;
            width: 100%;
            display: flex;
            justify-content: flex-start;
            margin-bottom: 0;
            padding-left: 0;
            -webkit-column-count: 3;
            column-count: 3;
            flex-wrap: wrap;
            height: 300px;
            overflow: auto;
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
            margin: 10px 10px;
            font-family: inherit;
            min-height: 120px;
            background: #fff;
            cursor: pointer;
            color: #6d5804;
            font-weight: bold;
        }

        .payment li label p {
            font-size: 18px;
            margin-bottom: 0;
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
            font-size: 18px;
            color: #0d2f95;
        }

        :checked+label {
            background: #f6ef08;
            transition-duration: 0.4s;
        }

        /* :checked+label:before {
            content: "✓";
            background-color: green;
            transform: scale(1);
        } */

        :checked+i.mdi {
            transform: scale(0.9);
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

        .content {
            height: 530px;
        }

        .table {
            margin-bottom: .8rem;
        }

        .card-body {
            padding: .51rem;
        }

        .payment1 li label {
            border: 1px solid #CCC;
            border-radius: 5px;
            line-height: 1;
            padding: 10px;
            display: block;
            position: relative;
            margin: 5px;
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

        .prod_img {
            width: 90px;
            min-width: 90px;
            margin: 0 auto;
            border-radius: 50%;
            min-height: 90px;
            max-height: 90px;
            background: #e1e1d68a;
            padding: 5px;
        }

        .text-muted.arch {
            color: #000000 !important;
            font-size: 13px;
            text-align: center;
            padding: 10px;
            width: 100%;
            line-height: 1.5em;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            max-height: 65px;
            min-height: 65px;
            text-transform: uppercase;
        }

        .ar_btn {
            background: linear-gradient(179deg, rgb(0 126 212) 0%, rgb(16 197 180) 35%, rgb(59 134 209) 100%);
            border-radius: 15px;
            font-weight: bold;
            height: 1.75em;
        }

        .btn {
            padding: 0.25rem 0.5rem;
            height: 2rem;
        }

        .show-cart1 {
            max-height: 350px;
            overflow: auto;
        }

        .show-cart1 tr {
            border-radius: 10px;
        }

        .show-cart1 td {
            font-size: 13px;
            padding: 3px 10px;
        }

        .total {
            margin-top: 15px;
            padding-bottom: 10px;
        }

        .total p {
            font-size: 24px;
            font-weight: bold;
        }

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

        input[type="radio"][id^="deity_id"] {
            display: none;
        }

        @media (max-width: 960px) {
            .prod_img {
                width: 50px;
                min-width: 50px;
                margin: 0 auto;
                border-radius: 50%;
                min-height: 50px;
                max-height: 50px;
            }

            .payment li {
                width: 25%;
            }

            .payment1 li label {
                padding: 15px 1px;
                margin: 15px 5px;
            }
        }

        .cal_head {
            background: #f34c22;
            color: #FFF;
            padding: 2px 5px;
        }

        .dashed {
            background: linear-gradient(90deg, blue 50%, transparent 50%), linear-gradient(90deg, blue 50%, transparent 50%), linear-gradient(0deg, blue 50%, transparent 50%), linear-gradient(0deg, blue 50%, transparent 50%);
            background-repeat: repeat-x, repeat-x, repeat-y, repeat-y;
            background-size: 11px 2px, 11px 2px, 2px 11px, 2px 11px;
            background-position: 0px 0px, 200px 100px, 0px 100px, 200px 0px;
            padding: 10px;
            animation: border-dance 4s infinite linear;
        }

        @keyframes border-dance {
            0% {
                background-position: 0px 0px, 300px 116px, 0px 150px, 216px 0px;
            }

            100% {
                background-position: 300px 0px, 0px 116px, 0px 0px, 216px 150px;
            }
        }
    </style>

    <!-- Serve Time and Payment type change -->
    <script>
        $(document).ready(function () {
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
            $(document).on('change', '.payment_type', function () {
                updatePaymentSections();
            });

            updatePaymentSections();
        });

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
                url: "<?php echo base_url(); ?>/templeubayam_online/get_devotee_details", 
                method: "POST",
                data: {code: phone_code, number:  phone_number},
                datatype: "json",
                success: function(response) {
                    response = JSON.parse(response);
                    console.log(response);
                    
                    $('#devotee_id').val(response.id);
                    $('#name').val(response.name);
                    $('#ic_number').val(response.ic_no);
                    $('#email_id').val(response.email);
                    $('#address').val(response.address);
                    $('select[name="rasi_id"]').val(response.rasi_id);
                    $("#rasi_id").change();
                    setTimeout(function() {
                        $('select[name="natchathra_id"]').val(response.natchathra_id);
                    }, 500);

                    $('#familySelectionModal .modal-title').html('Hello ' + response.name + '!');
                },
                error: function() {
                    alert("Failed to load user data.");
                }
            });
        }

        $('#mobile').on('blur', function() {
            loadUserData();
        });

        function loadfamilydetails() {
            var devotee_id = $('#devotee_id').val();

            $.ajax({
                url: "<?php echo base_url(); ?>/templeubayam_online/get_devotee_family_details",
                method: "POST",
                data: { id: devotee_id },
                success: function(response) {
                    response = JSON.parse(response);
                    console.log("family members:", response);
                    var tbody = $('#selectFamilyTable tbody');
                    tbody.empty();

                    $.each(response, function(index, member) {
                        var row = '<tr>' +
                            '<td>' + member.name + '</td>' +
                            '<td>' + member.dob + '</td>' +
                            '<td data-id="' + member.rasi_id + '" data-name="' + member.rasi_name + '">' + member.rasi_name + '</td>' +
                            '<td data-id="' + member.natchathra_id + '" data-name="' + member.natchathra_name + '">' + member.natchathra_name + '</td>' +
                            '<td>' + member.relationship + '</td>' +
                            '<td><input type="checkbox" class="familyCheckbox" data-member=\'' + JSON.stringify(member) + '\'></td>' +
                            '</tr>';
                        tbody.append(row);
                    });

                    $('#familySelectionModal').modal('show');
                },
                error: function() {
                    alert("Failed to load family details.");
                }
            });
        }

        function addSelectedFamily() {
            var selected = $(".familyCheckbox:checked");
            var currentCount = $("#familyDetailsTable tbody tr").length;
            var max_rows = 4;

            selected.each(function(index, el) {
                if (currentCount >= max_rows) {
                    alert("Maximum 4 family members can be added.");
                    return false; // break out of loop
                }

                var member = JSON.parse($(this).attr('data-member'));

                var html_f = '<tr id="rmv_family_row' + currentCount + '">';
                html_f += '<td style="width: 30%;"><input type="text" style="text-align: center; border: none;width: 100%;" name="family[' + currentCount + '][name]" readonly value="' + member.name + '"></td>';
                html_f += '<td style="width: 15%;"><input type="text" style="text-align: center; border: none;width: 100%;" name="family[' + currentCount + '][dob]" readonly value="' + member.dob + '"></td>';
                html_f += '<td style="width: 15%;"><input type="hidden" name="family[' + currentCount + '][rasi_id]" value="' + member.rasi_id + '">' + member.rasi_name + '</td>';
                html_f += '<td style="width: 15%;"><input type="hidden" name="family[' + currentCount + '][natchathra_id]" value="' + member.natchathra_id + '">' + member.natchathra_name + '</td>';
                html_f += '<td style="width: 15%;"><input type="text" style="text-align: center; border: none;width: 100%;" name="family[' + currentCount + '][relationship]" readonly value="' + member.relationship + '"></td>';
                html_f += '<td style="width: 10%;"><a class="btn btn-danger btn-rad" onclick="removeFamilyMember(' + currentCount + ')" style="width:auto;padding: 0px 3px !important;"><span>X</span></a></td>';
                html_f += '</tr>';

                $("#familyDetailsTable tbody").append(html_f);
                currentCount++;
            });

            $('#familySelectionModal').modal('hide');
            $('#familyDetailsModal').modal('show');
        }
    </script>

    <!-- Family Details-->
    <script>
        $(document).ready(function() {
            var max_rows = 4;

            $("#family_rasi_id").change(function() {
                var rasi_id = $(this).val();
                if (rasi_id) {
                    fetchNatchathiram(rasi_id);
                }
            });


            $("#addFamilyMember").click(function() {
                var name = $("#family_name").val();
                var dob = $("#family_dob").val();
                var rasi_id = $("#family_rasi_id").val();
                var rasi_name = $("#family_rasi_id option:selected").data('name');
                var natchathra_id = $("#family_natchathra_id").val();
                var natchathra_name = $("#family_natchathra_id option:selected").data('name');
                var relationship = $("#family_relationship").val();

                // Validation
                if (!name || !dob || !rasi_id || !natchathra_id || !relationship) {
                    alert("Please fill in all fields.");
                    return;
                }

                var count = $("#familyDetailsTable tbody tr").length;
                if (count < max_rows) {

                    var html_f = '<tr id="rmv_family_row' + count + '">';
                    html_f += '<td style="width: 30%;"><input type="text" style="text-align: center; border: none;width: 100%;" name="family[' + count + '][name]" readonly value="' + name + '"></td>';
                    html_f += '<td style="width: 15%;"><input type="text" style="text-align: center; border: none;width: 100%;" name="family[' + count + '][dob]" readonly value="' + dob + '"></td>';
                    html_f += '<td style="width: 15%;"><input type="hidden" name="family[' + count + '][rasi_id]" value="' + rasi_id + '">' + rasi_name +'</td>';
                    html_f += '<td style="width: 15%;"><input type="hidden" name="family[' + count + '][natchathra_id]" value="' + natchathra_id + '">' + natchathra_name +'</td>';
                    html_f += '<td style="width: 15%;"><input type="text" style="text-align: center; border: none;width: 100%;" name="family[' + count + '][relationship]" readonly value="' + relationship + '"></td>';
                    html_f += '<td style="width: 10%; align="center""><a class="btn btn-danger btn-rad" onclick="removeFamilyMember(' + count + ')" style="width:auto;padding: 0px 3px !important;"><span>X</span></a></td>';
                    html_f += '</tr>';

                    $("#familyDetailsTable tbody").append(html_f);
                    $("#family_name").val('');
                    $("#family_dob").val('');
                    $("#family_rasi_id").val('');
                    $("#family_natchathra_id").val('');
                    $("#family_relationship").val('');
                } else {
                    alert("You can only add up to 4 family members.");
                }
            });
        });

        function removeFamilyMember(count) {
            $("#rmv_family_row"+count).remove();
        }

        function fetchNatchathiram() {
            var rasi = $("#family_rasi_id").val();

            if (rasi != "") {
                $.ajax({
                    url: '<?php echo base_url(); ?>/templeubayam_online/get_natchathram', 
                    type: 'post',
                    data: { rasi_id: rasi },
                    dataType: 'json',
                    success: function(response) {
                        var natchathramDropdown = $("#family_natchathra_id");
                        natchathramDropdown.empty();  // Clear existing options
                        natchathramDropdown.append('<option value="">Select Natchathiram</option>');

                        if (response.natchathra_id) {
                            var str = response.natchathra_id;

                            $.each(str.split(','), function(key, value) {
                                $.ajax({
                                    url: '<?php echo base_url(); ?>/templeubayam_online/get_natchathram_name', 
                                    type: 'post',
                                    data: { id: value },
                                    dataType: 'json',
                                    success: function(natchathraResponse) {
                                        natchathramDropdown.append('<option value="' + natchathraResponse.id + '" data-name="' + natchathraResponse.name_eng + '">' + natchathraResponse.name_eng + '</option>');
                                    }
                                });
                            });
                        }
                    }
                });
            }
        }
    </script>

    <!-- Abishegam Details-->
    <script>
        $("#abishegamDropdown").change(function () {
            var select = $(this);
            var selectedOption = select.find("option:selected");
            var deityId = selectedOption.val();
            var deityName = selectedOption.text();
            var amount = selectedOption.data('amount');

            if (deityId !== "") {
                var isDuplicate = false;
                $("#selectedAbishegamTable input[name^='abishegam']").each(function() {
                    var nameAttr = $(this).attr('name');
                    if (nameAttr && nameAttr.endsWith('[deity_id]')) {
                        if ($(this).val() === deityId) {
                            isDuplicate = true;
                            return false; 
                        }
                    }
                });

                if (isDuplicate) {
                    alert("This deity is already added for Abishegam");
                    select.prop('selectedIndex', 0); 
                    return;
                }

                let cnt = $("#selectedAbishegamTable tr").length;

                var html = '<tr id="rmv_abishegam_row' + cnt + '">';
                html += '<td>' + (cnt + 1) + '</td>'; 
                html += '<td>' + deityName +
                            '<input type="hidden" name="abishegam[' + cnt + '][deity_id]" value="' + deityId + '">' +
                        '</td>';
                 html += '<td>' + parseFloat(amount).toFixed(2) +
                            '<input type="hidden" name="abishegam[' + cnt + '][amount]" class="abishegam_amt" value="' + parseFloat(amount).toFixed(2) + '">' +
                        '</td>';
                html += '<td><a class="btn btn-danger btn-rad" onclick="rmv_abishegam(' + cnt + ')" style="width:auto;padding: 0px 3px !important;"><span>X</span></a></td>';
                html += '</tr>';

                $("#selectedAbishegamTable").append(html);

                select.prop('selectedIndex', 0);
            }
            sum_amount();
        });

        function rmv_abishegam(id) {
            $("#rmv_abishegam_row" + id).remove();
            sum_amount();
        }
    </script>

    <!-- Homam Details-->
    <script>
        $("#homamDropdown").change(function () {
            var select = $(this);
            var selectedOption = select.find("option:selected");
            var deityId = selectedOption.val();
            var deityName = selectedOption.text();
            var amount = selectedOption.data('amount');

            if (deityId !== "") {
                var isDuplicate = false;
                $("#selectedHomamTable input[name^='homam']").each(function() {
                    var nameAttr = $(this).attr('name');
                    if (nameAttr && nameAttr.endsWith('[deity_id]')) {
                        if ($(this).val() === deityId) {
                            isDuplicate = true;
                            return false;
                        }
                    }
                });

                if (isDuplicate) {
                    alert("This deity is already added for Homam");
                    select.prop('selectedIndex', 0);
                    return;
                }

                let cnt = $("#selectedHomamTable tr").length;

                var html = '<tr id="rmv_homam_row' + cnt + '">';
                html += '<td>' + (cnt + 1) + '</td>';
                html += '<td>' + deityName +
                            '<input type="hidden" name="homam[' + cnt + '][deity_id]" value="' + deityId + '">' +
                        '</td>';
                html += '<td>' + parseFloat(amount).toFixed(2) +
                            '<input type="hidden" name="homam[' + cnt + '][amount]" class="homam_amt" value="' + parseFloat(amount).toFixed(2) + '">' +
                        '</td>';
                html += '<td><a class="btn btn-danger btn-rad" onclick="rmv_homam(' + cnt + ')" style="width:auto;padding: 0px 3px !important;"><span>X</span></a></td>';
                html += '</tr>';

                $("#selectedHomamTable").append(html);

                select.prop('selectedIndex', 0);
                sum_amount();
            }
        });


        function rmv_homam(id) {
            $("#rmv_homam_row" + id).remove();
            sum_amount();
        }
    </script>

    <!-- Free Prasadam-->
    <script>
        $("#pack_add_free_prasadam").click(function () {
            var prasadamLimit = 0; // Stores prasadam_count from selected package
            var selectedPrasadamCount = 0;
            var id = $("#add_free_prasadam option:selected").val();
            var cnt = $("#package_table_free_prasadam tbody tr").length;
            var package_id = $('.package_id').val();

            if (id != '') {
                var status_check = 0;
                var rowId;

                $(".package_category_prasadam").each(function () {
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
                        url: "<?php echo base_url(); ?>/templeubayam_online/get_free_prasadam_list",
                        type: "post",
                        data: { id: id, package_id: package_id },
                        success: function (response) {
                            response = JSON.parse(response);
                            if (response.length > 0) {
                                let prasadamCountLimit = response[0].prasadam_count;
                                // Get current prasadam count from table
                                let currentCount = $("#package_table_free_prasadam tbody tr").length;

                                if (currentCount >= prasadamCountLimit) {
                                    alert("You can only add up to " + prasadamCountLimit + " prasadam.");
                                    return;
                                }

                                console.log('response:', response);
                                $.each(response, function (key, value) {
                                    var countid = value.id;
                                    var serviceid = value.id;
                                    var quantity = value.quantity;

                                    get_free_prasadam_name(serviceid, countid);

                                    var html_f = '<tr id="rmv_prasadam_row' + countid + '">';
                                    html_f += '<td style="width: 20%;"><input type="hidden" readonly name="free_prasadam[' + countid + '][id]" value="' + serviceid + '">';
                                    html_f += '<input type="text" style="border: none;width: 100%;" readonly id="free_prasadam_name_' + countid + '"></td>';

                                    // Changed to prasadam-specific controls
                                    html_f += '<td><div class="itemcountrr">';
                                    html_f += '<div class="value-button" onclick="decreasePrasadamValue(' + countid + ')" style="font-weight: bold;font-size: 16px; cursor: pointer;">-</div>';
                                    html_f += '<input type="number" name="free_prasadam[' + countid + '][quantity]" min="1" ' +
                                        'id="prasadam_quantity' + countid + '" value="1" class="prasadam-qty" ' +
                                        'style="text-align: center;border: none;border-top: 1px solid #ddd;' +
                                        'border-bottom: 1px solid #ddd;margin: 0px;width: 35px;height: 25px;" ' +
                                        'max="' + quantity + '" onkeyup="prasadamQtyKeyup(' + countid + ')">';
                                    html_f += '<div class="value-button" onclick="increasePrasadamValue(' + countid + ')" ' +
                                        'style="font-weight: bold;font-size: 16px; cursor: pointer;">+</div>';
                                    html_f += '</div></td>';

                                    html_f += '<td style="width: 10%;">';
                                    html_f += '<a class="btn btn-danger btn-rad" onclick="rmv_prasadam(' + countid + ')" ' +
                                        'style="width:auto;padding: 0px 3px !important;">X</a>';
                                    html_f += '<input type="hidden" class="package_category_prasadam" value="' + id + '">';
                                    html_f += '</td></tr>';

                                    $("#package_table_free_prasadam").append(html_f);
                                });
                            }
                        }
                    });
                    $('#add_free_prasadam').prop('selectedIndex', 0);
                }
            }
        });
        
        function increasePrasadamValue(cnt) {
            var quantity = $("#prasadam_quantity" + cnt);
            var currentVal = parseInt(quantity.val());
            var maxVal = parseInt(quantity.attr('max'));
            
            if (!isNaN(currentVal) && currentVal < maxVal) {
                quantity.val(currentVal + 1);
            }
        }

        function decreasePrasadamValue(cnt) {
            var quantity = $("#prasadam_quantity" + cnt);
            var currentVal = parseInt(quantity.val());
            
            if (!isNaN(currentVal) && currentVal > 1) {
                quantity.val(currentVal - 1);
            }
        }

        function prasadamQtyKeyup(cnt) {
            var quantity = $("#prasadam_quantity" + cnt);
            var currentVal = parseInt(quantity.val());
            var maxVal = parseInt(quantity.attr('max'));
            
            if (!isNaN(currentVal)) {
                if (currentVal > maxVal) quantity.val(maxVal);
                if (currentVal < 1) quantity.val(1);
            } else {
                quantity.val(1);
            }
        }

        function rmv_prasadam(id) {
            $("#rmv_prasadam_row" + id).remove();
        }

        function get_free_prasadam_name(id, cmlp) {
            if (id != '') {
                $.ajax({
                    url: "<?php echo base_url(); ?>/templeubayam_online/get_prasadam_name",
                    type: "post",
                    data: { id: id },
                    dataType: "json",
                    success: function (data) {
                        console.log('name response:', data);
                        $("#free_prasadam_name_" + cmlp).val(data['name']);
                    }
                });
            }
        }

    </script>   

    <!-- Add-on Prasadam-->
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

        $("#prasadam_add").click(function () {
            var id = $("#add_one_prasadam option:selected").val();
            var cnt = parseInt($("#prasadam_row_count").val());
            var amt = $("#get_prasadam_amt").val();

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
                                    var html_p = '<tr id="rmv_packrow_addon' + countid + '">';
                                    html_p += '<td style="width: 20%;"><input type="hidden" readonly name="prasadam[' + countid + '][id]" value="' + serviceid + '"><input type="text" style="border: none;width: 100%;" readonly id="prasadam_name_' + countid + '" data-amount1="' + serviceamount + '"></td>';
                                    html_p += '<td><div class="itemcountrr"><div class="value-button" id="decrease" onclick="decreaseValue1(' + countid + ')" value="Decrease Value" style="font-weight: bold;font-size: 16px; cursor: pointer;">-</div><input type="number" name="prasadam[' + countid + '][quantity]" min="1" id="quantity1' + countid + '" value="1" pattern="[0-9]*" oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" class="qty_amt" style="text-align: center;border: none;border-top: 1px solid #ddd;border-bottom: 1px solid #ddd;margin: 0px;width: 35px;height: 25px;" onkeyup="qtykeyup(' + countid + ')" /><div class="value-button" id="increase" onclick="increaseValue1(' + countid + ')" value="Increase Value" style="font-weight: bold;font-size: 16px; cursor: pointer;">+</div></div></td>';
                                    html_p += '<td style="width: 25%;"><input  type="hidden" style="border: none; width: 100%;" class="package_amt_prasadams"  name="prasadam[' + countid + '][amount]" value="' + Number(serviceamount).toFixed(2) + '" ><input type="text" style="border: none;width: 100%;" class="package_amt_prasadam" id="package_amt_prasadam_' + countid + '" name="prasadam[' + countid + '][total_amount]" value="' + Number(serviceamount).toFixed(2) + '" onkeyup="serviceamount()"></td>';
                                    html_p += '<td style="width: 10%;"><a class="btn btn-danger btn-rad" onclick="rmv_pack_addon(' + countid + ')" style="width:auto;padding: 0px 3px !important;"><i class="material-icons">X</i></a><input type="hidden" class="prasadam_category" value=' + id + '></td>';
                                    html_p += '</tr>';
                                    $("#prasadam_table").append(html_p);
                                });
                                sum_amount();
                            }
                        }
                    });
                    $("#get_prasadam_amt").val('');
                    $('#add_one_prasadam').prop('selectedIndex', 0);
                    // $("#add_one_addon").selectpicker("refresh");
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
                updateAmount(cnt);
            } else {
                quantity.val(1);
            }
            sum_amount();
        }

        function updateAmount1(cnt) {
            var quantity = $("#quantity1" + cnt).val();
            var unitPrice = parseFloat($("#prasadam_name_" + cnt).data('amount1'));
            var totalPrice = quantity * unitPrice;
            $("#package_amt_prasadam_" + cnt).val(Number(totalPrice).toFixed(2));
        }

        function rmv_pack_prasadam1(id) {
            $("#rmv_packrow_prasadam1" + id).remove();
            sum_amount();
        }

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

    <!-- Extra Charges-->
    <script>
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

            sum_amount();

            $("#extra_desc").val('');
            $('#extra_amount').val('');
        });

        function rmv_extra_charges(id){
            $("#rmv_extra_row"+id).remove();
            sum_amount();
        }
    </script>

    <!-- Sum Amount-->
    <script>
        function sum_amount() {
            var total = 0;

            $(".package_amt_addon").each(function () {
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

            $(".abishegam_amt").each(function () {
                var amount = parseFloat($(this).val());
                console.log('abishegam amount:', amount);
                if (!isNaN(amount)) {
                    total += amount;
                }
            });

            $(".homam_amt").each(function () {
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

            var pack_amount = parseFloat($("#pack_amount").val());
            var total_val = total + pack_amount;

            $("#sub_total").val(Number(total_val).toFixed(2));

            var discount_amount = $('#discount_amount').val();
            var max_discount = 0;
            if (discount_amount) {
                discount_amount = Number(discount_amount);
                max_discount = total_val - 1;
                if (max_discount < 0) max_discount = 0;
                if (discount_amount > max_discount) {
                    discount_amount = max_discount;
                    $('#discount_amount').val(discount_amount.toFixed(2))
                }
                total_val = total_val - discount_amount;
            }
            // Update the total amount field with the new total
            $("#total_amt").val(Number(total_val).toFixed(2));
        }
    </script>


    <script>
        function rePrint() {
            $("#myModal_reprint").modal("show");
        }
        var userDetail = (function () {
            user = [];
            // Constructor
            function Item(dt, name, email_id, phonecode, mobile, ic_number, rasi_id, rasi_text, natchathra_id, natchathra_text, address, description) {
                this.dt = dt;
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
            obj.addUserToCart = function (dt, name, email_id, phonecode, mobile, ic_number, rasi_id, rasi_text, natchathra_id, natchathra_text, address, description) {
                var item = new Item(dt, name, email_id, phonecode, mobile, ic_number, rasi_id, rasi_text, natchathra_id, natchathra_text, address, description);
                user.push(item);
                saveUser();
            }
            // clear user
            obj.clearUser = function () {
                user = [];
                saveUser();
            }
            // List user
            obj.listUser = function () {
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
            var ic_number = $('#ic_number').val();
            var address = $('#address').val();
            var rasi_id = $('#rasi_id').val();
            var rasi_text = $("#rasi_id option:selected").text();
            var natchathra_id = $('#natchathra_id').val();
            var natchathra_text = $("#natchathra_id option:selected").text();
            var phonecode = $('#phonecode').val();
            var description = $('#description').val();

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
                userDetail.addUserToCart(dt, name, email_id, phonecode, mobile, ic_number, rasi_id, rasi_text, natchathra_id, natchathra_text, address, description);
                displayCart();
            }

            loadfamilydetails();
        });

        function displayCart() {
            var cartArray = userDetail.listUser();
            if (cartArray.length > 0) {
                //console.log(cartArray);
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
                        <input type="hidden" name="ic_number" value="' + cartArray[0].ic_number + '" />\
                        <input type="hidden" name="mobile_code" value="' + cartArray[0].phonecode + '" />\
                        <input type="hidden" name="rasi_id" value="' + cartArray[0].rasi_id + '" />\
                        <input type="hidden" name="natchathra_id" value="' + cartArray[0].natchathra_id + '" />\
                        <input type="hidden" name="address" value="' + cartArray[0].address + '" />\
                        <input type="hidden" name="description" value="' + cartArray[0].description + '" />';
                $('.show-cart').html(output);
                $('.show-cart').show();
            }
            else {
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
        
        $(document).on('click', '.clear-cart', function () {
            clearCart();
        });

        $('.clear-cart').click(function () {
            userDetail.clearUser();
        });

        function formatDate(dateString) {
            var options = { day: '2-digit', month: '2-digit', year: 'numeric' };
            var date = new Date(dateString);
            return date.toLocaleDateString('en-GB', options);
        }

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
            $.ajax({
                url: "<?php echo base_url() ?>/templeubayam_online/get_payfor_collection",
                type: "POST",
                data: { id: pay_id },
                dataType: "json",
                success: function (data) {
                    var discount_amount = $('#discount_amount').val();
                    var total_amt = Number(data.amt);
                    var sub_total = Number(data.amt);
                    var max_discount = 0;
                    if (discount_amount) {
                        discount_amount = Number(discount_amount);
                        max_discount = total_amt - 1;
                        if (discount_amount > max_discount) {
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
                        data.services.forEach(function (service) {
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
                if (data.free_prasadam == 1) {
                        $('#free_prasadam').show();
                        if (data.prasadam.length > 0) {
                            var b_html = '<option value="">Select From</option>';
                            data.prasadam.forEach(function (value, key) {
                                b_html += '<option value="' + value.id + '">' + value.name_eng + '</option>';
                            });
                        }
                        $('#add_free_prasadam').html(b_html);
                    } else {
                        $('#free_prasadam').hide();
                    }


                    if (data.addons.length > 0) {
                        var a_html = '<option value="">Select From</option>';
                        data.addons.forEach(function (value, key) {
                            a_html += '<option value="' + value.id + '">' + value.name + '</option>';
                        });
                    } else {
                        var a_html = '<option value="">No Addons Found</option>';
                    }
                    $('#add_one_addon').html(a_html);

                    $("#package_table_addon tbody").empty();
                    $("#pack_row_count_addon").val(0);
                    sum_amount();
                }
            });
            if (validatePage(pages[currentPage])) {
                currentPage++;
                showPage(currentPage);
            }
        }

        $(".clear-cart").click(function () {
            $("#package_name").hide();
        });

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

        $(document).ready(function () {
            $("#rasi_id").change(function () {
                var rasi = $("#rasi_id").val();
                if (rasi != "") {
                    $.ajax({
                        url: '<?php echo base_url(); ?>/ubayam_online/get_natchathram',
                        type: 'post',
                        data: { rasi_id: rasi },
                        dataType: 'json',
                        success: function (response) {
                            $('#natchathram_id').val(response.natchathra_id);

                            var str = response.natchathra_id;
                            console.log(str);
    
                            if (str != "") {
                                $("#natchathra_id").empty();

                                $('#natchathra_id').append('<option value="">Select Natchiram</option>');
                                $.each(str.split(','), function (key, value) {
                                    //$('#natchathra_id').append('<option value="' + value + '">' + value + '</option>');
                                    $.ajax({
                                        url: '<?php echo base_url(); ?>/ubayam_online/get_natchathram_name',
                                        type: 'post',
                                        data: { id: value },
                                        dataType: 'json',
                                        success: function (response) {
                                            $('#natchathra_id').append('<option value="' + response.id + '">' + response.name_eng + '</option>');
                                        }
                                    });
                                });
                            }
                        }
                    });
                }
            });
        });

        $("#submit").click(function () {
            var currentDate = new Date();
            var date = currentDate.getFullYear() + '-' + String(currentDate.getMonth() + 1).padStart(2, '0') + '-' + String(currentDate.getDate()).padStart(2, '0');
            var amount = $("#pay_amt").val();
            var paymentMode = $('input[name="payment_mode"]:checked').val();
            console.log('payment mode:', paymentMode);
            var paymentType = $('input[name="payment_type"]:checked').val();

            if (paymentType === 'partial') {
                var cnt = $("#paymentForm input[type='hidden']").length;

                var hiddenInputs = `<input type="hidden" name="payment_details[${cnt}][paid_date]" value="${date}">
                            <input type="hidden" name="payment_details[${cnt}][amount]" value="${Number(amount).toFixed(2)}">
                            <input type="hidden" name="payment_details[${cnt}][payment_mode]" value="${paymentMode}">`;

                $("#form").append(hiddenInputs);
            }

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
            //var email_id = $("#email_id").val();
            var mobile = $("#mobile").val();
            //var rasi_id = $("#rasi_id").val();
            //var natchathra_id = $("#natchathra_id").val();
            var dt = $("#dt").val();
            var total_amt = parseFloat($("#total_amt").val());
            var check_dep = parseFloat((total_amt / 100) * 30).toFixed(2);
            console.log(check_dep);
            // if (pay_for === 0) {
            //     $("input[name='pay_for']").addClass("error-input");
            //     $(".payment li label").addClass("error-input");
            //     $('html, body').animate({
            //         scrollTop: $("input[name='pay_for']").focus().offset().top - 25
            //     }, 500);
            // }
            // else if (total_amt.length === 0 || total_amt == '') {
            //     $("#total_amt").addClass("error-input");
            //     $('html, body').animate({
            //         scrollTop: $("#total_amt").focus().offset().top - 25
            //     }, 500);
            // }

            // else
            if (name == "") {
                //alert("Please enter user details.");
                $("#modalMsg").text('Please enter user details.');
                $('#alertModal').modal();
            }

            else if (mobile == "") {
                //alert("Please enter user details.");
                $("#modalMsg").text('Please enter user details.');
                $('#alertModal').modal();
            } else {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>/ajax/save_booking",
                    data: $("form").serialize(),
                    beforeSend: function () {
                        $("#loader").show();
                    },
                    success: function (data) {
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
                                    window.open("<?php echo base_url(); ?>/templeubayam_online/print_page_ubayam_imin/" + bookingId, '_blank');
                                });

                                $('#print-a4').click(function () {
                                    window.open("<?php echo base_url(); ?>/templeubayam_online/print_page_ubayam/" + bookingId, '_blank');
                                });

                                $('#print-a5').click(function () {
                                    window.open("<?php echo base_url(); ?>/templeubayam_online/print_page_ubayam_a5/" + bookingId, '_blank');
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
                        } else {
                            $('#alert-modal').modal('show', { backdrop: 'static' });
                            $("#spndeddelid").text("An error occurred. Please try again later1");
                            $("#spndeddelid").css("color", "red");
                        }
                    },
                    error: function () {
                        $('#alert-modal').modal('show', { backdrop: 'static' });
                        $("#spndeddelid").text("An error occurred. Please try again later2");
                        $("#spndeddelid").css("color", "red");
                    },
                    complete: function () {
                        $("#loader").hide();
                    }
                });

            }



        });

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
        // MAIN JS FILE START
        (function ($) {
            "use strict";

            $(document).ready(function () {
                var date = new Date();
                var today = date.getDate();
                // Set click handlers for DOM elements
                $(".right-button").click({ date: date }, next_year);
                $(".left-button").click({ date: date }, prev_year);
                $(".month").click({ date: date }, month_click);
                $("#add-button").click({ date: date }, new_event);
                // Set current month as active
                $(".months-row").children().eq(date.getMonth()).addClass("active-month");
                init_calendar(date);
                var events = check_events(today, date.getMonth() + 1, date.getFullYear());
                show_events(events, months[date.getMonth()], today);
            });

            // Initialize the calendar by appending the HTML dates
            function init_calendar(date) {
                $(".tbody").empty();
                $(".events-container").empty();
                var calendar_days = $(".tbody");
                var month = date.getMonth();
                var year = date.getFullYear();
                var day_count = days_in_month(month, year);
                var row = $("<tr class='table-row'></tr>");
                var today = date.getDate();
                // Set date to 1 to find the first day of the month
                date.setDate(1);
                //get_booking_ubhayam(today);
                var first_day = date.getDay();
                // 35+firstDay is the number of date elements to be added to the dates table
                // 35 is from (7 days in a week) * (up to 5 rows of dates in a month)
                for (var i = 0; i < 35 + first_day; i++) {
                    // Since some of the elements will be blank, 
                    // need to calculate actual date from index
                    var day = i - first_day + 1;
                    // If it is a sunday, make a new row
                    if (i % 7 === 0) {
                        calendar_days.append(row);
                        row = $("<tr class='table-row'></tr>");
                    }
                    // if current index isn't a day in this month, make it blank
                    if (i < first_day || day > day_count) {
                        var curr_date = $("<td class='table-date nil'>" + "</td>");
                        row.append(curr_date);
                    }
                    else {
                        var curr_date = $("<td class='table-date'>" + day + "</td>");
                        var events = check_events(day, month + 1, year);
                        if (today === day && $(".active-date").length === 0) {
                            curr_date.addClass("active-date");
                            show_events(events, months[month], day);
                        }
                        // If this date has any events, style it with .event-date
                        if (events.length !== 0) {
                            curr_date.addClass("event-date");
                        }
                        // Set onClick handler for clicking a date
                        curr_date.click({ events: events, month: months[month], day: day, year: year }, date_click);
                        row.append(curr_date);
                    }
                }
                // Append the last row and set the current year
                calendar_days.append(row);
                $(".year").text(year);
            }

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
                // threeDaysAfterToday.setDate(today.getDate() + 3); // Add 3 days to today

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
                var new_year = date.getFullYear() + 1;
                $("year").html(new_year);
                date.setFullYear(new_year);
                init_calendar(date);
            }

            // Event handler for when the year left-button is clicked
            function prev_year(event) {
                $("#dialog").hide(250);
                var date = event.data.date;
                var new_year = date.getFullYear() - 1;
                $("year").html(new_year);
                date.setFullYear(new_year);
                init_calendar(date);
            }

            // Event handler for clicking the new event button
            function new_event(event) {
                // if a date isn't selected then do nothing
                if ($(".active-date").length === 0)
                    return;
                // remove red error input on click
                $("input").click(function () {
                    $(this).removeClass("error-input");
                });
                $(function () {
                    var filterList = {
                        init: function () {
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
                /* $('#dt').val(event_date); */
                $("#ubhayam_date").val(event_date);
                $("#booking_date").val(event_date);
                get_booking_ubhayam(event_date);
                loadbookingslots(event_date);
                // empty inputs and hide events
                $("#dialog input[type=text]").val('');
                $("#dialog input[type=number]").val('');
                $(".events-container").hide(250);
                $("#dialog").show(250);
                // Event handler for cancel button
                $("#cancel-button").click(function () {
                    $("#name").removeClass("error-input");
                    $("#count").removeClass("error-input");
                    $("#dialog").hide(250);
                    $(".events-container").show(250);
                });
                // Event handler for ok button
                $("#ok-button").unbind().click({ date: event.data.date }, function () {
                    var date = event.data.date;
                    var name = $("#name").val().trim();
                    var count = parseInt($("#count").val().trim());
                    var day = parseInt($(".active-date").html());
                    // Basic form validation
                    if (name.length === 0) {
                        $("#name").addClass("error-input");
                    }
                    else if (isNaN(count)) {
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
            
            function loadbookingslots_old(date) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>/templeubayam_online/loadbookingslots",
                    data: { bookeddate: date },
                    success: function (data) {
                        // console.log(data);
                        if (data) {
                            $("#booking_slot").html(data);
                        } else {
                            alert("Selected date is not available, Kindly select various date!");
                            window.location.replace("<?php echo base_url(); ?>/templeubayam_online/ubayam");


                        }
                    }
                });
            }

            function loadbookingslots(date) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>/templeubayam_online/loadbookingslots",
                    data: { bookeddate: date },
                    success: function (data) {
                        console.log(data);
                        var result = JSON.parse(data);
                        if (result.status) {
                            if (result.html != '') {
                                $("#booking_slot").html(result.html);
                            } else {
                                alert("Selected date is not available, Kindly select various date!");
                                window.location.replace("<?php echo base_url(); ?>/templeubayam_online/ubayam");
                            }
                        } else {
                            alert(result.error_msg);
                            window.location.replace("<?php echo base_url(); ?>/templeubayam_online/ubayam");
                        }
                    }
                });
            }
            // Adds a json event to event_data
            function new_event_json(name, count, date, day) {
                var event = {
                    "occasion": name,
                    "invited_count": count,
                    "year": date.getFullYear(),
                    "month": date.getMonth() + 1,
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
                if (events.length === 0) {
                    var event_card = $("<div class='event-card'></div>");
                    var event_name = $("<div class='event-name'>There are no events planned for " + month + " " + day + ".</div>");
                    $(event_card).css({ "border-left": "10px solid #FF1744" });
                    $(event_card).append(event_name);
                    $(".events-container").append(event_card);
                }
                else {
                    // Go through and add each event as a card to the events container
                    for (var i = 0; i < events.length; i++) {
                        var event_card = $("<div class='event-card'></div>");
                        var event_name = $("<div class='event-name'>Event Detail: " + events[i]["event_name"] + "</div>");
                        var event_count = $("<div class='cal_head'><span>Booked By: " + events[i]["name"] + "</span>&nbsp;&nbsp;-&nbsp;&nbsp;<span>Slot: " + events[i]["slot_name"] + "</span></div>");
                        $(event_card).append(event_name).append(event_count);
                        $(".events-container").append(event_card);
                    }
                }
            }

            // Checks if a specific date has any events
            function check_events(day, month, year) {
                var events = [];
                for (var i = 0; i < event_data["events"].length; i++) {
                    var event = event_data["events"][i];
                    if (event["day"] === day &&
                        event["month"] === month &&
                        event["year"] === year) {
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
            var quantity = $("#quantity" + cnt);
            var currentVal = parseInt(quantity.val());
            if (!isNaN(currentVal)) {
                if ((currentVal + 1) > quantity.attr('max')) quantity.val(quantity.attr('max'));
                else quantity.val(currentVal + 1);
                updateAmount(cnt);
            } else {
                quantity.val(1);
            }
            sum_amount();
        }

        function decreaseValue(cnt) {
            console.log("decreaseValue called with cnt: " + cnt);
            var quantity = $("#quantity" + cnt);
            var currentVal = parseInt(quantity.val());
            if (!isNaN(currentVal) && currentVal > 1) {
                quantity.val(currentVal - 1);
                updateAmount(cnt);
            } else {
                quantity.val(1);
            }
            sum_amount();
        }

        function qtykeyup(cnt) {
            var quantity = $("#quantity" + cnt);
            var currentVal = parseInt(quantity.val());
            console.log(currentVal);
            console.log();
            if (!isNaN(currentVal) && currentVal >= 0) {
                if (currentVal > quantity.attr('max')) quantity.val(quantity.attr('max'));
                updateAmount(cnt);
            } else {
                quantity.val(1);
            }
            sum_amount();
        }

        function updateAmount(cnt) {
            console.log("updateAmount called with cnt: " + cnt);
            var quantity = $("#quantity" + cnt).val();
            var unitPrice = parseFloat($("#service_name_addon_" + cnt).data('amount'));
            var totalPrice = quantity * unitPrice;
            $("#package_amt_addon_" + cnt).val(Number(totalPrice).toFixed(2));
        }

        function rmv_pack_addon(id) {
            console.log("rmv_pack_addon called with id: " + id);
            $("#rmv_packrow_addon" + id).remove();
            sum_amount();
        }

        function serviceamount() {
            sum_amount();
        }

        function get_service_name_addon(id, cmlp) {
            console.log("get_service_name_addon called with id: " + id + " and cmlp: " + cmlp);
            if (id != '') {
                $.ajax({
                    url: "<?php echo base_url(); ?>/templeubayam_online/get_service_name_addon",
                    type: "post",
                    data: { id: id },
                    dataType: "json",
                    success: function (data) {
                        $("#service_name_addon_" + cmlp).val(data['name']);
                        $("#service_description_addon_" + cmlp).val(data['description']);
                    }
                });
            }
        }
        $('#discount_amount').on('blur change', function () {
            sum_amount();
        });
        $("#add_one_addon").change(function () {
            var id = $("#add_one_addon").val();
            if (id != '') {
                $.ajax({
                    url: "<?php echo base_url(); ?>/templeubayam_online/getpack_amt_addon",
                    type: "post",
                    data: { id: id },
                    dataType: "json",
                    success: function (data) {
                        console.log(data)
                        $("#get_pack_amt_addon").val(Number(data['amt']).toFixed(2));
                        $("#pack_name_addon").val(data['name']);
                    }
                });
            } else {
                $("#get_pack_amt_addon").val(0);
            }
            sum_amount();
        });
        $("#pack_add_addon").click(function () {
            var id = $("#add_one_addon option:selected").val();
            var cnt = parseInt($("#pack_row_count_addon").val());
            var amt = $("#get_pack_amt_addon").val();
            var package_id = $('.package_id').val();

            if (id != '' && parseFloat(amt) > 0) {
                var status_check = 0;
                var rowId;

                $(".package_category_addon").each(function () {
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
                    $('#add_one_addon').prop('selectedIndex', 0);
                    // $("#add_one_addon").selectpicker("refresh");
                } else {
                    $.ajax({
                        url: "<?php echo base_url(); ?>/templeubayam_online/get_service_list_addon",
                        type: "post",
                        data: { id: id, package_id: package_id },
                        success: function (response) {
                            response = JSON.parse(response);
                            if (response.length > 0) {
                                $.each(response, function (key, value) {
                                    var countid = value.id;
                                    var serviceid = value.id;
                                    var quantity = value.quantity;
                                    var serviceamount = value.amount;
                                    get_service_name_addon(serviceid, countid);
                                    var html = '<tr id="rmv_packrow_addon' + countid + '">';
                                    html += '<td style="width: 20%;"><input type="hidden" readonly name="add_on[' + countid + '][id]" value="' + serviceid + '"><input type="text" style="border: none;width: 100%;" readonly id="service_name_addon_' + countid + '" data-amount="' + serviceamount + '"></td>';
                                    html += '<td style="width: 45%;"><input type="text" style="border: none;width: 100%;" id="service_description_addon_' + countid + '"></td>';
                                    html += '<td><div class="itemcountrr"><div class="value-button" id="decrease" onclick="decreaseValue(' + countid + ')" value="Decrease Value" style="font-weight: bold;font-size: 16px; cursor: pointer;">-</div><input type="number" name="add_on[' + countid + '][quantity]" min="1" id="quantity' + countid + '" value="1" pattern="[0-9]*" oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" class="qty_amt" style="text-align: center;border: none;border-top: 1px solid #ddd;border-bottom: 1px solid #ddd;margin: 0px;width: 35px;height: 25px;" max="' + quantity + '" onkeyup="qtykeyup(' + countid + ')" /><div class="value-button" id="increase" onclick="increaseValue(' + countid + ')" value="Increase Value" style="font-weight: bold;font-size: 16px; cursor: pointer;">+</div></div></td>';
                                    // html += '<td style="width: 25%;"></td>';
                                    html += '<td style="width: 25%;"><input  type="hidden" style="border: none;width: 100%;" class="package_amt_addons"  name="add_on[' + countid + '][amount]" value="' + Number(serviceamount).toFixed(2) + '" ><input type="text" style="border: none;width: 100%;" class="package_amt_addon" id="package_amt_addon_' + countid + '" name="add_on[' + countid + '][total_amount]" value="' + Number(serviceamount).toFixed(2) + '" onkeyup="serviceamount()"></td>';
                                    html += '<td style="width: 10%;"><a class="btn btn-danger btn-rad" onclick="rmv_pack_addon(' + countid + ')" style="width:auto;padding: 1px 6px !important;height: auto;font-size: 14px;color: #FFF;">X</a><input type="hidden" class="package_category_addon" value=' + id + '></td>';
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

    
        $(document).on('click', '.booking_slot', function () {
            let slotId = this.value;
            let packageType = 2;

            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>/ajax/getDeitiesBySlot",
                data: {
                    package_type: packageType,
                    slot_id: slotId
                },
                dataType: 'json',
                beforeSend: function () {
                    // Optional: You can add a loader here if necessary
                },
                success: function (data) {
                    console.log(data); // Log data for debugging

                    var deityHtml = '';

                    // Check if deity data is available
                    if (data.success && data.deities.length > 0) {
                        data.deities.forEach(function (deity) {
                            deityHtml += '<div class="col-xl-3 col-sm-6 col-lg-3 col-md-4 stretch-card portfolio" data-cat="">';
                            deityHtml += '<input type="radio" class="deity_slot" name="deity_id" value="' + deity.id + '" id="deity_id' + deity.id + '" />';
                            deityHtml += '<label class="card dashed" for="deity_id' + deity.id + '">';
                            deityHtml += '<div class="d-flex justify-content-between align-items-center mb-2 mt-2" style="flex-direction: column;">';
                            deityHtml += '<p class="text-muted arch1" id="deity_name' + deity.id + '">' + deity.name + '</p>';
                            deityHtml += '</div></label></div>';
                        });
                    } else {
                        deityHtml = '<p>No Deities Found</p>';
                        alert("No deities available for this slot. Please choose another slot.");
                    }

                    // Update the deity container with the generated HTML
                    $('#deity').html(deityHtml);
                },
                error: function () {
                    alert("Failed to fetch deity data. Please try again.");
                },
                complete: function () {
                    // Optional: Hide loader here if you used one
                }
            });
        });

        $(document).on('change', 'input[name="deity_id"]', function () {
            // var booking_slot = this.value;
            var booking_slot = $('input[name="booking_slot[]"]:checked').val();
            var booking_date = $('#ubhayam_date').val();
            var deity_id = $('input[name="deity_id"]:checked').val();
            // alert(deity_id);
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>/ajax/get_packages_list",
                data: { slot_id: booking_slot, booking_date: booking_date, deity_id: deity_id, package_type: 2 },
                dataType: 'json',
                beforeSend: function () {
                    // $("#loader").show();
                },
                success: function (data) {
                    console.log(data);
                    if (data.success) {
                        // Generate HTML for packages
                        var packageHtml = '';
                        if (data.data.packages.length > 0) {
                            data.data.packages.forEach(function (value, key) {
                                packageHtml += '<div class="col-xl-3 col-sm-6 col-lg-3 col-md-4 grid-margin stretch-card portfolio" data-cat="">';
                                packageHtml += '<input type="radio" class="ubayam_slot" name="pay_for" value="' + value.id + '" id="pay_for' + value.id + '" onclick="createHiddenInput(' + value.id + ')"/>';
                                packageHtml += '<label class="card" for="pay_for' + value.id + '" onclick="payfor(' + value.id + ')">';
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
                        if (data.data.addons.length > 0) {
                            data.data.addons.forEach(function (value, key) {
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
                error: function () {
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
                complete: function () {
                    // $("#loader").hide();
                }
            });
            if (validatePage(pages[currentPage])) {
                currentPage++;
                showPage(currentPage);
            }


        });

        function createHiddenInput(packageId) {
            // Remove any existing hidden input
            $('input[name^="packages["]').remove();

            // Create a new hidden input with the selected package ID
            var hiddenInput = '<input type="hidden" name="packages[' + packageId + '][id]" class="package_id" value="' + packageId + '">';
            $('form').append(hiddenInput);
        }

        $('#termsLabel').on('click', function (event) {
            event.preventDefault();
            var name = $("#name").val();
            var ic_number = $("#ic_number").val();

            if (name && ic_number) {
                // Both name and IC number are provided
                $.ajax({
                    url: '<?php echo base_url(); ?>/templeubayam_online/get_terms',
                    method: 'POST',
                    data: { name: name, ic_number: ic_number },
                    success: function (response) {
                        if (response.success) {
                            $('.modal-body-terms').html(response.terms);
                            $('#termsModal').modal('show');
                        } else {
                            alert('Failed to load terms. Please try again.');
                        }
                    },
                    error: function () {
                        alert('An error occurred. Please try again.');
                    }
                });
            } else {
                alert('Please enter both name and IC number.');
            }
        });
    </script>

    <script>
        $(document).on('keypress', function (e) {
            if (e.which === 13) { // Check for Enter key
                e.preventDefault(); // Prevent the default behavior (form submission)
            }
        });
    </script>
</body>

</html>