# ksf_Leave_UI - UAT Plan

## Document Information

| Field | Value |
|-------|-------|
| **Document ID** | UAT-LEAVE-001 |
| **Module** | ksf_Leave_UI |
| **Project** | Leave Management System |
| **Version** | 1.0.0 |
| **Author** | KS Fraser Development Team |
| **Created** | 2024-01-15 |

---

## 1. Introduction

### 1.1 Purpose
This User Acceptance Testing (UAT) Plan defines the objectives, scope, scenarios, and success criteria for validating the ksf_Leave_UI module against business requirements. UAT represents the final validation stage before production deployment.

### 1.2 Scope of UAT
- Leave request submission by employees
- Leave balance viewing
- Request cancellation
- Manager approval/rejection workflow
- Navigation and UI functionality

### 1.3 Out of Scope
- Unit and integration testing (completed in Test Plan)
- Performance/load testing
- Security penetration testing
- Mobile application testing (future phase)

---

## 2. UAT Objectives

### 2.1 Primary Objectives

| ID | Objective | Success Metric |
|----|-----------|----------------|
| OBJ-001 | Validate leave request submission workflow | Employee can complete full submission |
| OBJ-002 | Validate balance display accuracy | Balances match calculated values |
| OBJ-003 | Validate approval workflow | Managers can approve/reject requests |
| OBJ-004 | Validate user permissions | Correct access control enforced |
| OBJ-005 | Validate UI/UX requirements | Intuitive, error-free interface |

### 2.2 Secondary Objectives

| ID | Objective | Success Metric |
|----|-----------|----------------|
| OBJ-006 | Validate error handling | User-friendly error messages |
| OBJ-007 | Validate data persistence | All records saved correctly |
| OBJ-008 | Validate audit trail | Actions logged for compliance |

---

## 3. UAT Scope and Prerequisites

### 3.1 User Roles for UAT

| Role | Username | Permissions | Count |
|------|----------|-------------|-------|
| Employee | test_employee | SA_LEAVE | 3 |
| Manager | test_manager | SA_LEAVE, SA_LEAVEAPPROVE | 2 |
| HR Admin | test_admin | SA_LEAVE, SA_LEAVEAPPROVE | 1 |
| System Admin | test_sysadmin | Full access | 1 |

### 3.2 Test Environment

| Component | Configuration |
|-----------|---------------|
| Environment | UAT Server |
| URL | https://uat.example.com |
| Database | UAT Database (isolated) |
| FrontAccounting | v2.4.3 |
| PHP | 7.4.33 |

### 3.3 Prerequisites

- [ ] UAT environment deployed and accessible
- [ ] Test user accounts created and configured
- [ ] Test data loaded (employees, balances, leave types)
- [ ] UAT team trained on test procedures
- [ ] Test accounts verified functional
- [ ] FrontAccounting core verified working

---

## 4. UAT Scenarios

### 4.1 Scenario SU-01: Employee Submit Leave Request

**Scenario ID**: SU-01  
**Priority**: Critical  
**Business Value**: Core employee functionality

#### Scenario
John Smith (Employee) needs to submit a leave request for his upcoming vacation from July 1-5, 2024.

#### Pre-Conditions
- John Smith is logged in as test_employee
- John has remaining Annual leave balance
- System date is before July 1, 2024

#### Test Steps
1. Navigate to Leave Management page
2. Verify "My Requests" tab is displayed
3. Click on leave type dropdown
4. Select "Annual Leave"
5. Enter start date: 07/01/2024
6. Enter end date: 07/05/2024
7. Verify days calculated as 5
8. Enter notes: "Family vacation"
9. Click "Submit" button
10. Verify success message displayed
11. Verify request appears in list with "Pending" status

#### Expected Results
- Request created successfully
- Status is "Pending"
- Days count is correct (5)
- Dates match entered values
- Notes saved

#### Pass Criteria
- [ ] Form accepts all valid input
- [ ] Days auto-calculated correctly
- [ ] Success message displayed
- [ ] Request visible in list
- [ ] Status shown as "Pending"

---

### 4.2 Scenario SU-02: View Leave Balance

**Scenario ID**: SU-02  
**Priority**: Critical  
**Business Value**: Self-service transparency

