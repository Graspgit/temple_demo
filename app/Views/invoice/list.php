<?php global $lang;?>
<?php $db = db_connect();?>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
        <h2><?php echo $lang->account; ?><small><?php echo "Sales Invoice"; ?>  / <b><?php echo $lang->list; ?></b></small></h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row"><div class="col-md-8"></div>
                        <div class="col-md-4" align="right"><a href="<?php echo base_url(); ?>/invoice/add"><button type="button" class="btn bg-deep-purple waves-effect"><?php echo $lang->add; ?></button></a></div></div>
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
											<th>Invoice No</th>
											<th><?php echo $lang->name; ?></th>
											<th><?php echo $lang->date; ?></th>
											<th><?php echo $lang->total; ?> </th>
											<th><?php echo "Discount"; ?> </th>
											<th><?php echo "Grand Total"; ?> </th>
											<th>Paid Amount</th>
											<th>Due Amount</th>
											<th>Status</th>
											<th><?php echo $lang->action; ?> </th>
										</tr>
									</thead>
									<tbody>
										<?php 
                                        if(!empty($suppliers))
                                        {
										$i = 1; 
										
										foreach($suppliers as $supplier) {
										
										$nm = "";
										if($supplier['invoice_type'] == 2)
										{
										    if(isset($supparr[$supplier['customer_supplier_id']]))
										        $nm = $supparr[$supplier['customer_supplier_id']];
										}
										else
										{
										    if(isset($custarr[$supplier['customer_supplier_id']]))
										        $nm = $custarr[$supplier['customer_supplier_id']];
										}
										
										// Calculate payment status
										$grand_total = floatval($supplier['grand_total']);
										$paid_amount = floatval($supplier['paid_amount']);
										$due_amount = $grand_total - $paid_amount;
										
										if($paid_amount == 0) {
										    $status = '<span class="badge bg-red">Unpaid</span>';
										} elseif($due_amount > 0) {
										    $status = '<span class="badge bg-orange">Partial</span>';
										} else {
										    $status = '<span class="badge bg-green">Paid</span>';
										}
										?>
										<tr>
											<td><?php echo $i++; ?></td>
											<td><?php echo isset($supplier['invoice_no']) ? $supplier['invoice_no'] : 'N/A'; ?></td>
											<td><?php echo $nm; ?></td>
											<td><?php echo date("d-m-Y",strtotime($supplier['date'])); ?></td>
											<td><?php echo number_format($supplier['total'],2); ?></td>
											<td><?php echo number_format($supplier['discount'],2); ?></td>
											<td><?php echo number_format($grand_total,2); ?></td>
											<td><?php echo number_format($paid_amount,2); ?></td>
											<td><?php echo number_format($due_amount,2); ?></td>
											<td><?php echo $status; ?></td>
											<td>
                                                <a class="btn btn-warning btn-rad" title="View" href="<?php echo base_url(); ?>/invoice/view/<?php echo $supplier['id']; ?>"><i class="material-icons">&#xE417;</i></a>&nbsp;
                                                
                                                <?php if($supplier['account_migration'] == 0) { ?>
                                                <a class="btn btn-primary btn-rad" title="Edit" href="<?php echo base_url(); ?>/invoice/edit/<?php echo $supplier['id']; ?>"><i class="material-icons">&#xE3C9;</i></a>&nbsp;
                                                <?php } ?>
                                                
                                                <?php if($due_amount > 0) { ?>
                                                <a class="btn btn-info btn-rad" title="Knock Off Payment" href="<?php echo base_url(); ?>/Knock_off/add?invoice_id=<?php echo $supplier['id']; ?>&type=1">
                                                    <i class="material-icons">&#xE8D4;</i>
                                                </a>
                                                <?php } ?>
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
        "searching": true,
        "order": [[ 0, "desc" ]], // Order by first column (ID) descending
        "pageLength": 25
      });
      var table = $('#datatables').DataTable();
      // Apply the filter
        $("#categoryFilter").on( 'keyup change', function () {
        table
            .column( 2 ) // Search in name column
            .search( this.value )
            .draw();
        });
    });
</script>