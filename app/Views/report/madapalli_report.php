<style>
    .btn-default,
    .btn-default:hover,
    .btn-default:active,
    .btn-default:focus {
        background: transparent !important;
    }

    .form-group {
        margin-bottom: 0 !important;
    }

    .col-sm-3 {
        margin-bottom: 10px !important;
    }

    .table tr th,
    .table tr td {
        text-align: center;
    }

    .paid_text {
        color: green;
        font-weight: 600;
    }

    .unpaid_text {
        color: red;
        font-weight: 600;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2> MADAPALLI REPORT <small><b>Madapalli Report</b></small></h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body">
                        <form action="<?php echo base_url(); ?>/report/print_madapalli" method="get"
                            target="_blank">
                            <div class="container-fluid">
                                <div class="row clearfix">
                                    <!-- <div class="col-md-2 col-sm-4">
                                            <div class="form-group form-float">
                                                <div class="form-line" id="bs_datepicker_container" >
                                                    <input type="date" name="collection_date" id="collection_date" class="form-control" value="<?php echo date('Y-m-d'); ?>"  >
                                                    <label class="form-label">Collection Date</label>
                                                </div>                                                        
                                            </div>                                            
                                        </div> -->
                                    <div class="col-md-2 col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="form-line" id="bs_datepicker_container">
                                                <input type="date" name="fdt" id="fdt" class="form-control" value="<?php echo $from_date; ?>">
                                                <label class="form-label">Date </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-2 col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="form-line" id="bs_datepicker_container">
                                                <input type="date" name="tdt" id="tdt" class="form-control" value="">
                                                <label class="form-label">To Date (Collection)</label>
                                            </div>
                                        </div>
                                    </div> -->


                                    <div class="cocol-md-2 col-sm-4">
                                        <div class="form-group form-float">
                                            <label type="submit" class="btn btn-success btn-lg waves-effect"
                                                id="submit">Submit</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12" style="margin:0px;">
                                        <button type="submit" class="btn btn-primary btn-lg waves-effect"
                                            id="submit">Print</button>
                                        <!-- <input name="pdf_prasadamreport" type="submit"
                                            class="btn btn-danger btn-lg waves-effect" id="pdf_prasadamreport"
                                            value="PDF">
                                        <input name="excel_prasadamreport" type="submit"
                                            class="btn btn-success btn-lg waves-effect" id="excel_prasadamreport"
                                            value="EXCEL"> -->
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive col-md-12 det" style="background:#FFF; float:none;">
                        <h3>Prasadam</h3><br>
                            <table class="table table-striped dataTable" id="datatables">
                                <thead>
                                    <tr>
                                        <th style="width:10%;">S.No</th>
                                        <th style="width:20%;">Products</th>
                                        <th style="width:30%;">Quantity</th>
                                        <th style="width:20%;">Session</th>
                                        <th style="width:20%;">Details</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive col-md-12 det" style="background:#FFF; float:none;">
                        <h3>Annathanam</h3><br>
                            <table class="table table-striped dataTable" id="datatablesa">
                                <thead>
                                    <tr>
                                        <th style="width:10%;">S.No</th>
                                        <th style="width:20%;">Products</th>
                                        <th style="width:30%;">Quantity</th>
                                        <th style="width:20%;">Session</th>
                                        <th style="width:20%;">Details</th>
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
    <div class="modal fade" id="reprintModal" tabindex="-1" aria-labelledby="reprintModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reprintModalLabel">Booking Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S. No</th>
                            <th>Name</th>
							<th>Mobile</th>
							<th>Quantity</th>
							<th>Amount</th>
                            <th>Session</th>
                        </tr>
                    </thead>
                    <tbody id="detailsTableBody">
                        <!-- Data will be appended here via JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</section>
<script>
    //$(document).ready
    // (
    //     function()
    //     {        
    //         report = $('#datatables').DataTable({
    //             dom: 'Bfrtip',
    //             buttons: [],
    //             "ajax":{
    //                 url: "<?php echo base_url(); ?>/report/prasadam_collection_rep_ref",
    //                 dataType: "json",
    //                 type: "POST",
    //                 data: function ( data ) {
    //                     data.collection_date = $('#collection_date').val();
    //                      data.fltername = $('#fltername').val();
    //                     }
    //             },
    //         });

    //         $('#submit').click(function() {
    // 			report.ajax.reload();
    //         });

    //     });
    $(document).ready(function () {
        report = $('#datatables').DataTable({
            dom: 'Bfrtip',
            buttons: [],
            "ajax": {
                url: "<?php echo base_url(); ?>/report/madapalli_rep",
                dataType: "json",
                type: "POST",
                data: function (data) {
                    data.fdt = $('#fdt').val();  
                    
                }
            }
        });
        reporta = $('#datatablesa').DataTable({
            dom: 'Bfrtip',
            buttons: [],
            "ajax": {
                url: "<?php echo base_url(); ?>/report/madapalli_repa",
                dataType: "json",
                type: "POST",
                data: function (data) {
                    data.fdt = $('#fdt').val();  
                    
                }
            }
        });

        $('#submit').click(function () {
            report.ajax.reload();  
            reporta.ajax.reload();  
        });
        $(document).on("click", ".btn-reprint", function (e) {
    e.preventDefault();
    
    var productId = $(this).data("id");
    var date = $(this).data("date");
    var slot = $(this).data("slot");
    var type = $(this).data("type");

    $.ajax({
        type: "POST",
        url: "<?= base_url(); ?>/report/get_madapalli_user_details", 
        data: { id: productId, date: date, session: slot, type: type },
        success: function (response) {
            try {
                var obj = JSON.parse(response);
                console.log('Result:', obj);
                
                $("#detailsTableBody").empty();

                if (!obj.list || obj.list.length === 0) {
                    $("#detailsTableBody").append("<tr><td colspan='6' class='text-center'>No data available</td></tr>");
                } else {
                    $.each(obj.list, function (index, item) {
                        $("#detailsTableBody").append(
                            "<tr>" +
                                "<td>" + (index + 1) + "</td>" +
                                "<td>" + item.name + "</td>" +
                                "<td>" + item.mobile + "</td>" +
                                "<td>" + item.quantity + "</td>" +
                                "<td>" + item.amount + "</td>" +
                                "<td>" + item.session + "</td>" +
                            "</tr>"
                        );
                    });
                }

                $("#reprintModal").modal("show");
            } catch (error) {
                alert("Error processing response.");
                console.error("Parsing error:", error);
            }
        },
        error: function () {
            alert("Error fetching data.");
        }
    });
});

    });

</script>