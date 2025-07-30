<?php global $lang;?>
<?php $booking_calendar_range_year = booking_calendar_range_year($_SESSION['booking_range_year']); ?>
<style>
.btn-default, .btn-default:hover, .btn-default:active, .btn-default:focus {
    background: transparent !important;
}
.form-group { margin-bottom:0 !important; }
.col-sm-3 { margin-bottom:10px !important; }
.table tr th, .table tr td { text-align:center; }
</style>
  <section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Kattalai <?php echo $lang->archanai; ?><?php echo $lang->report; ?>  <small><?php echo $lang->archanai; ?> / <b>Kattalai <?php echo $lang->archanai; ?> <?php echo $lang->booking; ?> <?php echo $lang->report; ?></b></small></h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                        <div class="body">
                            
                       <form action="<?php echo base_url(); ?>/kattalai_archanai/kattalai_archanai_report" method="POST">
                                <div class="container-fluid">
                                    <div class="row clearfix">
                                        <div class="col-md-2 col-sm-3">
                                            <div class="form-group form-float">
                                                <div class="form-line" id="bs_datepicker_container" >
                                                    <input type="date" name="fdt" id="fdt" class="form-control" value="<?php echo date('Y-m-01'); ?>"  max="<?php echo $booking_calendar_range_year; ?>">
                                                    <label class="form-label"><?php echo $lang->from; ?></label>
                                                </div>                                                        
                                            </div>                                            
                                        </div>
                                        <div class="col-md-2 col-sm-3">
                                            <div class="form-group form-float">
                                                <div class="form-line" id="bs_datepicker_container" >
                                                    <input type="date" name="tdt" id="tdt" class="form-control" value="<?php echo date('Y-m-d'); ?>"  max="<?php echo $booking_calendar_range_year; ?>">
                                                    <label class="form-label"><?php echo $lang->to; ?></label>
                                                </div>                                                        
                                            </div>                                            
                                        </div>

										<div class="col-md-2 col-sm-4">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <select class="form-control" name="group_filter" id="group_filter">
                                                    <option value="0" <?php echo (empty($group_filter) || $group_filter == '0' ? 'selected' : ''); ?>>Select Type</option>
                                                        <option value="daily" <?php echo ($group_filter == 'daily' ? 'selected' : ''); ?>>Daily</option>
                                                        <option value="weekly" <?php echo ($group_filter == 'weekly' ? 'selected' : ''); ?>>Weekly</option>
                                                        <option value="days" <?php echo ($group_filter == 'days' ? 'selected' : ''); ?>>Multiple Dated</option>
                                                        <option value="years" <?php echo ($group_filter == 'years' ? 'selected' : ''); ?>>Yearly</option>
                                                    </select>
                                                    <label class="form-label"></label>
                                                </div>
											</div>                                            
                                        </div>
										
                                        <div class="col-md-3 col-sm-4">
                                            <!-- <button type="submit" id="submit" class="btn btn-success">Filter</button> -->
                                            <label type="submit" class="btn btn-success btn-lg waves-effect" id="submit">Filter</label>
                                        </div>
											
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive col-md-12 det" style="background:#FFF; float:none;">
                                <table style="width:100%;" align="center" class="table table-striped dataTable" id="datatables">
                                            <thead>
											    <tr>
													<th style="width:5%;">S.No</th>
													<th style="width:8%;">Date</th>
													<th style="width:15%;text-align:left;">Devotee Name</th>
													<th style="width:9%;">Types</th>
													<th style="width:12%;">Payment Type</th>
													<th style="width:10%;">Amount</th>
													<th style="width:10%;">Paid Amount</th>
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
</section>
<script>
    
    $(document).ready
(
    function()

    {
        report = $('#datatables').DataTable({
            dom: 'Bfrtip',
            buttons: [],
            "ajax":{
                url: "<?php echo base_url(); ?>/kattalai_archanai/kattalai_archanai_report_ref",
                dataType: "json",
                type: "POST",
                
                data: function ( data ) {

                    data.fdt = $('#fdt').val();
                    data.tdt = $('#tdt').val();
                    data.group_filter = $('#group_filter').val();
                    data.fltername = $('#fltername').val();
                    }

            },
        });



        $('#submit').click(function() {
        report.ajax.reload();
        });



    });
    
    function reloadTable() {
      $.ajax({
        url: "<?php echo site_url('report/arch_book_rep_refresh'); ?>",
        type:"POST",
        data:{fdt:$('#fdt').val(),tdt:$('#tdt').val()},
        beforeSend: function (f) {
          $('#userTable').html('Load Table ...');
        },
        success: function (data) {
         //$('#userTable').removeClass();
          $('#userTable').html(data);
        //$('#userTable').addClass("table table-bordered table-striped table-hover js-basic-example dataTable");
        }
      })
    }
</script>
