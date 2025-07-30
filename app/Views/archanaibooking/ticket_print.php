<?php global $lang;?>
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2><?php echo $lang->archanai; ?><?php echo $lang->ticket; ?><?php echo $lang->print; ?> <small><?php echo $lang->archanai; ?> / <b><?php echo $lang->archanai; ?><?php echo $lang->ticket; ?><?php echo $lang->print; ?><?php echo $lang->list; ?></b></small></h2>
            </div>
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
					<?php if($permission['create_p'] == 1) { ?>
                        <div class="header">
                            <div class="row"><div class="col-md-8"></div>
                            <div class="col-md-4" align="right"><a href="<?php echo base_url(); ?>/archanai/add"><button type="button" class="btn bg-deep-purple waves-effect"><?php echo $lang->add; ?></button></a></div></div>
                        </div>
					<?php } ?>
                        <div class="body">
                            <?php if(!empty($_SESSION['succ'])){ ?>
                                <div class="row" style="padding: 0 30%;" id="content_alert">
                                    <div class="suc-alert">
                                        <span class="suc-closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                                        <p><?php echo $_SESSION['succ']; ?></p> 
                                    </div>
                                </div>
                            <?php } ?>
                             <?php if(!empty($_SESSION['fail'])){ ?>
                                <div class="row" style="padding: 0 30%;" id="content_alert">
                                    <div class="alert">
                                        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                                        <p><?php echo $_SESSION['fail']; ?></p>
                                    </div>
                                </div>
                            <?php } ?>
							<div class="container-fluid">
								<div class="row clearfix">
									<div class="col-md-10 col-sm-10">
										<form action="<?php echo base_url(); ?>/archanaibooking/ticket_print" method="get">
											<div class="row clearfix">
												<div class="col-md-2 col-sm-2">
													<div class="form-group form-float">
														<div class="form-line" id="bs_datepicker_container">
															<input type="date" name="fdate" id="fdate" class="form-control"
																value="<?php echo $fdate; ?>" max="<?php echo $booking_calendar_range_year; ?>">
															<label class="form-label"><?php echo $lang->from; ?></label>
														</div>
													</div>
												</div>
												<div class="col-md-2 col-sm-2">
													<div class="form-group form-float">
														<div class="form-line" id="bs_datepicker_container">
															<input type="date" name="tdate" id="tdate" class="form-control"
																value="<?php echo $tdate; ?>" max="<?php echo $booking_calendar_range_year; ?>">
															<label class="form-label"><?php echo $lang->to; ?></label>
														</div>
													</div>
												</div>
												<div class="col-md-3 col-sm-3">
													<div class="form-group form-float">
														<div class="form-line">
															<label class="form-label">User</label>
															<select class="form-control" name="user_id[]" id="user_id" multiple>
															<?php
															if(count($users)){
																foreach($users as $u){
																	echo '<option value="' . $u['id'] . '"' . (in_array($u['id'], $user_id) ? ' selected="selected"' : '') .'>' . $u['name'] . '</option>';
																}
															}
															?>
															</select>
														</div>
													</div>
												</div>
                                                <div class="col-md-3 col-sm-3">
													<div class="form-group form-float">
														<div class="form-line">
															<label class="form-label">Booked Through</label>
															<select class="form-control" name="paid_through[]" id="paid_through" multiple>
															<option value="COUNTER" <?php if(in_array("COUNTER", $paid_through)){ echo "selected"; } ?>>COUNTER</option>
															<option value="ADMIN" <?php if(in_array("ADMIN", $paid_through)){ echo "selected"; } ?>>ADMIN</option>
															<option value="KIOSK" <?php if(in_array("KIOSK", $paid_through)){ echo "selected"; } ?>>KIOSK</option>
															</select>
														</div>
													</div>
												</div>
											   <div class="col-md-2 col-sm-2">
													<div class="form-group">
														<button type="submit" class="btn btn-success btn-lg waves-effect"
															id="submit"><?php echo $lang->submit; ?></label>
													</div>
												</div>
											</div>
										</form>
									</div>
									<div class="col-md-2 col-sm-2 text-right">
										<form action="<?php echo base_url(); ?>/archanaibooking/print_archanaiticket" method="get" target="_blank">
											<div class="row clearfix">
												<div class="col-md-12 col-sm-12" style="margin:0px;">
													<input type="hidden" name="fdt" id="fdt" class="form-control" value="<?php echo $fdate; ?>">
													<input type="hidden" name="tdt" id="tdt" class="form-control" value="<?php echo $tdate; ?>">
													<?php 
													if(!empty($user_id)){ 
														foreach($user_id as $ui){
															echo '<input type="hidden" name="user_id[]" class="form-control" value="' . $ui . '">';
														}															
													}
													?>
													<?php 
													if(!empty($paid_through)){ 
														foreach($paid_through as $pt){
															echo '<input type="hidden" name="paid_through[]" class="form-control" value="' . $pt . '">';
														}															
													}
													?>
													<button type="submit" class="btn btn-primary btn-lg waves-effect"
														id="print_submit">Print</button>
													<input name="pdf_archanaiticket" type="submit"
														class="btn btn-danger btn-lg waves-effect" id="pdf_archanaiticket"
														value="PDF">
													<input name="excel_archanaiticket" type="submit"
														class="btn btn-success btn-lg waves-effect" id="excel_archanaiticket"
														value="EXCEL">
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
                            <div class="table-responsive col-md-12 det" style="background:#FFF; float:none;">
                                <table class="table table-bordered table-striped table-hover dataTable">
                                    <thead>
                                        <tr>
                                            <th style="width:10%;"><?php echo $lang->date; ?></th>
                                            <th style="width:20%;"><?php echo $lang->bill_no; ?></th>
                                            <th style="width:20%;"><?php echo $lang->ref_no; ?></th>
                                            <th style="width:10%;"><?php echo $lang->amount; ?></th>
                                            <th style="width:10%;"><?php echo $lang->booked . ' ' . $lang->by; ?></th>
                                            <th style="width:10%;"><?php echo $lang->payment; ?></th>
                                            <th><?php echo $lang->action; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; foreach($list as $row) { ?>
                                        <tr>
                                            <td><?php echo date('d-m-Y', strtotime($row['date'])); ?></td>
                                            <td><?php echo $row['ref_no']; ?></td>
                                            <td><?php echo $row['reference_id']; ?></td>
                                            <td><?php echo number_format($row['amount'], 2); ?></td>
											<td><?php echo $row['paid_through']; ?></td>
											<td><?php echo $row['payment_mode_name']; ?></td>
                                            <td style="width: 10%;">
                                                <a class="btn btn-success btn-rad" title="Print" href="<?= base_url()?>/archanaibooking/print_ticket/<?php echo $row['id'];?>" target="_blank"><i class="material-icons">print</i></a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <div id="alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body p-4">
                        <div class="text-center">
                            <i class="dripicons-information h1 text-info"></i>
                            <h4 class="mt-2"><?php echo $lang->delete; ?><?php echo $lang->donation; ?></h4>
                            <table>
        
                            <tr><span id="spndeddelid"><b></b></span></tr>
                          </table>
                            
                            <a href="#" id="del" class="btn btn-danger my-3" data-dismiss="modal"><?php echo $lang->yes; ?></a> &nbsp;
                            <button type="button" class="btn btn-info my-3" data-dismiss="modal"><?php echo $lang->no; ?></button>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div>
        </div>
        <div id="del-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body p-4">
                        <div class="text-center">
                            <i class="dripicons-information h1 text-info"></i>
                            <table>
                                <tr><span id="delmol"><b></b></span>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-info my-3" data-dismiss="modal"> &times;</button></tr>
                            </table>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div>
        </div>
        <!--Delete Form-->
        <div id=delete-form>
            
        </div>
        <!--End Delete Form-->
    </section>
<script>
    function confirm_modal(id)
    {
		//alert(id)
        $.ajax({
            url: "<?php echo base_url();?>/archanai/del_check",
            type: "post",
            data: {id: id},
            success:function(data){
                if(data == 0){
                    $('#alert-modal').modal('show', {backdrop: 'static'});
                    document.getElementById('del').setAttribute('onclick' , 'dedDel('+id+')');
                    $("#spndeddelid").text("Are you sure to Delete "+$("#pay"+id).attr("data-id") + "?" );
                }else{
					//alert(id)
                    $('#del-modal').modal('show', {backdrop: 'static'});
                    $("#delmol").text("We used for this Archanai, So cant delete this Archanai" );
                }
            }
        });
    }
    
    function dedDel(id)
    {
        var act = "<?php echo base_url(); ?>/archanai/delete/"+id;
        $( "#delete-form" ).append( "<form action='"+act+"'><button type='submit' id='delete"+id+"' >submit</button></form>");
        $( "#delete"+id).trigger( "click" );
    }
$(document).ready(function(){
	$('.dataTable').DataTable({
		"order": [[0, "desc"]]
    });
});
</script>