<?php global $lang; ?>
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
                Settings<small>
                    Archanai / <a href="#" target="_blank">
                        <?php echo $lang->setting; ?>
                    </a>
                </small>
            </h2>
        </div>

        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-8">
                                <h2>
                                    <?php echo $lang->archanai; ?>  <?php echo $lang->setting; ?>
                                </h2>
                            </div>
                            <div class="col-md-4" align="right"></div>
                        </div>
                    </div>
                    <form action="<?php echo base_url(); ?>/settings/save_archanai_setting" method="POST" enctype="multipart/form-data">
					<input type="hidden" name="id" value="<?php echo $archanai['id'];?>">
                        <div class="body">
                            <div class="container-fluid">
							<h3> Booking Settings</h3>
								<hr>
                                <div class="row clearfix">
									<div class="col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="">
                                                <input type="checkbox" class="form-control" name="enable_tender" id="enable_tender" <?php echo !empty($archanai['enable_tender']) ? ' checked="checked"' : ''; ?>" value="1">
												<label class="form-label" for="enable_tender">Enable Tender Concept:</label>
                                            </div>
                                        </div>
                                    </div>
								</div>

								<h3> Print Settings </h3>
								<hr>
                                <div class="row clearfix">
									<div class="col-sm-4">
										<h5>Please select your preferred Receipt type:</h5>
										<div class="form-group form-float">
                                            <div class="">
                                                <input type="checkbox" name="enable_print" id="enable_print" value="1"
                                                    <?php echo !empty($archanai['enable_print']) ? 'checked="checked"' : ''; ?>>
                                                <label for="enable_print">Print</label>
                                            </div>
                                            <div class="">
                                                <input type="checkbox" name="enable_sep_print" id="enable_sep_print" value="1"
                                                    <?php echo !empty($archanai['enable_sep_print']) ? 'checked="checked"' : ''; ?>>
                                                <label for="enable_sep_print">Separate Print</label>
                                            </div>
                                            <div class="">
                                                <input type="checkbox" name="no_print" id="no_print" value="1"
                                                    <?php echo !empty($archanai['no_print']) ? 'checked="checked"' : ''; ?>>
                                                <label for="no_print">No Print</label>
                                            </div>
										</div>
									</div>
								</div>
								
                                <div class="row clearfix">
                                    <div class="col-sm-12" align="center">
                                        <button type="submit" class="btn btn-success btn-lg waves-effect">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('input[type="checkbox"][name="enable_print"], input[type="checkbox"][name="enable_sep_print"], input[type="checkbox"][name="no_print"]');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const checkedBoxes = Array.from(checkboxes).filter(cb => cb.checked);

                if (checkedBoxes.length === 0) {
                    alert('At least one option must be selected.');
                    checkbox.checked = true; 
                }
            });
        });
    });
</script>