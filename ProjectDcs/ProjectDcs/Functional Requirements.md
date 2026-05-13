# ksf_Leave_UI - Functional Requirements

## Document Information

| Field | Value |
|-------|-------|
| **Document ID** | FRD-LEAVE-001 |
| **Module** | ksf_Leave_UI |
| **Project** | Leave Management System |
| **Version** | 1.0.0 |
| **Author** | KS Fraser Development Team |
| **Created** | 2024-01-15 |

---

## 1. Introduction

### 1.1 Purpose
This document defines the detailed functional requirements for the ksf_Leave_UI module. These requirements serve as the basis for development, testing, and validation of the leave management user interface.

### 1.2 Scope
Functional requirements cover all user-facing features including leave request submission, balance display, approval workflow, and administrative functions within the FrontAccounting platform.

---

## 2. Functional Requirements

### 2.1 Leave Request Management

#### FR-001: View Leave Requests
| Requirement ID | FR-001 |
|----------------|--------|
| **Priority** | High |
| **Complexity** | Low |

**Description**: Display a list of leave requests for the currently logged-in employee.

**Functional Specification**:
- Retrieve all leave requests where `employee_id` matches the current user's employee ID
- Display columns: Leave Type, Dates, Days, Status
- Sort by date descending (most recent first)
- Show empty state message when no requests exist
- Apply alternating row styling for readability

**Business Rules**:
- Only show requests belonging to the logged-in user
- Include all statuses (pending, approved, rejected, cancelled)
- Never display requests from other employees

**Data Fields**:
| Field | Source | Format |
|-------|--------|--------|
| Leave Type | `leave_type` | String (Annual, Sick, Personal) |
| Dates | `start_date` - `end_date` | Formatted date range |
| Days | `days` | Decimal (1.0, 0.5 for half days) |
| Status | `status` | Enum value |

**Validation Rules**:
- User must be authenticated
- Employee ID must be valid and present in session

---

#### FR-002: View Leave Balance
| Requirement ID | FR-002 |
|----------------|--------|
| **Priority** | High |
| **Complexity** | Low |

**Description**: Display current leave balances for all leave types for the logged-in employee.

**Functional Specification**:
- Calculate and display balance for each supported leave type
- For each type, show: Entitlement, Used, Remaining
- Use current calendar year for calculations
- Display in tabular format with header row

**Business Rules**:
- `Remaining = Entitlement - Used`
- Include carried forward balance if applicable
- Use current year as default
- Handle partial day calculations

**Calculations**:
```
Remaining = Entitlement - Used + CarriedForward
Available Balance = Entitlement - Used
```

**Data Fields**:
| Field | Source | Calculation |
|-------|--------|-------------|
| Leave Type | `leave_types` table | Static display |
| Entitlement | `0_leave_balances.entitlement` | Per-year allocation |
| Used | Sum of `0_leave_requests.days` where status='approved' | Current year approved days |
| Remaining | Calculated | `Entitlement - Used` |

**Validation Rules**:
- User must be authenticated with valid employee ID
- Year must be valid (current or past)
- Leave types must be configured

---

#### FR-003: Submit Leave Request
| Requirement ID | FR-003 |
|----------------|--------|
| **Priority** | High |
| **Complexity** | Medium |

**Description**: Allow employees to submit new leave requests.

**Functional Specification**:
- Display form with fields: Leave Type, Start Date, End Date, Days, Notes
- Calculate days automatically based on date range
- Validate input before submission
- Save to database with status='pending'
- Return confirmation with request ID

**Form Fields**:
| Field | Type | Required | Validation |
|-------|------|----------|------------|
| Leave Type | Select | Yes | Must be valid leave type |
| Start Date | Date | Yes | Must be future date or today |
| End Date | Date | Yes | Must be >= start date |
| Days | Number | Yes | Calculated, must be > 0 |
| Notes | Textarea | No | Max 500 characters |

**Business Rules**:
- Start date cannot be in the past
- End date cannot be before start date
- Days must not exceed available balance
- Half-day leave supported (0.5 days)

**Validation Rules**:
- All required fields must be populated
- Dates must be valid format (YYYY-MM-DD)
- Leave type must exist and be active
- Days must not exceed remaining balance

**Error Messages**:
| Error | Message |
|-------|---------|
| Missing leave type | "Please select a leave type" |
| Invalid dates | "End date must be after start date" |
| Past date | "Start date cannot be in the past" |
| Exceeds balance | "Insufficient leave balance. Available: X days" |

---

#### FR-004: Cancel Leave Request
| Requirement ID | FR-004 |
|----------------|--------|
| **Priority** | Medium |
| **Complexity** | Low |

**Description**: Allow employees to cancel their own pending leave requests.

**Functional Specification**:
- Allow cancellation of requests with status='pending' only
- Update status to 'cancelled'
- Retain record for audit purposes
- No refund of balance for cancelled requests until approved

**Business Rules**:
- Only pending requests can be cancelled
- Cannot cancel approved or rejected requests
- Cannot cancel requests that have already started
- Cancellation is immediate (no approval required)

---

### 2.2 Approval Workflow

