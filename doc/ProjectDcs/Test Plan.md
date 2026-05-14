# Leave_UI - Test Plan

**Document ID:** TP-LEAVEUI-001  
**Module:** ksf_Leave_UI  
**Version:** 1.0.0  

---

## 1. Test Scope

- Leave request display
- Pending requests view
- Leave balance calculation
- Navigation functionality

## 2. Test Cases

| ID | Test | Test Data | Pass Criteria |
|---------|-----------|-----------|---------------|
| TC-001 | testDisplayLeaveRequests | valid employee | Requests displayed |
| TC-002 | testDisplayLeaveRequests_Empty | no requests | Empty table displayed |
| TC-003 | testDisplayPendingRequests | with pending | All pending shown |
| TC-004 | testDisplayPendingRequests_NoPermission | no SA_LEAVEAPPROVE | Access denied |
| TC-005 | testDisplayLeaveBalance | annual: 20, sick: 10 | Correct calculations |
| TC-006 | testNavigationSections | all sections | Correct section loads |

## 3. Test Data

```php
$testEmployee = [
    'employee_id' => 1,
    'name' => 'Test Employee',
];

$testRequests = [
    ['type' => 'Annual', 'start' => '2025-01-15', 'end' => '2025-01-17', 'days' => 3, 'status' => 'approved'],
    ['type' => 'Sick', 'start' => '2025-02-10', 'end' => '2025-02-10', 'days' => 1, 'status' => 'pending'],
];

$testBalance = [
    'Annual' => ['entitlement' => 20, 'used' => 5, 'remaining' => 15],
    'Sick' => ['entitlement' => 10, 'used' => 2, 'remaining' => 8],
    'Personal' => ['entitlement' => 5, 'used' => 1, 'remaining' => 4],
];
```