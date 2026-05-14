# Leave_UI - Business Requirements

**Document ID:** BR-LEAVEUI-001  
**Module:** ksf_Leave_UI  
**Version:** 1.0.0  

---

## 1. Overview

Leave_UI provides the FrontAccounting user interface for leave management, enabling employees to submit leave requests, view balances, and managers to approve or reject requests.

## 2. Purpose

The module delivers a self-service leave management interface where employees track their leave entitlements and managers oversee the approval workflow within the FA system.

## 3. Scope

### 3.1 Core Features

- **Employee Self-Service**
  - Submit leave requests (type, dates, days)
  - View personal leave requests
  - View leave balance by type

- **Manager Functions**
  - View pending leave requests
  - Approve leave requests
  - Reject leave requests

- **Leave Types**
  - Annual leave
  - Sick leave
  - Personal leave

- **Views**
  - My Requests - Employee's own requests
  - Pending Approval - Manager's queue
  - Leave Balance - Entitlement summary

### 3.2 Out of Scope

- Leave calendar integration
- Public holiday handling
- Leave carry-over processing
- Leave encashment

## 4. Integration Dependencies

| Module | Dependency Type | Purpose |
|--------|-----------------|---------|
| ksf_FA_Leave | Required | Backend logic, database |
| ksf_HRM | Optional | Employee data |

## 5. User Roles

| Role | Permissions |
|------|-------------|
| Employee | View requests, submit new requests |
| Manager | SA_LEAVEAPPROVE - approve/reject |
| HR Admin | Full leave administration |

## 6. Acceptance Criteria

- [ ] Employees can view their leave requests
- [ ] Employees can view their leave balance
- [ ] Managers can view pending requests
- [ ] Managers can approve/reject requests
- [ ] Navigation between views functions correctly