# Leave_UI - Functional Requirements

**Document ID:** FR-LEAVEUI-001  
**Module:** ksf_Leave_UI  
**Version:** 1.0.0  

---

## 1. Functional Requirements

### 1.1 Leave Request Display

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-001 | System SHALL display employee's leave requests | MUST |
| FR-002 | System SHALL show request type, dates, days, status | MUST |
| FR-003 | System SHALL filter requests by employee ID | MUST |
| FR-004 | System SHALL use current user's employee ID | MUST |

### 1.2 Pending Requests

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-010 | System SHALL display requests awaiting approval | MUST |
| FR-011 | System SHALL show employee name with request | MUST |
| FR-012 | System SHALL display approve/reject actions | MUST |
| FR-013 | System SHALL restrict to users with SA_LEAVEAPPROVE | MUST |

### 1.3 Leave Balance

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-020 | System SHALL display entitlement by leave type | MUST |
| FR-021 | System SHALL calculate remaining as entitlement minus used | MUST |
| FR-022 | System SHALL support Annual, Sick, Personal types | MUST |
| FR-023 | System SHALL use current year for calculations | MUST |

### 1.4 Navigation

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-030 | URL parameter 'section' controls active view | MUST |
| FR-031 | Navigation links allow switching between views | MUST |
| FR-032 | Default section is 'requests' | MUST |

## 2. URL Parameters

| Parameter | Values | Description |
|-----------|--------|-------------|
| section | requests, pending, balance | Active view |
| approve | request ID | Approve action |
| reject | request ID | Reject action |