#### Scenario
Mary Johnson (Employee) wants to check her current leave balances before planning time off.

#### Pre-Conditions
- Mary Johnson is logged in as test_employee
- Mary has existing balance records

#### Test Steps
1. Navigate to Leave Management page
2. Click "Leave Balance" tab
3. View the balance table
4. Verify Annual Leave row shows: Entitlement=20, Used=5, Remaining=15
5. Verify Sick Leave row shows: Entitlement=10, Used=2, Remaining=8
6. Verify Personal Leave row shows: Entitlement=5, Used=0, Remaining=5

#### Expected Results
- All three leave types displayed
- Values match database calculations
- Remaining = Entitlement - Used

#### Pass Criteria
- [ ] All leave types visible
- [ ] Values mathematically correct
- [ ] Table properly formatted

---

### 4.3 Scenario SU-03: Cancel Pending Request

**Scenario ID**: SU-03  
**Priority**: High  
**Business Value**: Flexibility for employees

#### Scenario
Robert Brown (Employee) needs to cancel a pending leave request because his plans changed.

#### Pre-Conditions
- Robert is logged in as test_employee
- Robert has at least one pending request with future start date

#### Test Steps
1. Navigate to Leave Management page
2. View My Requests list
3. Locate a pending request
4. Click "Cancel" button on the request
5. Confirm cancellation in dialog
6. Verify status changes to "Cancelled"
7. Verify request still visible in list

#### Expected Results
- Status updated to "Cancelled"
- Request record preserved
- Balance unchanged

#### Pass Criteria
- [ ] Status changed to "Cancelled"
- [ ] Record preserved for audit
- [ ] No balance deduction

---

### 4.4 Scenario SU-04: Manager Approve Request

**Scenario ID**: SU-04  
**Priority**: Critical  
**Business Value**: Core management functionality

#### Scenario
Sarah Manager needs to approve John Smith's leave request for July 1-5.

#### Pre-Conditions
- Sarah is logged in as test_manager
- John Smith's pending request exists
- Sarah has SA_LEAVEAPPROVE permission

#### Test Steps
1. Navigate to Leave Management page
2. Click "Pending Approval" tab
3. Verify John Smith's request is listed
4. Click "Approve" link
5. Verify status changes to "Approved"
6. Verify request no longer in pending list
7. Log in as John Smith
8. View My Requests
9. Verify request status shows "Approved"

#### Expected Results
- Approval processed immediately
- Status updated to "Approved"
- Request moved from pending list
- Balance should be deducted (future enhancement)

#### Pass Criteria
- [ ] Status changed to "Approved"
- [ ] Approved request not in pending list
- [ ] Employee sees "Approved" status
- [ ] Approval timestamp recorded

---

### 4.5 Scenario SU-05: Manager Reject Request

**Scenario ID**: SU-05  
**Priority**: Critical  
**Business Value**: Management control

#### Scenario
Sarah Manager needs to reject a leave request due to team scheduling conflict.

#### Pre-Conditions
- Sarah is logged in as test_manager
- At least one pending request exists

#### Test Steps
1. Navigate to Leave Management page
2. Click "Pending Approval" tab
3. Locate a pending request
4. Click "Reject" link
5. Enter rejection reason: "Team scheduling conflict"
6. Confirm rejection
7. Verify status changes to "Rejected"
8. Verify leave balance NOT deducted

#### Expected Results
- Request rejected successfully
- Rejection reason recorded
- Balance unchanged

#### Pass Criteria
- [ ] Status changed to "Rejected"
- [ ] Rejection reason saved
- [ ] Balance unchanged

---

### 4.6 Scenario SU-06: Access Control

**Scenario ID**: SU-06  
**Priority**: High  
**Business Value**: Security compliance

#### Scenario
Regular employee should NOT be able to access pending approval functionality.

#### Pre-Conditions
- Employee account (test_employee) logged in
- Does NOT have SA_LEAVEAPPROVE permission

#### Test Steps
1. Log in as test_employee
2. Attempt to navigate to ?section=pending
3. Verify access denied message or redirect
4. Verify "Pending Approval" tab not visible or non-functional

#### Expected Results
- Access denied message displayed
- OR redirected to My Requests view
- "Pending Approval" tab hidden or disabled

