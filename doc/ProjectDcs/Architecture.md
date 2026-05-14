# Leave_UI - Architecture

**Document ID:** ARCH-LEAVEUI-001  
**Module:** ksf_Leave_UI  
**Version:** 1.0.0  

---

## 1. Module Overview

Leave_UI follows FrontAccounting page-based UI pattern with server-side rendering and FA database functions.

## 2. Component Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    Pages (UI Layer)                          │
├─────────────────────────────────────────────────────────────┤
│ - leave.php                                                  │
│   ├─ display_leave_requests()                                │
│   ├─ display_pending_requests()                              │
│   ├─ display_leave_balance()                                │
│   └─ leave_navbar()                                          │
└─────────────────────────────────────────────────────────────┘
                              │
                              │ includes
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                   Includes (Backend)                        │
├─────────────────────────────────────────────────────────────┤
│ - leave_db.inc (from FA_Leave module)                       │
│   ├─ get_leave_requests(filters)                            │
│   ├─ get_pending_leave_requests()                           │
│   ├─ get_leave_balance(empId, type, year)                  │
│   └─ approve/reject functions                               │
└─────────────────────────────────────────────────────────────┘
```

## 3. Directory Structure

```
ksf_Leave_UI/
├── pages/
│   └── leave.php
└── doc/ProjectDcs/
```

## 4. Page Sections

| Section | Description |
|---------|-------------|
| requests | Display user's own leave requests |
| pending | Display requests awaiting approval |
| balance | Display leave entitlements and used/remaining |

## 5. Technology Stack

| Component | Technology |
|-----------|------------|
| Language | PHP |
| UI | FrontAccounting UI helpers |
| Database | FA db.inc functions |
| Permissions | SA_LEAVE, SA_LEAVEAPPROVE |