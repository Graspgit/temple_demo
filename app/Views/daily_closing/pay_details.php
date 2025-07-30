<?php global $lang; ?>
<?php $booking_calendar_range_year = booking_calendar_range_year($_SESSION['booking_range_year']); ?>
<section class="content">
	<div class="container-fluid">
		<div class="block-header">
			<h2>
				<?php echo $lang->daily_closing; ?> <small>
					Pay Details / <b>
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
						<form action="<?php echo base_url(); ?>/paydetails" method="post">
							<div class="row">
								<div class="col-md-2">
									<input type="date" name="paydetails_from_date" id="paydetails_from_date"
										class="form-control" value="<?php echo $paydetails_from_date; ?>"
										max="<?php echo $booking_calendar_range_year; ?>">
								</div>
								<div class="col-md-2">
									<input type="date" name="paydetails_to_date" id="paydetails_to_date"
										class="form-control" value="<?php echo $paydetails_to_date; ?>"
										max="<?php echo $booking_calendar_range_year; ?>">
								</div>

								<div class="col-md-6">
									<button type="submit" id="dailyclosing_filter"
										class="btn btn-success">Filter</button>
								</div>
								
								<div class="col-md-2" align="right">
									<a href="<?php echo base_url('paydetails/excel_paydetails') . '?paydetails_from_date=' . $paydetails_from_date . '&paydetails_to_date=' . $paydetails_to_date; ?>" 
									class="btn btn-primary">Export to Excel </a>
								</div>
							</div>
						</form>
					</div>

					<!-- <pre><?php print_r($archanai['archanai_details']); ?></pre> -->
					<div class="card">
						<div class="body">
							<h3 style="text-align: center;">Daily sales report</h3>
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<th>S.no</th>
											<th>Products</th>
											<?php foreach ($payment_mode as $mode) { ?>
												<th><?php echo htmlspecialchars($mode['name']); ?> Quantity</th>
												<th><?php echo htmlspecialchars($mode['name']); ?> Amount</th>
												<?php /*<th><a href="<?php echo base_url() . '/accountreport/ledger_report/?ledger=' . htmlspecialchars($mode['ledger_id']) . '&fdate=' . htmlspecialchars($fromDate) . '&tdate=' . htmlspecialchars($toDate); ?>" target="_blank">
														<?php echo htmlspecialchars($mode['name']); ?>
													</a></th> */ ?>
											<?php } ?>
											<th>Total Quantity</th>
											<th>Total Amount</th>
										</tr>
									</thead>

									<tbody>
										<?php
										$s_no = 1;
										$grandTotalAmount = 0;
										$grandTotalQuantity = 0;

										if (!empty($archanai['archanai_details']) && is_array($archanai['archanai_details'])) {
											foreach ($archanai['archanai_details'] as $product) {
												echo "<tr>";
												echo "<td>" . htmlspecialchars($s_no++) . "</td>";
												//echo '<td><a href="' . base_url() . '/accountreport/ledger_report/?ledger=' . htmlspecialchars($product['ledger_id']) . '&fdate=' . htmlspecialchars($fromDate) . '&tdate=' . htmlspecialchars($toDate) . '" target="_blank">' . htmlspecialchars($product['name_eng']) . '</a></td>';
												echo "<td>" . htmlspecialchars($product['name_eng']) . "</td>";

												$payment_total = 0;
												$total_quantity = 0;

												foreach ($payment_mode as $method) {
													if (isset($product['payment_info'][$method['name']])) {
														$quantity = $product['payment_info'][$method['name']]['quantity'] ?? 0;
														$amount = $product['payment_info'][$method['name']]['amount'] ?? 0;

														$total_quantity += $quantity;
														$payment_total += $amount;

														echo "<td>" . htmlspecialchars($quantity) . "</td>";
														echo "<td>" . htmlspecialchars(number_format($amount, 2)) . "</td>";
													} else {
														echo "<td>-</td>";
														echo "<td>-</td>";
													}
												}

												echo "<td>" . htmlspecialchars($total_quantity) . "</td>";
												echo "<td>" . htmlspecialchars(number_format($payment_total, 2)) . "</td>";

												echo "</tr>";

												$grandTotalAmount += $payment_total;
												$grandTotalQuantity += $total_quantity;
											}
										} else {
											echo "<tr><td colspan='7'>No data available</td></tr>";
										}
										?>

										<tr>
											<td colspan="2" style="text-align: right;"><strong>Grand Total:</strong></td>
											<?php
											foreach ($payment_mode as $method) {
												$methodName = $method['name'];
												if (isset($archanai['archanai_pay_total']['payMethodTotals'][$methodName])) {
													$pay = $archanai['archanai_pay_total']['payMethodTotals'][$methodName];
													echo "<td><strong>" . htmlspecialchars($pay['quantity']) . "</strong></td>";
													echo "<td><strong>" . htmlspecialchars(number_format($pay['amount'], 2)) . "</strong></td>";
												} else {
													echo "<td>-</td>";
													echo "<td>-</td>";
												}
											}
											?>
											<td><strong><?php echo htmlspecialchars($grandTotalQuantity); ?></strong></td>
											<td><strong><?php echo htmlspecialchars(number_format($grandTotalAmount, 2)); ?></strong></td>
										</tr>
									</tbody>
								</table>
							</div><br>


							<?php /* <h3 style="text-align: center;">Donation</h3>
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<th>S.no</th>
											<th>Products</th>
											<th>Total Sales (credit)</th>
											<th>Counter (debit)</th>
											<th>Ipay_card (debit)</th>
											<th>Ipay_merch_qr (debit)</th>
											<th>Ipay_online (debit)</th>
											<th>Variance</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$s_no = 1;
										$grandTotal = 0; 

										if (isset($donation['donation_details']) && is_array($donation['donation_details'])) {
											foreach ($donation['donation_details'] as $product) {
												echo "<tr>";
												echo "<td>" . htmlspecialchars($s_no++) . "</td>";

												// Make donation name a clickable link
												echo '<td><a href="' . base_url() . '/accountreport/ledger_report/?ledger=' . htmlspecialchars($product['ledger_id']) . '&fdate=' . $fromDate . '&tdate=' . $toDate . '" target="_blank">' . htmlspecialchars($product['name']) . '</a></td>';

												echo "<td>" . htmlspecialchars($product['total_per_product']) . "</td>";

												// Display payment information
												$cashTotal = isset($product['payment_info']['cash']) ? $product['payment_info']['cash'] : 0;
												$ipayCardTotal = isset($product['payment_info']['ipay_card']) ? $product['payment_info']['ipay_card'] : 0;
												$ipayMerchQrTotal = isset($product['payment_info']['ipay_merch_qr']) ? $product['payment_info']['ipay_merch_qr'] : 0;
												$ipayOnlineTotal = isset($product['payment_info']['ipay_online']) ? $product['payment_info']['ipay_online'] : 0;
												$payment_total = (float) $cashTotal + (float) $ipayCardTotal + (float) $ipayMerchQrTotal + (float) $ipayOnlineTotal; 

												echo "<td>" . htmlspecialchars($cashTotal) . "</td>";
												echo "<td>" . htmlspecialchars($ipayCardTotal) . "</td>";
												echo "<td>" . htmlspecialchars($ipayMerchQrTotal) . "</td>";
												echo "<td>" . htmlspecialchars($ipayOnlineTotal) . "</td>";

												// Calculate and display variance
												$variance = (float) $product['total_per_product'] - $payment_total;
												echo '<td>' . number_format($variance, 2) . '</td>';

												echo "</tr>";

												$grandTotal += $product['total_per_product'];
											}
										} else {
											echo "<tr><td colspan='7'>No data available</td></tr>";
										}
										?>
										<tr>
											<td colspan="2" style="text-align: right;"><strong>Grand Total:</strong></td>
											<td><strong><?php echo htmlspecialchars($grandTotal); ?></strong></td>
											<td><strong><?php echo isset($donation['donation_pay_total']['payMethodTotals']['cash']) ? htmlspecialchars($donation['donation_pay_total']['payMethodTotals']['cash']) : '0'; ?></strong></td>
											<td><strong><?php echo isset($donation['donation_pay_total']['payMethodTotals']['ipay_card']) ? htmlspecialchars($donation['donation_pay_total']['payMethodTotals']['ipay_card']) : '0'; ?></strong></td>
											<td><strong><?php echo isset($donation['donation_pay_total']['payMethodTotals']['ipay_merch_qr']) ? htmlspecialchars($donation['donation_pay_total']['payMethodTotals']['ipay_merch_qr']) : '0'; ?></strong></td>
											<td><strong><?php echo isset($donation['donation_pay_total']['payMethodTotals']['ipay_online']) ? htmlspecialchars($donation['donation_pay_total']['payMethodTotals']['ipay_online']) : '0'; ?></strong></td>
											<td><strong><?php $tot_prod_amount = (float) $donation['donation_pay_total']['payMethodTotals']['cash'] + (float) $donation['donation_pay_total']['payMethodTotals']['ipay_card'] + (float) $donation['donation_pay_total']['payMethodTotals']['ipay_merch_qr'] + (float) $donation['donation_pay_total']['payMethodTotals']['ipay_online']; $variance_total = (float) $grandTotal - $tot_prod_amount; echo number_format($variance_total, 2); ?></strong></td>
										</tr>
									</tbody>

								</table><br>
							</div>

							<h3 style="text-align: center;">Hall Booking</h3>
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<th>S.no</th>
											<th>Event Type</th>
											<th>Total Sales (credit)</th>
											<th>Counter (debit)</th>
											<th>Ipay Card (debit)</th>
											<th>Ipay Merch QR (debit)</th>
											<th>Ipay Online (debit)</th>
											<th>Variance</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$s_no = 1;
										$grandTotal = 0;
										$totalCounter = $totalIpayCard = $totalIpayMerchQr = $totalIpayOnline = 0;

										if (isset($hall_booking['booking_details']) && is_array($hall_booking['booking_details'])) {
											foreach ($hall_booking['booking_details'] as $booking) {
												echo "<tr>";
												echo "<td>" . htmlspecialchars($s_no++) . "</td>";
												echo '<td><a href="' . base_url() . '/accountreport/ledger_report/?ledger=' . htmlspecialchars($booking['ledger_id']) . '&fdate=' . $fromDate . '&tdate=' . $toDate . '" target="_blank">' . htmlspecialchars($booking['name']) . '</a></td>';
												echo "<td>" . htmlspecialchars($booking['total_per_package']) . "</td>";

												$cashTotal = $booking['payment_info']['Cash Ledger'] ?? 0;
												$ipayCardTotal = $booking['payment_info']['Ipay QR Card'] ?? 0;
												$ipayMerchQrTotal = $booking['payment_info']['Ipay Merch QR'] ?? 0;
												$ipayOnlineTotal = $booking['payment_info']['Ipay Online'] ?? 0;

												$totalCounter += $cashTotal;
												$totalIpayCard += $ipayCardTotal;
												$totalIpayMerchQr += $ipayMerchQrTotal;
												$totalIpayOnline += $ipayOnlineTotal;

												echo "<td>" . htmlspecialchars($cashTotal) . "</td>";
												echo "<td>" . htmlspecialchars($ipayCardTotal) . "</td>";
												echo "<td>" . htmlspecialchars($ipayMerchQrTotal) . "</td>";
												echo "<td>" . htmlspecialchars($ipayOnlineTotal) . "</td>";

												$variance = (float)$booking['total_per_package'] - ($cashTotal + $ipayCardTotal + $ipayMerchQrTotal + $ipayOnlineTotal);
												echo '<td>' . number_format($variance, 2) . '</td>';

												echo "</tr>";
												$grandTotal += $booking['total_per_package'];
											}
										} else {
											echo "<tr><td colspan='8'>No data available</td></tr>";
										}
										?>
										<tr>
											<td colspan="2" style="text-align: right;"><strong>Grand Total:</strong></td>
											<td><strong><?php echo htmlspecialchars($grandTotal); ?></strong></td>
											<td><strong><?php echo htmlspecialchars($totalCounter); ?></strong></td>
											<td><strong><?php echo htmlspecialchars($totalIpayCard); ?></strong></td>
											<td><strong><?php echo htmlspecialchars($totalIpayMerchQr); ?></strong></td>
											<td><strong><?php echo htmlspecialchars($totalIpayOnline); ?></strong></td>
											<td></td>
										</tr>
									</tbody>
								</table><br>
							</div>


							<h3 style="text-align: center;">Ubayam</h3>
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<th>S.no</th>
											<th>Event Type</th>
											<th>Total Sales (credit)</th>
											<th>Counter (debit)</th>
											<th>Ipay Card (debit)</th>
											<th>Ipay Merch QR (debit)</th>
											<th>Ipay Online (debit)</th>
											<th>Variance</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$s_no = 1;
										$grandTotal = 0;
										$totalCounter = $totalIpayCard = $totalIpayMerchQr = $totalIpayOnline = 0;

										if (isset($ubayam['booking_details']) && is_array($ubayam['booking_details'])) {
											foreach ($ubayam['booking_details'] as $booking) {
												echo "<tr>";
												echo "<td>" . htmlspecialchars($s_no++) . "</td>";
												echo '<td><a href="' . base_url() . '/accountreport/ledger_report/?ledger=' . htmlspecialchars($booking['ledger_id']) . '&fdate=' . $fromDate . '&tdate=' . $toDate . '" target="_blank">' . htmlspecialchars($booking['name']) . '</a></td>';
												echo "<td>" . htmlspecialchars($booking['total_per_package']) . "</td>";

												$cashTotal = $booking['payment_info']['Cash Ledger'] ?? 0;
												$ipayCardTotal = $booking['payment_info']['Ipay QR Card'] ?? 0;
												$ipayMerchQrTotal = $booking['payment_info']['Ipay Merch QR'] ?? 0;
												$ipayOnlineTotal = $booking['payment_info']['Ipay Online'] ?? 0;

												$totalCounter += $cashTotal;
												$totalIpayCard += $ipayCardTotal;
												$totalIpayMerchQr += $ipayMerchQrTotal;
												$totalIpayOnline += $ipayOnlineTotal;

												echo "<td>" . htmlspecialchars($cashTotal) . "</td>";
												echo "<td>" . htmlspecialchars($ipayCardTotal) . "</td>";
												echo "<td>" . htmlspecialchars($ipayMerchQrTotal) . "</td>";
												echo "<td>" . htmlspecialchars($ipayOnlineTotal) . "</td>";

												$variance = (float)$booking['total_per_package'] - ($cashTotal + $ipayCardTotal + $ipayMerchQrTotal + $ipayOnlineTotal);
												echo '<td>' . number_format($variance, 2) . '</td>';

												echo "</tr>";
												$grandTotal += $booking['total_per_package'];
											}
										} else {
											echo "<tr><td colspan='8'>No data available</td></tr>";
										}
										?>
										<tr>
											<td colspan="2" style="text-align: right;"><strong>Grand Total:</strong></td>
											<td><strong><?php echo htmlspecialchars($grandTotal); ?></strong></td>
											<td><strong><?php echo htmlspecialchars($totalCounter); ?></strong></td>
											<td><strong><?php echo htmlspecialchars($totalIpayCard); ?></strong></td>
											<td><strong><?php echo htmlspecialchars($totalIpayMerchQr); ?></strong></td>
											<td><strong><?php echo htmlspecialchars($totalIpayOnline); ?></strong></td>
											<td></td>
										</tr>
									</tbody>
								</table><br>
							</div>

							<h3 style="text-align: center;">Prasadam</h3>
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<th>S.no</th>
											<th>Products</th>
											<th>Total Sales (credit)</th>
											<th>Counter (debit)</th>
											<th>Ipay_card (debit)</th>
											<th>Ipay_merch_qr (debit)</th>
											<th>Ipay_online (debit)</th>
											<th>Variance</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$s_no = 1;
										$grandTotal = 0; 

										if (isset($prasadam['prasadam_details']) && is_array($prasadam['prasadam_details'])) {
											foreach ($prasadam['prasadam_details'] as $product) {
												echo "<tr>";
												echo "<td>" . htmlspecialchars($s_no++) . "</td>";

												// Make donation name a clickable link
												echo '<td><a href="' . base_url() . '/accountreport/ledger_report/?ledger=' . htmlspecialchars($product['ledger_id']) . '&fdate=' . $fromDate . '&tdate=' . $toDate . '" target="_blank">' . htmlspecialchars($product['name']) . '</a></td>';

												echo "<td>" . htmlspecialchars($product['total_per_product']) . "</td>";

												// Display payment information
												$cashTotal = isset($product['payment_info']['cash']) ? $product['payment_info']['cash'] : 0;
												$ipayCardTotal = isset($product['payment_info']['ipay_card']) ? $product['payment_info']['ipay_card'] : 0;
												$ipayMerchQrTotal = isset($product['payment_info']['ipay_merch_qr']) ? $product['payment_info']['ipay_merch_qr'] : 0;
												$ipayOnlineTotal = isset($product['payment_info']['ipay_online']) ? $product['payment_info']['ipay_online'] : 0;
												$payment_total = (float) $cashTotal + (float) $ipayCardTotal + (float) $ipayMerchQrTotal + (float) $ipayOnlineTotal; 

												echo "<td>" . htmlspecialchars($cashTotal) . "</td>";
												echo "<td>" . htmlspecialchars($ipayCardTotal) . "</td>";
												echo "<td>" . htmlspecialchars($ipayMerchQrTotal) . "</td>";
												echo "<td>" . htmlspecialchars($ipayOnlineTotal) . "</td>";

												// Calculate and display variance
												$variance = (float) $product['total_per_product'] - $payment_total;
												echo '<td>' . number_format($variance, 2) . '</td>';

												echo "</tr>";

												$grandTotal += $product['total_per_product'];
											}
										} else {
											echo "<tr><td colspan='7'>No data available</td></tr>";
										}
										?>
										<tr>
											<td colspan="2" style="text-align: right;"><strong>Grand Total:</strong></td>
											<td><strong><?php echo htmlspecialchars($grandTotal); ?></strong></td>
											<td><strong><?php echo isset($prasadam['prasadam_pay_total']['payMethodTotals']['cash']) ? htmlspecialchars($prasadam['prasadam_pay_total']['payMethodTotals']['cash']) : '0'; ?></strong></td>
											<td><strong><?php echo isset($prasadam['prasadam_pay_total']['payMethodTotals']['ipay_card']) ? htmlspecialchars($prasadam['prasadam_pay_total']['payMethodTotals']['ipay_card']) : '0'; ?></strong></td>
											<td><strong><?php echo isset($prasadam['prasadam_pay_total']['payMethodTotals']['ipay_merch_qr']) ? htmlspecialchars($prasadam['prasadam_pay_total']['payMethodTotals']['ipay_merch_qr']) : '0'; ?></strong></td>
											<td><strong><?php echo isset($prasadam['prasadam_pay_total']['payMethodTotals']['ipay_online']) ? htmlspecialchars($prasadam['prasadam_pay_total']['payMethodTotals']['ipay_online']) : '0'; ?></strong></td>
											<td><strong><?php $tot_prod_amount = (float) $prasadam['prasadam_pay_total']['payMethodTotals']['cash'] + (float) $prasadam['prasadam_pay_total']['payMethodTotals']['ipay_card'] + (float) $prasadam['prasadam_pay_total']['payMethodTotals']['ipay_merch_qr'] + (float) $prasadam['prasadam_pay_total']['payMethodTotals']['ipay_online']; $variance_total = (float) $grandTotal - $tot_prod_amount; echo number_format($variance_total, 2); ?></strong></td>
										</tr>
									</tbody>

								</table><br>
							</div> */ ?>

						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</section>
