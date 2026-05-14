# Leave UI Module - UAT Plan

## Document Information

| Field | Value |
|-------|-------|
| Document Title | User Acceptance Test Plan |
| Module | ksf_Leave_UI |
| Version | 1.0.0 |
| Author | KSF Development Team |
| Last Updated | May 2026 |

---

## 1. UAT Scope

### Features to Test
| Feature | Priority | Test Scenarios |
|---------|----------|----------------|
| View balances | Must | Employee sees correct balance |
| Submit request | Must | Form validates, submits |
| Approve request | Must | Manager can approve/deny |

### Users Involved
| Role | Responsibilities |
|------|------------------|
| Employee | Request leave |
| Manager | Approve requests |

---

## 2. Test Scenarios

| Scenario | Steps | Expected Result |
|----------|-------|-----------------|
| View balance | Login → Leave | Balance shown |
| Submit request | Fill form → Submit | Request pending |
| Approve | See pending → Approve | Request approved |

---

## 3. Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0.0 | May 2026 | KSF Development Team | Initial specification |