#### FR-005: View Pending Approval Requests
| Requirement ID | FR-005 |
|----------------|--------|
| **Priority** | High |
| **Complexity** | Medium |

**Description**: Display list of leave requests awaiting approval for managers.

**Functional Specification**:
- Retrieve all requests with status='pending'
- Display columns: Employee, Type, Dates, Days, Action
- Show approve and reject links for each request
- Only visible to users with SA_LEAVEAPPROVE permission

**Data Fields**:
| Field | Source | Format |
|-------|--------|--------|
| Employee | `employee_id` | Employee name from lookup |
| Leave Type | `leave_type` | String |
| Dates | `start_date` - `end_date` | Formatted date range |
| Days | `days` | Decimal |
| Action | Links | Approve/Reject URLs |

**Business Rules**:
- Only show requests from direct reports (manager hierarchy)
- OR show all requests if HR Admin role
- Requests sorted by submission date (oldest first)

**Validation Rules**:
- User must have SA_LEAVEAPPROVE permission
- Manager-employee relationship validated

---

#### FR-006: Approve Leave Request
| Requirement ID | FR-006 |
|----------------|--------|
| **Priority** | High |
| **Complexity** | Low |

**Description**: Allow managers to approve pending leave requests.

**Functional Specification**:
- Update request status from 'pending' to 'approved'
- Record approver ID and approval timestamp
- Deduct days from leave balance
- Send notification to employee (future enhancement)

**Business Rules**:
- Request must be in 'pending' status
- Approver must have SA_LEAVEAPPROVE permission
- Days deducted from balance upon approval
- Approval is immediate

**Database Updates**:
```sql
UPDATE 0_leave_requests 
SET status = 'approved', 
    approved_by = :approver_id, 
    approved_at = NOW() 
WHERE id = :request_id;
```

---

#### FR-007: Reject Leave Request
| Requirement ID | FR-007 |
|----------------|--------|
| **Priority** | High |
| **Complexity** | Low |

**Description**: Allow managers to reject pending leave requests.

**Functional Specification**:
- Update request status from 'pending' to 'rejected'
- Record approver ID and rejection timestamp
- Do NOT deduct days from balance (not yet approved)
- Require rejection reason (optional comment)

**Business Rules**:
- Request must be in 'pending' status
- Rejection reason recommended but optional
- No balance deduction for rejected requests
- Rejection can be reversed (future enhancement)

---

### 2.3 User Interface

#### FR-008: Navigation Menu
| Requirement ID | FR-008 |
|----------------|--------|
| **Priority** | High |
| **Complexity** | Low |

**Description**: Provide navigation between leave management sections.

**Functional Specification**:
- Display horizontal navigation bar with tabs
- Tabs: My Requests, Pending Approval, Leave Balance
- Highlight current active tab
- Support URL parameter for direct section access

**URL Parameters**:
| Parameter | Value | Section |
|-----------|-------|---------|
| `section` | `requests` | My Requests (default) |
| `section` | `pending` | Pending Approval |
| `section` | `balance` | Leave Balance |

**Visual Requirements**:
- Tab styling consistent with FA theme
- Active tab visually distinct
- Hover effects on tab links

---

#### FR-009: Responsive Table Display
| Requirement ID | FR-009 |
|----------------|--------|
| **Priority** | Medium |
| **Complexity** | Low |

**Description**: Display data in properly formatted HTML tables.

**Functional Specification**:
- Use FrontAccounting table styling (TABLESTYLE)
- Include table headers with sortable columns (future)
- Apply alternating row colors for readability
- Include pagination for large result sets (>50 rows)

**Table Styling**:
```php
start_table(TABLESTYLE);
table_header([_('Type'), _('Dates'), _('Days'), _('Status')]);
while ($row = db_fetch($result)) {
    alt_table_row($row);
    label_cell($row['leave_type']);
    // ... additional cells
}
end_table(1);
```

---

#### FR-010: Status Display
| Requirement ID | FR-010 |
|----------------|--------|
| **Priority** | Low |
| **Complexity** | Low |

**Description**: Display leave request status with visual indicators.

**Functional Specification**:
- Use text labels for status values
- Apply color coding for visual distinction:
  - Pending: Orange/Yellow
  - Approved: Green
  - Rejected: Red
  - Cancelled: Gray

**Status Labels**:
| Status | Display Text | Color Code |
|--------|--------------|------------|
| pending | Pending | #F0AD4E |
| approved | Approved | #5CB85C |
| rejected | Rejected | #D9534F |
| cancelled | Cancelled | #777777 |

---

## 3. Data Requirements

### 3.1 Input Data

| Data Element | Type | Source | Validation |
|-------------|------|--------|------------|
| Employee ID | Integer | Session | Required, must exist |
| Leave Type | String | User Input | Required, from enum |
| Start Date | Date | User Input | Required, valid date format |
| End Date | Date | User Input | Required, >= start date |
| Days | Decimal | Calculated | Required, > 0 |
| Notes | Text | User Input | Optional, max 500 chars |

### 3.2 Output Data

