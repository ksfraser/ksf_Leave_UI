<?php
/**
 * Leave Management
 */

$page_security = 'SA_LEAVE';
$path_to_root = "../../..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/modules/FA_Leave/includes/leave_db.inc");

page(_("Leave Management"), false, false, "", "");

start_table(TABLESTYLE_NOBORDER);
start_row();
leave_navbar();
end_row();
end_table();

echo '<br>';

$section = isset($_GET['section']) ? $_GET['section'] : 'requests';

switch ($section) {
    case 'balance':
        display_leave_balance();
        break;
    case 'pending':
        display_pending_requests();
        break;
    case 'requests':
    default:
        display_leave_requests();
        break;
}

end_page(true);

function leave_navbar(): void
{
    $section = isset($_GET['section']) ? $_GET['section'] : 'requests';
    echo "<td><a href='?section=requests'>" . _("My Requests") . "</a></td>";
    echo "<td><a href='?section=pending'>" . _("Pending Approval") . "</a></td>";
    echo "<td><a href='?section=balance'>" . _("Leave Balance") . "</a></td>";
}

function display_leave_requests(): void
{
    $my_id = isset($_SESSION["wa_user"]) ? $_SESSION["wa_user"]->employee_id : 0;
    $requests = get_leave_requests(['employee_id' => $my_id]);
    
    start_table(TABLESTYLE);
    table_header([_('Type'), _('Dates'), _('Days'), _('Status')]);
    
    while ($req = db_fetch($requests)) {
        alt_table_row($req);
        label_cell($req['leave_type']);
        label_cell(sql2date($req['start_date']) . ' - ' . sql2date($req['end_date']));
        label_cell($req['days']);
        label_cell($req['status']);
    }
    end_table(1);
}

function display_pending_requests(): void
{
    if (!user_has_permission('SA_LEAVEAPPROVE')) return;
    
    $requests = get_pending_leave_requests();
    
    start_table(TABLESTYLE);
    table_header([_('Employee'), _('Type'), _('Dates'), _('Days'), _('Action')]);
    
    while ($req = db_fetch($requests)) {
        alt_table_row($req);
        label_cell($req['employee_name']);
        label_cell($req['leave_type']);
        label_cell(sql2date($req['start_date']) . ' - ' . sql2date($req['end_date']));
        label_cell($req['days']);
        echo "<td><a href='?approve=" . $req['id'] . "'>" . _("Approve") . "</a> | <a href='?reject=" . $req['id'] . "'>" . _("Reject") . "</a></td>";
    }
    end_table(1);
}

function display_leave_balance(): void
{
    $my_id = isset($_SESSION["wa_user"]) ? $_SESSION["wa_user"]->employee_id : 0;
    $year = date('Y');
    
    $types = ['Annual', 'Sick', 'Personal'];
    start_table(TABLESTYLE);
    table_header([_('Leave Type'), _('Entitlement'), _('Used'), _('Remaining')]);
    
    foreach ($types as $type) {
        $balance = get_leave_balance($my_id, $type, $year);
        $entitlement = $balance['entitlement'] ?? 0;
        $used = $balance['used'] ?? 0;
        $remaining = $entitlement - $used;
        
        alt_table_row();
        label_cell($type);
        label_cell($entitlement);
        label_cell($used);
        label_cell($remaining);
    }
    end_table(1);
}