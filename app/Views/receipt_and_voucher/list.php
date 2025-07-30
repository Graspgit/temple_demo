<?php global $lang;?>
<?php $db = db_connect();?>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
        <h2><?php echo $lang->inventory; ?><small><?php echo $lang->receipt_and_voucher; ?>  / <b><?php echo $lang->list; ?></b></small></h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row"><div class="col-md-8"></div>
                        <div class="col-md-4" align="right"><a href="<?php echo base_url(); ?>/Receipt_and_Voucher/add"><button type="button" class="btn bg-deep-purple waves-effect"><?php echo $lang->add; ?></button></a></div></div>
                    </div>
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
                            <div class="table-responsive col-md-12 det" style="background:#FFF; float:none;">
								<table class="table table-bordered table-striped table-hover dataTable" id="datatables">
									<thead>
										<tr>
											<th><?php echo $lang->sno; ?></th>
											<th><?php echo $lang->type; ?></th>
											<th><?php echo $lang->date; ?></th>
											<th><?php echo $lang->name; ?></th>
											<th><?php echo $lang->amount; ?></th>
											<th><?php echo $lang->remarks; ?></th>
											<th><?php echo $lang->action; ?></th>
										</tr>
									</thead>
									<tbody>
										<?php 
                                        if(!empty($suppliers))
                                        {
                                            
										$i = 1; 
										foreach($suppliers as $supplier) { 
										$nm = "";
										$supparr[$supplier['name']];
										if($supplier['rv_type'] == 2)
										{
										    if(isset($supparr[$supplier['name']]))
										        $nm = $supparr[$supplier['name']];
										}
										else
										{
										    if(isset($custarr[$supplier['name']]))
										        $nm = $custarr[$supplier['name']];
										}
										
										?>
										<tr>
											<td><?php echo $i++; ?></td>
											<td><?php echo ($supplier['rv_type'] == 1?"Receipt":"paymnet voucher"); ?></td>
											<td><?php echo date("d-m-Y",strtotime($supplier['rv_date'])); ?></td>
											<td><?php echo $nm; ?></td>
											<td><?php echo $supplier['amount']; ?></td>
											<td><?php echo $supplier['remarks']; ?></td>
											<td>
                                                <a class="btn btn-warning btn-rad" title="View" href="<?php echo base_url(); ?>/Receipt_and_Voucher/view/<?php echo $supplier['id']; ?>"><i class="material-icons">&#xE417;</i></a>&nbsp;
                                                <a class="btn btn-primary btn-rad" title="Edit" href="<?php echo base_url(); ?>/Receipt_and_Voucher/edit/<?php echo $supplier['id']; ?>"><i class="material-icons">&#xE3C9;</i></a>
                                            </td>
										</tr>
										<?php }
                                        }
                                        ?>
									</tbody>
								</table>
                            </div>
						</div>
            
            </div>
        </div>
    </div>

</section>
<script>
    $("document").ready(function () {
      $("#datatables").dataTable({
        "searching": true
      });
      var table = $('#datatables').DataTable();
      // Apply the filter
        $("#categoryFilter").on( 'keyup change', function () {
        table
            .column( 1 )
            .search( this.value )
            .draw();
        });
    });
</script>