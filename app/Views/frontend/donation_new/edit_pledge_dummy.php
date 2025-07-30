<?php $booking_calendar_range_year = booking_calendar_range_year($_SESSION['booking_range_year_frend']); ?>
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/typicons/typicons.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/mdi/css/materialdesignicons.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/css/vertical-layout-light/style.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/style.css">
<link rel="shortcut icon" href="<?php echo base_url(); ?>/assets/archanai/images/favicon.png" />
<style>
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

    #error_msg,
    .form_error {
        color: red;
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
            width: 25% !important;
        }

        .payment1 li label {
            padding: 15px 1px;
            margin: 15px 5px;
        }
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
  width:20%;
  padding:10px;
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
                            <form class="form" id="form" method="post">
                                <input type="hidden" name="date" id="date" value="<?php echo date("Y-m-d"); ?>" max="<?php echo $booking_calendar_range_year; ?>">
                                 <input type="hidden" name="print_type" id="print_type" value="<?php echo $setting['print_method']; ?>">
                                <div class="form-container card-body" align="center">
                                    <div class="container-fluid">
                                        <div class="row">

                                            <div class="col-md-8">
                                                <h4
                                                    style="margin-bottom:5px; margin-top:5px; color:#FFFFFF; background:#d4aa00;">
                                                    Choose Any Donation for Pay</h4>
                                               <ul id="filters" class="clearfix prod1">
									  <?php
                                    foreach ($sett_don as $key => $value) {
                                        if (!empty($value)) {
                                            ?>
                                            <li>
                                                <span class="filter" data-filter=".<?php echo str_replace(' ', '_', strtolower($key)); ?>">
                                                    <?php echo strtoupper($key); ?>
                                                </span>
                                            </li>
                                        <?php
                                        }
                                    }
                                    ?>

                                   </ul>
                                   
                                  
                                   <div class="row prod product" id="portfoliolist">
									<?php
                                    foreach ($sett_don as $key => $value) {
                                      if (!empty($value)) {
                                        foreach ($value as $row) {
                                          ?>
                                          <div
                                            class="portfolio <?php if (!empty($key)) {
                                              echo str_replace(' ', '_', strtolower($key));
                                            } ?>"
                                            data-cat="<?php if (!empty($key)) {
                                              echo str_replace(' ', '_', strtolower($key));
                                            } ?>">
                                              <input type="radio" class="donation_slot" name="pay_for" value="<?php echo $row['id']; ?>" id="pay_for<?php echo $row['id']; ?>" />
                                              <label class="card" for="pay_for<?php echo $row['id']; ?>" onclick="payfor(<?php echo $row['id']; ?>)" >
                                              <img class="img-fluid prod_img" src="<?php echo base_url(); ?>/uploads/donation/<?php echo $row['image']; ?>">
                                              <div class="d-flex justify-content-between align-items-center mb-2 mt-2" style="flex-direction: column;">
                                                <p class="mb-0 text-muted arch" id="pay_name<?php echo $row['id']; ?>"><?php echo $row['name']; ?></p>
                                              </div>
                                              </label>
                                          </div>
                                        <?php
                                        }
                                      }
                                    }
                                    ?>
                                  </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="row">
                                                    <div class="col-md-4" style="text-align:left;">
                                                        <p style=" margin-top:10px;">
                                                            <button type="button" class="btn btn-danger btn-lg ar_btn"
                                                                onClick="rePrint();"
                                                                style="background: #f44336;border: 1px solid #f44336;color: #fff;">Reprint</button>
                                                        </p>
                                                    </div>
                                                     <div class="col-md-4" style="text-align:center;">
                                                         <p style="margin-top:10px;">
                                                             <button type="button" class="btn ar_btn1 btn-danger btn-lg" id="delete-button">CLEAR ALL</button>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-4" style="text-align:right;">
                                                        <p style="text-align:right; margin-top:10px;"><button
                                                                type="button" class="btn ar_btn btn-info btn-lg"
                                                                onClick="userModalOpen();">Add Detail</button></p>
                                                    </div>
                                                </div>

                                                <table class="show-cart table table-bordered"
                                                    style="width:100%;display:none;"></table>

                                                <h4
                                                    style="margin-bottom:5px; margin-top:5px; color:#FFFFFF; background:#d4aa00;">
                                                    Amount Pay for Donation</h4>
                                                <input type="number" step="0.1" name="total_amount" id="total_amount"
                                                    class="form-control" step=".01" value="0.00"
                                                    style="margin-top:20px;font-weight:bold;font-size: 36px;text-align: center;">

                                                <ul class="payment1">
                                                <?php foreach ($payment_mode as $key => $pay) { ?>
                                                    <li>
                                                        <input type="radio" class="pay_method_class" name="pay_method" id="cb<?php echo $pay['id']; ?>" value="<?php echo $pay['id']; ?>" data-name="<?php echo $pay['name']; ?>" />
                                                        <label for="cb<?php echo $pay['id']; ?>">
                                                            <?php echo $pay['name']; ?>
                                                        </label>
                                                    </li>
                                                <?php } ?>
                                                    <!-- <li><input type="radio" name="pay_method" id="cb1" value="cash" />
                                                        <label for="cb1"><i class="mdi mdi-square-inc-cash"></i>
                                                            Cash</label>
                                                    </li>
                                                    <li><input type="radio" name="pay_method" id="cb3" value="qr" />
                                                        <label for="cb3"><i class="mdi mdi-qrcode"></i> QR Code</label>
                                                    </li>
                                                    <li><input type="radio" name="pay_method" id="cb4" value="card" />
                                                        <label for="cb4"><i class="mdi mdi-credit-card-multiple"></i> Card</label>
                                                    </li>
                                                    <li><input type="radio" name="pay_method" id="cb3" value="ipay_merch_online" />
                                                        <label for="cb3"><i class="mdi mdi-web"></i> Online</label>
                                                    </li> -->
                                                </ul>

                                                <input type="button" value="OK" class="button button-white greensubmit"
                                                    id="submit">
                                                <input type="button" onclick="pledge_btn()" value="Pludge" class="button button-white greensubmit"
                                                    id="pludge_bt">


                                            </div>

                                        </div>
                                    </div>

                                </div>
                                
                                 <div id="myModalPledge" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                                                                <label class="form-label" for="name">Name<span
                                                                        style="color:red;">*</span></label>
                                                                <input class="form-control" type="text" id="name"
                                                                    name="name" autocomplete="off">
                                                                <span id="error_msg"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="email_id">Email
                                                                    Address</label>
                                                                <input class="form-control" type="email" id="email_id"
                                                                    name="email_id" autocomplete="off">
                                                                <!-- <span id="error_msg"></span><span class="form_error" id="invalid_email">This email is not valid</span> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="ic_number">Ic No /
                                                                    Passport No</label>
                                                                <input class="form-control" type="text" id="ic_number"
                                                                    name="ic_number" autocomplete="off">
                                                                <!-- <span id="error_msg"></span> -->
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="mobile">Mobile No<span
                                                                        style="color:red;">*</span></label>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <select class="form-control" name="phonecode"
                                                                            id="phonecode">
                                                                            <option value="">Dialing code</option>
                                                                            <?php
                                                                            if (!empty ($phone_codes)) {
                                                                                foreach ($phone_codes as $phone_code) {
                                                                                    ?>
                                                                                    <option
                                                                                        value="<?php echo $phone_code['dailing_code']; ?>"
                                                                                        <?php if ($phone_code['dailing_code'] == "+60") {
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
                                                                        <input class="form-control" type="number"
                                                                            id="mobile" name="mobile" min="0"
                                                                            autocomplete="off">
                                                                        <span id="error_msg"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="address">Address</label>
                                                                <textarea class="form-control" id="address"
                                                                    name="address" style="width:100%;"
                                                                    rows="2">  </textarea>
                                                                <!-- <span id="error_msg"></span> -->
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label"
                                                                    for="description">Remarks</label>
                                                                <textarea class="form-control" id="description"
                                                                    name="description" style="width:100%;" rows="2"
                                                                    autocomplete="off"></textarea>
                                                                <!-- <span id="error_msg"></span> -->
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label"
                                                                    for="description">
                                                                    <div style="float:left"><input type="checkbox" style="width:14px;height:14px" class="form-control" id="is_pledge"
                                                                    name="is_pledge" onclick="getPledgeAmt()" value="1"
                                                                    />
                                                                    </div>
                                                                    <div style="float:left;margin-left:6px">
                                                                    Is Pledge</div></label>
                                                                
                                                                <!-- <span id="error_msg"></span> -->
                                                            </div>
                                                        </div>
                                                        <div id="pledge_amt_div" style="display:none" class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="name">Pledge Amount<span
                                                                        style="color:red;">*</span></label>
                                                                <input class="form-control" type="number" id="pledge_amount"
                                                                    name="pledge_amount" autocomplete="off">
                                                                <span id="error_msg"></span>
                                                            </div>
                                                        </div>
                                                        
                                                        
                                                    </div>
                                                    <button type="button" onclick="pledge_add_btn_sbmt()" name="pledge_add_btn" id="pledge_add_btn"
                                                        class="btn btn-info my-3"
                                                        style="width:100%; font-size:24px; height:auto;margin-bottom: 0 !important;">Submit</button>
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
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <div id="alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <p style="text-align:center;"><br><i class="mdi mdi-alert-circle-outline"
                            style="font-size:42px; color:red;"></i></p>
                    <h5 style="text-align:center;" id="spndeddelid"></h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">OK</button>
                </div>
            </div><!-- /.modal-content -->
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
                                                        <?php echo $reprintlist['amount']; ?>
                                                    </td>
                                                    <td style="width: 10%;padding: 5px 0px!important;text-align:center;">
                                                        <a class='btn btn-primary'
                                                            style='font-size: 13px;font-weight: bold;padding: 6px 10px;background: #2196F3;border: 1px solid #2196F3;'
                                                            title='Print'
                                                            href='<?php echo base_url(); ?>/donation_online/reprint_booking/<?php echo $reprintlist['id']; ?>'
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
		<div id="qr_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		  <div class="modal-dialog modal-md">
			<div class="modal-content p-4">
			  <div class="text-center">
				<h4><img src="<?php echo base_url(); ?>/assets/archanai/images/duitnow.png" alt="DuitNow" style="height:24px; vertical-align:middle;"> <b>DuitNow QR Payment</b></h4>
				<div id="payment_timer" style="font-size: 24px; color: #cc0000; margin: 10px 0;">02:00</div>
				<div style="color: #cc0000; font-weight: bold; ">Payment Time Out</div>
				<img src="" class="qr_image" style="width: 200px; margin: 10px auto; display: block;" />
				<h5 style="margin-top: 10px; color: #006400; font-weight: bold;"> <p class="mb-0"> Total Amount: RM : <span class="total-cart"></span></p>
				<input type="hidden" id="tot_amt" name="tot_amt"></h5>
				<p style="font-weight: bold;">Please scan the QR Code</p>
				<button type="button" class="btn btn-danger" id="cancel_payment_btn">Cancel</button>
			  </div>
			</div>
		  </div>
		</div>
        <!-- REPRINT SECTION END -->
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
        <script src="<?php echo base_url(); ?>/assets/archanai/js/dashboard.js"></script>
         <script  src="<?php echo base_url(); ?>/assets/archanai/script.js"></script>

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
                height: 350px;
                overflow: auto;
            }

            .payment li {
                display: inline-block;
                width: 20%;
            }

            input[type="radio"][id^="pay_for"] {
                display: none;
            }

            input[type="radio"] {
                display: none;
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
                background: #d8f7f7;
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
                background: #f6ef08 !important;
                transition-duration: 0.4s;
            }

            :checked+label:before {
                content: "✓";
                background-color: green;
                transform: scale(1);
            }

            :checked+i.mdi {
                transform: scale(0.9);
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
                background: #f6ef08 !important;
                transition-duration: 0.4s;
            }

            :checked+label:before {
                content: "✓";
                background-color: green;
                transform: scale(1);
            }

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
                font-size: 14px;
                text-align: center;
                padding: 10px;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
                text-overflow: ellipsis;
                max-height: 50px;
                min-height: 50px;
                text-transform: uppercase;
            }

            .ar_btn {
                background: linear-gradient(179deg, rgb(0 126 212) 0%, rgb(16 197 180) 35%, rgb(59 134 209) 100%);
                border-radius: 15px;
                font-weight: bold;
                height: 1.75em;
            }
.ar_btn1 {
   background: linear-gradient(179deg, rgb(255 5 38) 0%, rgb(197 16 16) 35%, rgb(255 0 0) 100%);
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
        </style>
        <link href="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/css/bootstrap-select.css"
            rel="stylesheet" />
        <script src="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>
        <script>
function plotUpdate(pledge_data,don_data)
{
    var customer_data = ["name","phone_code","mobile","email_id","ic_or_passport","address","description","pledge_amount"];
    var donation_data = ["pay_for","payment_mode","total_amount"];
    var pledge_type = pledge_data["pledge_type"]|0;
    $(".donation_slot").each((i,ele)=>{
        if(pledge_type == ele.value)
        {
            $(ele).prop("checked",true);
            return false;
        }
    })
    
    var inp = {};
    var customer_det = {};
    $(pledge_data).each((i,ele)=>{
        $(customer_data).each((j,ele1)=>{
            if(ele1 == "ic_or_passport")
                customer_det[ele1] = parseFloat(ele[ele1]);
            else
                customer_det[ele1] = ele[ele1];
        })
        customer_det["pledge_type"] = ele["pledge_type"];
    });
    
    userDetail.addUserToCart(customer_det["name"], customer_det["email_id"], customer_det["phone_code"], customer_det["mobile"], customer_det["ic_or_passport"], customer_det["address"], customer_det["description"],1,customer_det["pledge_amount"]);
    
    $(don_data).each((i,ele)=>{
        $(donation_data).each((j,ele1)=>{

            if(ele1 == "payment_mode")
            {
                $(".pay_method_class").each((k,ele2)=>{
                    if(ele2.value == ele[ele1])
                    {
                        $(ele2).prop("checked",true);
                        return false;
                    }
                })
            }
            else if(ele1 == "total_amount")
            {
                $("#"+ele1).val(parseFloat(ele["target_amount"]));
                
            }
            else
                $("#"+ele1).val(ele[ele1]);
            //inp[ele1] = ele[ele1];
        })
    })
    
    
}
$(document).ready(function() {
    var pledge_data = JSON.parse('<?php echo json_encode($pledge_data,true); ?>');
    var don_data = JSON.parse('<?php echo json_encode($don_data,true); ?>');
    
    plotUpdate(pledge_data,don_data);
    console.log(pledge_data,don_data,"tt");
    // Handle the delete button click event
    $('#delete-button').click(function() {
        // Clear user details from sessionStorage
        userDetail.clearUser();
        // Clear the table contents
        $('.show-cart').empty().hide();
    });
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
function pledge_btn()
{
    
    var cartArray = userDetail.listUser();
    //console.log(cartArray);
    
    if("undefined" == typeof cartArray || "undefined" == typeof cartArray[0] || cartArray[0].name=="" || cartArray[0].mobile=="")
    {
        alert("Customer information must be required");
        return;
    }
    var pledgeAmt = parseFloat(cartArray[0].pledge_amount);
    if(pledgeAmt == 0 || cartArray[0].is_pledge==0)
    {
        alert("Pledge amount must be required");
        return;
    }
    
    btn_sub('pledge');
}
function getPledgeAmt()
{
    if($("#is_pledge").is(":checked"))
    {
        $("#pledge_amt_div").show();
    }
    else
        $("#pledge_amt_div").hide();
        
}
            function rePrint() {
                $("#myModal_reprint").modal("show");
            }
            $('#total_amount').click(function () {
                $(this).val('');
            });
            var userDetail = (function () {
                user = [];
                // Constructor
                function Item(name, email_id, phonecode, mobile, ic_number, address, description,is_pledge,pledge_amount) {
                    this.name = name;
                    this.email_id = email_id;
                    this.phonecode = phonecode;
                    this.mobile = mobile;
                    this.ic_number = ic_number;
                    this.address = address;
                    this.description = description;
                    this.pledge_amount = pledge_amount;
                    this.is_pledge = is_pledge;
                }
                // Save user
                function saveUser() {
                    sessionStorage.setItem('donation_userdetails', JSON.stringify(user));
                }
                // Load user
                function loadUser() {
                    user = JSON.parse(sessionStorage.getItem('donation_userdetails'));
                }
                if (sessionStorage.getItem("donation_userdetails") != null) {
                    loadUser();
                }
                var obj = {};
                // Add to user
                obj.addUserToCart = function (name, email_id, phonecode, mobile, ic_number, address, description,is_pledge,pledge_amount) {
                    var item = new Item(name, email_id, phonecode, mobile, ic_number, address, description,is_pledge,pledge_amount);
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
            function pledge_add_btn_sbmt()
            {
                userDetail.clearUser();
                event.preventDefault();

                // Get values from form
                var name = $('#name').val();
                var email_id = $('#email_id').val();
                var ic_number = $('#ic_number').val();
                var address = $('#address').val();
                var phonecode = $('#phonecode').val();
                var mobile = $('#mobile').val();
                var description = $('#description').val();
                var pledge_amount = $('#pledge_amount').val();
                var is_pledge = $("#is_pledge").is(":checked")?1:0;
                // Validate required fields
                if (name == "") {
                    $('#name').siblings('#error_msg').text('Name is required').css('color', 'red');;
                } else {
                    $('#name').siblings('#error_msg').text('').css('color', 'red');;
                }


                if (mobile == "") {
                    $('#mobile').siblings('#error_msg').text('Mobile No is required').css('color', 'red');;
                } else {
                    $('#mobile').siblings('#error_msg').text('').css('color', 'red');;
                }
                
                if (pledge_amount == "") {
                    $('#pledge_amount').siblings('#error_msg').text('Pledge Amount is required').css('color', 'red');;
                } else {
                    $('#pledge_amount').siblings('#error_msg').text('').css('color', 'red');;
                }
                // $('.form-control').each(function () {
                //     if ($(this).val() == "") {
                //         $(this).siblings('#error_msg').text('Field needs to Fill');
                //     } else {
                //         $(this).siblings('#error_msg').text('');
                //     }
                // });

                // if (email_id != "") {
                //     if (IsEmail(email_id) == false) {
                //         $('#invalid_email').show();
                //         return false;
                //     }
                // }

                if (!name || !mobile || !pledge_amount) {
                    console.log(name,mobile,pledge_amount,"yy");
                    return;
                }
                $("#myModalPledge").modal("hide");
                userDetail.addUserToCart(name, email_id, phonecode, mobile, ic_number, address, description,is_pledge,pledge_amount);
                displayCart();
            }
            function userModalOpen(typ='submit')
            {
                if(typ!="submit")
                    $("#myModalPledge").modal("show");
                    
                var cartArray = userDetail.listUser();
                if (cartArray.length > 0) {
                    $('#name').val(cartArray[0].name);
                    $('#email_id').val(cartArray[0].email_id);
                    $('#ic_number').val(cartArray[0].ic_number);
                    $('#phonecode').val(cartArray[0].phonecode);
                    $('#mobile').val(cartArray[0].mobile);
                    $('#address').val(cartArray[0].address);
                    $('#description').val(cartArray[0].description);
                    
                    if((cartArray[0].is_pledge)==1)
                    {
                        $("#is_pledge").prop("checked",true);
                        getPledgeAmt();
                    }
                    else
                        $("#is_pledge").removeProp("checked");
                        
                    $('#pledge_amount').val(cartArray[0].pledge_amount);
                    
                    $('.show-cart').show();
                }
                else {
                    /*
                    $('#name').val("");
                    $('#email_id').val("");
                    $('#ic_number').val("");
                    $('#phonecode').val("+60");
                    $('#mobile').val("");
                    $('#address').val("");
                    $('#description').val("");
                    $('#pledge_amount').val("");
                    $('.show-cart').empty();
                    $('.show-cart').hide();
                    $("#is_pledge").removeProp("checked");
                    */
                }
            }


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
                    $('.show-cart').html(output);
                    $('.show-cart').show();
                }
                else {
                    /*
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
                    */
                }
                //alert(cartArray.length);
            }
            displayCart();
/*function payfor(pay_id)
{
    $.ajax({
        type:"POST",
        url: "<?php echo base_url(); ?>/donation_online/get_donation_amount",
            data: { id: pay_id },
            success: function(data) {
                $('#total_amount').val(data);
                $("#pay_for" + pay_id).prop("checked", true);
                $("input[name='pay_for']").removeClass("error-input");
                $(".payment li label").removeClass("error-input");
            }
    });
}*/
            function btn_sub(typ)
            {
                userModalOpen("submit");
                var total_amt = parseFloat($("#total_amount").val());
                var pay_for = $('.donation_slot').filter(':checked').length;
                
                var name = $("#name").val();
                var email_id = $("#email_id").val();
                var mobile = $("#mobile").val();
                //console.log("tt",pay_for,name,mobile,$("form").serialize());
                window.print_type = $("#print_type").val();
                if (pay_for === 0) {
                    $("input[name='pay_for']").addClass("error-input");
                    $(".payment li label").addClass("error-input");
                    $('html, body').animate({
                        scrollTop: $("input[name='pay_for']").focus().offset().top - 25
                    }, 500);
                }
                else if (total_amt.length === 0 || total_amt == '') {
                    $("#total_amount").addClass("error-input");
                    $('html, body').animate({
                        scrollTop: $("#total_amount").focus().offset().top - 25
                    }, 500);
                } else if (name.length === 0 || name == '') {
                    $("#name").addClass("error-input");
                    $('html, body').animate({
                        scrollTop: $("#name").focus().offset().top - 25
                    }, 500);
                } else if (mobile.length === 0 || mobile == '') {
                    $("#mobile").addClass("error-input");
                    $('html, body').animate({
                        scrollTop: $("#mobile").focus().offset().top - 25
                    }, 500);
                }
                else {
                    $.ajax
                        ({
                            type: "POST",
                            url: "<?php echo base_url(); ?>/donation_online/pledge_post_update",
                            data: $("form").serialize(),
                            beforeSend: function () {
                                $("#loader").show();
                            },
               
             success: function (data) {
                                //obj = jQuery.parseJSON(data);
                                //location.reload();
                                obj = jQuery.parseJSON(data);
                                if (obj.err != '') {
                                    $('#alert-modal').modal('show', { backdrop: 'static' });
                                    $("#spndeddelid").text(obj.err);
                                } else {
									if(obj.pay_status){
									    /*
										userDetail.clearUser();
										if (window.print_type == 'imin') {
											window.open("<?php echo base_url(); ?>/donation_online/print_booking/" + obj.id, "_blank", "width=680,height=500");
										} else {
											window.open("<?php echo base_url(); ?>/donation_online/print_report_a5/" + obj.id, "_blank", "width=680,height=500");
										}
										window.location.reload(true);
										*/
									}else{
										if(obj.payment_key == 'rhb_qr'){
											window.booking_id = obj.id;
											showQRPaymentModal(obj.qr_code, obj.total_amount);
											setTimeout(function(){
												repeat_load(obj.id);
											}, 5000);
										}
									} 
                                }
                            },
                            complete: function (data) {
                                // Hide image container
                                $("#loader").hide();
                            }
                        });
                }
            }
            $("#submit").click(function () {
                btn_sub('sub');
            });
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
		window.load_no = 1;
		function cancel_booking(booking_id){
			$.ajax({
				type:"POST",
				url: "<?php echo base_url(); ?>/donation_online/cancel_booking",
				data: {donation_booking_id: booking_id},
				beforeSend: function() {
					$('#qr_modal').modal('hide');
					$("#loader").show();
				},
				success:function(data){
					console.log(data);
					obj = jQuery.parseJSON(data);
					$("#submit, #submit_sep").prop('disabled', false);
					$('#loader').hide();
					if(obj.pay_status){
						userDetail.clearUser();
						if (window.print_type == 'imin') {
							window.open("<?php echo base_url(); ?>/donation_online/print_booking/" + booking_id, "_blank", "width=680,height=500");
						} else {
							window.open("<?php echo base_url(); ?>/donation_online/print_report_a5/" + booking_id, "_blank", "width=680,height=500");
						}
						window.location.reload(true);
					}else{
						$('#alert-modal').modal('show', {backdrop: 'static'});
						$("#spndeddelid").text('Payment Failed. Kindly try again.');
					}
				},
				error:function(err)
				{
					console.log('err');
					console.log(err);
					$("#submit, #submit_sep").prop('disabled', false);
					$('#loader').hide();
					$('#alert-modal').modal('show', {backdrop: 'static'});
					$("#spndeddelid").text('Payment Failed. Kindly try again.');
				}
			});
		}
		function repeat_load(booking_id){
			$.ajax({
				type:"POST",
				url: "<?php echo base_url(); ?>/donation_online/payment_check",
				data: {donation_booking_id: booking_id},
				success:function(data){
					console.log(data);
					obj = jQuery.parseJSON(data);
					if(obj.pay_status){
						userDetail.clearUser();
						if (window.print_type == 'imin') {
							window.open("<?php echo base_url(); ?>/donation_online/print_booking/" + booking_id, "_blank", "width=680,height=500");
						} else {
							window.open("<?php echo base_url(); ?>/donation_online/print_report_a5/" + booking_id, "_blank", "width=680,height=500");
						}
						window.location.reload(true);
					}else{
						if(window.load_no < 20 && window.paymentSeconds > 0){
							window.load_no++;
							setTimeout(function(){
								repeat_load(booking_id);
							}, 5000);
						}else{
							setTimeout(function(){
								cancel_booking(booking_id);
							}, 5000);
						}
					}
				},
				error:function(err)
				{
					cancel_booking(booking_id);
					console.log('err');
					console.log(err);
				}
			});	
		}
</script>
<script>
var paymentTimer;
function startPaymentTimer() {
    window.paymentSeconds = 120;
    // $('#payment_timeout_msg').hide();
    $('#payment_timer').show();
    updateTimerDisplay();

    paymentTimer = setInterval(function() {
        window.paymentSeconds--;
        if(window.paymentSeconds <= 0) {
            clearInterval(paymentTimer);
            $('#payment_timer').hide();
            // $('#payment_timeout_msg').show();
            // Optional: You can auto-cancel or reset the payment here
        } else {
            updateTimerDisplay();
        }
    }, 1000);
}

function updateTimerDisplay() {
    var minutes = Math.floor(window.paymentSeconds / 60);
    var seconds = window.paymentSeconds % 60;
    $('#payment_timer').text(('0' + minutes).slice(-2) + ':' + ('0' + seconds).slice(-2));
}

function showQRPaymentModal(qrCodeBase64, amount) {
    $(".qr_image").attr('src', 'data:image/jpeg;base64,' + qrCodeBase64);
    $('.total-cart').text(parseFloat(amount).toFixed(2));
    $('#qr_modal').modal('show');
    startPaymentTimer();
}

function hideQRPaymentModal() {
    clearInterval(paymentTimer);
	window.paymentSeconds = 0;
	// cancel_booking(window.booking_id);
	$('#qr_modal').modal('hide');
	$("#loader").show();
}

// Cancel button handler
$('#cancel_payment_btn').on('click', function() {
    hideQRPaymentModal();
    // Add your cancel logic here, e.g., cancel booking/payment on server
});

</script>
</body>

</html>