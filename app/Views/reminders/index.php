<style>
    #loader {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        font-size: 18px;
    }

    .spinner {
        border: 4px solid rgba(255, 255, 255, 0.3);
        border-top: 4px solid #fff;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Success Modal */
    #successModal {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: none;
    }

    #closeModal {
        margin-top: 10px;
        padding: 5px 10px;
        background: #28a745;
        color: white;
        border: none;
        cursor: pointer;
    }
    #closeModal:hover {
        background: #218838;
    }
    .leftnav {
        flex-direction: column; width: 200px; background: #8BC34A; position:absolute;
    }
    .nav-tabs + .tab-content {
        padding: 0;
    }
    .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
        background-color: rgb(0 0 0 / 32%);
    }
    .nav-tabs > li > a:before { border-bottom: 0; }
    .nav-tabs > li {
        position: relative;
        top: 0px;
        left: 0px;
    }
    .nav-tabs > li > a { color: #393737 !important; margin-right:0; }
    .nav-tabs li.active a { color: #000 !important; }
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
                Reminders and Messages
            </h2>
        </div>

        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body">
                        <ul class="nav nav-tabs tab-nav-right leftnav" role="tablist" style="">
                            <li role="presentation" class="active"><a href="#custom_messages" data-toggle="tab" aria-expanded="true">Custom Messages</a></li>
                            <li role="presentation" class=""><a href="#auto_messages" data-toggle="tab" aria-expanded="false">Auto Messages</a></li>
                            <!-- Add more tabs here as needed -->
                        </ul>
                    
                        <div class="tab-content" style="margin-left: 210px;">
                            <!-- Tab contents will be loaded from separate view files -->
                            <div role="tabpanel" class="tab-pane fade in active" id="custom_messages"></div>
                            <div role="tabpanel" class="tab-pane fade" id="auto_messages"></div>
                            <!-- Add more tab panes here as needed -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Common loader and modal -->
    <div id="loader" style="display: none;">
        <div class="spinner"></div>
        <p>Sending Messages...</p>
    </div>

    <div id="alert-successModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" style=" max-width: 500px;">
            <div class="modal-content">
                <div class="modal-body">
                    <p style="text-align:center;">
                        <br><i class="mdi mdi-checkbox-marked-circle-outline" style="font-size:42px; color:green;"></i>
                    </p>
                    <h4 style="text-align:center;" id="spndeddelid1"></h4>
                </div>
                <div class="modal-footer text-center">
                    <button type="button" class="btn btn-info" id="okay" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Common JS/CSS includes -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet" />
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">

<script>
    // Common JavaScript functions can go here
    $(document).ready(function() {
        // Any common initialization code
    });
</script>