#### Pass Criteria
- [ ] Unauthorized access blocked
- [ ] Appropriate error message
- [ ] No sensitive data exposed

---

### 4.7 Scenario SU-07: Validation - Past Date

**Scenario ID**: SU-07  
**Priority**: Medium  
**Business Value**: Data integrity

#### Scenario
Employee attempts to submit a request with start date in the past.

#### Pre-Conditions
- Employee logged in
- Current date is after any past test dates

#### Test Steps
1. Navigate to Leave Management page
2. Attempt to enter start date: 01/01/2023 (past date)
3. Attempt to submit
4. Verify validation error message displayed
5. Verify request NOT created

#### Expected Results
- Error message: "Start date cannot be in the past"
- No request created
- Form retained for correction

#### Pass Criteria
- [ ] Error message displayed
- [ ] Request not created
- [ ] Form data preserved

---

### 4.8 Scenario SU-08: Validation - Invalid Date Range

**Scenario ID**: SU-08  
**Priority**: Medium  
**Business Value**: Data integrity

#### Scenario
Employee attempts to submit a request where end date is before start date.

#### Pre-Conditions
- Employee logged in

#### Test Steps
1. Navigate to Leave Management page
2. Enter start date: 07/10/2024
3. Enter end date: 07/05/2024 (before start)
4. Attempt to submit
5. Verify validation error message displayed

#### Expected Results
- Error message: "End date must be after start date"
- No request created

#### Pass Criteria
- [ ] Error message displayed
- [ ] Request not created
- [ ] Form data preserved

---

### 4.9 Scenario SU-09: Navigation Flow

**Scenario ID**: SU-09  
**Priority**: Medium  
**Business Value**: Usability

#### Scenario
Employee navigates between different sections of the Leave Management module.

#### Pre-Conditions
- Employee logged in

#### Test Steps
1. Load Leave Management page (no section parameter)
2. Verify My Requests displayed by default
3. Click Leave Balance tab
4. Verify Leave Balance section displayed
5. Click My Requests tab
6. Verify My Requests section displayed
7. Use URL parameter ?section=balance
8. Verify Leave Balance section loads directly

#### Expected Results
- Default section loads correctly
- Tab navigation works
- URL parameter navigation works

#### Pass Criteria
- [ ] Default loads My Requests
- [ ] Tab switching works
- [ ] URL navigation works

---

## 5. Test Data for UAT

### 5.1 Test Users

| User ID | Name | Role | Employee ID | Permissions |
|---------|------|------|-------------|-------------|
| UAT-001 | John Smith | Employee | 101 | SA_LEAVE |
| UAT-002 | Mary Johnson | Employee | 102 | SA_LEAVE |
| UAT-003 | Robert Brown | Employee | 103 | SA_LEAVE |
| UAT-004 | Sarah Manager | Manager | 201 | SA_LEAVE, SA_LEAVEAPPROVE |
| UAT-005 | Tom Manager | Manager | 202 | SA_LEAVE, SA_LEAVEAPPROVE |
| UAT-006 | HR Admin | Admin | 301 | SA_LEAVE, SA_LEAVEAPPROVE |

### 5.2 Test Data - Leave Balances

| Employee | Leave Type | Entitlement | Used | Remaining |
|----------|-----------|-------------|------|-----------|
| John Smith | Annual | 20.0 | 5.0 | 15.0 |
| John Smith | Sick | 10.0 | 2.0 | 8.0 |
| John Smith | Personal | 5.0 | 0.0 | 5.0 |
| Mary Johnson | Annual | 20.0 | 8.0 | 12.0 |
| Mary Johnson | Sick | 10.0 | 1.0 | 9.0 |
| Robert Brown | Annual | 20.0 | 10.0 | 10.0 |

### 5.3 Test Data - Leave Requests

| ID | Employee | Type | Start | End | Days | Status |
|----|----------|------|-------|-----|------|--------|
| 1 | John Smith | Annual | 2024-07-01 | 2024-07-05 | 5.0 | pending |
| 2 | Mary Johnson | Sick | 2024-05-15 | 2024-05-16 | 2.0 | approved |
| 3 | Robert Brown | Annual | 2024-08-01 | 2024-08-03 | 3.0 | pending |

---

## 6. Success Criteria

### 6.1 UAT Pass Criteria

