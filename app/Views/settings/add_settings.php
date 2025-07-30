<?php global $lang; ?>
<style>
    .kazhanji_option_text,
    .kazhanji_option_image {
        display: none;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <?php if ($_SESSION['succ'] != '') { ?>
            <div class="row" style="padding: 0 30% 2% 30%;" id="content_alert">
                <div class="suc-alert">
                    <span class="suc-closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <p>
                        <?php echo $_SESSION['succ']; ?>
                    </p>
                </div>
            </div>
        <?php } ?>
        <?php if ($_SESSION['fail'] != '') { ?>
            <div class="row" style="padding: 0 30% 2% 30%;" id="content_alert">
                <div class="alert">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <p>
                        <?php echo $_SESSION['fail']; ?>
                    </p>
                </div>
            </div>
        <?php } ?>
        <div class="block-header">
            <h2>
                Settings
            </h2>
        </div>

        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">

                    <div class="body">
                        <ul class="nav nav-tabs tab-nav-right" role="tablist" style="flex-direction: row;">
                            <li role="presentation" class="active"><a href="#archanai" data-toggle="tab"
                                    aria-expanded="false">ARCHANAI</a></li>
                            <li role="presentation" class=""><a href="#donation" data-toggle="tab"
                                    aria-expanded="false">DONATION</a></li>
                            <li role="presentation" class=""><a href="#prasadam" data-toggle="tab"
                                    aria-expanded="false">PRASADAM</a></li>
                            <li role="presentation" class=""><a href="#annathanam" data-toggle="tab"
                                    aria-expanded="false">ANNATHANAM</a></li>
                            <li role="presentation" class=""><a href="#ubayam" data-toggle="tab"
                                    aria-expanded="false">UBAYAM</a></li>
                            <li role="presentation" class=""><a href="#hall_booking" data-toggle="tab"
                                    aria-expanded="false">HALL BOOKING</a></li>
                            <li role="presentation" class=""><a href="#daily_closing" data-toggle="tab"
                                    aria-expanded="false">DAILY CLOSING</a></li>
                            <li role="presentation" class=""><a href="#sales_invoice" data-toggle="tab"
                                    aria-expanded="false">SALES INVOICE</a></li>
                            <li role="presentation" class=""><a href="#purchase_invoice" data-toggle="tab"
                                    aria-expanded="false">PURCHASE INVOICE</a></li>
                        </ul>

                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active" id="archanai">
                                <form action="<?php echo base_url(); ?>/settings/save_settings" method="POST"
                                    enctype="multipart/form-data">
                                    <div class="body">
                                        <div class="container-fluid">
                                            <h4> Booking Settings</h4>
                                            <hr>
                                            <div class="row clearfix">
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="">
                                                            <input type="checkbox" class="form-control"
                                                                name="settings[1][enable_tender]" id="enable_tender"
                                                                <?php echo !empty($settings[1]['enable_tender']) ? ' checked="checked"' : ''; ?> value="1">
                                                            <label class="form-label" for="enable_tender">Enable Tender
                                                                Concept:</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="">
                                                            <input type="checkbox" class="form-control"
                                                                name="settings[1][show_deity_print]"
                                                                id="show_deity_print" <?php echo !empty($settings[1]['show_deity_print']) ? ' checked="checked"' : ''; ?> value="1">
                                                            <label class="form-label" for="show_deity_print">Show Deity
                                                                in Receipt</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="">
                                                            <input type="checkbox" class="form-control"
                                                                name="settings[1][show_deity_code_no]"
                                                                id="show_deity_code_no" <?php echo !empty($settings[1]['show_deity_code_no']) ? ' checked="checked"' : ''; ?> value="1">
                                                            <label class="form-label" for="show_deity_code_no">Show
                                                                Deity Code in Print Ticket No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="">
                                                            <input type="checkbox" class="form-control"
                                                                name="settings[1][archanai_only_booking]"
                                                                id="archanai_only_booking" <?php echo !empty($settings[1]['archanai_only_booking']) ? ' checked="checked"' : ''; ?> value="1">
                                                            <label class="form-label"
                                                                for="archanai_only_booking">Archanai Only
                                                                Booking</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">

                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="">
                                                            <input type="checkbox" class="form-control"
                                                                name="settings[1][archanai_discount]"
                                                                id="archanai_discount" <?php echo !empty($settings[1]['archanai_discount']) ? ' checked="checked"' : ''; ?> value="1">
                                                            <label class="form-label" for="archanai_discount">Enable
                                                                <?php echo $lang->archanai; ?> Discount <span
                                                                    style="color: red;"> *</span></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 archanai_discount">
                                                    <div class="form-group">
                                                        <select class="form-control search_box" data-live-search="true"
                                                            name="settings[1][discount_archanai_ledger_id]"
                                                            id="discount_archanai_ledger_id">
                                                            <option value="">Discount Ledger</option>
                                                            <?php
                                                            if (!empty($discount_ledgers)) {
                                                                foreach ($discount_ledgers as $ledger) {
                                                            ?>
                                                                    <option value="<?php echo $ledger["id"]; ?>" <?php if (!empty($settings[1]['discount_archanai_ledger_id'])) {
                                                                                                                        if ($settings[1]['discount_archanai_ledger_id'] == $ledger["id"]) {
                                                                                                                            echo "selected";
                                                                                                                        }
                                                                                                                    } ?>>
                                                                        <?php echo $ledger['left_code'] . '/' . $ledger['right_code'] . " - " . $ledger["name"]; ?>
                                                                    </option>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <h4> Print Settings </h4>
                                            <hr>
                                            <div class="row clearfix">
                                                <div class="col-sm-4">
                                                    <h5>Please select your preferred Receipt type:</h5>
                                                    <div class="form-group form-float">
                                                        <div class="">
                                                            <input type="checkbox" name="settings[1][enable_print]"
                                                                id="enable_print" value="1" <?php echo !empty($settings[1]['enable_print']) ? 'checked="checked"' : ''; ?>>
                                                            <label for="enable_print">Print</label>
                                                        </div>
                                                        <div class="">
                                                            <input type="checkbox" name="settings[1][enable_sep_print]"
                                                                id="enable_sep_print" value="1" <?php echo !empty($settings[1]['enable_sep_print']) ? 'checked="checked"' : ''; ?>>
                                                            <label for="enable_sep_print">Separate Print</label>
                                                        </div>
                                                        <div class="">
                                                            <input type="checkbox" name="settings[1][no_print]"
                                                                id="no_print" value="1" <?php echo !empty($settings[1]['no_print']) ? 'checked="checked"' : ''; ?>>
                                                            <label for="no_print">No Print</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="">
                                                        <label>Archanai Slogan</label>
                                                        <textarea name="settings[1][archanai_slogan]"
                                                            class="form-control"><?php echo $settings[1]['archanai_slogan']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-sm-4">
                                                    
                                                    <input type="checkbox" name="settings[1][show_date]"
                                                                    id="show_date" value="1" <?php echo !empty($settings[1]['show_date']) ? 'checked="checked"' : ''; ?>>
                                                                <label for="show_date">Show Date</label>
                                                    </div>
                                                    <!--<div class="col-sm-4">
                                                    <input type="checkbox" name="settings[1][want_prod_img]"
                                                                    id="want_prod_img" value="1" <?php echo !empty($settings[1]['want_prod_img']) ? 'checked="checked"' : ''; ?>>
                                                                <label for="want_prod_img">Do you want product image</label>
                                                                 </div>
                                                                -->
                                               
                                            </div>
                                            <?php /*
                                            <div class="row clearfix">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Kalanji Option:</label>
                                                        <span>
                                                            <input type="radio" name="settings[1][kazhanji_option]"
                                                                id="kazhanji_text" value="text" <?php echo !empty($settings[1]['kazhanji_option']) ? ($settings[1]['kazhanji_option'] == 'text' ? 'checked="checked"' : '') : ''; ?>>
                                                            <label for="kazhanji_text">Text</label>
                                                        </span>
                                                        <span>
                                                            <input type="radio" name="settings[1][kazhanji_option]"
                                                                id="kazhanji_image" value="image" <?php echo !empty($settings[1]['kazhanji_option']) ? ($settings[1]['kazhanji_option'] == 'image' ? 'checked="checked"' : '') : ''; ?>>
                                                            <label for="kazhanji_image">Image</label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 kazhanji_option_sec">
                                                    <div class="kazhanji_option_text" <?php echo !empty($settings[1]['kazhanji_option']) ? ($settings[1]['kazhanji_option'] == 'text' ? 'style="display: block;"' : '') : ''; ?>>
                                                        <input type="text" class="form-control"
                                                            name="settings[1][kazhanji_option_text]"
                                                            id="kazhanji_option_text"
                                                            value="<?php echo !empty($settings[1]['kazhanji_option_text']) ? $settings[1]['kazhanji_option_text'] : ''; ?>">
                                                    </div>
                                                    <div class="kazhanji_option_image" <?php echo !empty($settings[1]['kazhanji_option']) ? ($settings[1]['kazhanji_option'] == 'image' ? 'style="display: block;"' : '') : ''; ?>>
                                                        <input type="file" name="archanai_kazhanji_upload"
                                                            id="archanai_kazhanji_upload" />
                                                        <input type="hidden" name="settings[1][kazhanji_option_image]"
                                                            id="kazhanji_option_image"
                                                            value="<?php echo !empty($settings[1]['kazhanji_option_image']) ? $settings[1]['kazhanji_option_image'] : ''; ?>">
                                                        <?php echo !empty($settings[1]['kazhanji_option_image']) ? '<p style="margin-top: 15px;"><img src="' . base_url() . '/uploads/kazhanji/' . $settings[1]['kazhanji_option_image'] . '" style="max-width:172px;>"</p>' : ''; ?>
                                                    </div>
                                                </div>
                                            </div>
                                                           */ ?>
                                            <div class="row clearfix">
                                                <div class="col-sm-12" align="center">
                                                    <button type="submit"
                                                        class="btn btn-success btn-lg waves-effect">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="donation">
                                <form action="<?php echo base_url(); ?>/settings/save_settings" method="POST"
                                    enctype="multipart/form-data">
                                    <div class="body">
                                        <div class="container-fluid">
                                            <h4> Print Settings </h4>
                                            <hr>
                                            <div class="row clearfix">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="print_method">Choose your preferred print method
                                                            after booking:</label>
                                                        <div>
                                                            <input type="radio" name="settings[2][print_method]"
                                                                id="print_imin" value="imin" <?php echo (isset($settings[5]['print_method']) && $settings[2]['print_method'] == 'imin') ? 'checked="checked"' : ''; ?>>
                                                            <label for="print_imin">Print Imin</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="settings[2][print_method]"
                                                                id="print_a4" value="a4" <?php echo (isset($settings[2]['print_method']) && $settings[2]['print_method'] == 'a4') ? 'checked="checked"' : ''; ?>>
                                                            <label for="print_a4">Print A4</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row clearfix">
                                                <div class="col-sm-12" align="center">
                                                    <button type="submit"
                                                        class="btn btn-success btn-lg waves-effect">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="prasadam">
                                <form action="<?php echo base_url(); ?>/settings/save_settings" method="POST"
                                    enctype="multipart/form-data">
                                    <div class="body">
                                        <div class="container-fluid">
                                            <h4> Booking Settings</h4>
                                            <hr>
                                            <div class="row clearfix">
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="">
                                                            <input type="checkbox" class="form-control"
                                                                name="settings[3][enable_madapalli]"
                                                                id="enable_madapalli3" <?php echo !empty($settings[3]['enable_madapalli']) ? ' checked="checked"' : ''; ?> value="1">
                                                            <label class="form-label" for="enable_madapalli3">Add
                                                                Bookings to Madapalli</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="">
                                                            <input type="checkbox" class="form-control"
                                                                name="settings[3][prasadam_discount]"
                                                                id="prasadam_discount" <?php echo !empty($settings[3]['prasadam_discount']) ? ' checked="checked"' : ''; ?> value="1">
                                                            <label class="form-label" for="prasadam_discount">Enable
                                                                <?php echo $lang->prasadam; ?> Discount <span
                                                                    style="color: red;"> *</span></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row clearfix">
                                                    <div class="col-sm-4">
                                                        <div class="form-group form-float">
                                                            <div class="">
                                                                <input type="checkbox" class="form-control"
                                                                    name="settings[3][prasadam_only_booking]"
                                                                    id="prasadam_only_booking" <?php echo !empty($settings[3]['prasadam_only_booking']) ? ' checked="checked"' : ''; ?> value="1">
                                                                <label class="form-label"
                                                                    for="prasadam_only_booking">Prasadam Only
                                                                    Booking</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-4 prasadam_discount">
                                                    <div class="form-group">
                                                        <select class="form-control search_box" data-live-search="true"
                                                            name="settings[3][discount_prasadam_ledger_id]"
                                                            id="discount_prasadam_ledger_id">
                                                            <option value="">Discount Ledger</option>
                                                            <?php
                                                            if (!empty($discount_ledgers)) {
                                                                foreach ($discount_ledgers as $ledger) {
                                                            ?>
                                                                    <option value="<?php echo $ledger["id"]; ?>" <?php if (!empty($settings[3]['discount_prasadam_ledger_id'])) {
                                                                                                                        if ($settings[3]['discount_prasadam_ledger_id'] == $ledger["id"]) {
                                                                                                                            echo "selected";
                                                                                                                        }
                                                                                                                    } ?>>
                                                                        <?php echo $ledger['left_code'] . '/' . $ledger['right_code'] . " - " . $ledger["name"]; ?>
                                                                    </option>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <h4> Print Settings </h4>
                                            <hr>
                                            <div class="row clearfix">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="print_method">Choose your preferred print method
                                                            after booking:</label>
                                                        <div>
                                                            <input type="radio" name="settings[3][print_method]"
                                                                id="print_imin3" value="imin" <?php echo (isset($settings[3]['print_method']) && $settings[3]['print_method'] == 'imin') ? 'checked="checked"' : ''; ?>>
                                                            <label for="print_imin3">Print Imin</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="settings[3][print_method]"
                                                                id="3print_a4" value="a4" <?php echo (isset($settings[3]['print_method']) && $settings[3]['print_method'] == 'a4') ? 'checked="checked"' : ''; ?>>
                                                            <label for="3print_a4">Print A4</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="settings[3][print_method]"
                                                                id="3print_a5" value="a5" <?php echo (isset($settings[3]['print_method']) && $settings[3]['print_method'] == 'a5') ? 'checked="checked"' : ''; ?>>
                                                            <label for="3print_a5">Print A5</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row clearfix">
                                                <div class="col-sm-12" align="center">
                                                    <button type="submit"
                                                        class="btn btn-success btn-lg waves-effect">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="annathanam">
                                <form action="<?php echo base_url(); ?>/settings/save_settings" method="POST"
                                    enctype="multipart/form-data">
                                    <div class="body">
                                        <div class="container-fluid">
                                            <h4> Booking Settings</h4>
                                            <hr>
                                            <div class="row clearfix">
                                                <div class="col-sm-3">
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="text" name="settings[4][annathanam_min_pax]"  class="form-control" value="<?php echo $settings[4]['annathanam_min_pax']; ?>" <?php echo $readonly; ?> >
                                                            <label class="form-label"> Minimum No.of Pax <span style="color: red;">*</span></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group form-float">
                                                        <div class="">
                                                            <input type="checkbox" class="form-control"
                                                                name="settings[4][enable_madapalli]"
                                                                id="enable_madapalli4" <?php echo !empty($settings[4]['enable_madapalli']) ? ' checked="checked"' : ''; ?> value="1">
                                                            <label class="form-label" for="enable_madapalli4">Add
                                                                Bookings to Madapalli</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group form-float">
                                                        <div class="">
                                                            <input type="checkbox" class="form-control" name="settings[4][enable_terms]" id="enable_terms4" <?php echo !empty($settings[4]['enable_terms']) ? ' checked="checked"' : ''; ?> value="1">
                                                            <label class="form-label" for="enable_terms4">Enable Terms and Conditions</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="">
                                                            <input type="checkbox" class="form-control"
                                                                name="settings[4][annathanam_only_booking]"
                                                                id="annathanam_only_booking" <?php echo !empty($settings[4]['annathanam_only_booking']) ? ' checked="checked"' : ''; ?> value="1">
                                                            <label class="form-label"
                                                                for="annathanam_only_booking">Annathanam Only
                                                                Booking</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="">
                                                            <input type="checkbox" class="form-control"
                                                                name="settings[4][annathanam_discount]"
                                                                id="annathanam_discount" <?php echo !empty($settings[4]['annathanam_discount']) ? ' checked="checked"' : ''; ?> value="1">
                                                            <label class="form-label" for="annathanam_discount">Enable
                                                                <?php echo $lang->annathanam; ?> Discount <span
                                                                    style="color: red;"> *</span></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 annathanam_discount">
                                                    <div class="form-group">
                                                        <select class="form-control search_box" data-live-search="true"
                                                            name="settings[4][discount_annathanam_ledger_id]"
                                                            id="discount_annathanam_ledger_id">
                                                            <option value="">Discount Ledger</option>
                                                            <?php
                                                            if (!empty($discount_ledgers)) {
                                                                foreach ($discount_ledgers as $ledger) {
                                                            ?>
                                                                    <option value="<?php echo $ledger["id"]; ?>" <?php if (!empty($settings[4]['discount_annathanam_ledger_id'])) {
                                                                                                                        if ($settings[4]['discount_annathanam_ledger_id'] == $ledger["id"]) {
                                                                                                                            echo "selected";
                                                                                                                        }
                                                                                                                    } ?>>
                                                                        <?php echo $ledger['left_code'] . '/' . $ledger['right_code'] . " - " . $ledger["name"]; ?>
                                                                    </option>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <h4> Print Settings </h4>
                                            <hr>
                                            <div class="row clearfix">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="print_method">Choose your preferred print method
                                                            after booking:</label>
                                                        <div>
                                                            <input type="radio" name="settings[4][print_method]"
                                                                id="print_imin4" value="imin" <?php echo (isset($settings[4]['print_method']) && $settings[4]['print_method'] == 'imin') ? 'checked="checked"' : ''; ?>>
                                                            <label for="print_imin4">Print Imin</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="settings[4][print_method]"
                                                                id="4print_a4" value="a4" <?php echo (isset($settings[4]['print_method']) && $settings[4]['print_method'] == 'a4') ? 'checked="checked"' : ''; ?>>
                                                            <label for="4print_a4">Print A4</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="settings[4][print_method]"
                                                                id="4print_a5" value="a5" <?php echo (isset($settings[4]['print_method']) && $settings[4]['print_method'] == 'a5') ? 'checked="checked"' : ''; ?>>
                                                            <label for="4print_a5">Print A5</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row clearfix">
                                                <div class="col-sm-12" align="center">
                                                    <button type="submit"
                                                        class="btn btn-success btn-lg waves-effect">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="ubayam">
                                <form action="<?php echo base_url(); ?>/settings/save_settings" method="POST" enctype="multipart/form-data">
                                    <div class="body">
                                        <div class="container-fluid">
                                            <h4> Booking Settings</h4>
                                            <hr>
                                            <div class="row clearfix">
                                                <div class="col-sm-3">
                                                     <p><b> Select Prefered Ubayam Services: </b></p>
                                                     <br>
                                                    <div class="form-group form-float">
                                                        <div class="">

                                                            <input type="checkbox" class="form-control" name="settings[5][enable_abishegam]" id="enable_abishegam" <?php echo !empty($settings[5]['enable_abishegam']) ? ' checked="checked"' : ''; ?> value="1">
                                                            <label class="form-label" for="enable_abishegam">Enable Abishegam</label><br>

                                                            <input type="checkbox" class="form-control" name="settings[5][enable_homam]" id="enable_homam" <?php echo !empty($settings[5]['enable_homam']) ? ' checked="checked"' : ''; ?> value="1">
                                                            <label class="form-label" for="enable_homam">Enable Homam</label><br>

                                                            <input type="checkbox" class="form-control" name="settings[5][enable_prasadam]" id="enable_prasadam" <?php echo !empty($settings[5]['enable_prasadam']) ? ' checked="checked"' : ''; ?> value="1">
                                                            <label class="form-label" for="enable_prasadam">Enable Addon Prasadam</label><br>

                                                            <input type="checkbox" class="form-control" name="settings[5][enable_extra_charges]" id="enable_extra_charges" <?php echo !empty($settings[5]['enable_extra_charges']) ? ' checked="checked"' : ''; ?> value="1">
                                                            <label class="form-label" for="enable_extra_charges">Enable Extra Charges:</label><br>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="">
                                                            <input type="checkbox" class="form-control" name="settings[5][ubayam_only_booking]" id="ubayam_only_booking" <?php echo !empty($settings[5]['ubayam_only_booking']) ? ' checked="checked"' : ''; ?> value="1">   
                                                            <label class="form-label" for="ubayam_only_booking">Ubayam Only Booking</label>      
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="">
                                                            <input type="checkbox" class="form-control"
                                                                name="settings[5][enable_madapalli]"
                                                                id="enable_madapalli5" <?php echo !empty($settings[5]['enable_madapalli']) ? ' checked="checked"' : ''; ?> value="1">
                                                            <label class="form-label" for="enable_madapalli5">Add
                                                                Prasadam Items to Madapalli</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="">
                                                            <input type="checkbox" class="form-control"
                                                                name="settings[5][ubayam_discount]" id="ubayam_discount"
                                                                <?php echo !empty($settings[5]['ubayam_discount']) ? ' checked="checked"' : ''; ?> value="1">
                                                            <label class="form-label" for="ubayam_discount">Enable
                                                                <?php echo $lang->ubayam; ?> Discount <span
                                                                    style="color: red;"> *</span></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 ubayam_discount">
                                                    <div class="form-group">
                                                        <select class="form-control search_box" data-live-search="true"
                                                            name="settings[5][discount_ubayam_ledger_id]"
                                                            id="discount_ubayam_ledger_id">
                                                            <option value="">Discount Ledger</option>
                                                            <?php
                                                            if (!empty($discount_ledgers)) {
                                                                foreach ($discount_ledgers as $ledger) {
                                                            ?>
                                                                    <option value="<?php echo $ledger["id"]; ?>" <?php if (!empty($settings[5]['discount_ubayam_ledger_id'])) {
                                                                                                                        if ($settings[5]['discount_ubayam_ledger_id'] == $ledger["id"]) {
                                                                                                                            echo "selected";
                                                                                                                        }
                                                                                                                    } ?>>
                                                                        <?php echo $ledger['left_code'] . '/' . $ledger['right_code'] . " - " . $ledger["name"]; ?>
                                                                    </option>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <h4> Print Settings </h4>
                                            <hr>
                                            <div class="row clearfix">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="print_method">Choose your preferred print method
                                                            after booking:</label>
                                                        <div>
                                                            <input type="radio" name="settings[5][print_method]"
                                                                id="print_imin" value="imin" <?php echo (isset($settings[5]['print_method']) && $settings[5]['print_method'] == 'imin') ? 'checked="checked"' : ''; ?>>
                                                            <label for="print_imin">Print Imin</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="settings[5][print_method]"
                                                                id="print_a4" value="a4" <?php echo (isset($settings[5]['print_method']) && $settings[5]['print_method'] == 'a4') ? 'checked="checked"' : ''; ?>>
                                                            <label for="print_a4">Print A4</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="settings[5][print_method]"
                                                                id="print_a5" value="a5" <?php echo (isset($settings[5]['print_method']) && $settings[5]['print_method'] == 'a5') ? 'checked="checked"' : ''; ?>>
                                                            <label for="print_a5">Print A5</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row clearfix">
                                                <div class="col-sm-12" align="center">
                                                    <button type="submit"
                                                        class="btn btn-success btn-lg waves-effect">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="hall_booking">
                                <form action="<?php echo base_url(); ?>/settings/save_settings" method="POST"
                                    enctype="multipart/form-data">
                                    <div class="body">
                                        <div class="container-fluid">
                                            <h4> Booking Settings</h4>
                                            <hr>
                                            <div class="row clearfix">
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="">
                                                            <input type="checkbox" class="form-control"
                                                                name="settings[6][enable_extra_charges]"
                                                                id="enable_extra_charges6" <?php echo !empty($settings[6]['enable_extra_charges']) ? ' checked="checked"' : ''; ?> value="1">
                                                            <label class="form-label" for="enable_extra_charges6">Enable
                                                                Extra Charges:</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="">
                                                            <input type="checkbox" class="form-control" name="settings[6][enable_prasadam]" id="enable_prasadam6" <?php echo !empty($settings[6]['enable_prasadam']) ? ' checked="checked"' : ''; ?> value="1">
                                                            <label class="form-label" for="enable_prasadam6">Enable Addon Prasadam:</label>
                                                        </div>
                                                    </div>
                                                </div> -->
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="">
                                                            <input type="checkbox" class="form-control"
                                                                name="settings[6][Hall_only_booking]"
                                                                id="Hall_only_booking" <?php echo !empty($settings[6]['Hall_only_booking']) ? ' checked="checked"' : ''; ?> value="1">
                                                            <label class="form-label" for="Hall_only_booking">Hall Only
                                                                Booking</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row clearfix">
                                                <div class="col-sm-4">
                                                    <div class="form-group form-float">
                                                        <div class="">
                                                            <input type="checkbox" class="form-control"
                                                                name="settings[6][hall_discount]" id="hall_discount"
                                                                <?php echo !empty($settings[6]['hall_discount']) ? ' checked="checked"' : ''; ?> value="1">
                                                            <label class="form-label" for="hall_discount">Enable
                                                                <?php echo $lang->hall; ?>
                                                                <?php echo $lang->discount; ?> <span
                                                                    style="color: red;"> *</span></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 hall_discount">
                                                    <div class="form-group">
                                                        <select class="form-control search_box" data-live-search="true"
                                                            name="settings[6][discount_hall_ledger_id]"
                                                            id="discount_hall_ledger_id">
                                                            <option value="">Discount Ledger</option>
                                                            <?php
                                                            if (!empty($discount_ledgers)) {
                                                                foreach ($discount_ledgers as $ledger) {
                                                            ?>
                                                                    <option value="<?php echo $ledger["id"]; ?>" <?php if (!empty($settings[6]['discount_hall_ledger_id'])) {
                                                                                                                        if ($settings[6]['discount_hall_ledger_id'] == $ledger["id"]) {
                                                                                                                            echo "selected";
                                                                                                                        }
                                                                                                                    } ?>>
                                                                        <?php echo $ledger['left_code'] . '/' . $ledger['right_code'] . " - " . $ledger["name"]; ?>
                                                                    </option>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <h4> Print Settings </h4>
                                            <hr>
                                            <div class="row clearfix">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="print_method">Choose your preferred print method
                                                            after booking:</label>
                                                        <div>
                                                            <input type="radio" name="settings[6][print_method]"
                                                                id="6print_imin" value="imin" <?php echo (isset($settings[6]['print_method']) && $settings[6]['print_method'] == 'imin') ? 'checked="checked"' : ''; ?>>
                                                            <label for="6print_imin">Print Imin</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="settings[6][print_method]"
                                                                id="6print_a4" value="a4" <?php echo (isset($settings[6]['print_method']) && $settings[6]['print_method'] == 'a4') ? 'checked="checked"' : ''; ?>>
                                                            <label for="6print_a4">Print A4</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="settings[6][print_method]"
                                                                id="6print_a5" value="a5" <?php echo (isset($settings[6]['print_method']) && $settings[6]['print_method'] == 'a5') ? 'checked="checked"' : ''; ?>>
                                                            <label for="6print_a5">Print A5</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row clearfix">
                                                <div class="col-sm-12" align="center">
                                                    <button type="submit"
                                                        class="btn btn-success btn-lg waves-effect">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="daily_closing">
                                <b>Settings Content</b>
                                <p>
                                    Lorem ipsum dolor sit amet, ut duo atqui exerci dicunt, ius impedit mediocritatem
                                    an. Pri ut tation electram moderatius.
                                    Per te suavitate democritum. Duis nemore probatus ne quo, ad liber essent aliquid
                                    pro. Et eos nusquam accumsan, vide mentitum fabellas ne est, eu munere gubergren
                                    sadipscing mel.
                                </p>
                            </div>
                            
                            <div id="sales_invoice" role="tabpanel" class="tab-pane fade">
                                <b>Sales Ledger</b>
                                
                                <form action="<?php echo base_url(); ?>/settings/save_settings" method="POST">
                                     <div class="row clearfix">
                                        <div class="col-sm-3" align="center">
                                            <div>Discount Ledger</div>
                                        </div>
                                        <div class="col-sm-3" align="center">
                                            <select data-live-search="true" id="sales_discount_ledger" name="settings[8][sales_default_discount_ledger]">
                                                <option
                                                
                                                value="">Select Ledger</option>
                                                <?php foreach($sales_ledgers as $iter){?>
                                                <option <?php echo (
                                                    isset($settings[8]['sales_default_discount_ledger']) &&
                                                ($settings[8]['sales_default_discount_ledger'] ==$iter["id"])
                                                ?"selected":"");
                                                 ?> value="<?php echo $iter["id"]; ?>"><?php echo $iter["name"]; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        </div>
                                        <div class="row clearfix">
                                        <div class="col-sm-3" align="center">
                                            <div>Sales Default Ledger</div>
                                        </div>
                                        <div class="col-sm-3" align="center">
                                            <select data-live-search="true" id="sales_ledger" name="settings[8][sales_default_ledger]">
                                                <option value="">Select Ledger</option>
                                                <?php foreach($sales_ledgers as $iter){?>
                                                <option <?php echo (
                                                    isset($settings[8]['sales_default_ledger']) &&
                                                ($settings[8]['sales_default_ledger'] ==$iter["id"])
                                                ?"selected":"");
                                                 ?> value="<?php echo $iter["id"]; ?>"><?php echo $iter["name"]; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row clearfix">
                                        <div class="col-sm-3" align="center">
                                            <div>Sales Default Tax Ledger</div>
                                        </div>
                                        <div class="col-sm-3" align="center">
                                            <select data-live-search="true" id="sales_tax_ledger" name="settings[8][sales_default_tax_ledger]">
                                                <option value="">Select Tax Ledger</option>
                                                <?php foreach($sales_ledgers as $iter){?>
                                                <option <?php echo (
                                                    isset($settings[8]['sales_default_tax_ledger']) &&
                                                ($settings[8]['sales_default_tax_ledger'] ==$iter["id"])
                                                ?"selected":"");
                                                 ?> value="<?php echo $iter["id"]; ?>"><?php echo $iter["name"]; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row clearfix">
                                                <div class="col-sm-12" align="center">
                                                    <button type="submit"
                                                        class="btn btn-success btn-lg waves-effect">Save</button>
                                        </div>
                                    </div>
                                    
                                </form>
                            </div>
                            
                            <div role="tabpanel" class="tab-pane fade" id="purchase_invoice">
                                <b>Purchase Ledger<?php echo $settings[9]['purchase_default_discount_ledger']; ?></b>
                                <form action="<?php echo base_url(); ?>/settings/save_settings" method="POST">
                                    <div class="row clearfix">
                                        <div class="col-sm-3" align="center">
                                            <div>Discount Ledger</div>
                                        </div>
                                        <div class="col-sm-3" align="center">
                                            <select data-live-search="true" id="purchase_discount_ledger" name="settings[9][purchase_default_discount_ledger]"
                                            
                                            >
                                                <option value="">Select Ledger</option>
                                                <?php foreach($purchase_ledgers as $iter){?>
                                                <option <?php echo (
                                                    isset($settings[9]['purchase_default_discount_ledger']) &&
                                                ($settings[9]['purchase_default_discount_ledger'] ==$iter["id"])
                                                ?"selected":"");
                                                 ?> value="<?php echo $iter["id"]; ?>"><?php echo $iter["name"]; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row clearfix">
                                        <div class="col-sm-3" align="center">
                                            <div>Purchase Default Ledger</div>
                                        </div>
                                        <div class="col-sm-3" align="center">
                                            <select data-live-search="true" id="purchase_ledger" name="settings[9][purchase_default_ledger]"
                                            
                                            >
                                                <option value="">Select Ledger</option>
                                                <?php foreach($purchase_ledgers as $iter){?>
                                                <option <?php echo (
                                                    isset($settings[9]['purchase_default_ledger']) &&
                                                ($settings[9]['purchase_default_ledger'] ==$iter["id"])
                                                ?"selected":"");
                                                 ?> value="<?php echo $iter["id"]; ?>"><?php echo $iter["name"]; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row clearfix">
                                        <div class="col-sm-3" align="center">
                                            <div>Purchase Default Tax Ledger</div>
                                        </div>
                                        <div class="col-sm-3" align="center">
                                            <select data-live-search="true" id="sales_tax_ledger" name="settings[9][purchase_default_tax_ledger]">
                                                <option value="">Select Tax Ledger</option>
                                                <?php foreach($sales_ledgers as $iter){?>
                                                <option <?php echo (
                                                    isset($settings[9]['purchase_default_tax_ledger']) &&
                                                ($settings[9]['purchase_default_tax_ledger'] ==$iter["id"])
                                                ?"selected":"");
                                                 ?> value="<?php echo $iter["id"]; ?>"><?php echo $iter["name"]; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row clearfix">
                                                <div class="col-sm-12" align="center">
                                                    <button type="submit"
                                                        class="btn btn-success btn-lg waves-effect">Save</button>
                                        </div>
                                    </div>
                                    
                                </form>
                            </div>
                            
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        const checkboxes = $('input[type="checkbox"][name="settings[1][enable_print]"], input[type="checkbox"][name="settings[1][enable_sep_print]"], input[type="checkbox"][name="settings[1][no_print]"]');

        checkboxes.on('change', function() {
            if (checkboxes.filter(':checked').length === 0) {
                alert('At least one option must be selected.');
                $(this).prop('checked', true);
            }
        });
    });

    $(document).ready(function() {
        $('#archanai_discount').on('change', function() {
            if ($(this).is(":checked")) $('.archanai_discount').show();
            else $('.archanai_discount').hide();
        });
        $('#hall_discount').on('change', function() {
            if ($(this).is(":checked")) $('.hall_discount').show();
            else $('.hall_discount').hide();
        });
        $('#ubayam_discount').on('change', function() {
            if ($(this).is(":checked")) $('.ubayam_discount').show();
            else $('.ubayam_discount').hide();
        });
        $('#prasadam_discount').on('change', function() {
            if ($(this).is(":checked")) $('.prasadam_discount').show();
            else $('.prasadam_discount').hide();
        });
        $('#annathanam_discount').on('change', function() {
            if ($(this).is(":checked")) $('.annathanam_discount').show();
            else $('.annathanam_discount').hide();
        });
        $('#archanai_discount').trigger("change");
        $('#hall_discount').trigger("change");
        $('#ubayam_discount').trigger("change");
        $('#prasadam_discount').trigger("change");
        $('#annathanam_discount').trigger("change");
        $('input[name="settings[1][kazhanji_option]"]').change(function() {
            var selectedValue = $(this).val();
            console.log('Selected option:', selectedValue);
            if (selectedValue == 'text') {
                $('.kazhanji_option_sec .kazhanji_option_text').show();
                $('.kazhanji_option_sec .kazhanji_option_image').hide();
            } else {
                $('.kazhanji_option_sec .kazhanji_option_text').hide();
                $('.kazhanji_option_sec .kazhanji_option_image').show();
            }
        });

    });
</script>