| Data Element | Type | Format |
|-------------|------|--------|
| Request ID | Integer | Display only |
| Employee Name | String | Full name |
| Leave Type | String | Display label |
| Date Range | String | "Jan 15, 2024 - Jan 20, 2024" |
| Days | Decimal | "5.0" |
| Status | Enum | Display label |

### 3.3 Database Operations

| Operation | Table | Action |
|-----------|-------|--------|
| Create Request | `0_leave_requests` | INSERT |
| Read Requests | `0_leave_requests` | SELECT |
| Update Status | `0_leave_requests` | UPDATE |
| Read Balance | `0_leave_balances` | SELECT |
| Update Balance | `0_leave_balances` | UPDATE (accrual tracking) |

---

## 4. User Interactions

### 4.1 Employee User Flows

#### Submit Leave Request Flow
```
1. User clicks "My Requests" tab
2. System displays current requests list
3. User clicks "New Request" button
4. System displays leave request form
5. User fills form fields
6. User clicks "Submit" button
7. System validates input
   a. If invalid: Display error messages
   b. If valid: Continue to step 8
8. System saves request with status='pending'
9. System displays confirmation message
10. System refreshes requests list
```

#### View Balance Flow
```
1. User clicks "Leave Balance" tab
2. System retrieves employee's leave balances
3. System calculates remaining for each type
4. System displays balance table
5. User views their balances
```

### 4.2 Manager User Flows

#### Approve Request Flow
```
1. Manager clicks "Pending Approval" tab
2. System checks SA_LEAVEAPPROVE permission
   a. If denied: Display access denied message
   b. If granted: Continue to step 3
3. System retrieves all pending requests
4. System displays pending requests table
5. Manager clicks "Approve" link
6. System updates request status
7. System deducts days from balance
8. System displays success message
9. System refreshes pending list (request removed)
```

---

## 5. Integration Requirements

### 5.1 FrontAccounting Integration

| Integration Point | Method | Purpose |
|-------------------|--------|---------|
| Session | `$_SESSION["wa_user"]` | User authentication |
| UI Functions | `page()`, `start_table()` | UI rendering |
| Database | `db_query()`, `db_fetch()` | Data access |
| Permissions | `user_has_permission()` | Authorization |

### 5.2 FA_Leave Module Integration

| Function | Source | Purpose |
|----------|--------|---------|
| `get_leave_requests()` | FA_Leave | Retrieve employee requests |
| `get_pending_leave_requests()` | FA_Leave | Retrieve pending requests |
| `get_leave_balance()` | FA_Leave | Calculate balance |
| `get_employee_name()` | FA_Leave | Display employee name |

---

## 6. Non-Functional Requirements

### 6.1 Performance Requirements

| Metric | Target | Measurement |
|--------|--------|-------------|
| Page Load | < 2 seconds | Time to interactive |
| Query Execution | < 500ms | Database response |
| Concurrent Users | 100+ | Simultaneous sessions |

### 6.2 Security Requirements

| Requirement | Implementation |
|-------------|----------------|
| Authentication | FA session validation |
| Authorization | Permission checks per action |
| Input Validation | Server-side validation all inputs |
| SQL Injection | Parameterized queries via db_escape |
| XSS Prevention | htmlspecialchars() on output |

### 6.3 Accessibility Requirements

| Requirement | Implementation |
|-------------|----------------|
| WCAG 2.1 AA | Semantic HTML, proper labels |
| Keyboard Navigation | Tab order, focus states |
| Screen Reader | ARIA labels on interactive elements |
| Color Contrast | 4.5:1 minimum ratio |

---

## 7. Validation Checklists

### 7.1 Leave Request Validation

- [ ] Leave type is selected
- [ ] Start date is not in the past
- [ ] End date is not before start date
- [ ] Days calculated correctly
- [ ] Days does not exceed remaining balance
- [ ] Notes length within limit (500 chars)

### 7.2 Approval Validation

- [ ] User has SA_LEAVEAPPROVE permission
- [ ] Request exists and is pending
- [ ] Employee is in manager's scope (if hierarchical)
- [ ] Approve action updates status correctly
- [ ] Reject action updates status correctly

### 7.3 Balance Validation

- [ ] Entitlement values are positive
- [ ] Used calculation excludes cancelled requests
- [ ] Remaining = Entitlement - Used
- [ ] Year is current or valid past year

---

## 8. Appendix: Requirement Traceability

| Requirement ID | Use Case ID | Test Case ID | Status |
|----------------|-------------|--------------|--------|
| FR-001 | UC-001 | TC-001 | Pending |
| FR-002 | UC-002 | TC-002 | Pending |
| FR-003 | UC-003 | TC-003 | Pending |
| FR-004 | UC-004 | TC-004 | Pending |
| FR-005 | UC-005 | TC-005 | Pending |
| FR-006 | UC-006 | TC-006 | Pending |
| FR-007 | UC-007 | TC-007 | Pending |
| FR-008 | UC-008 | TC-008 | Pending |
| FR-009 | UC-009 | TC-009 | Pending |
| FR-010 | UC-010 | TC-010 | Pending |

---

**Document Owner**: KS Fraser Development Team  
**Review Status**: Pending Approval