| Criterion | Description | Target | Weight |
|-----------|-------------|--------|--------|
| SC-01 | All critical scenarios executed successfully | 100% | 40% |
| SC-02 | All high priority scenarios executed successfully | 100% | 30% |
| SC-03 | All medium priority scenarios executed successfully | 90% | 20% |
| SC-04 | No critical or high severity defects open | 0 | 10% |

**Overall Pass Threshold**: 95%

### 6.2 Scenario Completion Matrix

| Scenario ID | Executed | Passed | Failed | Blocked |
|-------------|----------|--------|--------|---------|
| SU-01 | [ ] | [ ] | [ ] | [ ] |
| SU-02 | [ ] | [ ] | [ ] | [ ] |
| SU-03 | [ ] | [ ] | [ ] | [ ] |
| SU-04 | [ ] | [ ] | [ ] | [ ] |
| SU-05 | [ ] | [ ] | [ ] | [ ] |
| SU-06 | [ ] | [ ] | [ ] | [ ] |
| SU-07 | [ ] | [ ] | [ ] | [ ] |
| SU-08 | [ ] | [ ] | [ ] | [ ] |
| SU-09 | [ ] | [ ] | [ ] | [ ] |

---

## 7. Defect Reporting

### 7.1 UAT Defect Template

```
UAT DEFECT REPORT
====================
Defect ID: UAT-DEF-001
Date Reported: [Date]
Reported By: [Tester Name]
Scenario: SU-XX
Severity: [Critical/High/Medium/Low]

Description:
[Detailed description of the issue]

Steps to Reproduce:
1. Step one
2. Step two
3. Step three

Expected Behavior:
[What should happen]

Actual Behavior:
[What actually happened]

Screenshots Attached: [Yes/No]
Environment: UAT Server

Priority for Fix: [P1/P2/P3/P4]
Notes:
```

### 7.2 Severity Definitions

| Severity | Definition | Response Time |
|-----------|------------|---------------|
| Critical | Business process blocked | 4 hours |
| High | Major feature not working | 24 hours |
| Medium | Feature partially working | 3 days |
| Low | Cosmetic/minor issue | 1 week |

---

## 8. UAT Schedule

### 8.1 Timeline

| Phase | Activities | Duration | Dates |
|-------|------------|----------|-------|
| Preparation | Test data setup, environment prep | 1 day | Day 1 |
| Execution | Execute UAT scenarios | 3 days | Days 2-4 |
| Defect Resolution | Fix and retest | 2 days | Days 5-6 |
| Sign-off | Final validation | 1 day | Day 7 |

### 8.2 Roles and Responsibilities

| Role | Responsibilities |
|------|-------------------|
| UAT Lead | Coordinate testing, manage defects |
| Business Users | Execute test scenarios |
| Developers | Fix defects, support testing |
| QA Support | Test environment, test data |

---

## 9. Risk Assessment

### 9.1 Known Risks

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| Environment issues | Medium | High | Pre-deployment checklist |
| Test data unavailability | Low | Medium | Backup data scripts |
| Resource availability | Medium | Medium | Cross-train team members |
| Scope creep | Low | High | Clear scope document |

### 9.2 Contingency Plans

| Risk | Contingency |
|------|-------------|
| Environment failure | Use backup environment |
| Key tester unavailable | Documented procedures for backup tester |
| Major defect discovered | Escalate to Project Manager immediately |

---

## 10. Sign-Off and Acceptance

### 10.1 UAT Completion Checklist

- [ ] All 9 scenarios executed
- [ ] All critical scenarios passed
- [ ] No open Critical/High defects
- [ ] All medium defects documented with fix timeline
- [ ] Test data verified
- [ ] Defect report reviewed
- [ ] Performance acceptable

### 10.2 Sign-Off Authority

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Business Owner | | | |
| Project Manager | | | |
| UAT Lead | | | |
| QA Lead | | | |
| Development Lead | | | |

### 10.3 Final Acceptance Statement

> By signing below, the undersigned confirm that User Acceptance Testing has been completed for the ksf_Leave_UI module. All exit criteria have been met or formally accepted exceptions documented. The module is approved for deployment to production.

---

**Document Owner**: KS Fraser Development Team  
**Status**: Ready for UAT Execution

---

*End of Document*