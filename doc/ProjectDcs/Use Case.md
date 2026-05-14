# Leave_UI - Use Cases

**Document ID:** UC-LEAVEUI-001  
**Module:** ksf_Leave_UI  
**Version:** 1.0.0  

---

## 1. Use Case Overview

### UC-001: View My Leave Requests

**Description:** Employee views their submitted leave requests.

**Primary Flow:**
1. Employee navigates to Leave Management page
2. System defaults to My Requests view
3. System retrieves current user's requests
4. System displays requests table

**Preconditions:** User is authenticated employee.

---

### UC-002: View Leave Balance

**Description:** Employee checks their leave entitlements.

**Primary Flow:**
1. Employee clicks Leave Balance navigation
2. System retrieves entitlements for current year
3. System calculates used and remaining
4. System displays balance table

**Preconditions:** User is authenticated.

---

### UC-003: Approve Leave Request

**Description:** Manager approves an employee's leave request.

**Primary Flow:**
1. Manager navigates to Leave Management
2. Manager clicks Pending Approval section
3. Manager views pending requests list
4. Manager clicks Approve for specific request
5. System updates request status to approved

**Preconditions:** User has SA_LEAVEAPPROVE permission.

---

### UC-004: Reject Leave Request

**Description:** Manager rejects an employee's leave request.

**Primary Flow:**
1. Manager views pending requests
2. Manager clicks Reject for specific request
3. System updates request status to rejected

**Preconditions:** User has SA_LEAVEAPPROVE permission.

## 2. Actors

| Actor | Role |
|-------|------|
| Employee | Submit and view requests |
| Manager | Approve/reject requests |
| HR Admin | Full leave management |