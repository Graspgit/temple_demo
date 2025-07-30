<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/typicons/typicons.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/css/vendor.bundle.base.css">
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

    .ar_btn {
        background: linear-gradient(179deg, rgb(0 126 212) 0%, rgb(16 197 180) 35%, rgb(59 134 209) 100%);
        border-radius: 15px;
        font-weight: bold;
        height: 2.75em;
        margin: 10px;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;

        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 155px auto 20px auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-height: 60vh;
        overflow-y: auto;
    }

    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        z-index: -1;
        width: 100vw;
        height: 100vh;
        background-color: #000;
    }

    .body-no-scroll {
        overflow: hidden;
        height: 100%;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
    
    .capitalize{
        text-transform: capitalize;
        text-align: center;
    }
    
    .card .header1 {
        color: #555;
        padding: 10px 20px;
        position: relative;
        border-bottom: 1px solid rgba(204, 204, 204, 0.35);
    }

    .card .body {
        font-size: 14px;
        color: #222222;
        padding: 20px;
    }
    
    .tax-summary {
        background: #f0f8ff;
        border: 2px solid #4169e1;
        padding: 15px;
        margin: 20px 0;
        border-radius: 5px;
    }
    
    .tax-summary h4 {
        color: #4169e1;
        margin-bottom: 10px;
    }
    
    .tax-icon {
        color: #4169e1;
        margin-right: 5px;
    }
</style>

<body class="sidebar-icon-only">
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="col-md-12">
                        <h3 style="text-align: center"><i class="fa fa-file-text tax-icon"></i>Tax Exemption Donations Report</h3>
                    </div><br>

                    <div class="header1">
                        <form action="<?php echo base_url(); ?>/report_online/print_tax_exemption_report" method="POST">
                            <div class="container-fluid">
                                <div class="row clearfix">
                                    <div class="col-md-2 col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="form-line" id="bs_datepicker_container">
                                                <input type="date" name="fdt" id="fdt" class="form-control" value="<?php echo $from_date; ?>">
                                                <label class="form-label">From Date</label>
                                            </div>                                                        
                                        </div>                                            
                                    </div>
                                    <div class="col-md-2 col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="form-line" id="bs_datepicker_container">
                                                <input type="date" name="tdt" id="tdt" class="form-control" value="<?php echo $to_date; ?>">
                                                <label class="form-label">To Date</label>
                                            </div>                                                        
                                        </div>                                            
                                    </div>
                                    <div class="col-md-2 col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <select class="form-control" name="payfor" id="payfor">
                                                    <option value="0">All Donation Types</option>
                                                    <?php foreach ($dons_set as $row) { ?>
                                                            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <label class="form-label"></label>
                                            </div>
                                        </div>                                            
                                    </div>
                                    <div class="col-md-2 col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" name="fltername" id="fltername" class="form-control" placeholder="Search by donor name...">
                                                <label class="form-label">Donor Name</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4">
                                        <label type="submit" class="btn btn-success btn-lg waves-effect" id="submit">
                                            <i class="fa fa-search"></i> FILTER
                                        </label>
                                    </div>

                                    <div class="col-md-12 col-sm-12" style="margin:0px;">                                    
                                        <button type="submit" class="btn btn-primary btn-lg waves-effect" id="submit">
                                            <i class="fa fa-print"></i> PRINT
                                        </button>
                                        <input name="pdf_taxexemptionreport" type="submit" class="btn btn-danger btn-lg waves-effect" id="pdf_taxexemptionreport" value="PDF">
                                        <input name="excel_taxexemptionreport" type="submit" class="btn btn-success btn-lg waves-effect" id="excel_taxexemptionreport" value="EXCEL">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                        
                    <div class="body">
                        <!-- Tax Summary Box -->
                        <div class="tax-summary" id="tax_summary" style="display:none;">
                            <h4><i class="fa fa-info-circle"></i> Tax Exemption Summary</h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Total Tax Exempt Donations:</strong> 
                                    <span id="totalTaxAmount" style="font-size: 18px; color: #4169e1;">RM 0.00</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Number of Donations:</strong> 
                                    <span id="totalTaxCount" style="font-size: 18px; color: #4169e1;">0</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Average Donation:</strong> 
                                    <span id="avgTaxAmount" style="font-size: 18px; color: #4169e1;">RM 0.00</span>
                                </div>
                            </div>
                        </div>
                        
                       <div class="table-responsive col-md-12 det" style="background:#FFF; float:none;">
                            <table style="width:100%;" align="center" class="table table-striped dataTable" id="datatables">
                                <thead>
                                    <tr>
                                        <th style="width:5%;">S.No</th>
                                        <th style="width:10%;">Date</th>
                                        <th style="width:10%;">Receipt No</th>
                                        <th style="width:15%;">Donor Name</th>
                                        <th style="width:10%;">IC Number</th>
                                        <th style="width:10%;">Mobile</th>
                                        <th style="width:15%;">Donation Type</th>
                                        <th style="width:10%;">Amount(RM)</th>
                                        <th style="width:10%;">Payment Method</th>
                                        <th style="width:15%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>            
                    </div>
                </div>   
            </div>

            <!-- Alert Modal -->
            <div id="alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content" style="width: 100%;">
                        <div class="modal-body">
                            <div class="text-center">
                                <i class="dripicons-information h1 text-info"></i>
                                <table>
                                    <tr><span id="spndeddelid"><b></b></span>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-info my-3" data-dismiss="modal"> &times;</button></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
  
<script src="<?php echo base_url(); ?>/assets/archanai/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/archanai/vendors/js/vendor.bundle.base.js"></script>
<script src="<?php echo base_url(); ?>/assets/archanai/vendors/chart.js/Chart.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/archanai/js/jquery.cookie.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/assets/archanai/js/off-canvas.js"></script>
<script src="<?php echo base_url(); ?>/assets/archanai/js/hoverable-collapse.js"></script>
<script src="<?php echo base_url(); ?>/assets/archanai/js/template.js"></script>
<script src="<?php echo base_url(); ?>/assets/archanai/js/settings.js"></script>
<script src="<?php echo base_url(); ?>/assets/archanai/js/todolist.js"></script>
<script src="<?php echo base_url(); ?>/assets/archanai/js/dashboard.js"></script>
<script src="<?php echo base_url(); ?>/assets/archanai/script.js"></script>
  
<link href="https://panel.srimuneeswaran.org/dev/assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">  
  
<!-- Jquery DataTable Plugin Js -->
<script src="https://panel.srimuneeswaran.org/dev/assets/plugins/jquery-datatable/jquery.dataTables.js"></script>
<script src="https://panel.srimuneeswaran.org/dev/assets/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
<script src="https://panel.srimuneeswaran.org/dev/assets/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
<script src="https://panel.srimuneeswaran.org/dev/assets/plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
<script src="https://panel.srimuneeswaran.org/dev/assets/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
<script src="https://panel.srimuneeswaran.org/dev/assets/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
<script src="https://panel.srimuneeswaran.org/dev/assets/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
<script src="https://panel.srimuneeswaran.org/dev/assets/plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
<script src="https://panel.srimuneeswaran.org/dev/assets/plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>

<script src="https://panel.srimuneeswaran.org/dev/assets/js/pages/tables/jquery-datatable.js"></script>
<script>
$(document).ready(function() {
    var report = $('#datatables').DataTable({
        dom: 'Bfrtip',
        paging: false,
        buttons: [],
        "ajax": {
            url: "<?php echo base_url(); ?>/report_online/tax_exemption_rep_ref",
            dataType: "json",
            type: "POST",
            data: function(data) {
                data.fdt = $('#fdt').val();
                data.tdt = $('#tdt').val();
                data.payfor = $('#payfor').val();
                data.fltername = $('#fltername').val();
            },
            dataSrc: function(json) {
                // Update summary box
                if (json.totalAmount) {
                    $('#tax_summary').show();
                    $('#totalTaxAmount').text('RM ' + json.totalAmount);
                    $('#totalTaxCount').text(json.recordsTotal);
                    
                    // Calculate average
                    var avg = json.recordsTotal > 0 ? (parseFloat(json.totalAmount.replace(/,/g, '')) / json.recordsTotal).toFixed(2) : '0.00';
                    $('#avgTaxAmount').text('RM ' + formatNumber(avg));
                }
                return json.data;
            }
        },
    });

    $('#submit').click(function() {
        report.ajax.reload();
    });
    
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
});
</script>
</body>