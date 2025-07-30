<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/demo.css"> 
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">-->

<style>
.links li img { display:none !important; }
.navbar1 .links li .sub-menu { top: 23px; }
.navbar1 .links li { min-width: 80px; }
[type="checkbox"] + label {
	display:none;
}
[type="checkbox"] + label.s_print {
	display:block;
}
.heading { text-align:center; background:#000; color:#FFF; padding:10px; }
.products { 
	background:#FFF;
	display: flex;
    flex-wrap: wrap;
    align-items: center; 
	max-height: 420px;
    overflow-y: scroll;
}
.products .col-md-3{ 
	margin-bottom: 0px;
}
.prod { background:#CCCCCC; padding:5px 3px; margin-top:3px; margin-bottom:3px; cursor:pointer; }
.prod img { width:30%; float:left; border-right:1px dashed #999999; }
.prod .detail { width:60%; position:relative; margin-left:40%; }
.prod .detail h4,.prod .detail h5 { font-weight:bold; }
.vl { border-left: 2px dashed #999999; height: 82%; position: absolute; left: 38%; margin-left: -3px; top: 0; bottom:0; margin-top:10px; }
.cart-table { width:100%; } 
.cart-table tr th, .rasi-table tr th { font-weight:600; padding:4px;  }
.cart-table tr td, .rasi-table tr td { padding:2px; font-size:12px; border :none;}
.row_amt,.row_qty,.row_tot,.tot  {border :none;width: 100%;}
.detail h5 { font-size:11px; }
form.example input[type=text] {
  padding: 10px;
  font-size: 17px;
  border: 1px solid grey;
  float: left;
  width: 90%;
  background: #f1f1f1;
}

form.example button {
  float: left;
  width: 10%;
  padding: 10px;
  background: #000;
  color: white;
  font-size: 17px;
  border: 1px solid grey;
  border-left: none;
  cursor: pointer;
}

form.example button:hover {
  background:#333333;
}

form.example::after {
  content: "";
  clear: both;
  display: table;
}
.all_close{
	height: auto;
}
.cart-body{
    overflow-y: scroll;
    overflow-x: hidden;
    /*height: 220px;*/
	height:100px;
	display: block;
}
.rasi-body { 
	overflow-y: scroll;
    overflow-x: hidden;
    /*height: 220px;*/
	height:50px;
	display: block; 
}
.cart-table thead, tbody.cart-body tr {
    display: table;
    width: 100%;
    table-layout: fixed;
}
.rasi-table thead, tbody.rasi-body tr {
    display: table;
    width: 100%;
    table-layout: fixed;
}
.arch_total { background:#CCC; color:#000; font-weight:bold; text-align:center; font-size:38px; padding:0px; line-height:40px; } 

.card .body .col-xs-12, .card .body .col-sm-12, .card .body .col-md-12, .card .body .col-lg-12 {
    margin-bottom: 0px !important;
}
.detail h4 { font-size:19px; }
.prod { min-height:110px; }
@media (min-width: 992px) and (max-width: 1285px) {
.detail h4 { font-size:19px; }
}






/*@media screen and (orientation:portrait) {
body {
    transform: rotate(-90deg);
    transform-origin: 50% 50%;
}
}*/
.name { min-height:45px; max-height:45px; }
.form-group { margin-bottom: 10px; }
hr { margin: 2px auto;}
.cart { padding: 5px 20px 0 !important;  }
.btn {
    padding: 5px 7px !important;
}
.card { margin-bottom: 10px !important; }
.form-control { height: 27px; }
.card .body { padding: 15px 20px; }
section.content { min-height: 470px; }
@media (min-width: 1020px) {
    .card .body, .btn, .form-control { font-size: 12px !important; }
    .arch_total { font-size: 22px !important; line-height: 30px !important; }
    .cart-table tr td, .rasi-table tr td { font-size: 10px !important; }
    .dropdown-menu > li > a { font-size: 12px; line-height: 14px; }
    .btn { padding: 5px 2px !important; }
    .submit_btn, .clear_btn { font-size:14px !important;padding:5px 15px !important; }
}
.header_txt { margin-bottom:25px; margin-top:5px; background:#999999; padding:3px 7px; }
</style>  
<section class="content">
    <div class="container-fluid">
        <!-- <div class="block-header">
            <h2> ARCHANAI Ticket Entry<small>Archanai / <b>Archanai Ticket</b></small></h2>
        </div>
        Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body">
                            <?php if($_SESSION['succ'] != '') { ?>
                                <div class="row" style="padding: 0 30%;" id="content_alert">
                                    <div class="suc-alert">
                                        <span class="suc-closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                                        <p><?php echo $_SESSION['succ']; ?></p> 
                                    </div>
                                </div>
                            <?php } ?>
                             <?php if($_SESSION['fail'] != '') { ?>
                                <div class="row" style="padding: 0 30%;" id="content_alert">
                                    <div class="alert">
                                        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                                        <p><?php echo $_SESSION['fail']; ?></p>
                                    </div>
                                </div>
                            <?php } ?>
                        <form  class="form" id="form" action="<?php echo base_url(); ?>/kattalai_archanai/save_booking" method="post">
                        <div class="container-fluid">
                            <div class="row">
                                  
                                <div class="col-sm-12">
                                    <h3 class="header_txt">Archanai Details</h3>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <select class="form-control">
                                                <option value="">--Select--</option>
                                                <?php foreach($archanai as $row) { ?>
                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['name_eng']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <label class="form-label">Archanai Type</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <select class="form-control">
                                                <option value="">--Select--</option>
                                                <?php foreach($archanai_diety as $row) { ?>
                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <label class="form-label">Diety</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="txt" class="form-control" >
                                            <label class="form-label">Contact No.</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="txt" class="form-control" >
                                            <label class="form-label">Email</label>
                                        </div>
                                    </div>
                                </div>
                                
                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <div class="form-line focused">
                                                <input type="txt" name="name" id="name" class="form-control" >
                                                <label class="form-label">Name</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group form-float">
                                            <div class="form-line focused">
                                                <select class="form-control" name="rasi_id" id="rasi_id" onchange="fetchNatchathiram()">
                                                    <option value="">--Select--</option>
                                                    <?php foreach($rasi as $row) { ?>
                                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name_eng']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <label class="form-label">Star</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2" style="margin: 0px;">
                                        <div class="form-group form-float">
                                            <div class="form-line focused" >
                                                <label class="form-label">Natchathiram</label>
                                                <input type="hidden" id="natchathram_id" name="natchathram_id" class="form-control">
                                                <select class="form-control" name="natchathra_id" id="natchathra_id">
                                                <option>Select Natchathiram</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <div class="form-group form-float">
                                            <div class="form-line" style="border: none;">
                                                <label id="pack_add" class="btn btn-success" style="padding: 5px 12px !important;" onClick="append();">Add More</label>
                                            </div>
                                        </div>
                                    </div>  
                                
                                <!-- <div class="row">
                                    <div id="dynamicFields">
                                        Initial fields will be inserted here
                                    </div>

                                    <div class="col-sm-1">
                                        <div class="form-group form-float">
                                            <div class="form-line" style="border: none;">
                                                <label id="pack_add" class="btn btn-success" style="padding: 5px 12px !important;" onClick="append();">Add More</label>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                               
                                <div class="col-sm-8">
                                    <table id="detailsTable" class="table">
                                        <thead>
                                            <tr>
                                                <th>S.no</th>
                                                <th>Name</th>
                                                <th>Rasi</th>
                                                <th>Natchathiram</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                               

                            <input type="hidden" id="tot_count" value="1">
                			<div class="addmore"></div>  
                                <div class="col-sm-12">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <textarea class="form-control" ></textarea>
                                            <label class="form-label">Description</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-12">
                                    <h3 class="header_txt">Date Details</h3>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group form-float">
                                    	<label>Type</label><br>
                                
                                        <input name="day" type="radio" id="radio_30" checked="checked" class="with-gap type radio-col-red" value="daily">
                                        <label for="radio_30" style="margin-right:15px;">Daily</label>
                                        <input name="day" type="radio" id="radio_31" class="with-gap type radio-col-red" value="weekly">
                                        <label for="radio_31" style="margin-right:15px;">Weekly</label>
                                        <input name="day" type="radio" id="radio_32" class="with-gap type radio-col-red" value="days">
                                        <label for="radio_32" style="margin-right:15px;">Multiple Date(s)</label>
                                        <input name="day" type="radio" id="radio_33" class="with-gap type radio-col-red" value="years">
                                        <label for="radio_33" style="margin-right:15px;">Years</label>
                                	</div>
                                </div>
                                <div id="daily">
                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <div class="form-line focused" id="bs_datepicker_component_container">
                                                <input type="text" id="sdate" name="date" class="form-control datepicker">
                                                <label class="form-label">Start Date <span style="color: red;">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <div class="form-line focused" id="bs_datepicker_component_container">
                                                <input type="text" id="edate" name="date" class="form-control datepicker">
                                                <label class="form-label">End Date <span style="color: red;">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <div class="form-line focused" id="bs_datepicker_component_container">
                                                <input type="text" id="tdate" class="form-control" readonly="readonly" >
                                                <label class="form-label">Total Days</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="weekly" style="display:none;">
                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <div class="form-line focused">
                                                <select class="form-control">
                                                    <option value="0">Sunday</option>
                                                    <option value="1">Monday</option>
                                                    <option value="2">Tuesday</option>
                                                    <option value="3">Wednesday</option>
                                                    <option value="4">Thursday</option>
                                                    <option value="5">Friday</option>
                                                    <option value="6">Saturday</option>
                                                </select>
                                                <label class="form-label">Star</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <div class="form-line focused" id="bs_datepicker_component_container">
                                                <input type="text" name="date" id="startDate" class="form-control datepicker">
                                                <label class="form-label">Start Date <span style="color: red;">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <div class="form-line focused" id="bs_datepicker_component_container">
                                                <input type="text" name="date" id="endDate" class="form-control datepicker">
                                                <label class="form-label">End Date <span style="color: red;">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <div class="form-line focused" id="bs_datepicker_component_container">
                                                <input type="text" id="numDays" class="form-control" readonly="readonly">
                                                <label class="form-label">Total Days</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="day" style="display:none;">
                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <div class="form-line focused" id="bs_datepicker_component_container">
                                                <input type="text" id="multiDatePicker" class="form-control" autocomplete="off">
                                                <label class="form-label">Date's <span style="color: red;">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-3">
                                        <div class="form-group form-float">
                                            <div class="form-line focused" id="bs_datepicker_component_container">
                                                <input type="text" id="totalDays" class="form-control" readonly>
                                                <label class="form-label">Total Days</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="years" style="display:none;">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group form-float">
                                                <div class="form-line focused" id="bs_datepicker_component_container">
                                                    <label class="form-label">Start Date <span style="color: red;">*</span></label>
                                                    <input type="text" id="yearsdate" name="yearsdate" class="form-control datepicker">
                                                </div>
                                            </div>
                                        </div>
                        
                                        <div class="col-sm-6">
                                            <div class="form-group form-float">
                                                <div class="form-line focused" id="bs_datepicker_component_container">
                                                    <label class="form-label">End Date <span style="color: red;">*</span></label>
                                                    <input type="text" readonly="readonly" id="yearedate" name="yearedate" class="form-control ">
                                                </div>
                                            </div>
                                        </div>
                        
                                        <div class="col-sm-6">
                                            <div class="form-group form-float">
                                                <div class="form-line focused" id="bs_datepicker_component_container">
                                                    <label class="form-label">Total Days</label>
                                                    <input type="text" readonly="readonly" id="ydate" name="ydate" class="form-control" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <h3 class="header_txt">Payment Details</h3>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line focused" >
                                            <label class="form-label">Total Amount</label>
                                            <input type="text" readonly="readonly" class="form-control total-cart">
                                            <input type="hidden" id="tot_amt" name="tot_amt">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select class="form-control" name="paymentmode" id="paymentmode" <?php echo $disable; ?>>
                                                <?php foreach($payment_modes as $payment_mode) { ?>
                                                <option value="<?php echo $payment_mode['id']; ?>" <?php if(!empty($data['payment_mode'])){ if($data['payment_mode'] == $payment_mode['id']){ echo "selected"; } } ?>><?php echo $payment_mode['name'];?></option>
                                                <?php } ?>
                                            </select>
                                            <label class="form-label">Paymentmode <span style="color: red;">*</span></label>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- <div class="total d-flex justify-content-between align-items-center" style="width:100%; border-bottom:1px dashed #CCC;">
                                    <p class="mb-0">Total </p>
                                    <p class="mb-0">SGD : <span class="total-cart"></span></p>
                                    <input type="hidden" id="tot_amt" name="tot_amt">
                                </div> -->

                            </div>
                            <div class="form-group" style="align:right;">
                                <input type="button" name="submit" value="Save" class="btn btn-info submit_btn" id="submit">
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Image loader -->
    <div id='loader' style='display: none;'>
        <img src='reload.gif' width='32px' height='32px'>
    </div>
    <!-- Image loader -->                                                        
    <div id="alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
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
</section>
<!--<link href="https://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.min.css" rel="stylesheet">
<script src="https://code.jquery.com/ui/1.10.2/jquery-ui.min.js"></script>-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>


<link href="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>

<script src="<?php echo base_url(); ?>/assets/archanai/js/jquery.min.js"></script>


<script>

function fetchNatchathiram() {
    var rasi = $("#rasi_id").val();

    if (rasi != "") {
        $.ajax({
            url: '<?php echo base_url(); ?>/kattalai_archanai/get_natchathram',
            type: 'post',
            data: { rasi_id: rasi },
            dataType: 'json',
            success: function(response) {
                console.log('natchathiram', response);
                var natchathramDropdown = $("#natchathra_id");
                natchathramDropdown.empty();  // Clear existing options
                natchathramDropdown.append('<option value="">Select Natchathiram</option>');

                if (response.natchathra_id) {
                    var str = response.natchathra_id;

                    $.each(str.split(','), function(key, value) {
                        $.ajax({
                            url: '<?php echo base_url(); ?>/kattalai_archanai/get_natchathram_name',
                            type: 'post',
                            data: { id: value },
                            dataType: 'json',
                            success: function(natchathraResponse) {
                                console.log('natchathiram_name', natchathraResponse);
                                natchathramDropdown.append('<option value="' + natchathraResponse.id + '">' + natchathraResponse.name_eng + '</option>');
                                natchathramDropdown.selectpicker("refresh");
                            }
                        });
                    });
                }
            }
        });
    }
}

function append() {
    var name = document.getElementById('name').value;
    var rasi = document.getElementById('rasi_id').options[document.getElementById('rasi_id').selectedIndex].text;
    var natchathiram = document.getElementById('natchathra_id').options[document.getElementById('natchathra_id').selectedIndex].text;

    if(name !== "" && rasi !== "--Select--" && natchathiram !== "Select Natchathiram") {
        var table = document.getElementById('detailsTable').getElementsByTagName('tbody')[0];
        var newRow = table.insertRow(table.rows.length);

        var cell1 = newRow.insertCell(0);
        var cell2 = newRow.insertCell(1);
        var cell3 = newRow.insertCell(2);
        var cell4 = newRow.insertCell(3);
        var cell5 = newRow.insertCell(4);

        cell1.innerHTML = table.rows.length; 
        cell2.innerHTML = name;
        cell3.innerHTML = rasi;
        cell4.innerHTML = natchathiram;
        cell5.innerHTML = '<button onclick="removeRow(this)">X</button>';
        updateSerialNumbers();
        
        document.getElementById('name').value = '';
        document.getElementById('rasi_id').selectedIndex = 0;
        document.getElementById('natchathra_id').selectedIndex = 0;
    } else {
        alert('Please fill in all fields before adding.');
    }
}

function removeRow(button) {
    var row = button.parentNode.parentNode;
    row.parentNode.removeChild(row);
    updateSerialNumbers();
}

function updateSerialNumbers() {
    $("#detailsTable tbody tr").each(function(index) {
        $(this).find("td:first").text(index + 1); // Update S.no
    });
}

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
            updateTotalCart();
        });

        $('.datepicker').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true
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
            format: 'mm/dd/yyyy',
            multidate: true,
            autoclose: false
        }).on('changeDate', function(e) {
            var selectedDates = $(this).datepicker('getDates');
            $('#totalDays').val(selectedDates.length);
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

            if (totalDays) {
                var archanaitype_id = $('#archanaitype_id').val();  
                // alert(archanaitype_id);
                $.ajax({
                    url: '<?php echo base_url();?>/kattalai_archanai/fetch_amount',
                    type: 'POST',
                    data: { archanaitype_id: archanaitype_id },
                    dataType: 'json',
                    success: function(response) {
                        // console.log(response.amount);
                        if (response.amount !== undefined) {
                            var amountPerDay = parseFloat(response.amount);  
                            
                            if (!isNaN(amountPerDay)) {  
                                // var totalDays = parseInt($('#totalDays').val());  // Parse totalDays as an integer
                                
                                if (!isNaN(totalDays)) {
                                    var totalAmount = amountPerDay * totalDays;
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
            if (startDate && endDate) {
                var start = new Date(startDate);
                var end = new Date(endDate);
                var timeDiff = end - start;
                if (timeDiff >= 0) {
                    var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
                    $('#tdate').val(diffDays);
                } else {
                    $('#tdate').val('Invalid range');
                }
            }
        }

        function calculateSpecificDays() {
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var selectedDay = parseInt($('#dayOfWeek').val());
            if (startDate && endDate) {
                var start = new Date(startDate);
                var end = new Date(endDate);
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
            var start = new Date(startDate);  
            var end = new Date(start); 
            
            end.setDate(end.getDate() + 365);
            
            var yearEndDateString = end.toISOString().split('T')[0];
            
            $('#yearedate').val(yearEndDateString);  
            $('#ydate').val(365);  
        }
    }
});
</script>
<script>

    $("#submit").click(function(){
        $.ajax
        ({
            type:"POST",
            url: "<?php echo base_url(); ?>/archanaibooking/save",
            data: $("form").serialize(),
            beforeSend: function() {    
				$("#submit").prop('disabled', true);
			},
            success:function(data)
            {
				console.log(data);
                obj = jQuery.parseJSON(data);
                if(obj.err != ''){
                    $('#alert-modal').modal('show', {backdrop: 'static'});
                    $("#spndeddelid").text(obj.err);
                }else{					
					if ($("#print").prop('checked')==true && $("#s_print").prop('checked')==false)	
                    {
                        printData(obj.id);
                    }
					else if ($("#print").prop('checked')==true && $("#s_print").prop('checked')==true)
                    {
                        printData_sep(obj.id);
                    }
					else
                    {
                        window.location.reload(true);
                    }
                }
            },error:function(err)
            {
				$("#submit").prop('disabled', false);
				console.log('err');
				console.log(err);
			}
        });
    }); 
    $("#submit_kzhanji").click(function(){
        $.ajax
        ({
            type:"POST",
            url: "<?php echo base_url(); ?>/archanaibooking/save",
            data: $("form").serialize(),
            beforeSend: function() {    
				$("#submit_kzhanji").prop('disabled', true);
			},
            success:function(data)
            {
				console.log(data);
                obj = jQuery.parseJSON(data);
                if(obj.err != ''){
                    $('#alert-modal').modal('show', {backdrop: 'static'});
                    $("#spndeddelid").text(obj.err);
                }else{					
					if ($("#print").prop('checked')==true)
                    {
                        printData_kzhanji(obj.id);
                    }
					else
                    {
                        window.location.reload(true);
                    }
                }
            },error:function(err)
            {
				$("#submit_kzhanji").prop('disabled', false);
				console.log('err');
				console.log(err);
			}
        });
    }); 
    
	$("#submit_mob").click(function(){
        $.ajax
        ({
            type:"POST",
            url: "<?php echo base_url(); ?>/archanaibooking/save",
            data: $("form").serialize(),
            beforeSend: function() {    
				//$("#submit").prop('disabled', true);
                $("#loader").show();
			},
            success:function(data)
            {
               // return;
				console.log(data);
                obj = jQuery.parseJSON(data);
                if(obj.err != ''){
                    $('#alert-modal').modal('show', {backdrop: 'static'});
                    $("#spndeddelid").text(obj.err);
                }else{
                    if ($("#print").prop('checked')==true && $("#s_print").prop('checked')==false)	
                    {
                        printData(obj.id);
                    }
					else if ($("#print").prop('checked')==true && $("#s_print").prop('checked')==true)
                    {
                        printData_sep(obj.id);
                    }
					else
                    {
                        window.location.reload(true);
                    }					
					/* if ($("#print").prop('checked')==true && $("#s_print").prop('checked')==false)	 window.open("<?php echo base_url(); ?>/archanaibooking/print_booking/" + obj.id, "_blank");
					else if ($("#print").prop('checked')==true && $("#s_print").prop('checked')==true) window.open("<?php echo base_url(); ?>/archanaibooking/print_booking_sep/" + obj.id, "_blank"); */
					//window.open("<?php echo base_url(); ?>/archanaibooking/print_booking1/" + obj.id, "_blank");
					
					//window.location.reload(true);
                }
            },
            complete:function(data){
                // Hide image container
                $("#loader").hide();
            },
            error:function(err)
            {
				$("#submit").prop('disabled', false);
				console.log('err');
				console.log(err);
			}
        });
    });  
    function printData(id) {
		
		// if ($("#print").prop('checked')==true)	
		// {
			$.ajax({
				url: "<?php echo base_url(); ?>/archanaibooking/print_booking/"+id,
				type: 'POST',
				success: function (result) {
					//console.log(result)
					popup(result);
				}
			});
		// }
		// else window.location.reload(true);
    }
	function printData_kzhanji(id) {
		
		// if ($("#print").prop('checked')==true)	
		// {
			$.ajax({
				url: "<?php echo base_url(); ?>/archanaibooking/print_booking_kzhanji/"+id,
				type: 'POST',
				success: function (result) {
					//console.log(result)
					popup_kzhanji(result);
				}
			});
		// }
		// else window.location.reload(true);
    }
	function printData_sep(id) {
		
			$.ajax({
				url: "<?php echo base_url(); ?>/archanaibooking/print_booking_sep/"+id,
				type: 'POST',
				success: function (result) {
					//console.log(result)
					popup_sep(result);
				}
			});
    }

    function popup(data)
    {
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        
		frameDoc.document.open();
		window.frameDoc = frameDoc;
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body >');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function () {
			
            window.frameDoc.focus();
            window.frameDoc.print();
            frame1.remove();
            window.location.reload(true);
        }, 500);
    }
	
	function popup_sep(data)
    {
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        
		frameDoc.document.open();
		window.frameDoc = frameDoc;
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body >');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function () {
			
            window.frameDoc.focus();
            window.frameDoc.print();
            frame1.remove();
            window.location.reload(true);
        }, 500);
    }

    function popup_kzhanji(data)
    {
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        
		frameDoc.document.open();
		window.frameDoc = frameDoc;
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body >');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function () {
			
            window.frameDoc.focus();
            window.frameDoc.print();
            frame1.remove();
            window.location.reload(true);
        }, 500);
    }
</script>
  
<script>

// $(document).ready(function() {
//     $('#dynamicFields').html(createEntryFields(entryCount));
// });

//     var entryCount = 0; // This will keep track of the number of entries

//     function append() {
//         var name = $('#name_' + entryCount).val();
//         var rasi = $('#rasi_id_' + entryCount).find('option:selected').text();
//         var rasiId = $('#rasi_id_' + entryCount).val();
//         var natchathiram = $('#natchathra_id_' + entryCount).find('option:selected').text();
//         var natchathiramId = $('#natchathra_id_' + entryCount).val();

//         console.log(name);
//         console.log(rasiId);
//         console.log(natchathiramId);

//         if (!name || !rasiId || !natchathiramId) {
//             alert('Please fill all the fields.');
//             return;
//         }

//         // Add the data to the table
//         $('#detailsTable tbody').append(`<tr>
//             <td>${entryCount + 1}</td>
//             <td>${name}</td>
//             <td>${rasi}</td>
//             <td>${natchathiram}</td>
//         </tr>`);

//         // Prepare fields for the next entry
//         entryCount++;
//         $('#dynamicFields').append(createEntryFields(entryCount));
//         $('#name_' + entryCount).val('');
//         $('#rasi_id_' + entryCount).val('');
//         $('#natchathra_id_' + entryCount).empty().append('<option value="">Select Natchathiram</option>');
//     }

//     function createEntryFields(count) {
//         return `
//             <div class="col-sm-3">
//                 <div class="form-group form-float">
//                     <div class="form-line focused">
//                         <input type="text" id="name_${count}" class="form-control">
//                         <label class="form-label">Name</label>
//                     </div>
//                 </div>
//             </div>
//             <div class="col-sm-2">
//                 <div class="form-group form-float">
//                     <div class="form-line focused">
//                         <select class="form-control" name="rasi_id[${count}]" id="rasi_id_${count}" onchange="fetchNatchathiram(${count})">
//                             <option value="">--Select--</option>
//                             <?php foreach($rasi as $row) { ?>
//                             <option value="<?php echo $row['id']; ?>"><?php echo $row['name_eng']; ?></option>
//                             <?php } ?>
//                         </select>
//                         <label class="form-label">Rasi</label>
//                     </div>
//                 </div>
//             </div>
//             <div class="col-sm-2" style="margin: 0px;">
//                 <div class="form-group form-float">
//                     <div class="form-line focused">
//                         <select class="form-control" name="natchathra_id[${count}]" id="natchathra_id_${count}">
//                             <option>Select Natchathiram</option>
//                         </select>
//                         <label class="form-label">Natchathiram</label>
//                     </div>
//                 </div>
//             </div>
//         `;
//     }


// console.log(name);
//     console.log(rasiId);
//     console.log(natchathiramId);

// function append() {
//     var count = parseInt($("#tot_count").val());
//     var max_fields = 4;

//     if (count < max_fields) {
//         var html = '<div class="row" id="row_' + count + '">';

//         // Name field
//         html += '<div class="col-sm-4"><div class="form-group form-float">';
//         html += '<div class="form-line focused">';
//         html += '<label class="form-label">Name</label>';
//         html += '<input type="text" class="form-control" name="name[' + count + ']"></div></div></div>';

//         // Rasi dropdown
//         html += '<div class="col-sm-3"><div class="form-group form-float">';
//         html += '<div class="form-line focused">';
//         html += '<label class="form-label">Rasi</label>';
//         html += '<select class="form-control rasi" name="rasi_id[' + count + ']" id="rasi_id_' + count + '" onchange="fetchNatchathiram(' + count + ')">';
//         html += '<option value="">--Select--</option>';
//         <?php foreach ($rasi as $row) { ?>
//             html += '<option value="<?php echo $row['id']; ?>"><?php echo $row['name_eng']; ?></option>';
//         <?php } ?>
//         html += '</select></div></div></div>';

//         // Natchathiram dropdown
//         html += '<div class="col-sm-4"><div class="form-group form-float">';
//         html += '<div class="form-line focused">';
//         html += '<label class="form-label">Natchathiram</label>';
//         html += '<select class="form-control natchathra" name="natchathra_id[' + count + ']" id="natchathra_id_' + count + '">';
//         html += '<option value="">Select Natchathiram</option>';
//         html += '</select></div></div></div>';

//         // Remove button
//         html += '<div class="col-sm-1" align="right">';
//         html += '<br><button class="btn btn-danger" style="padding: 5px !important;" onclick="remove_row(' + count + ')"> X </button>';
//         html += '</div></div>';

//         $(".addmore").append(html);

//         // Increment count
//         var cnt = count + 1;
//         $("#tot_count").val(cnt);
//     }
// }
</script>