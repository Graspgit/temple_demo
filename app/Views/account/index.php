<style>
    .coa-container {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .coa-header {
        padding: 20px;
        border-bottom: 1px solid #e9ecef;
        background: #f8f9fa;
        border-radius: 8px 8px 0 0;
    }
    
    .coa-filters {
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .coa-search {
        flex: 1;
        min-width: 300px;
    }
    
    .coa-content {
        padding: 20px;
        /* max-height: calc(100vh - 300px); */
        overflow-y: auto;
    }
    
    .coa-tree {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .coa-node {
        margin-bottom: 2px;
    }
    
    .coa-node-content {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .coa-node-content:hover {
        background: #f1f3f5;
    }
    
    .coa-node-content.active {
        background: #e7f5ff;
        border-left: 3px solid #339af0;
    }
    
    .coa-toggle {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        transition: transform 0.2s;
    }
    
    .coa-toggle.expanded {
        transform: rotate(90deg);
    }
    
    .coa-toggle.no-children {
        visibility: hidden;
    }
    
    .coa-icon {
        width: 24px;
        height: 24px;
        margin-right: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
    }
    
    .coa-icon.group {
        background: #ffe8cc;
        color: #fd7e14;
    }
    
    .coa-icon.ledger {
        background: #d3f9d8;
        color: #37b24d;
    }
    
    .coa-info {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
    .coa-name {
        flex: 1;
        font-weight: 500;
        color: #212529;
    }
    
    .coa-name a {
        color: inherit;
        text-decoration: none;
    }
    
    .coa-name a:hover {
        color: #339af0;
        text-decoration: underline;
    }
    
    .coa-code {
        font-size: 12px;
        color: #6c757d;
        font-weight: normal;
    }
    
    .coa-type {
        font-size: 12px;
        padding: 2px 8px;
        border-radius: 12px;
        background: #e9ecef;
        color: #495057;
        white-space: nowrap;
    }
    
    .coa-balance {
        min-width: 120px;
        text-align: right;
        font-family: monospace;
        color: #495057;
    }
    
    .coa-balance.negative {
        color: #e03131;
    }
    
    .coa-actions {
        display: flex;
        gap: 5px;
        margin-left: 20px;
    }
    
    .coa-action-btn {
        width: 32px;
        height: 32px;
        border-radius: 4px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .coa-action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .coa-action-btn.edit {
        background: #339af0;
        color: white;
    }
    
    .coa-action-btn.balance {
        background: #37b24d;
        color: white;
    }
    
    .coa-action-btn.delete {
        background: #f03e3e;
        color: white;
    }
    
    .coa-children {
        margin-left: 30px;
        display: none;
    }
    
    .coa-children.expanded {
        display: block;
    }
    
    .coa-loading {
        text-align: center;
        padding: 20px;
        color: #6c757d;
    }
    
    .coa-empty {
        text-align: center;
        padding: 40px;
        color: #6c757d;
    }
    
    .coa-stats {
        display: flex;
        gap: 20px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #e9ecef;
    }
    
    .coa-stat {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .coa-stat-value {
        font-size: 24px;
        font-weight: 600;
        color: #212529;
    }
    
    .coa-stat-label {
        font-size: 12px;
        color: #6c757d;
        text-transform: uppercase;
    }
    
    @media (max-width: 768px) {
        .coa-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
        
        .coa-balance {
            text-align: left;
        }
        
        .coa-actions {
            margin-left: 0;
            margin-top: 10px;
        }
    }
    
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    .bootstrap-select.btn-group .dropdown-menu.inner {
        padding-bottom: 50px;
    }
</style>

<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="coa-container">
                    <div class="coa-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h2 style="margin: 0;">Chart Of Accounts</h2>
                                <?php if(count($check_financial_year) > 0) {
                                    $from_date = $check_financial_year[0]['from_year_month']."-01";
                                    $from_date_re = date("d-m-Y", strtotime($from_date));
                                    $to_date = $check_financial_year[0]['to_year_month']."-31";
                                    $to_date_re = date("d-m-Y", strtotime($to_date));
                                ?>
                                <p style="margin: 5px 0 0 0; font-size: 14px; color: #6c757d;">
                                    Financial Year: <?php echo $from_date_re; ?> to <?php echo $to_date_re; ?>
                                </p>
                                <?php } ?>
                            </div>
                            <div class="col-md-6 text-right">
                                <?php if($add_ledger['create_p'] == 1) { ?>
                                <a href="<?php echo base_url(); ?>/account/add_ledger" class="btn bg-deep-purple waves-effect">
                                    <i class="material-icons" style="vertical-align: middle;">add</i> Add Ledger
                                </a>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <div class="coa-filters" style="margin-top: 20px;">
                            <div class="coa-search">
                                <input type="text" id="coaSearch" class="form-control" placeholder="Search accounts...">
                            </div>
                            <div>
                                <select class="form-control" id="coaTypeFilter">
                                    <option value="">All Types</option>
                                    <option value="group">Groups Only</option>
                                    <option value="ledger">Ledgers Only</option>
                                </select>
                            </div>
                            <div>
                                <button class="btn btn-default" id="expandAll">
                                    <i class="material-icons">unfold_more</i> Expand All
                                </button>
                                <button class="btn btn-default" id="collapseAll">
                                    <i class="material-icons">unfold_less</i> Collapse All
                                </button>
                            </div>
                        </div>
                        
                        <?php if($ac_op_diff != 0) { ?>
                        <div class="alert <?php echo ($ac_op_diff > 0) ? 'alert-warning' : 'alert-info'; ?>" style="margin-top: 15px; margin-bottom: 0;">
                            <i class="material-icons" style="vertical-align: middle;">info</i>
                            Difference in Opening Balance: <?php echo number_format(abs($ac_op_diff), 2); ?> <?php echo ($ac_op_diff > 0) ? 'Dr' : 'Cr'; ?>
                        </div>
                        <?php } ?>
                    </div>
                    
                    <div class="coa-content">
                        <?php if($_SESSION['succ'] != '') { ?>
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <?php echo $_SESSION['succ']; ?>
                        </div>
                        <?php } ?>
                        
                        <?php if($_SESSION['fail'] != '') { ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <?php echo $_SESSION['fail']; ?>
                        </div>
                        <?php } ?>
                        
                        <div id="coaTree" class="coa-tree">
                            <div class="coa-loading">
                                <i class="material-icons" style="font-size: 48px; color: #339af0;">account_balance</i>
                                <p>Loading chart of accounts...</p>
                            </div>
                        </div>
                        
                        <div class="coa-stats" id="coaStats" style="display: none;">
                            <div class="coa-stat">
                                <div class="coa-stat-value" id="totalGroups">0</div>
                                <div class="coa-stat-label">Groups</div>
                            </div>
                            <div class="coa-stat">
                                <div class="coa-stat-value" id="totalLedgers">0</div>
                                <div class="coa-stat-label">Ledgers</div>
                            </div>
                            <div class="coa-stat">
                                <div class="coa-stat-value" id="totalAccounts">0</div>
                                <div class="coa-stat-label">Total Accounts</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div id="alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="material-icons" style="font-size: 48px; color: #f03e3e;">delete_forever</i>
                        <h4 class="mt-2 heading">Delete Account</h4>
                        <p id="deleteMessage"></p>
                        <div style="margin-top: 20px;">
                            <button id="confirmDelete" class="btn btn-danger">Yes, Delete</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Error Modal -->
    <div id="alert-okay" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="material-icons" style="font-size: 48px; color: #fd7e14;">warning</i>
                        <p id="errorMessage" style="margin-top: 15px;"></p>
                        <button type="button" class="btn btn-info" data-dismiss="modal">Okay</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Chart of Accounts Enhanced Script
    var coaData = <?php echo json_encode($coa_tree_data ?? []); ?>;
    var permissions = {
        group_edit: <?php echo $add_group['edit'] ?? 0; ?>,
        group_delete: <?php echo $add_group['delete_p'] ?? 0; ?>,
        ledger_edit: <?php echo $add_ledger['edit'] ?? 0; ?>,
        ledger_delete: <?php echo $add_ledger['delete_p'] ?? 0; ?>
    };
    
    function initializeCoA() {
        loadCoAData();
        setupEventListeners();
    }
    
    function loadCoAData() {
        // Use AJAX to load data dynamically
        $.ajax({
            url: '<?php echo base_url(); ?>/account/get_coa_data',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    coaData = response.data;
                    renderCoATree();
                    updateStats();
                }
            },
            error: function() {
                // Fallback to server-rendered data
                renderCoATree();
                updateStats();
            }
        });
    }
    
    function renderCoATree() {
        var html = renderNodes(coaData);
        $('#coaTree').html(html || '<div class="coa-empty">No accounts found</div>');
        $('#coaStats').show();
    }
    
    function renderNodes(nodes, level = 0) {
        if (!nodes || nodes.length === 0) return '';
        
        var html = '';
        nodes.forEach(function(node) {
            html += renderNode(node, level);
        });
        return html;
    }
    
    function renderNode(node, level) {
        var hasChildren = node.children && node.children.length > 0;
        var isGroup = node.type === 'group';
        var nodeId = node.id + '_' + (isGroup ? '1' : '2');
        
        var html = '<li class="coa-node" data-id="' + nodeId + '" data-level="' + level + '">';
        html += '<div class="coa-node-content">';
        
        // Toggle button
        html += '<span class="coa-toggle ' + (hasChildren ? '' : 'no-children') + '">';
        if (hasChildren) {
            html += '<i class="material-icons">chevron_right</i>';
        }
        html += '</span>';
        
        // Icon
        html += '<span class="coa-icon ' + node.type + '">';
        html += '<i class="material-icons">' + (isGroup ? 'folder' : 'description') + '</i>';
        html += '</span>';
        
        // Info section
        html += '<div class="coa-info">';
        html += '<div class="coa-name">';
        if (!isGroup && node.url) {
            html += '<a href="' + node.url + '">';
        }
        html += '<span class="coa-code">(' + node.code + ')</span> ' + node.name;
        if (!isGroup && node.url) {
            html += '</a>';
        }
        html += '</div>';
        html += '<span class="coa-type">' + node.type + '</span>';
        
        // Balances
        if (!isGroup) {
            html += '<span class="coa-balance" title="Opening Balance">' + formatBalance(node.op_balance) + '</span>';
            html += '<span class="coa-balance" title="Closing Balance">' + formatBalance(node.cl_balance) + '</span>';
        } else {
            html += '<span class="coa-balance">-</span>';
            html += '<span class="coa-balance">-</span>';
        }
        html += '</div>';
        
        // Actions
        html += renderActions(node);
        
        html += '</div>';
        
        // Children container
        if (hasChildren) {
            html += '<ul class="coa-children">';
            html += renderNodes(node.children, level + 1);
            html += '</ul>';
        }
        
        html += '</li>';
        
        return html;
    }
    
    function renderActions(node) {
        var html = '<div class="coa-actions">';
        var isGroup = node.type === 'group';
        
        if (isGroup) {
            if (permissions.group_edit && !node.fixed) {
                html += '<button class="coa-action-btn edit" onclick="window.location.href=\'' + '<?php echo base_url(); ?>/account/edit_group/' + node.id + '\'" title="Edit Group">';
                html += '<i class="material-icons">edit</i></button>';
            }
            if (permissions.group_delete && !node.fixed) {
                html += '<button class="coa-action-btn delete" onclick="confirmDelete(' + node.id + ', 1, \'' + node.name + '\')" title="Delete Group">';
                html += '<i class="material-icons">delete</i></button>';
            }
        } else {
            if (permissions.ledger_edit) {
                html += '<button class="coa-action-btn edit" onclick="window.location.href=\'' + '<?php echo base_url(); ?>/account/edit_ledger/' + node.id + '\'" title="Edit Ledger">';
                html += '<i class="material-icons">edit</i></button>';
                
                if (node.show_op_bal) {
                    html += '<button class="coa-action-btn balance" onclick="window.location.href=\'' + '<?php echo base_url(); ?>/account/edit_opbal/' + node.id + '\'" title="Edit Opening Balance">';
                    html += '<i class="material-icons">attach_money</i></button>';
                }
            }
            if (permissions.ledger_delete) {
                html += '<button class="coa-action-btn delete" onclick="confirmDelete(' + node.id + ', 2, \'' + node.name + '\')" title="Delete Ledger">';
                html += '<i class="material-icons">delete</i></button>';
            }
        }
        
        html += '</div>';
        return html;
    }
    
    function formatBalance(balance) {
        if (!balance || balance === 0) return '0.00';
        var isNegative = balance < 0;
        var formatted = Math.abs(balance).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        return isNegative ? '(' + formatted + ')' : formatted;
    }
    
    function setupEventListeners() {
        // Toggle nodes
        $(document).on('click', '.coa-toggle:not(.no-children)', function(e) {
            e.stopPropagation();
            var $node = $(this).closest('.coa-node');
            var $children = $node.find('> .coa-children').first();
            var $icon = $(this).find('i');
            
            $(this).toggleClass('expanded');
            $children.toggleClass('expanded');
            
            if ($children.hasClass('expanded')) {
                $icon.text('expand_more');
            } else {
                $icon.text('chevron_right');
            }
        });
        
        // Search functionality
        var searchTimeout;
        $('#coaSearch').on('input', function() {
            clearTimeout(searchTimeout);
            var query = $(this).val().toLowerCase();
            
            searchTimeout = setTimeout(function() {
                filterNodes(query);
            }, 300);
        });
        
        // Type filter
        $('#coaTypeFilter').on('change', function() {
            var type = $(this).val();
            filterByType(type);
        });
        
        // Expand/Collapse all
        $('#expandAll').on('click', function() {
            $('.coa-children').addClass('expanded');
            $('.coa-toggle:not(.no-children)').addClass('expanded').find('i').text('expand_more');
        });
        
        $('#collapseAll').on('click', function() {
            $('.coa-children').removeClass('expanded');
            $('.coa-toggle').removeClass('expanded').find('i').text('chevron_right');
        });
    }
    
    function filterNodes(query) {
        if (!query) {
            $('.coa-node').show();
            return;
        }
        
        $('.coa-node').each(function() {
            var $node = $(this);
            var text = $node.find('.coa-name').first().text().toLowerCase();
            var matches = text.includes(query);
            
            if (matches) {
                $node.show();
                // Show all parents
                $node.parents('.coa-node').show();
                $node.parents('.coa-children').addClass('expanded');
                $node.parents('.coa-node').find('> .coa-node-content .coa-toggle').addClass('expanded').find('i').text('expand_more');
            } else {
                $node.hide();
            }
        });
    }
    
    function filterByType(type) {
        if (!type) {
            $('.coa-node').show();
            return;
        }
        
        $('.coa-node').each(function() {
            var $node = $(this);
            var nodeType = $node.find('.coa-type').first().text().toLowerCase();
            
            if (nodeType === type) {
                $node.show();
                // Show all parents
                $node.parents('.coa-node').show();
            } else {
                $node.hide();
            }
        });
    }
    
    function updateStats() {
        var groups = $('.coa-type:contains("group")').length;
        var ledgers = $('.coa-type:contains("ledger")').length;
        
        $('#totalGroups').text(groups);
        $('#totalLedgers').text(ledgers);
        $('#totalAccounts').text(groups + ledgers);
    }
    
    function confirmDelete(id, type, name) {
        var typeText = type === 1 ? 'Group' : 'Ledger';
        
        $.ajax({
            url: '<?php echo base_url(); ?>/account/check_' + (type === 1 ? 'group' : 'ledger') + '/' + id,
            method: 'POST',
            data: { id: id },
            success: function(response) {
                if (response === 'true' || response === true) {
                    $('#deleteMessage').html('Are you sure you want to delete <strong>' + name + '</strong> ' + typeText + '?');
                    $('#alert-modal').modal('show');
                    
                    $('#confirmDelete').off('click').on('click', function() {
                        window.location.href = '<?php echo base_url(); ?>/account/delete_' + (type === 1 ? 'group' : 'ledger') + '/' + id;
                    });
                } else {
                    $('#errorMessage').text('This ' + typeText.toLowerCase() + ' is in use and cannot be deleted.');
                    $('#alert-okay').modal('show');
                }
            }
        });
    }
    
    // Initialize on document ready
    $(document).ready(function() {
        initializeCoA();
    });
</script>