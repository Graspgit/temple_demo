<?php global $lang; ?>
<?php $booking_calendar_range_year = booking_calendar_range_year($_SESSION['booking_range_year']); ?>
<section class="content">
	<div class="container-fluid">
		<div class="block-header">
			<h2>
				<?php echo $lang->daily_closing; ?> <small>
					<?php echo $lang->print; ?> / <b>
						<?php echo $lang->daily_closing; ?>
					</b>
				</small>
			</h2>
		</div>
		<!-- Basic Examples -->
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="header">
						<form action="<?php echo base_url(); ?>/dailyclosing/getDailyReport" method="post">
							<div class="row">
								<div class="col-md-2">
									<input type="date" name="dailyclosing_start_date" id="dailyclosing_start_date"
										class="form-control" value="<?php echo $dailyclosing_start_date; ?>" max="<?php echo $booking_calendar_range_year; ?>">
								</div>
								<div class="col-md-2">
									<input type="date" name="dailyclosing_end_date" id="dailyclosing_end_date"
										class="form-control" value="<?php echo $dailyclosing_end_date; ?>" max="<?php echo $booking_calendar_range_year; ?>">
								</div>
								
								<div class="col-md-2">
									<div class="form-group form-float">
										<div class="form-line">
											<select class="form-control" name="username[]" id="username" multiple>
												<option disabled value="">Select Paid Through</option>
												<?php
												
												foreach($login_opt as $iter){ ?>
												<option <?php echo (isset($username) && in_array($iter["id"], $username)?"selected":""); ?> value="<?php echo $iter["id"] ?>"><?php echo $iter["name"]; ?></option>
												<?php } ?>
											</select>
											<label class="form-label"></label>
										</div>
									</div>                                            
								</div>
								
								<div class="col-md-2">
									<div class="form-group form-float">
										<div class="form-line">
											<select class="form-control" name="payment_mode[]" id="payment_mode" multiple>
												<option disabled value="">Select Paymentmode</option>
												<?php foreach($payment_mode_opt as $iter){ ?>
												<option <?php echo (isset($payment_mode) && in_array($iter["name"], $payment_mode)?"selected":""); ?> value="<?php echo $iter["name"] ?>"><?php echo $iter["name"]; ?></option>
												<?php } ?>
											</select>
											<label class="form-label"></label>
										</div>
									</div>                                            
								</div>
								
								<div class="col-md-2">
									<div class="form-group form-float">
										<div class="form-line">
											<select class="form-control" name="deity[]" id="deity" multiple>
												<option disabled value="">Select Deity</option>
												<?php foreach($deity_opt as $iter){ ?>
												<option <?php echo (isset($deity) && in_array($iter["id"], $deity)?"selected":""); ?> value="<?php echo $iter["id"] ?>"><?php echo $iter["name"]; ?></option>
												<?php } ?>
											</select>
											<label class="form-label"></label>
										</div>
									</div>                                            
								</div>
								
								<div class="col-md-2">
									<div class="form-group form-float">
										<div class="form-line">
											<select class="form-control" name="group[]" id="group" multiple>
												<option disabled value="">Select Group</option>
												<?php foreach($group_opt as $iter){ ?>
												<option <?php echo (isset($group) && in_array($iter["name"], $group)?"selected":""); ?> value="<?php echo $iter["name"] ?>"><?php echo $iter["name"]; ?></option>
												<?php } ?>
											</select>
											<label class="form-label"></label>
										</div>
									</div>                                            
								</div>
								
								<div class="col-md-2">
									<div class="form-group form-float">
										<div class="form-line">
											<select class="form-control" name="category[]" id="category" multiple>
												<option disabled value="">Select Category</option>
												<?php foreach($category_opt as $iter){ ?>
												<option <?php echo (isset($category) && in_array($iter["id"],$category)?"selected":""); ?> value="<?php echo $iter["id"] ?>"><?php echo $iter["name"]; ?></option>
												<?php } ?>
											</select>
											<label class="form-label"></label>
										</div>
									</div>                                            
								</div>
								
								<div class="col-md-2">
									<div class="form-group form-float">
										<div class="form-line">
											<select class="form-control" name="timezone[]" id="timezone" multiple>
												<option disabled value="">Select Timezone</option>
												<?php foreach(["AM","PM"] as $iter){ ?>
												<option <?php echo (isset($_POST["timezone"]) && in_array($iter,$_POST["timezone"])?"selected":""); ?> value="<?php echo $iter; ?>"><?php echo $iter; ?></option>
												<?php } ?>
											</select>
											<label class="form-label"></label>
										</div>
									</div>                                            
								</div>
								
								<div class="col-md-2">
									<div class="form-group form-float">
										<div class="form-line">
											<input  class="form-control" value="<?php echo $product_name; ?>" name="product_name" id="product_name" placeholder="Enter the Product Name" />
											<label class="form-label"></label>
										</div>
									</div>                                            
								</div>

								<div class="col-md-1">
									<button type="submit" id="dailyclosing_filter"
										class="btn btn-success">Filter</button>
								</div>
								
								<div class="col-md-2">
									<div class="form-group form-float">
										<div class="form-line">
											<select class="form-control" name="summary_filter[]" id="summary_filter" multiple>
												<option disabled hidden value="">Select Summary Filter</option><!-- Don't include empty option -->
												<option 
												<?php echo (in_array("added_by",$_POST["summary_filter"])?"selected":""); ?> 
												value="added_by">User name</option>
												<option
												<?php echo (in_array("pay_method",$_POST["summary_filter"])?"selected":""); ?> 
												value="pay_method">Payment Mode</option>
												<option
												<?php echo (in_array("abd.deity_id",$_POST["summary_filter"])?"selected":""); ?> 
												value="abd.deity_id">Deity</option>
												<option
												<?php echo (in_array("groupname",$_POST["summary_filter"])?"selected":""); ?> 
												value="groupname">Group</option>
												<option
												<?php echo (in_array("archanai_category",$_POST["summary_filter"])?"selected":""); ?>  
												value="archanai_category">Category</option>
												<option
												<?php echo (in_array("name_eng",$_POST["summary_filter"])?"selected":""); ?>  
												value="name_eng">Product Name</option>
											</select>
											<label class="form-label"></label>
										</div>
									</div>                                            
								</div>
								
								<div class="col-md-4">
									<button type="submit" id="dailyclosing_filter"
										class="btn btn-success">Filter</button>
								</div>
								
							</div>
						</form>
					</div>
					<div class="body">
					
					  <div class="table-responsive col-md-12 det" style="background:#FFF; float:none;">
                                <?php 
                                $summary_arr_tot = [];
                                $summary_arr_paymode = [];
                                foreach($title as $table=>$titleiter)
                                {
                                    //print_r($table);
                                    $title_name=$titleiter["title"];
                                    $cols = $titleiter["cols"];
                                    ?>
                                <div style="text-align:center;font-size:20px;font-weight:bold"><?php echo $title_name; ?></div>
                                 <table style="border-collapse:collapse!important" class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    
                               
                                <?php 
                                $totqtytyp = 0;
                                $tottyp = 0;
                                //loop:
                                foreach($$table as $typ=>$iter)
                                {?>
                                <tr style="background:none;border:2px solid white"><td style="background:none;border:none" colspan="6" align="center"><div style="font-size:18px;font-weight:bold;text-align:center"><?php echo strtoupper($typ) ?></div>
                                </td></tr>
                               <?php 
                               $totqtypaymode = 0;
                                $totpaymode = 0;
                                
                               foreach($iter as $payment_mode=>$iter2)
                                {?>
                                <tr style="background:none;border:2px solid white"><td style="background:none;border:none" align="left"><div style="font-size:18px;font-weight:bold;text-align:left"><?php echo strtoupper($payment_mode) ?></div>
                                </td></tr>
                                <?php
                                $had_deity = true;
                                foreach($iter2 as $chkiter)
                                {
                                    if(isset($chkiter["product_name"]))
                                        $had_deity = false;
                                        
                                    break;
                                        
                                }
                                //if not had deity skip deity
                                if(!$had_deity)
                                {
                                        ?>
                                    <tr style="background:none;border:2px solid white"><td style="background:none;border:none" align="left"><div style="font-size:16px;font-weight:bold;padding-left: 20px;margin-top: 11px;text-align:left"><?php echo strtoupper($deity_name) ?></div>
                                    </td></tr>
                                            <tr>
                                                <th style="width:5%;"><?php echo $lang->sno; ?></th>
                                                <?php foreach($cols["col"] as $col=>$itercol){  ?>
                                                <th align="center"
                                                style="width:10%;"><?php echo $itercol; ?></th>
                                                <?php } ?>
                                                
                                            </tr>
                                        
                                        
                                            <?php $i = 1;
                                            $totqty = 0;
                                            $tot = 0;
                                            foreach($iter2 as $row) { ?>
                                            <tr>
                                                <td align="center"><?php echo $i++; ?></td>
                                                <?php foreach($cols["col"] as $col=>$itercol){
                                                    
                                                    $val = ("product_name" == $col?(isset($row['shortcode'])?$row['shortcode']." - ".$row['product_name']:$row['product_name']):$row[$col]);
                                                    if((in_array($col,$cols["number_format_arr"])))
                                                        $val = number_format($val,2);
                                                        
                                                    //align
                                                    $align='';
                                                    if(isset($cols["right_align_col"]) && in_array($col,$cols["right_align_col"]))
                                                        $align="align='right'";
                                                    if(isset($cols["center_align_col"]) && in_array($col,$cols["center_align_col"]))
                                                        $align="align='center'";
                                                ?>
                                                <td <?php echo $align; ?> 
                                                style="width:10%;"><?php echo $val; ?></td>
                                                <?php } ?>
                                            
                                            </tr>
                                             <?php
                                              $totqty+=floatval($row['quantity']);
                                              $tot+=floatval($row['amount']);
                                                
                                            }
                                           
                                            ?>
                                            <tr><td colspan = "4"></td><td align="right">SUB TOTAL</td>
                                            <td align="center"><?php echo $totqty; ?></td><td align="right"><?php echo number_format($tot,2); ?></td></tr>
                                     
                                    
                                   
                                  <?php
                                    $totqtypaymode+=floatval($totqty);
                                    $totpaymode+=floatval($tot);
                                    
                                    
                                      if(!isset($summary_arr_paymode[$typ][$payment_mode]))
                                        $summary_arr_paymode[$typ][$payment_mode] = 0;
                                        
                                    //echo "1##$typ##$payment_mode##$totqtypaymode##$totpaymode";
                                    $summary_arr_paymode[$typ][$payment_mode] += floatval($tot);
                                    
                                    ?>
                                    <!--
                                     <tr><td colspan = "3"></td><td align="right"><span style="font-weight:bold">TOTAL <?php echo strtoupper($payment_mode); ?></span></td>
                                            <td align="center"><?php echo $totqtypaymode; ?></td><td align="right"><?php echo number_format($totpaymode,2); ?></td></tr>
                                   -->
                                   <?php
                                    }
                                
                                else
                                {
                                    $totqtydeity = 0;
                                    $totdeity = 0;
                                    foreach($iter2 as $deity_name=>$iter1)
                                    {
                                       
                                        ?>
                                    <tr style="background:none;border:2px solid white"><td style="background:none;border:none" align="left"><div style="font-size:16px;font-weight:bold;padding-left: 20px;margin-top: 11px;text-align:left"><?php echo strtoupper($deity_name) ?></div>
                                    </td></tr>
                                            <tr>
                                                <th style="width:5%;"><?php echo $lang->sno; ?></th>
                                                <?php foreach($cols["col"] as $col=>$itercol){ ?>
                                                <th align="center"
                                                style="width:10%;"><?php echo $itercol; ?></th>
                                                <?php } ?>
                                                
                                            </tr>
                                        
                                        
                                            <?php $i = 1;
                                            $totqty = 0;
                                            $tot = 0;
                                            foreach($iter1 as $row) { 
                                            if(!isset($row["product_name"]))
                                            continue;
                                            
                                            ?>
                                            <tr>
                                                <td align="center"><?php echo $i++; ?></td>
                                                <?php foreach($cols["col"] as $col=>$itercol){
                                                    
                                                    $val = ("product_name" == $col?(isset($row['shortcode'])?$row['shortcode']." - ".$row['product_name']:$row['product_name']):$row[$col]);
                                                    if((in_array($col,$cols["number_format_arr"])))
                                                        $val = number_format($val,2);
                                                        
                                                    //align
                                                    $align='';
                                                    if(isset($cols["right_align_col"]) && in_array($col,$cols["right_align_col"]))
                                                        $align="align='right'";
                                                    if(isset($cols["center_align_col"]) && in_array($col,$cols["center_align_col"]))
                                                        $align="align='center'";
                                                ?>
                                                <td <?php echo $align; ?> 
                                                style="width:10%;"><?php echo $val; ?></td>
                                                <?php } ?>
                                            </tr>
                                             <?php
                                              $totqty+=floatval($row['quantity']);
                                              $tot+=floatval($row['amount']);
                                                
                                            }
                                           
                                            ?>
                                            <tr><td colspan = "4"></td><td align="right">SUB TOTAL</td>
                                            <td align="center"><?php echo $totqty; ?></td><td align="right"><?php echo number_format($tot,2); ?></td></tr>
                                        
                                   
                                   <?php 
                                    $totqtydeity+=floatval($totqty);
                                    $totdeity+=floatval($tot);
                                    } 
                                    if(!isset($summary_arr_paymode[$typ][$payment_mode]))
                                        $summary_arr_paymode[$typ][$payment_mode] = 0;
                                        
                                    //echo "2##$typ##$payment_mode##$totqtydeity##$totdeity";
                                    $summary_arr_paymode[$typ][$payment_mode] += floatval($totdeity);
                                    ?>
                                    
                                    <tr><td colspan = "4"></td><td align="right"><span style="font-weight:bold">TOTAL <?php echo strtoupper($payment_mode); ?></span></td>
                                            <td align="center"><?php echo $totqtydeity; ?></td><td align="right"><?php echo number_format($totdeity,2); ?></td></tr>
                                    
                                    <?php
                                    $totqtypaymode+=floatval($totqtydeity);
                                    $totpaymode+=floatval($totdeity);
                                    }
                                    
                                    if(!isset($summary_arr_tot[$typ]))
                                        $summary_arr_tot[$typ] = 0;
                                        
                                }
                                
                                ?>
                                <tr><td colspan = "4"></td><td align="right"><span style="font-weight:bold">TOTAL <?php echo strtoupper($typ); ?></span></td>
                                        <td align="center"><?php echo $totqtypaymode; ?></td><td align="right"><?php echo number_format($totpaymode,2); ?></td></tr>
                                <?php
                                $totqtytyp+=floatval($totqtypaymode);
                                $tottyp+=floatval($totpaymode);
                                } 
                                
                                    if($tottyp>0)
                                    {
                                       
                                    ?>
                                    
                                    
                                    <tr><td colspan = "4"></td><td align="right"><span style="font-weight:bold">TOTAL </span></td>
                                            <td align="center"><?php echo $totqtytyp; ?></td><td align="right"><?php echo number_format($tottyp,2); ?></td></tr>
                                            
                                            </tr></table>
                                    <?php 
                                    }
                                    else
                                    {
                                        ?>
                                        <tr><td colspan = "4" align="center">No record Found</td></tr></table>
                                        <?php
                                    }
                                }
                                ?>
                                 
                            </div>       
					
					        
					        <!-- Summary div  -->
					        
						    <div style="display: flex;flex-direction: column;justify-content: start;font-weight:bold;font-size:19px;margin-right: 13px;margin-left: 13px;">
						        
						            <div style="display: flex;justify-content: center;margin-bottom: 12px;">Summary</div>
						            
						            <div style="margin-bottom: 12px;">Incomes</div>
						        <?php 
						        $tot_qty = 0;
						        $tot_amt = 0;
						       if(!empty($archanai_summary))
						       {
						           ?>
						            
						        <?php
						        $k=0;
						        foreach($archanai_summary as $typ=>$iter)
						        {
						            
						            ?>
						            <div style="overflow-x: scroll;" >
						            <table class="table table-bordered table-striped table-hover"><tr style="background: none">
						            <?php
						           //for header purpose
						       
						            if($typ == "added_by"){
						                    $part = "User Name";
						                    
						                }
						                else if($typ == "name_eng"){
						                    $part = "Product";
						                }
						                else
						                {
						                $part = ($typ == "abd.deity_id"?"Deity":ucwords(str_ireplace("_"," ",$typ)));
						                
						                }?>
						                <td colspan="20" style="text-align:center"><?php echo $part; ?></td></tr>
						                <tr><th>SNo</th>
						                
						                <?php
						                $arr = [];
						                foreach($iter as $type1=>$iter1)
    						            {
    						                if(in_array($type1,$arr))continue;
    						                
    						                $arr[] = $type1;
    						            ?>
    						                 <th style="text-align:center"><?php echo ucwords(str_ireplace("_"," ",$type1))." Quantity"; ?></th>
    						                 <th style="text-align:center"><?php echo ucwords(str_ireplace("_"," ",$type1))." Amount"; ?></th>
    						                 
    						                 
    						            <?php
    						            
    						            }
    						            ?>
    						            </tr>
    						            <?php
						            ?>
                                       <tr>
                                            <td style="text-align:center"><?php $sno = 1; echo $sno++; ?></td>
						                <?php
						                 
    						            foreach($iter as $type1=>$iter1)
    						            {
    						               
    						                ?>
    						                 
    						                    
    						                 <td style="text-align:center"><?php echo $iter1["quantity"]; ?></td>
    						                
    						                <td style="text-align:center">RM <?php echo number_format($iter1["amount"],2); ?></td>
    						                        
    						                       <?php 
    						                       if($k == 0)
    						                       {
    						                       $tot_amt += floatval($iter1["amount"]);
    						                       $tot_qty += floatval($iter1["quantity"]);
    						                       }
    						                       
    						                       ?>
    						                       
    						                       <?php
    						                }
    						                $k=1;
    						                ?>
    						                </tr>
    						                </table>
    						                </div>
    						                <?php
                                                  
						            }
						       }
						            ?>
						                            <div style="margin-top: 12px;text-align:center">
						                		        <?php echo "Total Quantity"; ?>
    						                            :  <?php echo $tot_qty; ?>
    						                            </div>
						                		    <div style="margin-top: 12px;text-align:center">
						                		        <?php echo "Total Amount"; ?>
    						                            : RM <?php echo number_format($tot_amt,2); ?>
    						                            </div>
						        
						        
						    </div>
					
					</div>
				</div>
			</div>
		</div>
	</div>
</section>