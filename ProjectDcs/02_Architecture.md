# Leave UI Module - Architecture

## Document Information

| Field | Value |
|-------|-------|
| Document Title | Technical Architecture Specification |
| Module | ksf_Leave_UI |
| Version | 1.0.0 |
| Author | KSF Development Team |
| Last Updated | May 2026 |

---

## 1. Architecture Overview

### 1.1 Module Structure

```
ksf_Leave_UI/
├── src/Ksfraser/LeaveUI/
│   ├── LeaveWidget.php          # Leave balance widget
│   ├── RequestForm.php          # Leave request form
│   └── CalendarWidget.php       # Team calendar widget
└── templates/                   # UI templates
```

---

## 2. Integration

### 2.1 Dependencies

| Module | Purpose |
|--------|---------|
| ksf_Leave | Leave business logic |
| ksf_WP_ESS | WordPress ESS integration |

---

## 3. Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0.0 | May 2026 | KSF Development Team | Initial specification |