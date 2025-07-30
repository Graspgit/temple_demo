<style>
    body {
        font-family: 'Barlow', sans-serif;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table td, table th {
        padding: 10px;
        border: 1px solid #000;
        text-align: center;
    }

    table th {
        background-color: #f7ebbb;
        font-weight: bold;
    }

    .right-align {
        text-align: right;
    }

    .header-table td {
        border: none;
        text-align: center;
    }

    .header-table th {
        border: none;
    }

    .invoice-details td {
        text-align: left;
        padding: 5px 15px;
    }

    .footer-table td {
        border: none;
        text-align: center;
    }
</style>

<!-- Temple Details -->
<table style="border: none; width: 100%; text-align: center;">
    <tr>
        <td style="border: none; padding-bottom: 0;" align="center">
            <img src="<?php echo base_url(); ?>/uploads/main/<?php echo $temp_details['image']; ?>"
                style="width: 200px; max-width: 100%; display: block; margin: auto;">
        </td>
    </tr>
    <tr>
        <td style="border: none; padding-top: 0;">
            <h2 style="margin: 0; font-size: 15px; font-weight: bold;">
                <?php echo $temp_details['name_tamil']; ?>
            </h2>
            <h2 style="margin: 0; font-size: 18px; font-weight: bold;">
                <?php echo $temp_details['name']; ?>
            </h2>
            <p style="text-align: center; font-size: 18px; margin: 5px 0;">
                <?php echo $temp_details['address1'], $temp_details['address2']; ?>
                <?php echo $temp_details['city']; ?> - <?php echo $temp_details['postcode']; ?><br>
                Tel: <?php echo $temp_details['telephone']; ?>
            </p>
        </td>
    </tr>
</table>
<!-- Invoice Details Table -->
<table>
    <tr>
        <td style="text-align: left;"><strong>Devotee Name:</strong></td>
        <td style="text-align: left;"><?php echo $data['name']; ?>
        </td>
    </tr>
    <tr>
        <td style="text-align: left;"><strong>Phone Number:</strong></td>
        <td style="text-align: left;">
            <?php echo $data['phone']; ?>
        </td>
    </tr>
    <tr>
        <td style="text-align: left;"><strong>Invoice No:</strong></td>
        <td style="text-align: left;">
            <?php echo $data['ref_no']; ?>
        </td>
    </tr>
    <tr>
        <td style="text-align: left;"><strong>Date:</strong></td>
        <td style="text-align: left;">
            <?php echo date('d-m-Y'); ?>
        </td>
    </tr>
    <tr>
        <td style="text-align: left;"><strong>Description:</strong></td>
        <td style="text-align: left;">
            <?php echo $data['remarks']; ?>
        </td>
    </tr>
</table>

<br>
<br>

<!-- Product Offering Table -->
<table>
    <thead>
        <tr>
            <th>Category</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Value (&)</th>
            <th>Grams</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $total_grams = 0;
        foreach($offering_items as $item) {
            $total_grams += $item['grams']; ?>
            <tr>
                <td><?php echo $item['category_name']; ?></td>
                <td><?php echo $item['product_name']; ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td class="right-align"><?php echo number_format($item['value'], 2); ?></td>
                <td class="right-align"><?php echo $item['grams']; ?></td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="4"><strong>Total Grams</strong></td>
            <td class="right-align"><?php echo $total_grams; ?></td>
        </tr>
    </tbody>
</table>
<br>
<br>
   <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
    <div style="text-align: right;">
        
        <p>__________________________</p>
        <p><b>Signature</b></p>
    </div>
</div>


<script>
    window.print();
</script>
