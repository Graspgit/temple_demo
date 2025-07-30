<?php global $lang;?>
<?php $db = db_connect();?>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
        <h2><?php echo $lang->account; ?><small><?php echo "Sales Knock-off"; ?>  / <b><?php echo $lang->list; ?></b></small></h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row"><div class="col-md-8"></div>
                        <div class="col-md-4" align="right"><a href="<?php echo base_url(); ?>/Knock_off/add"><button type="button" class="btn bg-deep-purple waves-effect"><?php echo $lang->add; ?></button></a></div></div>
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
											<th>Date</th>
											<th>Type</th>
											<th>Customer</th>
											<th>Invoice No</th>
											<th>Amount</th>
											<th>Status</th>
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
										
										if($supplier['type'] == 2)
										{
										    if(isset($supparr[$supplier['supplier_id']]))
										        $nm = $supparr[$supplier['supplier_id']];
										}
										else
										{
										    if(isset($custarr[$supplier['supplier_id']]))
										        $nm = $custarr[$supplier['supplier_id']];
										}
										
										$invoice_data = $db->table('invoice')->where("id", $supplier["invoice_id"])->get()->getRowArray();
										
										// Calculate knock-off amount
										$entry_ids = explode(',', $supplier['inv_detail_ids']);
										$knock_off_amount = 0;
										foreach($entry_ids as $entry_id) {
										    if(intval($entry_id) > 0) {
										        $entry = $db->table('entries')->where('id', intval($entry_id))->get()->getRowArray();
										        if($entry) {
										            if($entry['entrytype_id'] == 1) { // Receipt
										                $knock_off_amount += floatval($entry['dr_total']);
										            } else if($entry['entrytype_id'] == 2) { // Payment
										                $knock_off_amount += floatval($entry['cr_total']);
										            }
										        }
										    }
										}
										?>
										<tr>
											<td><?php echo $i++; ?></td>
											<td><?php echo isset($supplier['created_at']) ? date("d-m-Y", strtotime($supplier['created_at'])) : 'N/A'; ?></td>
											<td>
												<?php if($supplier['type'] == 1) { ?>
													<span class="badge bg-green">Sales Receipt</span>
												<?php } else { ?>
													<span class="badge bg-blue">Purchase Payment</span>
												<?php } ?>
											</td>
											<td><?php echo $nm; ?></td>
											<td><?php echo isset($invoice_data['invoice_no']) ? $invoice_data['invoice_no'] : 'N/A'; ?></td>
											<td><?php echo number_format($knock_off_amount, 2); ?></td>
											<td>
												<span class="badge bg-green">Completed</span>
											</td>
											<td>
                                                <a class="btn btn-warning btn-rad" title="View" href="<?php echo base_url(); ?>/Knock_off/view/<?php echo $supplier['id']; ?>"><i class="material-icons">&#xE417;</i></a>&nbsp;
                                                <a class="btn btn-primary btn-rad" title="Edit" href="<?php echo base_url(); ?>/Knock_off/edit/<?php echo $supplier['id']; ?>"><i class="material-icons">&#xE3C9;</i></a>&nbsp;
                                                
                                                <!-- Show invoice link -->
                                                <?php if(isset($invoice_data['id'])) { ?>
                                                <a class="btn btn-info btn-rad" title="View Invoice" href="<?php echo base_url(); ?>/invoice/view/<?php echo $invoice_data['id']; ?>">
                                                    <i class="material-icons">&#xE8F6;</i>
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
            .column( 3 ) // Search in customer name column
            .search( this.value )
            .draw();
        });
    });
</script>