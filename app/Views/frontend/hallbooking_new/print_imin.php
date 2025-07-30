<body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mui/3.7.1/js/mui.min.js"
        integrity="sha512-5LSZkoyayM01bXhnlp2T6+RLFc+dE4SIZofQMxy/ydOs3D35mgQYf6THIQrwIMmgoyjI+bqjuuj4fQcGLyJFYg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="<?php echo base_url(); ?>/assets/plugins/jquery/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/js/imin-printer-2.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/js/dom-to-image.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/vConsole/3.9.1/vconsole.min.js"></script>
    <div style="width: 150mm;font-weight: 600;font-family: monospace;" id="archanai_ticket">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="<?php echo base_url(); ?>/assets/css/Barlow.css" rel="stylesheet">
        <style>
            body {
                font-family: 'Barlow', sans-serif;
                background: #fff;
                box-sizing: border-box;
            }

            table {
                border-collapse: collapse;
            }

            table td {
                padding: 5px;
            }

            hr {
                border: none;
                border-top: 1px dashed #000;
                color: #fff;
                background-color: #fff;
                height: 1px;
            }

            p {
                font-size: 26px;
                text-align: center;
                font-weight: 600;
                font-family: monospace;
                margin: 0px
            }

            h3 {
                font-size: 32px;
                text-align: center;
                font-weight: 600;
                font-family: monospace;
                text-transform: uppercase;
            }

            tr td,
            tr th {
                font-size: 20px;
            }

            #archanai_ticket {
                color: #000;
                background: #fff;
                padding: 5px;
                display: block;
            }

            #archanai_loader {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100%;
                height: 100%;
            }

            img {
                max-width: 100%;
            }
        </style>
       

        <p style="border-bottom: 3px dotted #9E9E9E;max-width: 150mm;"></p>
        <h3 style="max-width: 150mm;margin: 5px 0;">Customer Copy</h3>
        <p style="border-bottom: 3px dotted #9E9E9E;max-width: 150mm;"></p>
        <br>

        <p><img src="<?php echo base_url(); ?>/uploads/main/<?php echo $temp_details['image']; ?>" style="width:200px;"
                align="center"></p>
        <h2 style="text-align:center; margin:0;font-size:26px;"><?php echo $temp_details['name']; ?></h2>
        <p><?php echo $temp_details['address1']; ?>, <?php echo $temp_details['address2']; ?></br>
            <?php echo $temp_details['city'] . '-' . $temp_details['postcode']; ?>.
            Tel: <?= $temp_details['telephone']; ?></p>
        <hr>
        <p style="text-align: center; font-size:28px;">Hall Booking Voucher</p>
        <hr>

        <table align="center" border="0">
            <tbody>
                <tr>
                    <td style="text-align: right">Payment Date</td>
                    <td>:</td>
                    <td><?php $date = new DateTime($qry1['dt']);
                        echo $date->format('d-m-Y'); ?> </td>
                </tr>
                <tr>
                    <td style="text-align: right">Invoice</td>
                    <td>:</td>
                    <td><?php echo $qry1['ref_no']; ?> </td>
                </tr>
                <tr>
                    <td style="text-align: right">Package Name</td>
                    <td>:</td>
                    <td><?php echo htmlspecialchars($package_names, ENT_QUOTES, 'UTF-8'); ?> </td>
                </tr>
                <tr>
                    <td style="text-align: right">Booked Date</td>
                    <td>:</td>
                    <td><?php echo !empty($qry1['booking_date']) ? date('d-m-Y', strtotime($qry1['booking_date'])) : ''; ?> </td>
                </tr>
                <tr>
                    <td style="text-align: right">Venue</td>
                    <td>:</td>
                    <td><?php echo $qry1['venue_name']; ?> </td>
                </tr>
                <tr>
                    <td style="text-align: right">Booked Slot</td>
                    <td>:</td>
                    <td><?php echo $booked_slot['slot_name']; ?> </td>
                </tr>
                <tr>
                    <td style="text-align: right">Name</td>
                    <td>:</td>
                    <td><?php echo $qry1['name']; ?> </td>
                </tr>
                <tr>
                    <td style="text-align: right">Mobile No</td>
                    <td>:</td>
                    <td><?php echo $qry1['mobile_no']; ?> </td>
                </tr>
                <?php if (!empty($qry1['discount_amount']) && $qry1['discount_amount'] > 0) { ?>
                    <tr>
                        <td style="text-align: right">Sub Total (RM)</td>
                        <td>:</td>
                        <td><?php echo number_format($qry1['amount'] + $qry1['discount_amount'], '2', '.', ','); ?> </td>
                    </tr>
                    <tr>
                        <td style="text-align: right">Discount (RM)</td>
                        <td>:</td>
                        <td>- <?php echo number_format($qry1['discount_amount'], '2', '.', ','); ?> </td>
                    </tr>
                    <tr>
                        <td style="text-align: right">Total (RM)</td>
                        <td>:</td>
                        <td><?php echo number_format($qry1['amount'], '2', '.', ','); ?> </td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <td style="text-align: right">Total (RM)</td>
                        <td>:</td>
                        <td><?php echo number_format($qry1['amount'], '2', '.', ','); ?> </td>
                    </tr>
                <?php } ?> 
                <tr>
                    <td style="text-align: right">Refund Deposit(RM)</td>
                    <td>:</td>
                    <td><?php echo number_format($qry1['deposit_amount'], '2', '.', ','); ?> </td>
                </tr>
                <tr>
                    <td style="text-align: right">Paid Amount(RM)</td>
                    <td>:</td>
                    <td><?php echo number_format($qry1['paid_amount'], 2, '.', ','); ?> </td>
                </tr>
                <?php
                $amount = $qry1['amount']+ $qry1['deposit_amount'];
                $paid_amount = $qry1['paid_amount'];
                $balance_amount = $amount-$paid_amount;
                ?>
                <?php if ($balance_amount): ?>
                    <tr>
                        <td style="text-align: right">Balance Amount (RM)</td>
                        <td>:</td>
                        <td><?php echo number_format($balance_amount, 2, '.', ','); ?> </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <hr><br><br>

    <?php if (count($booked_services)) { ?>
        <tr>
            <td colspan="2">
                <h2 style="text-align:center; font-size: 28px;"> Included Services </h2>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table border="1" style="width:100%" align="center">
                    <tr>
                        <th width="50%" style="text-align:left">Name</th>
                        <th width="50%">Quantity</th>
                    </tr>
                    <?php
                    foreach ($booked_services as $row) { ?>
                        <tr>
                            <td>
                                <?php echo $row['name']; ?>
                            </td>
                            <td align="center">
                                <?php echo $row['quantity']; ?>
                            </td>

                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </td>
        </tr>
    <?php } ?>

	<?php if (count($booked_addon)) { ?>
        <tr>
            <td colspan="2">
                <h2 style="text-align:center; font-size: 28px;"> Add-on Details </h2>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table border="1" style="width:100%" align="center">
                    <tr>
                        <th width="33%" style="text-align:left">Name</th>
                        <th width="33%">Quantity</th>
                        <th width="33%" style="text-align:left">Amount</th>
                    </tr>
                    <?php
                    foreach ($booked_addon as $row) { ?>
                        <tr>
                            <td>
                                <?php echo $row['name']; ?>
                            </td>
                            <td align="center">
                                <?php echo $row['quantity']; ?>
                            </td>
                            <td>
                                <?php echo $row['amount']; ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </td>
        </tr>
    <?php } ?>
        <?php if (is_array($family_details) && count($family_details)) { ?>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">
                    <h4 style="text-align:center;"> Family Details </h4>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table border="1" style="width:100%" align="center">
                        <tr>
                            <th width="33%" style="text-align:left">Name</th>
                            <th width="33%">Rasi</th>
                            <th width="33%" style="text-align:left">Natchathiram</th>
                        </tr>
                        <?php
                        foreach ($family_details as $row) { ?>
                            <tr>
                                <td>
                                    <?php echo $row['name']; ?>
                                </td>
                                <td align="center">
                                    <?php echo $row['rasi']; ?>
                                </td>
                                <td>
                                    <?php echo $row['natchathiram']; ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
        <?php
        }
        ?>
        <?php if (count($extra_charges)) { ?>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">
                    <h2 style="text-align:center;font-size: 28px;"> Extra Charges </h2>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table border="1" style="width:100%" align="center">
                        <tr>
                            <th width="50%" style="font-size: 23px; text-align:left">Description</th>
                            <th width="50%" style="font-size: 23px;">Amount</th>
                        </tr>
                        <?php
                        foreach ($extra_charges as $row) { ?>
                            <tr>
                                <td style="font-size: 23px;">
                                    <?php echo $row['description']; ?>
                                </td>
                                <td style="font-size: 23px;" align="center">
                                    <?php echo $row['amount']; ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
        <?php
        }
        ?>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
    
        <!-- <p><span>---</span>GRASP SOFTWARE SOLUTIONS SDN. BHD.<span>---</span></p> -->
        <hr>
        <br>
    </div>
    <div class="archanai_loader">
        <img src="<?php echo base_url(); ?>/assets/images/loader.gif" />
    </div>
    <?php /* <img src="" id="test_img" /> */ ?>
    <?php /* <div>
   <button class="btn btn-primary" id="web_print">Web Print</button>
   <button class="btn btn-success" id="imin_print">Imin Print</button>
</div> */ ?>
    <script>
        var vConsole = new VConsole();
        function printDiv() {

            var divToPrint = document.getElementById('archanai_ticket');

            var newWin = window.open('', 'Print-Window');

            newWin.document.open();

            newWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');

            newWin.document.close();

            setTimeout(function () { newWin.close(); }, 1500);

        }
        $(document).ready(function () {
            $(document).on('click', '#web_print', function () {
                printDiv();
            });
            /* var node = document.getElementById('archanai_ticket');
            domtoimage.toJpeg(node).then(function (dataUrl) {
                $('#test_img').attr('src', dataUrl);
            }); */
        });
        var IminPrintInstance = new IminPrinter();
        console.log('IminPrintInstance');
        console.log(IminPrintInstance);
        IminPrintInstance.connect().then(async (isConnect) => {
            if (isConnect) {
                $('.archanai_loader').hide();
                $('#archanai_ticket').show();
                console.log(await IminPrintInstance.getPrinterStatus());
                var QrCodeSize;
                //mui('body').on('tap', '#imin_print', async function (e) {
                IminPrintInstance.initPrinter();
                console.log(await IminPrintInstance.getPrinterStatus());
                var node = document.getElementById('archanai_ticket');
                domtoimage.toJpeg(node).then(function (dataUrl) {
                    IminPrintInstance.printSingleBitmap(dataUrl).then(() => {
                        console.log('sucess');
                        IminPrintInstance.printAndFeedPaper(100);
                        IminPrintInstance.partialCut();
                        setTimeout(function () { window.close(); }, 500);
                        //setTimeout(function(){print_queue(IminPrintInstance, ticket, i + 1);},1000);
                    });
                    /* setTimeout(function(){window.close();}, 1500); */
                });
                //});
            } else {
                alert('error printer');
            }
        });
    </script>
</body>