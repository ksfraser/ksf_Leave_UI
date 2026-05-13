# ksf_Leave_UI - Test Plan

## Document Information

| Field | Value |
|-------|-------|
| **Document ID** | TP-LEAVE-001 |
| **Module** | ksf_Leave_UI |
| **Project** | Leave Management System |
| **Version** | 1.0.0 |
| **Author** | KS Fraser Development Team |
| **Created** | 2024-01-15 |

---

## 1. Introduction

### 1.1 Purpose
This Test Plan defines the testing strategy, test scenarios, and acceptance criteria for the ksf_Leave_UI module. It ensures all functional requirements are verified through systematic testing.

### 1.2 Scope
Testing covers all user-facing features including leave request submission, balance display, approval workflow, and navigation within the FrontAccounting platform.

### 1.3 Test Approach
- **Unit Testing**: Individual function testing
- **Integration Testing**: Module integration with FA_Leave
- **UI Testing**: User interface rendering and interaction
- **End-to-End Testing**: Complete user workflows

---

## 2. Test Strategy

### 2.1 Testing Levels

| Level | Focus | Tools | Coverage Target |
|-------|-------|-------|-----------------|
| Unit | Individual functions | PHPUnit | 90%+ |
| Integration | Module interactions | PHPUnit | 80%+ |
| System | Complete workflows | Manual/Selenium | 100% |
| UAT | Business requirements | Manual | 100% |

### 2.2 Test Environment

| Component | Specification |
|-----------|---------------|
| PHP Version | 7.3+ |
| Database | MySQL 5.7+ |
| FrontAccounting | 2.4.x |
| Web Browser | Chrome/Firefox latest |
| OS | Ubuntu 20.04 / Windows 10 |

---

## 3. Test Scenarios

### 3.1 TC-001: View Leave Requests

**Test Case ID**: TC-001  
**Feature**: FR-001 (View Leave Requests)  
**Priority**: High

#### Preconditions
- User is authenticated in FrontAccounting
- Employee ID is set in session
- Database has at least one leave request record

#### Test Data
```php
$testRequest = [
    'employee_id' => 1,
    'leave_type' => 'Annual',
    'start_date' => '2024-06-01',
    'end_date' => '2024-06-05',
    'days' => 5.0,
    'status' => 'pending'
];
```

#### Test Steps
1. Navigate to Leave Management page: `/modules/ksf_Leave_UI/pages/leave.php`
2. Verify page loads without errors
3. Verify table displays with correct headers: Type, Dates, Days, Status
4. Verify existing request is displayed in table
5. Verify leave type is correctly displayed
6. Verify date range format is correct (e.g., "Jun 01, 2024 - Jun 05, 2024")
7. Verify days value is correctly shown
8. Verify status badge displays correct value

#### Expected Results
| Step | Expected Result |
|------|----------------|
| 2 | Page loads with HTTP 200 |
| 3 | Table headers present in correct order |
| 4 | Request row displayed with all data |
| 5 | Leave type matches database value |
| 6 | Dates properly formatted |
| 7 | Days value matches (5.0) |
| 8 | Status displays "Pending" |

#### Pass Criteria
- [ ] Page loads successfully
- [ ] Request data displays correctly
- [ ] All columns populated
- [ ] No JavaScript errors in console

---

### 3.2 TC-002: View Leave Balance

**Test Case ID**: TC-002  
**Feature**: FR-002 (View Leave Balance)  
**Priority**: High

#### Preconditions
- User is authenticated with valid employee ID
- Leave balance records exist in database
- Current year is 2024

#### Test Data
```php
$testBalance = [
    'employee_id' => 1,
    'entitlement' => 20.0,
    'used' => 5.0,
    'carried_forward' => 2.0
];
// Expected remaining: 20.0 - 5.0 + 2.0 = 17.0
```

