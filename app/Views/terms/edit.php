<?php global $lang;?>
<style>
    .content {
        max-width: 1500px;
        padding: 0 2rem;
    }
</style>
<section class="content">
    <div class="container-fluid">
			<?php if($_SESSION['succ'] != '') { ?>
                <div class="row" style="padding: 0 30% 2% 30%;" id="content_alert">
                    <div class="suc-alert">
                        <span class="suc-closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                        <p><?php echo $_SESSION['succ']; ?></p> 
                    </div>
                </div>
            <?php } ?>
            <?php if($_SESSION['fail'] != '') { ?>
                <div class="row" style="padding: 0 30% 2% 30%;" id="content_alert">
                    <div class="alert">
                        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                        <p><?php echo $_SESSION['fail']; ?></p>
                    </div>
                </div>
            <?php } ?>
            <div class="block-header">
            <h2><?php echo $lang->profile; ?><small><?php echo $lang->terms; ?> / <a href="#" target="_blank"><?php echo $lang->terms; ?> <?php echo $lang->setting; ?></a></small></h2>
        </div>
		
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row"><div class="col-md-8"><h2><?php echo $lang->terms; ?> <?php echo $lang->and; ?> <?php echo $lang->conditions; ?></h2></div>
                        <div class="col-md-4" align="right"></div></div>
                    </div>

                    <div class="body">
                        <ul class="nav nav-tabs tab-nav-right" role="tablist" style="flex-direction: row;">
                            <li role="presentation" class="active"><a href="#ubayam" data-toggle="tab" aria-expanded="false">UBAYAM</a></li>
                            <li role="presentation" class=""><a href="#hall_booking" data-toggle="tab" aria-expanded="false">HALL BOOKING</a></li>
                            <li role="presentation" class=""><a href="#annathanam" data-toggle="tab" aria-expanded="false">ANNATHANAM</a></li>
                            <li role="presentation" class=""><a href="#outdoor_service" data-toggle="tab" aria-expanded="false">OUTDOOR SERVICES</a></li>
                            <li role="presentation" class=""><a href="#catering" data-toggle="tab" aria-expanded="false">CATERING</a></li>
                        </ul>

                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active" id="ubayam">
                                <form action="<?php echo base_url(); ?>/terms/save" method="POST" enctype="multipart/form-data" id="termsForm">
                                    <div class="body">
                                        <div class="container-fluid">
                                            <div class="row clearfix">
                                                <div class="col-sm-12" style="margin: 0px;">
                                                    <h3 style="margin: 0px 0 10px 0;font-weight: 500;"><?php echo $lang->ubayam; ?></h3>
                                                </div>
                                                <div id="editor-container">
                                                    <?php if ($term['ubayam']){ ?>
                                                    <?php foreach ($term['ubayam'] as $index => $ubayam) { ?>
                                                        <div class="col-sm-12 editor-wrapper" style="margin: 0px; position: relative;">
                                                            <div class="form-group form-float">
                                                                <div class="form-line">
                                                                    <div class="ubayam_editor"><?php echo $ubayam;?></div>
                                                                </div>
                                                            </div>
                                                            <button type="button" class="removeEditor btn btn-danger" style="position: absolute; top: 0; right: 0;">X</button>
                                                        </div>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-12" align="center">
                                                    <button type="button" id="addEditor" class="btn btn-primary btn-lg waves-effect">+</button>
                                                </div>
                                                <input type="hidden" name="ubayam_editor_json" id="ubayam_editor_json">

                                                <div class="col-sm-12" align="center">
                                                    <button type="submit" class="btn btn-success btn-lg waves-effect"><?php echo $lang->update; ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div role="tabpanel" class="tab-pane fade" id="hall_booking">
                                <form action="<?php echo base_url(); ?>/terms/save_hall" method="POST" enctype="multipart/form-data" id="hallForm">
                                    <div class="body">
                                        <div class="container-fluid">
                                            <div class="row clearfix">
                                                <div class="col-sm-12" style="margin: 0px;">
                                                    <h3 style="margin: 0px 0 10px 0;font-weight: 500;">Hall Booking</h3>
                                                </div>
                                                <?php foreach ($packages['hall'] as $package) { ?>
                                                    <div class="col-sm-12 package-container" style="margin: 0px;">
                                                        <h4><?php echo $package['name']; ?></h4>
                                                        <div id="textarea-container-hall-<?php echo $package['id']; ?>" class="textarea-container">
                                                            <?php if (isset($term['hall'][$package['id']])) {
                                                                foreach ($term['hall'][$package['id']] as $index => $hall) { ?>
                                                                    <div class="col-sm-12 textarea-wrapper" style="margin: 0px; position: relative;">
                                                                        <div class="form-group form-float">
                                                                            <div class="form-line">
                                                                                <textarea name="hall_editor[<?php echo $package['id']; ?>][]" class="hall_editor" cols="150" rows="5" required><?php echo $hall;?></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <button type="button" class="removeTextareahall btn btn-danger" style="position: absolute; top: 0; right: 0;">X</button>
                                                                    </div>
                                                                <?php }
                                                            } ?>
                                                        </div>
                                                        <div class="col-sm-12" align="center">
                                                            <button type="button" class="addTextareahall btn btn-primary btn-lg waves-effect" data-package-id="<?php echo $package['id']; ?>">+</button>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <input type="hidden" name="hall_editor_json" id="hall_editor_json">
                                                <div class="col-sm-12" align="center">
                                                    <button type="submit" class="btn btn-success btn-lg waves-effect">Update</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div role="tabpanel" class="tab-pane fade" id="annathanam">
                                <form action="<?php echo base_url(); ?>/terms/save_annathanam" method="POST" enctype="multipart/form-data" id="annathanamForm">
                                    <div class="body">
                                        <div class="container-fluid">
                                            <div class="row clearfix">
                                                <div class="col-sm-12" style="margin: 0px;">
                                                    <h3 style="margin: 0px 0 10px 0;font-weight: 500;">Annathanam</h3>
                                                </div>
                                                <div id="annathanam_editor-container">
                                                    <?php if ($term['annathanam']){ ?>
                                                    <?php foreach ($term['annathanam'] as $index => $annathanam) { ?>
                                                        <div class="col-sm-12 annathanam-editor-wrapper" style="margin: 0px; position: relative;">
                                                            <div class="form-group form-float">
                                                                <div class="form-line">
                                                                    <div class="annathanam_editor"><?php echo $annathanam;?></div>
                                                                </div>
                                                            </div>
                                                            <button type="button" class="removeEditorannathanam btn btn-danger" style="position: absolute; top: 0; right: 0;">X</button>
                                                        </div>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-12" align="center">
                                                    <button type="button" id="annathanam_addEditor" class="btn btn-primary btn-lg waves-effect">+</button>
                                                </div>
                                                <input type="hidden" name="annathanam_editor_json" id="annathanam_editor_json">

                                                <div class="col-sm-12" align="center">
                                                    <button type="submit" class="btn btn-success btn-lg waves-effect"><?php echo $lang->update; ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div role="tabpanel" class="tab-pane fade" id="outdoor_service">
                                <form action="<?php echo base_url(); ?>/terms/save_outdoor" method="POST" enctype="multipart/form-data" id="outdoorForm">
                                    <div class="body">
                                        <div class="container-fluid">
                                            <div class="row clearfix">
                                                <div class="col-sm-12" style="margin: 0px;">
                                                    <h3 style="margin: 0px 0 10px 0;font-weight: 500;">OUTDOOR SERVICES</h3>
                                                </div>
                                                <?php foreach ($packages['outdoor'] as $package) { ?>
                                                    <div class="col-sm-12 package-container" style="margin: 0px;">
                                                        <h4><?php echo $package['name']; ?></h4>
                                                        <div id="textarea-container-outdoor-<?php echo $package['id']; ?>" class="textarea-container">
                                                            <?php if (isset($term['outdoor'][$package['id']])) {
                                                                foreach ($term['outdoor'][$package['id']] as $index => $outdoor) { ?>
                                                                    <div class="col-sm-12 textarea-wrapper-outdoor" style="margin: 0px; position: relative;">
                                                                        <div class="form-group form-float">
                                                                            <div class="form-line">
                                                                                <textarea name="outdoor_editor[<?php echo $package['id']; ?>][]" class="outdoor_editor" cols="150" rows="5" required><?php echo $outdoor;?></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <button type="button" class="removeTextareaoutdoor btn btn-danger" style="position: absolute; top: 0; right: 0;">X</button>
                                                                    </div>
                                                                <?php }
                                                            } ?>
                                                        </div>
                                                        <div class="col-sm-12" align="center">
                                                            <button type="button" class="addTextareaoutdoor btn btn-primary btn-lg waves-effect" data-package-id="<?php echo $package['id']; ?>">+</button>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <input type="hidden" name="outdoor_editor_json" id="outdoor_editor_json">
                                                <div class="col-sm-12" align="center">
                                                    <button type="submit" class="btn btn-success btn-lg waves-effect">Update</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div role="tabpanel" class="tab-pane fade" id="catering">
                                <form action="<?php echo base_url(); ?>/terms/save_catering" method="POST" enctype="multipart/form-data" id="cateringForm">
                                    <div class="body">
                                        <div class="container-fluid">
                                            <div class="row clearfix">
                                                <div class="col-sm-12" style="margin: 0px;">
                                                    <h3 style="margin: 0px 0 10px 0;font-weight: 500;">Catering</h3>
                                                </div>
                                                <div id="editor-container">
                                                    <?php if ($term['catering']){ ?>
                                                    <?php foreach ($term['catering'] as $index => $catering) { ?>
                                                        <div class="col-sm-12 editor-wrapper" style="margin: 0px; position: relative;">
                                                            <div class="form-group form-float">
                                                                <div class="form-line">
                                                                    <div class="catering_editor"><?php echo $catering;?></div>
                                                                </div>
                                                            </div>
                                                            <button type="button" class="removeEditor btn btn-danger" style="position: absolute; top: 0; right: 0;">X</button>
                                                        </div>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-12" align="center">
                                                    <button type="button" id="addEditor" class="btn btn-primary btn-lg waves-effect">+</button>
                                                </div>
                                                <input type="hidden" name="catering_editor_json" id="catering_editor_json">

                                                <div class="col-sm-12" align="center">
                                                    <button type="submit" class="btn btn-success btn-lg waves-effect"><?php echo $lang->update; ?></button>
                                                </div>
                                            </div>
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

<script src="https://cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var editors = {};

    function initEditor(element) {
        return CKEDITOR.replace(element, {
            skin: 'moono',
            enterMode: CKEDITOR.ENTER_BR,
            shiftEnterMode: CKEDITOR.ENTER_P,
            toolbar: [
                { name: 'basicstyles', groups: [ 'basicstyles' ], items: [ 'Bold', 'Italic', 'Underline', "-", 'TextColor', 'BGColor' ] },
                { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
                { name: 'scripts', items: [ 'Subscript', 'Superscript' ] },
                { name: 'justify', groups: [ 'blocks', 'align' ], items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                { name: 'paragraph', groups: [ 'list', 'indent' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
                { name: 'spell', items: [ 'jQuerySpellChecker' ] },
                { name: 'table', items: [ 'Table' ] }
            ]
        });
    }

    // Initialize existing editors
    document.querySelectorAll('.ubayam_editor').forEach(function(element, index) {
        editors[index] = initEditor(element);
    });

    document.getElementById('addEditor').addEventListener('click', function() {
        var container = document.getElementById('editor-container');
        var newEditor = document.createElement('div');
        newEditor.className = 'col-sm-12 editor-wrapper';
        newEditor.style = 'margin: 0px; position: relative;';
        newEditor.innerHTML = `
            <div class="form-group form-float">
                <div class="form-line">
                    <div class="ubayam_editor"></div>
                </div>
            </div>
            <button type="button" class="removeEditor btn btn-danger" style="position: absolute; top: 0; right: 0;">X</button>
        `;
        container.appendChild(newEditor);

        var editorElement = newEditor.querySelector('.ubayam_editor');
        var newIndex = Object.keys(editors).length;
        editors[newIndex] = initEditor(editorElement);
    });

    document.getElementById('editor-container').addEventListener('click', function(e) {
        if (e.target && e.target.className.includes('removeEditor')) {
            var wrapper = e.target.closest('.editor-wrapper');
            var index = Array.from(wrapper.parentNode.children).indexOf(wrapper);
            if (editors[index]) {
                editors[index].destroy();
                delete editors[index];
            }
            wrapper.remove();
        }
    });

    $('#termsForm').submit(function(e) {
        e.preventDefault(); 

        var editorValues = Object.values(editors).map(function(editor) {
            return editor.getData();
        });

        var jsonData = JSON.stringify(editorValues);

        $('#ubayam_editor_json').val(jsonData);

        this.submit();
    });
});
</script>

<script>
$(document).ready(function() {
    var editorIdCounter = 1; // Unique ID counter for CKEditor instances

    function initCKEditor(textarea) {
        var editorId = 'editor-' + editorIdCounter++;
        $(textarea).attr('id', editorId); // Assign a unique ID to the textarea
        CKEDITOR.replace(editorId, {
            skin: 'moono',
            enterMode: CKEDITOR.ENTER_BR,
            shiftEnterMode: CKEDITOR.ENTER_P,
            toolbar: [
                { name: 'basicstyles', groups: [ 'basicstyles' ], items: [ 'Bold', 'Italic', 'Underline', "-", 'TextColor', 'BGColor' ] },
                { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
                { name: 'scripts', items: [ 'Subscript', 'Superscript' ] },
                { name: 'justify', groups: [ 'blocks', 'align' ], items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                { name: 'paragraph', groups: [ 'list', 'indent' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
                { name: 'spell', items: [ 'jQuerySpellChecker' ] },
                { name: 'table', items: [ 'Table' ] }
            ]
        });
    }

    // Initialize CKEditor for existing textareas
    $('textarea.hall_editor').each(function() {
        initCKEditor(this);
    });

    $('.addTextareahall').click(function() {
        var packageId = $(this).data('package-id');
        var html = '<div class="col-sm-12 textarea-wrapper" style="margin: 0px; position: relative;">' +
            '<div class="form-group form-float">' +
            '<div class="form-line">' +
            '<textarea name="hall_editor[' + packageId + '][]" class="hall_editor" cols="150" rows="5" required></textarea>' +
            '</div>' +
            '</div>' +
            '<button type="button" class="removeTextareahall btn btn-danger" style="position: absolute; top: 0; right: 0;">X</button>' +
            '</div>';
        var $newTextarea = $(html).appendTo('#textarea-container-hall-' + packageId).find('textarea');
        initCKEditor($newTextarea[0]);
    });

    $(document).on('click', '.removeTextareahall', function() {
        var $wrapper = $(this).closest('.textarea-wrapper');
        var editorId = $wrapper.find('textarea').attr('id');
        if (CKEDITOR.instances[editorId]) {
            CKEDITOR.instances[editorId].destroy(true);
        }
        $wrapper.remove();
    });

    $('#hallForm').submit(function(event) {
        event.preventDefault();
        var hallEditorArray = {};

        // Ensure all CKEditor instances are updated
        for (var instanceName in CKEDITOR.instances) {
            CKEDITOR.instances[instanceName].updateElement();
        }

        $('.textarea-container').each(function() {
            var packageId = $(this).attr('id').replace('textarea-container-hall-', '');
            hallEditorArray[packageId] = [];
            $(this).find('textarea.hall_editor').each(function() {
                hallEditorArray[packageId].push($(this).val());
            });
        });

        var hallEditorJson = JSON.stringify(hallEditorArray);

        try {
            JSON.parse(hallEditorJson); // Validate JSON
            console.log("Generated JSON: ", hallEditorJson);
            $('#hall_editor_json').val(hallEditorJson);
            this.submit();
        } catch (e) {
            console.error("Invalid JSON: ", e);
            alert("There was an error generating the JSON. Please try again.");
        }
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var editors = {};

    function initEditor(element) {
        return CKEDITOR.replace(element, {
            skin: 'moono',
            enterMode: CKEDITOR.ENTER_BR,
            shiftEnterMode: CKEDITOR.ENTER_P,
            toolbar: [
                { name: 'basicstyles', groups: [ 'basicstyles' ], items: [ 'Bold', 'Italic', 'Underline', "-", 'TextColor', 'BGColor' ] },
                { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
                { name: 'scripts', items: [ 'Subscript', 'Superscript' ] },
                { name: 'justify', groups: [ 'blocks', 'align' ], items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                { name: 'paragraph', groups: [ 'list', 'indent' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
                { name: 'spell', items: [ 'jQuerySpellChecker' ] },
                { name: 'table', items: [ 'Table' ] }
            ]
        });
    }

    // Initialize existing editors
    document.querySelectorAll('.annathanam_editor').forEach(function(element, index) {
        editors[index] = initEditor(element);
    });

    document.getElementById('annathanam_addEditor').addEventListener('click', function() {
        var container = document.getElementById('annathanam_editor-container');
        var newEditor = document.createElement('div');
        newEditor.className = 'col-sm-12 annathanam-editor-wrapper';
        newEditor.style = 'margin: 0px; position: relative;';
        newEditor.innerHTML = `
            <div class="form-group form-float">
                <div class="form-line">
                    <div class="annathanam_editor"></div>
                </div>
            </div>
            <button type="button" class="removeEditorannathanam btn btn-danger" style="position: absolute; top: 0; right: 0;">X</button>
        `;
        container.appendChild(newEditor);

        var editorElement = newEditor.querySelector('.annathanam_editor');
        var newIndex = Object.keys(editors).length;
        editors[newIndex] = initEditor(editorElement);
    });

    document.getElementById('annathanam_editor-container').addEventListener('click', function(e) {
        if (e.target && e.target.className.includes('removeEditorannathanam')) {
            var wrapper = e.target.closest('.annathanam-editor-wrapper');
            var index = Array.from(wrapper.parentNode.children).indexOf(wrapper);
            if (editors[index]) {
                editors[index].destroy();
                delete editors[index];
            }
            wrapper.remove();
        }
    });

    $('#annathanamForm').submit(function(e) {
        e.preventDefault(); 

        var editorValues = Object.values(editors).map(function(editor) {
            return editor.getData();
        });

        var jsonData = JSON.stringify(editorValues);

        $('#annathanam_editor_json').val(jsonData);

        this.submit();
    });
});
</script>

<script>
$(document).ready(function() {
    var editorIdCounter = 10; // Unique ID counter for CKEditor instances

    function initCKEditor(textarea) {
        var editorId = 'editor-' + editorIdCounter++;
        $(textarea).attr('id', editorId); // Assign a unique ID to the textarea
        CKEDITOR.replace(editorId, {
            skin: 'moono',
            enterMode: CKEDITOR.ENTER_BR,
            shiftEnterMode: CKEDITOR.ENTER_P,
            toolbar: [
                { name: 'basicstyles', groups: [ 'basicstyles' ], items: [ 'Bold', 'Italic', 'Underline', "-", 'TextColor', 'BGColor' ] },
                { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
                { name: 'scripts', items: [ 'Subscript', 'Superscript' ] },
                { name: 'justify', groups: [ 'blocks', 'align' ], items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                { name: 'paragraph', groups: [ 'list', 'indent' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
                { name: 'spell', items: [ 'jQuerySpellChecker' ] },
                { name: 'table', items: [ 'Table' ] }
            ]
        });
    }

    // Initialize CKEditor for existing textareas
    $('textarea.outdoor_editor').each(function() {
        initCKEditor(this);
    });

    $('.addTextareaoutdoor').click(function() {
        var packageId = $(this).data('package-id');
        var html = '<div class="col-sm-12 textarea-wrapper-outdoor" style="margin: 0px; position: relative;">' +
            '<div class="form-group form-float">' +
            '<div class="form-line">' +
            '<textarea name="outdoor_editor[' + packageId + '][]" class="outdoor_editor" cols="150" rows="5" required></textarea>' +
            '</div>' +
            '</div>' +
            '<button type="button" class="removeTextareaoutdoor btn btn-danger" style="position: absolute; top: 0; right: 0;">X</button>' +
            '</div>';
        var $newTextarea = $(html).appendTo('#textarea-container-outdoor-' + packageId).find('textarea');
        initCKEditor($newTextarea[0]);
    });

    $(document).on('click', '.removeTextareaoutdoor', function() {
        var $wrapper = $(this).closest('.textarea-wrapper-outdoor');
        var editorId = $wrapper.find('textarea').attr('id');
        if (CKEDITOR.instances[editorId]) {
            CKEDITOR.instances[editorId].destroy(true);
        }
        $wrapper.remove();
    });

    $('#outdoorForm').submit(function(event) {
        event.preventDefault();
        var outdoorEditorArray = {};

        // Ensure all CKEditor instances are updated
        for (var instanceName in CKEDITOR.instances) {
            CKEDITOR.instances[instanceName].updateElement();
        }

        $('.textarea-container').each(function() {
            var packageId = $(this).attr('id').replace('textarea-container-outdoor-', '');
            outdoorEditorArray[packageId] = [];
            $(this).find('textarea.outdoor_editor').each(function() {
                outdoorEditorArray[packageId].push($(this).val());
            });
        });

        var outdoorEditorJson = JSON.stringify(outdoorEditorArray);

        try {
            JSON.parse(outdoorEditorJson); // Validate JSON
            console.log("Generated JSON: ", outdoorEditorJson);
            $('#outdoor_editor_json').val(outdoorEditorJson);
            this.submit();
        } catch (e) {
            console.error("Invalid JSON: ", e);
            alert("There was an error generating the JSON. Please try again.");
        }
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var editors = {};

    function initEditor(element) {
        return CKEDITOR.replace(element, {
            skin: 'moono',
            enterMode: CKEDITOR.ENTER_BR,
            shiftEnterMode: CKEDITOR.ENTER_P,
            toolbar: [
                { name: 'basicstyles', groups: [ 'basicstyles' ], items: [ 'Bold', 'Italic', 'Underline', "-", 'TextColor', 'BGColor' ] },
                { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
                { name: 'scripts', items: [ 'Subscript', 'Superscript' ] },
                { name: 'justify', groups: [ 'blocks', 'align' ], items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                { name: 'paragraph', groups: [ 'list', 'indent' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
                { name: 'spell', items: [ 'jQuerySpellChecker' ] },
                { name: 'table', items: [ 'Table' ] }
            ]
        });
    }

    // Initialize existing editors
    document.querySelectorAll('.catering_editor').forEach(function(element, index) {
        editors[index] = initEditor(element);
    });

    document.getElementById('addEditor').addEventListener('click', function() {
        var container = document.getElementById('editor-container');
        var newEditor = document.createElement('div');
        newEditor.className = 'col-sm-12 editor-wrapper';
        newEditor.style = 'margin: 0px; position: relative;';
        newEditor.innerHTML = `
            <div class="form-group form-float">
                <div class="form-line">
                    <div class="catering_editor"></div>
                </div>
            </div>
            <button type="button" class="removeEditor btn btn-danger" style="position: absolute; top: 0; right: 0;">X</button>
        `;
        container.appendChild(newEditor);

        var editorElement = newEditor.querySelector('.catering_editor');
        var newIndex = Object.keys(editors).length;
        editors[newIndex] = initEditor(editorElement);
    });

    document.getElementById('editor-container').addEventListener('click', function(e) {
        if (e.target && e.target.className.includes('removeEditor')) {
            var wrapper = e.target.closest('.editor-wrapper');
            var index = Array.from(wrapper.parentNode.children).indexOf(wrapper);
            if (editors[index]) {
                editors[index].destroy();
                delete editors[index];
            }
            wrapper.remove();
        }
    });

    $('#cateringForm').submit(function(e) {
        e.preventDefault(); 

        var editorValues = Object.values(editors).map(function(editor) {
            return editor.getData();
        });

        var jsonData = JSON.stringify(editorValues);

        $('#catering_editor_json').val(jsonData);

        this.submit();
    });
});
</script>

<script>
CKEDITOR.replace('hall_editor', {
  skin: 'moono',
  enterMode: CKEDITOR.ENTER_BR,
  shiftEnterMode:CKEDITOR.ENTER_P,
  toolbar: [{ name: 'basicstyles', groups: [ 'basicstyles' ], items: [ 'Bold', 'Italic', 'Underline', "-", 'TextColor', 'BGColor' ] },
             { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
             { name: 'scripts', items: [ 'Subscript', 'Superscript' ] },
             { name: 'justify', groups: [ 'blocks', 'align' ], items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
             { name: 'paragraph', groups: [ 'list', 'indent' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
            /* { name: 'links', items: [ 'Link', 'Unlink' ] },
             { name: 'insert', items: [ 'Image'] },*/
             { name: 'spell', items: [ 'jQuerySpellChecker' ] },
             { name: 'table', items: [ 'Table' ] }
             ],
});
CKEDITOR.replace('cash_donation_editor', {
  skin: 'moono',
  enterMode: CKEDITOR.ENTER_BR,
  shiftEnterMode:CKEDITOR.ENTER_P,
  toolbar: [{ name: 'basicstyles', groups: [ 'basicstyles' ], items: [ 'Bold', 'Italic', 'Underline', "-", 'TextColor', 'BGColor' ] },
             { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
             { name: 'scripts', items: [ 'Subscript', 'Superscript' ] },
             { name: 'justify', groups: [ 'blocks', 'align' ], items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
             { name: 'paragraph', groups: [ 'list', 'indent' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
             { name: 'spell', items: [ 'jQuerySpellChecker' ] },
             { name: 'table', items: [ 'Table' ] }
             ],
});
CKEDITOR.replace('product_donation_editor', {
  skin: 'moono',
  enterMode: CKEDITOR.ENTER_BR,
  shiftEnterMode:CKEDITOR.ENTER_P,
  toolbar: [{ name: 'basicstyles', groups: [ 'basicstyles' ], items: [ 'Bold', 'Italic', 'Underline', "-", 'TextColor', 'BGColor' ] },
             { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
             { name: 'scripts', items: [ 'Subscript', 'Superscript' ] },
             { name: 'justify', groups: [ 'blocks', 'align' ], items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
             { name: 'paragraph', groups: [ 'list', 'indent' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
             { name: 'spell', items: [ 'jQuerySpellChecker' ] },
             { name: 'table', items: [ 'Table' ] }
             ],
});
CKEDITOR.replace('ubayam_editor', {
  skin: 'moono',
  enterMode: CKEDITOR.ENTER_BR,
  shiftEnterMode:CKEDITOR.ENTER_P,
  toolbar: [{ name: 'basicstyles', groups: [ 'basicstyles' ], items: [ 'Bold', 'Italic', 'Underline', "-", 'TextColor', 'BGColor' ] },
             { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
             { name: 'scripts', items: [ 'Subscript', 'Superscript' ] },
             { name: 'justify', groups: [ 'blocks', 'align' ], items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
             { name: 'paragraph', groups: [ 'list', 'indent' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
             { name: 'spell', items: [ 'jQuerySpellChecker' ] },
             { name: 'table', items: [ 'Table' ] }
             ],
});
CKEDITOR.replace('annathanam_editor', {
  skin: 'moono',
  enterMode: CKEDITOR.ENTER_BR,
  shiftEnterMode:CKEDITOR.ENTER_P,
  toolbar: [{ name: 'basicstyles', groups: [ 'basicstyles' ], items: [ 'Bold', 'Italic', 'Underline', "-", 'TextColor', 'BGColor' ] },
             { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
             { name: 'scripts', items: [ 'Subscript', 'Superscript' ] },
             { name: 'justify', groups: [ 'blocks', 'align' ], items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
             { name: 'paragraph', groups: [ 'list', 'indent' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
             { name: 'spell', items: [ 'jQuerySpellChecker' ] },
             { name: 'table', items: [ 'Table' ] }
             ],
});
CKEDITOR.replace('outdoor_editor', {
  skin: 'moono',
  enterMode: CKEDITOR.ENTER_BR,
  shiftEnterMode:CKEDITOR.ENTER_P,
  toolbar: [{ name: 'basicstyles', groups: [ 'basicstyles' ], items: [ 'Bold', 'Italic', 'Underline', "-", 'TextColor', 'BGColor' ] },
             { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
             { name: 'scripts', items: [ 'Subscript', 'Superscript' ] },
             { name: 'justify', groups: [ 'blocks', 'align' ], items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
             { name: 'paragraph', groups: [ 'list', 'indent' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
            /* { name: 'links', items: [ 'Link', 'Unlink' ] },
             { name: 'insert', items: [ 'Image'] },*/
             { name: 'spell', items: [ 'jQuerySpellChecker' ] },
             { name: 'table', items: [ 'Table' ] }
             ],
});
CKEDITOR.replace('catering_editor', {
  skin: 'moono',
  enterMode: CKEDITOR.ENTER_BR,
  shiftEnterMode:CKEDITOR.ENTER_P,
  toolbar: [{ name: 'basicstyles', groups: [ 'basicstyles' ], items: [ 'Bold', 'Italic', 'Underline', "-", 'TextColor', 'BGColor' ] },
             { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
             { name: 'scripts', items: [ 'Subscript', 'Superscript' ] },
             { name: 'justify', groups: [ 'blocks', 'align' ], items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
             { name: 'paragraph', groups: [ 'list', 'indent' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
             { name: 'spell', items: [ 'jQuerySpellChecker' ] },
             { name: 'table', items: [ 'Table' ] }
             ],
});
CKEDITOR.replace('common_editor', {
  skin: 'moono',
  enterMode: CKEDITOR.ENTER_BR,
  shiftEnterMode:CKEDITOR.ENTER_P,
  toolbar: [{ name: 'basicstyles', groups: [ 'basicstyles' ], items: [ 'Bold', 'Italic', 'Underline', "-", 'TextColor', 'BGColor' ] },
             { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
             { name: 'scripts', items: [ 'Subscript', 'Superscript' ] },
             { name: 'justify', groups: [ 'blocks', 'align' ], items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
             { name: 'paragraph', groups: [ 'list', 'indent' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
             { name: 'spell', items: [ 'jQuerySpellChecker' ] },
             { name: 'table', items: [ 'Table' ] }
             ],
});
</script>