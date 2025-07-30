<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/jquery-ui.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/typicons/typicons.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/mdi/css/materialdesignicons.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/css/vertical-layout-light/style.css">
<link href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css" rel="stylesheet">
<?php 
if($view == true){
    $readonly = 'readonly';
    $disabled = 'disabled';
}
?>
<?php $db = db_connect();?>
<style>
.form-group { margin-bottom: 0px; }
.form-group .form-control { border: 1px solid #CCC;padding-left: 5px; }
.btn { padding: 6px 12px !important; }

.dropdown-toggle {  border-bottom: 0px solid #ddd !important; }
.panel-primary { border-color: #d4aa00; }
.bootstrap-select.btn-group .dropdown-menu.inner { padding-bottom:50px; }

label {
    display: inline-block;
    max-width: 100%;
    margin-bottom: 5px;
    font-weight: bold;
    
}

div {
    display: block;
}

.panel-heading{
    margin-top:5px;
}
</style>

<style>
  body {
    height: 100vh;
    width: 100%;
  }

  .navbar-light .navbar-nav .nav-link {
    font-weight: 500;
  }

  /*.row { width:100%; }*/
  .btn {
    padding: 0.25rem 0.5rem;
    height: 2rem;
  }

  .product {
    /*height:500px;*/
    max-height: 67vh;
    overflow: auto;
  }

  .cart {
    /*height:330px;*/
    height: 32vh;
    max-height: 32vh;
    overflow: auto;
    width: 100%;
    margin-bottom: 10px;
    margin-top: 10px;
  }

  select.form-control:not([size]):not([multiple]) {
    height: calc(1.625rem + 2px);
  }

  .prod::-webkit-scrollbar {
    width: 3px;
  }

  .prod::-webkit-scrollbar-track,
  .prod1::-webkit-scrollbar-track {
    background: #f1f1f1;
  }

  .prod::-webkit-scrollbar-thumb,
  .prod1::-webkit-scrollbar-thumb {
    background: #d4aa00;
  }

  .prod::-webkit-scrollbar-thumb:hover,
  .prod1::-webkit-scrollbar-thumb:hover {
    background: #e91e63;
  }

  .prod1::-webkit-scrollbar {
    height: 3px;
  }

  .ui-front {
    z-index: 9999;
  }

  a {
    text-decoration: none !important;
  }

  .text-muted.arch {
    color: #000000 !important;
    font-size: 10px;
    text-align: center;
    padding: 10px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    /* max-height:50px;
  min-height:50px; */
    height: 78px;
    text-transform: uppercase;
    font-weight: bold;
  }

  .show-cart {
    max-height: 350px;
    overflow: auto;
  }

  .show-cart tr {
    border-radius: 10px;
  }

  .show-cart td {
    font-size: 11px;
    padding: 5px;
  }

  .total {
    margin-top: 15px;
    padding-bottom: 10px;
  }

  .total p {
    font-size: 24px;
    font-weight: bold;
  }

  .submit_btn {
    width: 100%;
    font-size: 24px;
    padding: 7px;
    height: 50px;
    background: #d4aa00;
    border: #d4aa00;
    margin-top: 1px;
  }

  .amt {
    padding: 3px 5px;
    font-weight: bold;
    color: #333333 !important;
  }

  .prod_img {
    width: 90px;
    margin: 0 auto;
    border-radius: 50%;
    min-height: 90px;
    max-height: 90px;
    background: #e1e1d68a;
    padding: 5px;
  }

  .clear-cart i,
  .total_cart i {
    font-size: 28px;
    color: #000000bd;
  }

  .item-count {
    background: #dfdbdb;
    padding: 5px 1px;
    margin: 0 3px;
    border-radius: 5px;
    max-width: 27px;
    min-width: 27px;
    text-align: center;
    font-size: 14px;
  }

  .count {
    position: absolute;
    left: 28px;
    top: 7px;
    background: #051898;
    border-radius: 50%;
    padding: 2px 7px;
    font-size: 12px;
    color: #FFF;
  }

  .fade.show {
    opacity: 1;
    background: #abaaaad4;
  }

  .item-count_new {
    background: #dfdbdb;
    padding: 2px 1px;
    margin: 0 3px;
    border-radius: 5px;
    max-width: 50px;
    min-width: 27px;
    text-align: center;
    font-size: 14px;
  }

  /* Chrome, Safari, Edge, Opera */
  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  /* Firefox */
  input[type=number] {
    -moz-appearance: textfield;
  }

  .popup_table {
    height: 50vh;
    overflow: auto;
    margin-top: 15px;
  }

  .show-cart_popup_table tr th {
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    padding: 5px;
    border-bottom: 1px solid #E4E4E4;
  }

  .show-cart_popup_table tr td,
  .show-cart_popup_table tr td p {
    text-align: left;
    font-size: 11px;
    padding: 5px;
    line-height: 17px;
    border: 0;
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

  .form-group {
    margin-bottom: 0.5rem;
  }

  .caticon {
    font-size: 22px;
    text-align: center;
    line-height: 2em;
  }

  @media (min-width: 1200px) {
    .col-xl-3 {
      flex: 0 0 20%;
      max-width: 20%;
    }
  }

  .sidebar-icon-only .main-panel {
    width: calc(100% - 0px);
  }

  .ar_btn {
    background: linear-gradient(179deg, rgb(0 126 212) 0%, rgb(16 197 180) 35%, rgb(59 134 209) 100%);
    border-radius: 15px;
    font-weight: bold;
    height: 1.75em;
  }

  .cl_btn {
    background: linear-gradient(179deg, rgb(212 0 0) 0%, rgb(242 105 105) 35%, rgb(209 59 59) 100%);
    border-radius: 15px;
    font-weight: bold;
    height: 1.75em;
  }

  .big_font {
    font-size: 14px;
  }

  @media (max-width: 960px) {
    span.archa_name {
      text-transform: uppercase;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      width: 100px;
      display: inline-block;
    }

    .btn {
      padding: 0.25rem 0.35rem !important;
    }
  }

  .money-input {
    width: 100%;
    /* height: 50px; */
    padding: 10px;
    padding-left: 50px;
    /* padding: 10px; */
    border: 2px solid #ccc;
    border-radius: 5px;
    background-size: 40px 40px;
    background-repeat: no-repeat;
    background-position: 10px center;
    font-size: 16px;
    text-align: right;
    display: inline-block;
    cursor: pointer;
  }

  .sgd-1 {
    background-image: url('<?php echo base_url(); ?>/assets/images/sg1.jpeg') !important;
  }

  .sgd_dollar2 {
    background-image: url('<?php echo base_url(); ?>/assets/images/sgd_dollar2.jpeg') !important;
  }

  .sgd_dollar5 {
    background-image: url('<?php echo base_url(); ?>/assets/images/sgd_dollar5.jpeg') !important;
  }

  .sgd_dollar10 {
    background-image: url('<?php echo base_url(); ?>/assets/images/sgd_dollar10.jpeg') !important;
  }

  .sgd_dollar50 {
    background-image: url('<?php echo base_url(); ?>/assets/images/sgd_dollar50.jpeg') !important;
  }

  .sgd_dollarl00 {
    background-image: url('<?php echo base_url(); ?>/assets/images/sgd_dollar100.jpeg') !important;
  }

  .sgd_dollar1000 {
    background-image: url('<?php echo base_url(); ?>/assets/images/sgd_dollar1000.jpeg') !important;
  } 

  .sgd-5 {
    background-image: url('<?php echo base_url(); ?>/assets/images/sg5.jpeg') !important;
  }
  .sgd-10 {
    background-image: url('<?php echo base_url(); ?>/assets/images/sg10.jpeg') !important;
  }

  .sgd-20 {
    background-image: url('<?php echo base_url(); ?>/assets/images/sg20.jpeg') !important;
  }

  .sgd-50 {
    background-image: url('<?php echo base_url(); ?>/assets/images/sg50.jpeg') !important;
  }
</style>
<style>
    /* .rasi-table thead,
    tbody.rasi-body tr,
    .rasi-table1 thead {
      display: table;
      width: 100%;
      table-layout: fixed;
    }

    .rasi-table th,
    .rasi-table1 th {
      font-size: 12px;
      text-align: left;
      background: #fcf8eb;
    }

    .rasi-table td,
    .rasi-table1 td {
      font-size: 12px;
      text-align: left;
    }

    .rasi-body {
      overflow: auto;
      height: 12vh;
      display: block;
    }

    .vehicle-table thead,
    tbody.vehicle-body tr {
      display: table;
      width: 100%;
      table-layout: fixed;
    }

    .vehicle-table th {
      font-size: 12px;
      text-align: left;
      background: #fcf8eb;
    }

    .vehicle-table td {
      font-size: 12px;
    }

    .vehicle-body {
      overflow: auto;
      height: 90px;
      display: block;
    } */

    .bal-amt {
      background: #1def3b;
      padding: 3px 5px;
    }

    .pay_amt p {
      font-size: 17px;
      font-weight: bold;
    }

    .navbar+.page-body-wrapper {
      padding-top: calc(3.625rem + 1.875rem);
    }

    .pay_mode {
      display: block;
      margin-bottom: 0;
      margin-top: 10px;
      width: 100%;
      background: #00d454;
      color: white;
      padding: 5px;
      text-align: center;
      font-size: 16px;
      text-transform: uppercase;
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
      width: 100px;
      max-height: 130px;
      min-width: 150px;
    }

    #filters li span {
      display: block;
      padding: 5px 2px;
      text-decoration: none;
      color: #000;
      cursor: pointer;
      transition: color 300ms ease-in-out;
      text-align: center;
      line-height: 1.5em;
      font-size: 13px;
      height: 130px;
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

    .container:after {
      content: "\0020";
      display: block;
      height: 0;
      clear: both;
      visibility: hidden;
    }

    .clearfix:before,
    .clearfix:after {
      content: '\0020';
      display: block;
      overflow: hidden;
      visibility: hidden;
      width: 0;
      height: 0;
    }

    .clearfix {
      zoom: 1;
    }

    .clear {
      clear: both;
    }

    .clearfix:after {
      clear: both;
      display: block;
      overflow: hidden;
      visibility: hidden;
      width: 0;
      height: 0;
    }

    ul.payment {
      list-style-type: none;
      width: 100%;
      display: flex;
      justify-content: space-between;
      margin-bottom: 0;
      padding-left: 0;
    }

    .payment li {
      display: inline-block;
      text-align: center;
      width: 50%;
    }

    input[type="radio"][id^="cb"] {
      display: none;
    }

    input[type="radio"][id^="radio"] {
      display: none;
    }

    label {
      /* border: 1px solid #CCC; */
      /* border-radius: 5px; */
      /* line-height: 1; */
      /* padding: 5px 15px; */
      /* display: block; */
      position: relative;
      /* margin: 10px 15px; */
      cursor: pointer;
      font-weight: bold;
    }

    label:before {
      background-color: white;
      color: white;
      content: " ";
      display: block;
      border-radius: 50%;
      border: 1px solid grey;
      position: absolute;
      top: -12px;
      left: -5px;
      width: 18px;
      height: 18px;
      text-align: center;
      line-height: 18px;
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
    }

    :checked+label:before {
      content: "✓";
      background-color: green;
      transform: scale(1);
    }

    :checked+i.mdi {
      transform: scale(0.9);
    }

    .archname {}

    .select2-container--default .select2-selection--multiple {
    border: 1px solid #ddd; /* Example to match your form design */
    border-radius: 4px;
    padding: 6px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    color: #555; /* Styling for tags in multi-select */
}
.dropdown-menu.open.show .dropdown-menu { display:block; max-height:100% !important; height:auto !important; }
.bootstrap-select > .dropdown-toggle {
    width: 90% !important; border: 1px solid #CCC;
}
.bootstrap-select.btn-group.show-tick .dropdown-menu li a span.text {
    margin-right: 0;
    color: black;
    font-size: 14px;
    padding: 2px 5px;
}
.bootstrap-select .dropdown-toggle {
    border-bottom: 1px solid #ccc !important;
}
li.selected { background:#d4aa004f; }
.dropdown-toggle::after {
    margin-left: 0 !important;
}
.selectpicker {
    max-height: 200px;  /* Adjust based on your needs */
    overflow-y: auto;   /* Enables vertical scrolling */
}
#deity-names-container {
    max-height: 150px;  /* Set to a suitable height */
    overflow-y: auto;   /* Enables vertical scrolling */
    width: 100%;        /* Optional, ensures it takes the full width of the parent */
}
.bootstrap-select.btn-group .dropdown-menu {
    overflow: auto !important;
}
.type {
    display: flex;
    justify-content: space-between;
}
.type div {
    width: 23%;
    background: #00d4cc;
    text-align: center;
}
.type label { padding:5px; margin-bottom:0; width:100%; }
.type :checked+label:before {
    content: none;
    background-color: green;
    transform: scale(1);
}
.type :checked+label {
    background: #05615d; color:#FFF;
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
    padding: 5px 2px;
    display: block;
    position: relative;
    margin: 10px 3px;
    cursor: pointer;
    font-weight: bold;
    font-size: 13px;
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
  /*content: "✓";
  background-color: green;
  transform: scale(1);*/
}

.payment1 li label :checked + i.mdi{
  transform: scale(0.9);
}

.payment-options {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 5px; /* space between buttons */
}

/* Hide the actual radio input */
.payment-options .payment_type {
    display: none;
}

/* Style labels to look like buttons */
.payment-options .btn-payment {
    padding: 5px 8px;
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
</style>

<!--link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.8.1/css/bootstrap-select.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.8.1/js/bootstrap-select.js"></script>
<script>
$('.selectpicker').selectpicker();
</script>

<section class="content mt-3">
    <div class="container-fluid ">
        <!--<div class="block-header">
            <h2> ENTRIES <small style="font-size: 14px;">Account / <?php echo $sub_title; ?></small></h2>
        </div>-->
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row"><div class="col-md-8"><!--<h2>Ubayam</h2>--></div>
                        </div>
                    </div>
                    <div class="body">
							<?php if($_SESSION['succ'] != '') { ?>
							<div class="row" style="padding: 0 30%;" id="content_alert">
								<div class="suc-alert" style="width: 100%;">
									<span class="suc-closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
									<p><?php echo $_SESSION['succ']; ?></p> 
								</div>
							</div> 
							<?php } ?>
							<?php if($_SESSION['fail'] != '') { ?>
							<div class="row" style="padding: 0 30%;" id="content_alert">
								<div class="alert" style="width: 100%;">
									<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
									<p><?php echo $_SESSION['fail']; ?></p>
								</div>
							</div>
							<?php } ?>
                        <form  class="form" id="form" action="<?php echo base_url(); ?>/kattalai_archanai_online/save_booking" method="post">
                        <input type="hidden" name="print_type" id="print_type" value="<?php echo $setting['print_method']; ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="panel panel-primary">
                                    <div class="panel-heading" style="color: #fff;background-color: #d4aa00;border-color: #d4aa00;padding: 5px 15px;">Archanai Details</div>
                                    <div class="panel-body" style="border: 1px solid #d4aa00;padding: 10px;">
                                <div class="row">       
                                  <div class="col-sm-4">
                                      <div class="form-group form-float">
                                          <div class="form-line focused">
                                              <label class="form-label">Archanai Type</label>
                                              <select class="form-control" name="archanaitype_id" id="archanaitype_id" required>
                                                  <option value="">--Select--</option>
                                                  <?php foreach($archanai as $row) { ?>
                                                  <option value="<?php echo $row['id']; ?>"><?php echo $row['name_eng']; ?></option>
                                                  <?php } ?>
                                              </select>
                                          </div>
                                      </div>
                                  </div>

                                  <div class="col-sm-4">
                                      <div class="form-group form-float">
                                          <div class="form-line focused">
                                              <label class="form-label">Deity</label>
                                              <select class="selectpicker" name="deities[]" id="deities" multiple required>
                                                  <option value="">--Select--</option>
                                              </select>
                                              <!-- Hidden inputs will be appended here -->
                                              <div id="deity-names-container"></div>
                                          </div>
                                      </div>
                                  </div>

                                  <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <label class="form-label">Devotee Name</label>
                                            <input type="txt" name="devotee_name" id="devotee_name" class="form-control" >
                                        </div>
                                    </div>
                                  </div>

                                </div>
                                <div class="row">
                                  <div class="col-sm-4">
                                      <div class="form-group form-float">
                                          <div class="form-line focused">
                                              <label class="form-label">Name</label>
                                              <input type="txt" name="name[0]" id="name" class="form-control" >
                                              
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-sm-3">
                                      <div class="form-group form-float">
                                          <div class="form-line focused">
                                              <label class="form-label">Rasi</label>
                                              <!-- <select class="form-control" name="rasi_id[0]" id="rasi_id_0" onchange="fetchNatchathiram(0)"> -->
                                              <select class="form-control" name="rasi_id[0]" id="rasi_id_0">
                                                  <option value="">--Select--</option>
                                                  <?php foreach($rasi as $row) { ?>
                                                  <option value="<?php echo $row['id']; ?>"><?php echo $row['name_eng']; ?> / <?php echo $row['name_tamil']; ?></option>
                                                  <?php } ?>
                                              </select>
                                              
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-sm-4" style="margin: 0px;">
                                      <div class="form-group form-float">
                                          <div class="form-line focused" >
                                              <!--<select class="form-control" name="natchathra_id" id="natchathra_id">
                                                  <option>Select Natchathiram</option>
                                                  <?php
                                                  //foreach($nat1 as $res) {
                                                  //$nat_name = $this->db->table('natchathram')->where('id', $nat)->get()->getResultArray();
                                                  //foreach($nat as $row) { ?>
                                                  <option value="<?php //echo $res; ?>"><?php //echo $res;?></option>
                                                  <?php //} ?>
                                              </select>-->
                                              <label class="form-label">Natchathiram</label>
                                              <select class="form-control" name="natchathra_id[0]" id="natchathra_id_0">
                                                  <option>Select Natchathiram</option>
                                                  <?php foreach($nat as $res) { ?>
                                                    <option value="<?php echo $res['id']; ?>"><?php echo $res['name_eng'];?> / <?php echo $res['name_tamil'];?></option>
                                                  <?php } ?>
                                              </select>


                                              <!-- <label class="form-label">Natchathiram</label>
                                              <input type="hidden" id="natchathram_id" name="natchathram_id" class="form-control">
                                              <select class="form-control" name="natchathra_id[0]" id="natchathra_id_0">
                                              <option>Select Natchathiram</option>
                                              </select> -->
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-sm-1">
                                    <div class="form-group form-float">
                                          <div class="form-line" style="border: none;">
                                              <br><button type="button" id="pack_add" class="btn btn-success" style="padding: 5px !important;" onClick="append();">+</button>
                                          </div>
                                      </div>
                                  </div> 
                                </div> 
                                <input type="hidden" id="tot_count" value="1">
                                  <div class="addmore"></div> 
                                      <div class="row">
                                      <div class="col-sm-6">
                                          <div class="form-group form-float">
                                              <div class="form-line focused">
                                                  <label class="form-label">Contact No.</label>
                                                  <input type="txt" name="contact_no" id="contact_no" class="form-control" >
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-sm-6">
                                          <div class="form-group form-float">
                                              <div class="form-line focused">
                                                  <label class="form-label">Email</label>
                                                  <input type="txt" name="email" id="email" class="form-control" >
                                              </div>
                                          </div>
                                      </div> 
                                      <div class="col-sm-12">
                                          <div class="form-group form-float">
                                              <div class="form-line focused">
                                                  <label class="form-label">Remarks</label>
                                                  <textarea class="form-control" name="description" id="description" ></textarea>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                
                                    </div>
                                </div>
                            </div>
                            
                            
                            
                            <div class="col-md-6">
                                <div class="panel panel-primary">
                                    <div class="panel-heading" style="color: #fff;background-color: #d4aa00;border-color: #d4aa00;padding: 5px 15px;">Date Details</div>
                                    <div class="panel-body" style="border: 1px solid #d4aa00;padding: 10px;">
                                        <div class="row">       
                                            <div class="col-sm-12">
                                                <div class="form-group form-float">
                                                    <label>Type</label><br>
                              
                                                    <div class="row"><div class="col-md-12 type">
                                                      <div id="1_daily">
                                                        <input name="day" type="radio" id="radio_30" checked="checked" class="with-gap type radio-col-red" value="daily">
                                                        <label for="radio_30">Daily</label>
                                                      </div>
                                                      <div id="2_weekly">
                                                        <input name="day" type="radio" id="radio_31" class="with-gap type radio-col-red" value="weekly">
                                                        <label for="radio_31">Weekly</label>
                                                      </div>
                                                      <div id="3_multiple">
                                                        <input name="day" type="radio" id="radio_32" class="with-gap type radio-col-red" value="days">
                                                        <label for="radio_32">Multiple Date(s)</label>
                                                      </div>
                                                      <div id="4_years">
                                                        <input name="day" type="radio" id="radio_33" class="with-gap type radio-col-red" value="years">
                                                        <label for="radio_33">Years</label>
                                                      </div>
                                                    </div></div>

                                                </div>
                                            </div>
               					                  </div>
                                
                                        <div id="daily">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="form-line focused" id="bs_datepicker_component_container">
                                                            <label class="form-label">Start Date <span style="color: red;">*</span></label>
                                                            <input type="text" id="sdate" name="sdate" class="form-control datepicker">
                                                        </div>
                                                    </div>
                                                </div>
                                
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="form-line focused" id="bs_datepicker_component_container">
                                                            <label class="form-label">End Date <span style="color: red;">*</span></label>
                                                            <input type="text" id="edate" name="edate" class="form-control datepicker">
                                                        </div>
                                                    </div>
                                                </div>
                                
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="form-line focused" id="bs_datepicker_component_container">
                                                            <label class="form-label">Total Days</label>
                                                            <input type="text" readonly="readonly" id="tdate" name="tdate" class="form-control" >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                
                                        <div id="weekly" style="display:none;">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="form-line focused">
                                                            <label class="form-label">Day</label>
                                                            <select class="form-control" id="dayOfWeek" name="dayOfWeek">
                                                                <option value="">Select a Day</option>
                                                                <option value="0">Sunday</option>
                                                                <option value="1">Monday</option>
                                                                <option value="2">Tuesday</option>
                                                                <option value="3">Wednesday</option>
                                                                <option value="4">Thursday</option>
                                                                <option value="5">Friday</option>
                                                                <option value="6">Saturday</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="form-line focused" id="bs_datepicker_component_container">
                                                            <label class="form-label">Start Date <span style="color: red;">*</span></label>
                                                            <input type="text" name="wsdate" id="startDate" class="form-control datepicker">
                                                        </div>
                                                    </div>
                                                </div>
                                
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="form-line focused" id="bs_datepicker_component_container">
                                                            <label class="form-label">End Date <span style="color: red;">*</span></label>
                                                            <input type="text" name="wedate" id="endDate" class="form-control datepicker">
                                                        </div>
                                                    </div>
                                                </div>
                                
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="form-line focused" id="bs_datepicker_component_container">
                                                            <label class="form-label">Total Days</label>
                                                            <input type="text" id="numDays" name="numDays" readonly="readonly" class="form-control" >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                
                                        <div id="day" style="display:none;">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="form-line focused" id="bs_datepicker_component_container">
                                                            <label class="form-label">Date's <span style="color: red;">*</span></label>
                                                            <input type="text" id="multiDatePicker" name="multiDatePicker" class="form-control" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="form-line focused" id="bs_datepicker_component_container">
                                                            <label class="form-label">Total Days</label>
                                                            <input type="text" id="totalDays" name="totalDays" class="form-control" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="years" style="display:none;">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="form-line focused" id="bs_datepicker_component_container">
                                                            <label class="form-label">Start Date <span style="color: red;">*</span></label>
                                                            <input type="text" id="yearsdate" name="yearsdate" class="form-control datepicker">
                                                        </div>
                                                    </div>
                                                </div>
                                
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="form-line focused" id="bs_datepicker_component_container">
                                                            <label class="form-label">End Date <span style="color: red;">*</span></label>
                                                            <input type="text" readonly="readonly" id="yearedate" name="yearedate" class="form-control ">
                                                        </div>
                                                    </div>
                                                </div>
                                
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="form-line focused" id="bs_datepicker_component_container">
                                                            <label class="form-label">Total Days</label>
                                                            <input type="text" readonly="readonly" id="ydate" name="ydate" class="form-control" >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="total d-flex justify-content-between align-items-center" style="width:100%; border-bottom:1px dashed #CCC;">
                                            <p class="mb-0">Total </p>
                                            <p class="mb-0">RM : <span class="total-cart"></span></p>
                                            <input type="hidden" id="tot_amt" name="tot_amt">
                                        </div>

                                        <div class="row clearfix" style="width:105%; border-bottom:1px dashed #CCC; display: flex; justify-content: center; align-items: center;">
                                          <div class="col-sm-12">
                                            <div class="payment-options" style="flex-grow: 1; display: flex; justify-content: center;">
                                                <div class="form-group" style="padding-left: 16px; padding-top: 10px">
                                                    <input type="radio" name="payment_type" id="payment_type_full" class="payment_type" value="full" 
                                                        <?php echo (empty($data['payment_type']) || $data['payment_type'] == 'full') ? 'checked' : ''; ?>>
                                                    <label for="payment_type_full" class="pay-label btn-payment">Full Payment</label>
                                                </div>
                                                <div class="form-group" style="padding-left: 16px; padding-top: 10px">
                                                    <input type="radio" name="payment_type" id="payment_type_partial" class="payment_type" value="partial" 
                                                        <?php echo ($data['payment_type'] == 'partial') ? 'checked' : ''; ?>>
                                                    <label for="payment_type_partial" class="pay-label btn-payment">Partial Payment</label>
                                                </div>
                                            </div>
                                          </div>
                                            <div class="row partial_paid_sec mb-2" align="center" style="<?php echo (!empty($data['payment_type']) && $data['payment_type'] == 'partial') ? '' : 'display: none;'; ?>">
                                                <label class="form-label col-sm-6" align="center">Pay Amount</label>
                                                <input type="number" name="paid_amount" id="paid_amount" step=".01" class="form-control col-sm-6" value="<?php echo $data['paid_amount'] ?? '0.00'; ?>">
                                            </div>
                                        </div>

                                        <!--h5 class="pay_mode">Payment Mode</h5-->
                                        <ul class="payment1">
                                        <?php foreach ($payment_mode as $key => $pay) { ?>
                                              <li>
                                                  <input type="radio" name="pay_method" id="cb<?php echo $pay['id']; ?>" value="<?php echo $pay['id']; ?>" data-name="<?php echo $pay['name']; ?>" <?php echo $key === 0 ? 'checked' : ''; ?>/>
                                                  <label for="cb<?php echo $pay['id']; ?>">
                                                      <?php echo $pay['name']; ?>
                                                  </label>
                                              </li>
                                          <?php } ?>
                                        </ul>
                                        <div class="form-group" style="align:right;">
                                          <input type="button" value="Save" class="btn btn-info submit_btn" id="submit">
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
	
	
</section>
<script src="<?php echo base_url(); ?>/assets/archanai/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/archanai/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page-->
<script src="<?php echo base_url(); ?>/assets/archanai/vendors/chart.js/Chart.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/archanai/js/jquery.cookie.js" type="text/javascript"></script>
<!--link href="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/js/bootstrap-select.js"></script-->

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"/>

<script>
var deities = <?php echo json_encode($archanai_diety); ?>;

document.addEventListener('DOMContentLoaded', function() {
    var selectElement = document.getElementById('deities');
    var namesContainer = document.getElementById('deity-names-container');

    // Populate the select options
    deities.forEach(function(deity) {
        var option = new Option(deity.name, deity.id);
        selectElement.add(option);
    });

    // Listen for changes and update hidden inputs
    selectElement.addEventListener('change', function() {
        // Clear previous inputs
        namesContainer.innerHTML = '';

        // Create hidden inputs for each selected option
        Array.from(this.selectedOptions).forEach(option => {
            var hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'deity_name[]';
            hiddenInput.value = option.text; // Send the deity name
            namesContainer.appendChild(hiddenInput);
        });
    });
});

$('#archanaitype_id').change(function() {
    var typeId = $(this).val();
    $('input[name="day"]').closest('div').show();

    if (typeId === '1') {
        $('#4_years').hide();
        $('input[name="day"]').not('#radio_33').closest('div').show();
        $('input[name="day"]').not('#radio_33').first().prop('checked', true);  // Automatically check the first available option
    } else if (typeId === '2') {
        $('input[name="day"]').not('#radio_33').closest('div').hide();
        $('#4_years').show();
        $('#radio_33').prop('checked', true);  // Check 'Years' when it's the only option available
    } else {
        $('input[name="day"]').closest('div').show();
        $('input[name="day"]').first().prop('checked', true);  // Default to first option when all are available
    }

    $('input[name="day"]:checked').trigger('change');
});
</script>


<!-- <script>
document.getElementById('deity_names').addEventListener('change', function() {
    var selectedOptions = Array.from(this.selectedOptions).map(option => ({
        id: option.value,
        name: option.text
    }));
    console.log(selectedOptions);  // This will log the array of selected deities
});
</script> -->


<script>
  $(document).on('change', '.payment_type', function(){
			if(this.value == 'partial'){
				$('.partial_paid_sec').show();
				$('#full_paid_amount').prop('disabled', true);
			}else{
				$('.partial_paid_sec').hide();
				$('#full_paid_amount').prop('disabled', false);
			}
		});
  </script>
  
<script>
// function append() {
//     var count = parseInt($("#tot_count").val());
//     var max_fields  = 4;
// 	if(count < max_fields){
// 	var html = '<div class="row" id="row_'+count+'"><div class="col-sm-6"><div class="form-group form-float"><div class="form-line focused"><label class="form-label">Name</label><input type="txt" class="form-control" ></div></div></div>';
// 	html +=' <div class="col-sm-5"><div class="form-group form-float"><div class="form-line focused"><label class="form-label">Rasi</label><select class="form-control"><option value="">--Select--</option><?php foreach($rasi as $row) { ?><option value="<?php echo $row['id']; ?>"><?php echo $row['name_eng']; ?></option><?php } ?></select></div></div></div>';
// 	html +='<div class="col-sm-1" align="right">';
// 	html +='<br><button class="btn btn-danger"  style="padding: 5px !important;" onclick="remove_row('+count+')"> X </button></div></div>';
// 	$(".addmore").append(html);
	  
// 	var cnt = count + 1;
// 	$("#tot_count").val(cnt);
// 	}
// }
// function remove_row(id){
//     $('#row_'+id).remove();
// 	var t_count = $("#tot_count").val();
// 	var cnt = t_count - 1;
// 	$("#tot_count").val(cnt);
// }
function append() {
    var count = parseInt($("#tot_count").val());
    var max_fields = 2;

    if (count < max_fields) {
        var html = '<div class="row" id="row_' + count + '">';

        // Name field
        html += '<div class="col-sm-4"><div class="form-group form-float">';
        html += '<div class="form-line focused">';
        html += '<label class="form-label">Name</label>';
        html += '<input type="text" class="form-control" name="name[' + count + ']"></div></div></div>';

        // Rasi dropdown
        html += '<div class="col-sm-3"><div class="form-group form-float">';
        html += '<div class="form-line focused">';
        html += '<label class="form-label">Rasi</label>';
        //html += '<select class="form-control rasi" name="rasi_id[' + count + ']" id="rasi_id_' + count + '" onchange="fetchNatchathiram(' + count + ')">';
        html += '<select class="form-control rasi" name="rasi_id[' + count + ']" id="rasi_id_' + count + '">';
        html += '<option value="">--Select--</option>';
        <?php foreach ($rasi as $row) { ?>
            html += '<option value="<?php echo $row['id']; ?>"><?php echo $row['name_eng']; ?> / <?php echo $row['name_tamil']; ?></option>';
        <?php } ?>
        html += '</select></div></div></div>';

        // Natchathiram dropdown
        // html += '<div class="col-sm-4"><div class="form-group form-float">';
        // html += '<div class="form-line focused">';
        // html += '<label class="form-label">Natchathiram</label>';
        // html += '<select class="form-control natchathra" name="natchathra_id[' + count + ']" id="natchathra_id_' + count + '">';
        // html += '<option value="">Select Natchathiram</option>';
        // html += '</select></div></div></div>';

        html += '<div class="col-sm-4"><div class="form-group form-float">';
        html += '<div class="form-line focused">';
        html += '<label class="form-label">Natchathiram</label>';
        html += '<select class="form-control natchathra" name="natchathra_id[' + count + ']" id="natchathra_id_' + count + '">';
        html += '<option value="">Select Natchathiram</option>';
        <?php foreach($nat as $row) { ?>
          html += '<option value="<?php echo $row['id']; ?>"><?php echo $row['name_eng'];?> / <?php echo $row['name_tamil'];?></option>';
        <?php } ?>
        html += '</select></div></div></div>';

        // Remove button
        html += '<div class="col-sm-1" align="right">';
        html += '<br><button class="btn btn-danger" style="padding: 5px !important;" onclick="remove_row(' + count + ')"> X </button>';
        html += '</div></div>';

        $(".addmore").append(html);

        // Increment count
        var cnt = count + 1;
        $("#tot_count").val(cnt);
    } else {
        alert('Only able to add 2 persons');
    }
}

function remove_row(row_id) {
    $("#row_" + row_id).remove();
    var count = parseInt($("#tot_count").val());
    $("#tot_count").val(count - 1);
}
function fetchNatchathiram(row_id) {
    var rasi = $("#rasi_id_" + row_id).val();

    if (rasi != "") {
        $.ajax({
            url: '<?php echo base_url(); ?>/kattalai_archanai_online/get_natchathram',
            type: 'post',
            data: { rasi_id: rasi },
            dataType: 'json',
            success: function(response) {
                var natchathramDropdown = $("#natchathra_id_" + row_id);
                natchathramDropdown.empty();  // Clear existing options
                natchathramDropdown.append('<option value="">Select Natchathiram</option>');

                if (response.natchathra_id) {
                    var str = response.natchathra_id;

                    $.each(str.split(','), function(key, value) {
                        $.ajax({
                            url: '<?php echo base_url(); ?>/kattalai_archanai_online/get_natchathram_name',
                            type: 'post',
                            data: { id: value },
                            dataType: 'json',
                            success: function(natchathraResponse) {
                              console.log('natchathram:', natchathraResponse);
                                natchathramDropdown.append('<option value="' + natchathraResponse.id + '">' + natchathraResponse.name_eng + ' / ' + natchathraResponse.name_tamil + '</option>');
                            }
                        });
                    });
                }
            }
        });
    }
}

$(document).ready(function(){
	var i= 1;
	$('#addcreditdetail').click(function(){
		var payment_credit_ac = $("#payment_credit_ac").val();
        var payment_amount = $("#payment_amount").val();  
      //  var cnt = parseInt($("#pay_row_count").val());
        if(payment_credit_ac != '' && (payment_amount != "0.00" || payment_amount != "0")){
			i++;
			var credit_ac = $("#payment_credit_ac").val();
			var ledgername = getladgerName(credit_ac,i);
			var payment_detail_particulars = $("#payment_detail_particulars").val();
			var amt = Number($("#payment_amount").val()).toFixed(2);
			
			var text1 = '<tr class="all_close" data-id="'+credit_ac+'" id="remov'+i+'"><td><input type="hidden" name="entries['+i+'][entryitemid]"><a class="btn btn-info" style="font-size: 15px;cursor: pointer;color: #fff;font-weight: bold;padding: 0px 5px;" onclick="remove('+i+')" id="remove">X</a></td>';
			text1 += '<td><input type="hidden" style="text-align: center;" class="row_amt" name="entries['+i+'][ledgerid]" value="'+credit_ac+'"><span id="ledgername_'+i+'"></span></td>';
			text1 += '<td><input type="hidden" style="text-align: center;" class="row_tot" id="tot_'+i+'" value="'+amt+'"><input type="hidden" style="text-align: center;" class="row_qty" name="entries['+i+'][amount]" id="qty_'+i+'" value="'+amt+'">'+amt+'</td>';
			text1 += '<td><input type="hidden" style="text-align: center;" class="" name="entries['+i+'][particulars]" id="particulars_'+i+'" value="'+payment_detail_particulars+'">'+payment_detail_particulars+'</td>';
			text1 += '</tr>';
			$(".cart-table").append(text1);
			sum_total();
			//$('#payment_credit_ac').prop('selectedIndex',0);
			//$("#payment_credit_ac").selectpicker("refresh");
			$("#payment_credit_ac").val("");
			$("#payment_detail_particulars").val("");
			$("#payment_amount").val("0.00");
		}
	});
    $("#rasi_id").change(function(){
	    var rasi = $("#rasi_id").val();
	    // alert(rasi);
            if(rasi != "")
			{
				
				//console.log(rasi_id);
				$.ajax({
					url: '<?php echo base_url();?>/kattalai_archanai_online/get_natchathram',
					type: 'post',
					data: {rasi_id:rasi},
					dataType: 'json',
					success:function(response)
					{
						$('#natchathram_id').val(response.natchathra_id);
						
						var str = response.natchathra_id;
						console.log(str);
						//return;
                        if(str !="") {
                            $("#natchathra_id").empty();
                            
    		                $('#natchathra_id').append('<option value="">Select Natchiram</option>');
                        	$.each(str.split(','), function(key, value) {
                        	    //$('#natchathra_id').append('<option value="' + value + '">' + value + '</option>');
                        	    $.ajax({
                					url: '<?php echo base_url();?>/kattalai_archanai_online/get_natchathram_name',
                					type: 'post',
                					data: {id:value},
                					dataType: 'json',
                					success:function(response)
                					{
                            console.log('natchathram:', response);
                                    $('#natchathra_id').append('<option value="' + response.id + '">' + response.name_eng + '/' + response.name_tamil + '</option>');
                                    $('#natchathra_id').prop('selectedIndex',0);
    		                        // $("#natchathra_id").selectpicker("refresh");
                					}
                        	    });
                            });
                        }
				    }
				});
			}
			/*if(rasi == "")
			{
				alert("empty");
			}*/
    });
});
	  
function getladgerName(ledgerid, incid) {  
	$.ajax({
		type: "POST",
		url: "<?php echo base_url(); ?>/payment_voucher/getladgerName",
		data:{ledger_id: ledgerid},
		success:function(data){
			$('#ledgername_'+incid).text(data);
		}
	});
}

function remove(id){
	$(".cart-table #remov"+id).remove();
	sum_total();
}
$("#clear_credit_ac").click(function() {
	//$('#payment_credit_ac').prop('selectedIndex',0);
	//$("#payment_credit_ac").selectpicker("refresh");
	$("#payment_paymode").val("");
	$("#payment_receivedfrom").val("");
	$("#payment_particulars").val("");
	$("#payment_credit_ac").val("");
	$("#payment_detail_particulars").val("");
	$("#payment_amount").val("0.00");
	$(".cart-table .all_close").empty();
	sum_total();
});

function sum_total(){
	var total_qty = 0;
	$( ".row_qty" ).each(function() {
		total_qty += parseFloat($( this ).val());
	});
	/* $("#tot_qty").text(total_qty); */

	var total_amt = 0;
	$( ".row_tot" ).each(function() {
		total_amt += parseFloat($( this ).val());
	});
	$("#tot_amt").val(Number(total_amt).toFixed(2));
	$("#tot_amt_parag").text(Number(total_amt).toFixed(2));
	$("#tot_amt_input").val(Number(total_amt).toFixed(2));
	numberToWords(total_amt);
}

function numberToWords(number) {  
	$.ajax({
		type: "POST",
		url: "<?php echo base_url(); ?>/payment_voucher/AmountInWords",
		data:{number: number},
		success:function(data){
			$('#tot_amt_txt').html(data);
			//console.log(data)
			//return str.trim() + "";  
		}
	});
}


// $(document).ready(function(){
// 	$(document).on('change', '.type', function(){
// 		//alert(this.value);
// 		if(this.value == 'weekly'){
// 			$("#weekly").css("display", "block");
// 			$("#day").css("display", "none");
// 			$("#daily").css("display", "none");
// 		}else if(this.value == 'days'){
// 			$("#day").css("display", "block");
// 			$("#weekly").css("display", "none");
// 			$("#daily").css("display", "none");
// 		}else if(this.value == 'daily'){
// 			$("#daily").css("display", "block");
// 			$("#weekly").css("display", "none");
// 			$("#day").css("display", "none");
// 		}
// 	});
	
// 	$('.datepicker').datepicker({
//         format: 'mm/dd/yyyy',
//         autoclose: true
//     });
	
// 	//Function to calculate and display number of days
//     function calculateDays() {
//         var startDate = $('#sdate').val();
//         var endDate = $('#edate').val();

//         // Check if both dates are selected
//         if (startDate && endDate) {
//             var start = new Date(startDate);
//             var end = new Date(endDate);

//             // Check if both dates are valid
//             if (start == "Invalid Date" || end == "Invalid Date") {
//                 $('#tdate').val('Invalid dates');
//                 return;
//             }

//             // Calculate the time difference in milliseconds
//             var timeDiff = end - start;

//             // If end date is earlier than start date
//             if (timeDiff < 0) {
//                 $('#tdate').val('End date is before start date');
//                 return;
//             }

//             // Convert milliseconds to days
//             var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

//             // Display the number of days
//             $('#tdate').val(diffDays);
//         }
//     }

//     // Trigger calculation when date is selected
//     $('#sdate, #edate').on('changeDate', function() {
//         calculateDays();
//     });
	
	
// 	// Function to calculate the total number of specific weekdays
//     function calculateSpecificDays() {
//         var startDate = $('#startDate').val();
//         var endDate = $('#endDate').val();
//         var selectedDay = parseInt($('#dayOfWeek').val());

//         if (startDate && endDate) {
//             var start = new Date(startDate);
//             var end = new Date(endDate);

//             // Check if both dates are valid
//             if (start == "Invalid Date" || end == "Invalid Date") {
//                 $('#numDays').val('Invalid dates');
//                 return;
//             }

//             // If end date is earlier than start date
//             if (end < start) {
//                 $('#numDays').val('End date is before start date');
//                 return;
//             }

//             // Count occurrences of the selected day between start and end dates
//             var count = 0;
//             var currentDate = start;

//             while (currentDate <= end) {
//                 if (currentDate.getDay() === selectedDay) {
//                     count++;
//                 }
//                 currentDate.setDate(currentDate.getDate() + 1); // Move to the next day
//             }

//             // Display the result
//             $('#numDays').val(count);
//         }
//     }

//     // Trigger calculation when date or day of the week is selected/changed
//     $('#startDate, #endDate, #dayOfWeek').on('changeDate change', function() {
//         calculateSpecificDays();
//     });
	
// 	$('#multiDatePicker').datepicker({
//         format: 'mm/dd/yyyy',
//         multidate: true,
//         autoclose: false
//     }).on('changeDate', function(e) {
//         // Get selected dates
//         var selectedDates = $(this).datepicker('getDates');

//         // Display the total number of selected dates
//         $('#totalDays').val(selectedDates.length);
//     });
// });
$(document).ready(function(){
    $(document).on('change', '.type', function(){
        resetForm();
        if (this.value == 'weekly') {
            $("#weekly").css("display", "block");
            $("#day").css("display", "none");
            $("#daily").css("display", "none");
            $("#years").css("display", "none");
        } else if (this.value == 'days') {
            $("#day").css("display", "block");
            $("#weekly").css("display", "none");
            $("#daily").css("display", "none");
            $("#years").css("display", "none");
        } else if (this.value == 'daily') {
            $("#daily").css("display", "block");
            $("#weekly").css("display", "none");
            $("#day").css("display", "none");
            $("#years").css("display", "none");
        } else if (this.value == 'years') {
            $("#years").css("display", "block");
            $("#daily").css("display", "none");
            $("#weekly").css("display", "none");
            $("#day").css("display", "none");
        }
        //updateTotalCart();
    });

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',   // Display format for user
        autoclose: true,
        startDate: new Date()
    });


    $('#sdate, #edate').on('changeDate', function() {
        calculateDays();
        updateTotalCart();
    });

    $('#startDate, #endDate, #dayOfWeek').on('changeDate change', function() {
        calculateSpecificDays();
        updateTotalCart();
    });

    $('#multiDatePicker').datepicker({
        format: 'dd/mm/yyyy',
        multidate: true,
        autoclose: false,
        startDate: new Date()
    }).on('changeDate', function(e) {
        var selectedDates = $(this).datepicker('getDates');
        $('#totalDays').val(selectedDates.length);
        updateTotalCart();
    });

    $('#deities').on('change', function() {
        updateTotalCart();
    });

    function resetForm() {
        $('#sdate, #edate, #startDate, #endDate, #numDays, #tdate, #totalDays, #yearsdate, #yearedate, #ydate').val('');
    }

    function updateTotalCart() {
      var archanaitype_id = $('#archanaitype_id').val();
      if(archanaitype_id == '') {
        alert('Please select the Archanaitype');
        return;
      }

      var selectedDeities = $('#deities').val(); // This gets the array of selected options
        if (!selectedDeities || selectedDeities.length === 0) {
            alert('Please select at least one deity');
            return;
        }

      var dayType = $('input[name="day"]:checked').val(); // Assuming the value of the radio button correctly specifies the day type
        console.log("Day type selected:", dayType); // Debugging output
       
        var totalDays = 0;
        if ($('input[name="day"]:checked').val() == 'daily') {
            totalDays = $('#tdate').val();
        } else if ($('input[name="day"]:checked').val() == 'weekly') {
            totalDays = $('#numDays').val();
        } else if ($('input[name="day"]:checked').val() == 'days') {
            totalDays = $('#totalDays').val();
        } else if ($('input[name="day"]:checked').val() == 'years') {
            totalDays = $('#ydate').val();
        }
        
        console.log('total days:', totalDays);

        if (totalDays) {
            var archanaitype_id = $('#archanaitype_id').val(); 
            console.log('archanai type:', archanaitype_id);
            // alert(archanaitype_id);
            $.ajax({
                url: '<?php echo base_url();?>/kattalai_archanai_online/fetch_amount',
                type: 'POST',
                data: { archanaitype_id: archanaitype_id },
                dataType: 'json',
                success: function(response) {
                     console.log(response.amount);
                    if (response.amount !== undefined) {
                        var amountPerDay = parseFloat(response.amount);  

                        if (!isNaN(amountPerDay)) {  
                            //var totalDays = parseInt($('#totalDays').val());  // Parse totalDays as an integer
                            var deityCount = $('#deities').val().length;

                            if (!isNaN(totalDays)) {
                              if (($('input[name="day"]:checked').val() == 'years') && archanaitype_id == 2) {
                                var totalAmount = amountPerDay * deityCount;
                              } else {
                                var totalAmount = amountPerDay * totalDays * deityCount;
                              }
                                $('.total-cart').text(totalAmount.toFixed(2));  
                                $('#tot_amt').val(totalAmount);  
                            } else {
                                console.error('Invalid totalDays value');
                            }
                        } else {
                            console.error('Invalid amountPerDay value');
                        }
                    } else if (response.error) {
                        console.error(response.error);  
                    }
                }
            });
        } else {
            $('.total-cart').text(0.00);  
            $('#tot_amt').val(0.00); 
        }
        
    }

    function calculateDays() {
        var startDate = $('#sdate').val();
        var endDate = $('#edate').val();
        console.log('start date:', startDate);
        console.log('start date:', endDate);

        // Convert dates from dd/mm/yyyy to mm/dd/yyyy for calculations
        if (startDate && endDate) {
            var start = convertToBackendFormat(startDate);
            var end = convertToBackendFormat(endDate);

            var timeDiff = end - start;
            if (timeDiff >= 0) {
                var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
                $('#tdate').val(diffDays + 1);
            } else {
                $('#tdate').val('Invalid range');
            }
        }
    }

    // Function to convert dd/mm/yyyy to mm/dd/yyyy format
    function convertToBackendFormat(dateStr) {
        var dateParts = dateStr.split('/');
        var day = dateParts[0];
        var month = dateParts[1];
        var year = dateParts[2];
        
        // Return new Date object in mm/dd/yyyy format for backend or calculations
        return new Date(month + '/' + day + '/' + year);
    }

  function calculateSpecificDays() {
    var startDate = $('#startDate').val();
    var endDate = $('#endDate').val();
    var selectedDay = parseInt($('#dayOfWeek').val());
    console.log('Selected day (0=Sunday, 6=Saturday):', selectedDay);
    console.log('start date:', startDate);
    console.log('start date:', endDate);


    if (startDate && endDate) {
        // Convert dd/mm/yyyy to a Date object
        var start = parseDate(startDate);
        var end = parseDate(endDate);
        console.log('end:',endDate);
        var count = 0;
        var currentDate = start;

        while (currentDate <= end) {
            if (currentDate.getDay() === selectedDay) {
                count++;
            }
            currentDate.setDate(currentDate.getDate() + 1);
        }
        $('#numDays').val(count);
    }
}
$('#yearsdate').on('changeDate', function() {
      calculateEndDateAndDays();
      updateTotalCart();
  });

  function calculateEndDateAndDays() {
    var startDate = $('#yearsdate').val();
    
    if (startDate) {
        // Convert dd/mm/yyyy to a Date object
        var start = parseDate(startDate);
        var end = new Date(start); 

        end.setDate(end.getDate() + 365);  // Add 365 days

        // Convert back to dd/mm/yyyy format
        var day = ('0' + end.getDate()).slice(-2);
        var month = ('0' + (end.getMonth() + 1)).slice(-2);
        var year = end.getFullYear();

        var yearEndDateString = day + '/' + month + '/' + year;

        console.log(yearEndDateString);
        
        $('#yearedate').val(yearEndDateString);  // Display in dd/mm/yyyy
        $('#ydate').val(365);  // Set number of days to 365
    }
}

// function parseDate(dateStr) {
//     var dateParts = dateStr.split('/');
//     var day = parseInt(dateParts[0], 10);
//     var month = parseInt(dateParts[1], 10) - 1;  // JavaScript months are 0-based
//     var year = parseInt(dateParts[2], 10);

//     return new Date(year, month, day);  // Return a valid Date object
// }

function parseDate(input) {
    var parts = input.split('/');
    return new Date(parts[2], parts[1] - 1, parts[0]); // Note: months are 0-based
}




$("#submit").click(function() {

  var archanaitype_id = $('#archanaitype_id').val();
  var print_type = $("#print_type").val();
  console.log('print type:', print_type);

  if(archanaitype_id == '') {
    alert('Please select the Archanaitype');
    exit();
  }

  $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>/kattalai_archanai_online/save_booking",
      data: $("form").serialize(),
      beforeSend: function () {
          // $("#loader").show();
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
              if (data.status) {
                  $('#alert-modal').modal('show', { backdrop: 'static' });
                  $("#spndeddelid").text(data.succ);
                  $("#spndeddelid").css("color", "green");
                  
                  if (print_type == 'imin') {
                    window.open("<?php echo base_url(); ?>/kattalai_archanai_online/print_booking/" + data.id, "_blank");
                    window.location.reload();
                  } else {
                    window.open("<?php echo base_url(); ?>/kattalai_archanai_online/print_booking_report/" + data.id, "_blank");
                    window.location.reload();
                  }
                  
              } else {
                  $('#alert-modal').modal('show', { backdrop: 'static' });
                  $("#spndeddelid").text(data.err);
                  $("#spndeddelid").css("color", "red");
              }
          } else {
              $('#alert-modal').modal('show', { backdrop: 'static' });
              $("#spndeddelid").text(data.err);
              $("#spndeddelid").css("color", "red");
          }
      },
      error: function () {
          $('#alert-modal').modal('show', { backdrop: 'static' });
          $("#spndeddelid").text(data.err);
          $("#spndeddelid").css("color", "red");
      },
      complete: function () {
          // $("#loader").hide();
      }
  });


  });
});
</script>