#### Test Steps
1. Navigate to Leave Management page
2. Click "Leave Balance" tab (?section=balance)
3. Verify balance table displays with columns: Leave Type, Entitlement, Used, Remaining
4. Verify Annual Leave row exists
5. Verify Entitlement shows 20.0
6. Verify Used shows 5.0
7. Verify Remaining shows 17.0 (calculation correct)

#### Expected Results
| Step | Expected Result |
|------|----------------|
| 3 | Table headers correct |
| 4 | Annual Leave row present |
| 5 | Entitlement = 20.0 |
| 6 | Used = 5.0 |
| 7 | Remaining = 17.0 |

#### Pass Criteria
- [ ] Balance table renders correctly
- [ ] Calculation formula correct: Remaining = Entitlement - Used + CarriedForward
- [ ] All leave types displayed (Annual, Sick, Personal)
- [ ] No calculation errors

---

### 3.3 TC-003: Submit Leave Request

**Test Case ID**: TC-003  
**Feature**: FR-003 (Submit Leave Request)  
**Priority**: High

#### Preconditions
- User is authenticated
- Employee has available leave balance
- Leave types are configured

#### Test Data
```php
$newRequest = [
    'employee_id' => 1,
    'leave_type' => 'Annual',
    'start_date' => '2024-07-01',
    'end_date' => '2024-07-03',
    'days' => 3.0,
    'notes' => 'Summer vacation'
];
```

#### Test Steps
1. Navigate to Leave Management page
2. Click "New Request" button (if implemented as button)
3. Fill leave type dropdown with "Annual"
4. Enter start date: 2024-07-01
5. Enter end date: 2024-07-03
6. Verify days auto-calculated to 3
7. Enter notes: "Summer vacation"
8. Click "Submit" button
9. Verify success message displayed
10. Verify request appears in list

#### Expected Results
| Step | Expected Result |
|------|----------------|
| 3 | Dropdown shows leave type options |
| 6 | Days field shows "3.0" |
| 9 | Success message: "Request submitted successfully" |
| 10 | New row in requests table with status "Pending" |

#### Alternative Flow Testing

**AF-003A: Past Date Validation**
1. Enter start date: 2024-01-01 (past date)
2. Click Submit
3. Verify error message: "Start date cannot be in the past"

**AF-003B: End Before Start Validation**
1. Enter start date: 2024-07-10
2. Enter end date: 2024-07-05
3. Click Submit
4. Verify error message: "End date must be after start date"

**AF-003C: Insufficient Balance**
1. Request 30 days Annual leave
2. Verify error: "Insufficient leave balance"

#### Pass Criteria
- [ ] Request created with status='pending'
- [ ] Days calculated correctly
- [ ] Validation errors displayed appropriately
- [ ] Record persists in database

---

### 3.4 TC-004: Cancel Leave Request

**Test Case ID**: TC-004  
**Feature**: FR-004 (Cancel Leave Request)  
**Priority**: Medium

#### Preconditions
- User has at least one pending leave request
- Request start date is in the future

#### Test Steps
1. Navigate to My Requests view
2. Identify a pending request
3. Click "Cancel" button/link on pending request
4. Confirm cancellation in dialog
5. Verify request status changes to "Cancelled"
6. Verify request remains in list

#### Expected Results
| Step | Expected Result |
|------|----------------|
| 4 | Confirmation dialog appears |
| 5 | Status changes to "Cancelled" |
| 6 | Cancelled request still visible in list |

#### Pass Criteria
- [ ] Status updated to 'cancelled'
- [ ] Request record preserved
- [ ] Balance not affected (wasn't approved)
- [ ] Audit trail maintained

---

### 3.5 TC-005: View Pending Approvals

**Test Case ID**: TC-005  
**Feature**: FR-005 (View Pending Approvals)  
**Priority**: High

#### Preconditions
- User is logged in as Manager
- User has SA_LEAVEAPPROVE permission
- At least one pending request exists

#### Test Steps
1. Navigate to Leave Management page
2. Click "Pending Approval" tab
3. Verify permission check passed (user has SA_LEAVEAPPROVE)
4. Verify pending requests table displays with columns: Employee, Type, Dates, Days, Action
5. Verify Approve/Reject links present for each request
6. Verify requests sorted by date (oldest first)

#### Expected Results
| Step | Expected Result |
|------|----------------|
| 3 | No access denied error |
| 4 | Table headers correct |
| 5 | Action column has clickable links |
| 6 | Requests ordered correctly |

#### Pass Criteria
- [ ] Permission check passes for authorized users
- [ ] All pending requests displayed
- [ ] Action links functional
- [ ] Employee names displayed correctly

---

### 3.6 TC-006: Approve Leave Request

**Test Case ID**: TC-006  
**Feature**: FR-006 (Approve Leave Request)  
**Priority**: High

#### Preconditions
- User is Manager with SA_LEAVEAPPROVE
- Pending request exists in database

#### Test Data
```php
$requestToApprove = [
    'id' => 123,
    'employee_id' => 1,
    'days' => 3.0,
    'status' => 'pending'
];
```

#### Test Steps
1. Navigate to Pending Approval tab
2. Locate request ID 123
3. Click "Approve" link (?approve=123)
4. Verify status changes to "Approved"
5. Verify request removed from pending list
6. Query database to verify:
   - Status = 'approved'
   - approved_by = manager_id
   - approved_at = timestamp

#### Expected Results
| Step | Expected Result |
|------|----------------|
| 4 | Status shows "Approved" |
| 5 | Request no longer in pending list |
| 6 | Database updated correctly |

#### Database Verification Query
```sql
SELECT id, status, approved_by, approved_at 
FROM 0_leave_requests 
WHERE id = 123;
// Expected: status='approved', approved_by=2, approved_at=NOW()
```

#### Pass Criteria
- [ ] Status updated to 'approved'
- [ ] Approver ID recorded
- [ ] Timestamp recorded
- [ ] Leave balance deducted

---

### 3.7 TC-007: Reject Leave Request

**Test Case ID**: TC-007  
**Feature**: FR-007 (Reject Leave Request)  
**Priority**: High

#### Preconditions
- User is Manager with SA_LEAVEAPPROVE
- Pending request exists in database

#### Test Data
```php
$requestToReject = [
    'id' => 124,
    'days' => 5.0,
    'status' => 'pending'
];
```

#### Test Steps
1. Navigate to Pending Approval tab
2. Locate request ID 124
3. Click "Reject" link (?reject=124)
4. Verify status changes to "Rejected"
5. Verify request removed from pending list
6. Verify leave balance NOT deducted
7. Query database to verify status='rejected'

#### Expected Results
| Step | Expected Result |
|------|----------------|
| 4 | Status shows "Rejected" |
| 5 | Request no longer in pending list |
| 6 | Balance unchanged (5.0 days still available) |

#### Pass Criteria
- [ ] Status updated to 'rejected'
- [ ] Rejection recorded
- [ ] Balance unchanged (no deduction)
- [ ] Audit trail created

---

### 3.8 TC-008: Navigation

**Test Case ID**: TC-008  
**Feature**: FR-008 (Navigation Menu)  
**Priority**: Medium

#### Test Steps
1. Navigate to Leave Management page (no section parameter)
2. Verify default section (My Requests) is active
3. Click "Leave Balance" tab
4. Verify URL updated to ?section=balance
5. Verify Leave Balance content displayed
6. Click "Pending Approval" tab
7. Verify URL updated to ?section=pending
8. Verify Pending Approval content displayed (if authorized)

#### Expected Results
| Step | Expected Result |
|------|----------------|
| 2 | My Requests tab is highlighted |
| 4 | URL contains ?section=balance |
| 6 | URL contains ?section=pending |

#### Pass Criteria
- [ ] Default section loads correctly
- [ ] Tab switching works via URL parameters
- [ ] Active tab visually highlighted
- [ ] Unauthorized sections show permission error

---

## 4. Test Data Management

### 4.1 Test Data Sets

| Data Set | Purpose | Size |
|----------|---------|------|
| Basic Requests | Core functionality | 10 records |
| Edge Cases | Boundary testing | 5 records |
| Large Dataset | Performance testing | 100 records |
| Historical | Balance calculations | 50 records |

### 4.2 Database Test Fixtures

```sql
-- Employee test data
INSERT INTO 0_employees (id, name, department_id) VALUES 
(1, 'John Employee', 1),
(2, 'Jane Manager', 1);

-- User test data  
INSERT INTO 0_users (id, employee_id, role) VALUES 
(1, 1, 'employee'),
(2, 2, 'manager');

-- Leave balance test data
INSERT INTO 0_leave_balances (employee_id, leave_type, year, entitlement, used) VALUES 
(1, 'Annual', 2024, 20.0, 5.0),
(1, 'Sick', 2024, 10.0, 2.0),
(1, 'Personal', 2024, 5.0, 0.0);

-- Leave request test data
INSERT INTO 0_leave_requests (employee_id, leave_type, start_date, end_date, days, status) VALUES 
(1, 'Annual', '2024-06-01', '2024-06-05', 5.0, 'pending'),
(1, 'Sick', '2024-05-15', '2024-05-16', 2.0, 'approved');
```

---

## 5. Test Execution Matrix

| Test Case | Priority | Type | Automated | Pass Criteria | Status |
|-----------|----------|------|-----------|---------------|--------|
| TC-001 | High | UI | Yes | 4/4 criteria | Pending |
| TC-002 | High | Unit | Yes | 4/4 criteria | Pending |
| TC-003 | High | Integration | Yes | 4/4 criteria | Pending |
| TC-004 | Medium | Integration | Yes | 4/4 criteria | Pending |
| TC-005 | High | UI | No | 4/4 criteria | Pending |
| TC-006 | High | Integration | Yes | 4/4 criteria | Pending |
| TC-007 | High | Integration | Yes | 4/4 criteria | Pending |
| TC-008 | Medium | UI | No | 4/4 criteria | Pending |

---

## 6. Defect Tracking

### 6.1 Defect Severity Levels

| Level | Description | Example |
|-------|-------------|---------|
| Critical | System unusable | Page crashes, data loss |
| High | Major feature broken | Cannot submit requests |
| Medium | Feature partially works | Validation fails silently |
| Low | Minor issue | Visual styling issue |

### 6.2 Defect Template

```
Defect ID: DEF-LEAVE-001
Title: [Brief description]
Severity: [Critical/High/Medium/Low]
Test Case: TC-XXX
Steps to Reproduce:
1. Step one
2. Step two
Expected Result: [What should happen]
Actual Result: [What actually happened]
Status: Open/In Progress/Resolved/Closed
```

---

## 7. Test Schedule

| Phase | Activities | Duration | Target Date |
|-------|------------|----------|-------------|
| Unit Testing | Function-level tests | 2 days | Week 2 |
| Integration | Module integration | 2 days | Week 2 |
| System Testing | End-to-end workflows | 2 days | Week 3 |
| UAT | User acceptance | 3 days | Week 4 |
| Fix & Retest | Defect resolution | 2 days | Week 4 |

---

## 8. Test Deliverables

| Deliverable | Description | Format |
|-------------|-------------|--------|
| Test Plan | This document | Markdown |
| Test Scripts | PHPUnit test files | PHP |
| Test Data | SQL fixtures | SQL |
| Test Reports | Execution results | HTML |
| Defect Reports | Issues found | Markdown |

---

## 9. Sign-Off Criteria

| Criteria | Description | Threshold |
|----------|-------------|-----------|
| Test Coverage | Requirements covered | 100% |
| Test Pass Rate | Tests passing | 95%+ |
| Critical Defects | Open critical issues | 0 |
| High Defects | Open high issues | < 3 |

**QA Lead Sign-Off**: _______________________  
**Date**: _______________________

---

**Document Owner**: KS Fraser Development Team  
**Review Status**: Pending Approval