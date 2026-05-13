# ksf_Leave_UI - Architecture

## Document Information

| Field | Value |
|-------|-------|
| **Document ID** | ARCH-LEAVE-001 |
| **Module** | ksf_Leave_UI |
| **Project** | Leave Management System |
| **Version** | 1.0.0 |
| **Author** | KS Fraser Development Team |
| **Created** | 2024-01-15 |

---

## 1. Technical Architecture Overview

### 1.1 Architecture Pattern
The ksf_Leave_UI module follows the **Adapter Pattern** as specified in the KSF module architecture guidelines. It serves as a UI bridge between the FrontAccounting presentation layer and the FA_Leave business logic module.

### 1.2 Module Classification
- **Type**: UI Adapter Module
- **Namespace**: Local page adapter (FrontAccounting)
- **Platform**: FrontAccounting 2.4.x+

### 1.3 Architecture Layers

```
┌──────────────────────────────────────────────────────────────┐
│                    PRESENTATION LAYER                        │
│  ┌────────────────────────────────────────────────────────┐  │
│  │  ksf_Leave_UI/pages/leave.php                        │  │
│  │  - User Interface Components                         │  │
│  │  - Form Handling                                     │  │
│  │  - Navigation                                       │  │
│  └────────────────────────────────────────────────────────┘  │
└──────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌──────────────────────────────────────────────────────────────┐
│                    ADAPTER LAYER                             │
│  ┌────────────────────────────────────────────────────────┐  │
│  │  ksf_Leave_UI/includes/                               │  │
│  │  - leave_ui_functions.inc                            │  │
│  │  - leave_display_helpers.inc                         │  │
│  │  - Adapter implementations                           │  │
│  └────────────────────────────────────────────────────────┘  │
└──────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌──────────────────────────────────────────────────────────────┐
│                 BUSINESS LOGIC LAYER                        │
│  ┌────────────────────────────────────────────────────────┐  │
│  │  modules/FA_Leave/                                    │  │
│  │  - leave_service.inc                                  │  │
│  │  - leave_db.inc                                       │  │
│  │  - leave_validation.inc                              │  │
│  └────────────────────────────────────────────────────────┘  │
└──────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌──────────────────────────────────────────────────────────────┐
│                    DATA ACCESS LAYER                         │
│  ┌────────────────────────────────────────────────────────┐  │
│  │  FrontAccounting Database                             │  │
│  │  - 0_leave_requests                                   │  │
│  │  - 0_leave_balances                                   │  │
│  │  - 0_leave_types                                      │  │
│  │  - 0_employees                                        │  │
│  └────────────────────────────────────────────────────────┘  │
└──────────────────────────────────────────────────────────────┘
```

---

## 2. Class Diagram

### 2.1 Module Structure

```
┌─────────────────────────────────────────────────────────────┐
│                      FrontAccounting Core                    │
├─────────────────────────────────────────────────────────────┤
│  page()                                                    │
│  start_table()                                             │
│  end_table()                                               │
│  label_cell()                                              │
│  alt_table_row()                                           │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                   ksf_Leave_UI Adapter                      │
├─────────────────────────────────────────────────────────────┤
│  leave.php (Page Controller)                                │
│  ├── leave_navbar()                                        │
│  ├── display_leave_requests()                              │
│  ├── display_pending_requests()                            │
│  └── display_leave_balance()                               │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                  FA_Leave Business Logic                    │
├─────────────────────────────────────────────────────────────┤
│  get_leave_requests(array $filter)                        │
│  get_pending_leave_requests()                              │
│  get_leave_balance(int $empId, string $type, int $year)    │
│  get_employee_name(int $empId)                             │
└─────────────────────────────────────────────────────────────┘
```

### 2.2 Component Relationships

```
                    ┌──────────────────┐
                    │  leave.php       │
                    │  (Page Controller)│
                    └────────┬─────────┘
                             │
              ┌──────────────┼──────────────┐
              │              │              │
              ▼              ▼              ▼
    ┌────────────────┐┌──────────────┐┌──────────────┐
    │ leave_navbar() ││ UI Functions ││ FA_Leave DB  │
    └────────────────┘└──────────────┘└──────────────┘
```

---

## 3. Data Flow

### 3.1 Leave Request Submission Flow

```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│   User      │     │  leave.php  │     │ FA_Leave    │     │  Database   │
│   Browser   │────▶│  Controller │────▶│  Service    │────▶│   (MySQL)   │
└─────────────┘     └─────────────┘     └─────────────┘     └─────────────┘
     │                    │                    │                    │
     │  1. GET /leave     │                    │                    │
     │     ?section=new  │                    │                    │
     │                    │                    │                    │
     │                    │  2. Render Form    │                    │
     │                    │                    │                    │
     │  3. POST Form      │                    │                    │
     │     with data      │                    │                    │
     │                    │  4. Validate       │                    │
     │                    │                    │                    │
     │                    │  5. Insert Record  │                    │
     │                    │                    │  6. INSERT query   │
     │                    │                    │                    │
     │                    │                    │  7. Success/Failure │
     │                    │                    │                    │
     │  8. Confirmation   │                    │                    │
     │     Page           │                    │                    │
     └────────────────────┴────────────────────┴────────────────────┘
```

### 3.2 Leave Balance Query Flow

```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│   User      │     │  leave.php  │     │ FA_Leave    │     │  Database   │
│   Browser   │────▶│  Controller │────▶│  Service    │────▶│   (MySQL)   │
└─────────────┘     └─────────────┘     └─────────────┘     └─────────────┘
     │                    │                    │                    │
     │  GET /leave        │                    │                    │
     │  ?section=balance  │                    │                    │
     │                    │                    │                    │
     │                    │ get_leave_balance()│                    │
     │                    │                    │                    │
     │                    │                    │ SELECT entitlement │
     │                    │                    │ SELECT used        │
     │                    │                    │                    │
     │                    │  Calculate:        │                    │
     │                    │  remaining =      │                    │
     │                    │  entitlement -    │                    │
     │                    │  used             │                    │
     │                    │                    │                    │
     │  4. Display Table  │                    │                    │
     │     with balances  │                    │                    │
     └────────────────────┴────────────────────┴────────────────────┘
```

### 3.3 Approval Flow

```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│   Manager   │     │  leave.php  │     │ FA_Leave    │     │  Database   │
│   Browser   │────▶│  Controller │────▶│  Service    │────▶│   (MySQL)   │
└─────────────┘     └─────────────┘     └─────────────┘     └─────────────┘
     │                    │                    │                    │
     │  GET /leave        │                    │                    │
     │  ?section=pending  │                    │                    │
     │                    │                    │                    │
     │                    │ get_pending_       │                    │
     │                    │   leave_requests() │                    │
     │                    │                    │                    │
     │  Click "Approve"   │                    │                    │
     │  ?approve=123      │                    │                    │
     │                    │  Update status:    │                    │
     │                    │  'approved'        │                    │
     │                    │                    │                    │
     │                    │                    │ UPDATE status     │
     │                    │                    │                    │
     │  Refreshed Queue   │                    │                    │
     │  (request removed) │                    │                    │
     └────────────────────┴────────────────────┴────────────────────┘
```

---

## 4. Database Schema

### 4.1 Core Tables

#### Table: 0_leave_requests
```sql
CREATE TABLE IF NOT EXISTS 0_leave_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    leave_type VARCHAR(50) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    days DECIMAL(5,2) NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    approved_by INT,
    approved_at DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_employee_id (employee_id),
    INDEX idx_status (status),
    INDEX idx_dates (start_date, end_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### Table: 0_leave_balances
```sql
CREATE TABLE IF NOT EXISTS 0_leave_balances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    leave_type VARCHAR(50) NOT NULL,
    year INT NOT NULL,
    entitlement DECIMAL(5,2) DEFAULT 0,
    used DECIMAL(5,2) DEFAULT 0,
    carried_forward DECIMAL(5,2) DEFAULT 0,
    UNIQUE KEY unique_balance (employee_id, leave_type, year),
    INDEX idx_employee_year (employee_id, year)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 4.2 Entity Relationship Diagram

```
┌──────────────────┐       ┌──────────────────┐
│   0_employees    │       │ 0_leave_types   │
├──────────────────┤       ├──────────────────┤
│ id (PK)          │       │ id (PK)          │
│ name             │       │ name             │
│ department_id    │       │ entitlement_days │
│ manager_id (FK)  │       │ accrual_rate     │
└──────────────────┘       └──────────────────┘
        │                          │
        │ 1:N                      │ 1:N
        ▼                          ▼
┌──────────────────┐       ┌──────────────────┐
│ 0_leave_requests │       │ 0_leave_balances │
├──────────────────┤       ├──────────────────┤
│ id (PK)          │       │ id (PK)          │
│ employee_id (FK) │───────┤ employee_id (FK) │
│ leave_type       │       │ leave_type (FK)  │───┐
│ start_date       │       │ year             │   │
│ end_date         │       │ entitlement      │   │
│ days             │       │ used             │   │
│ status           │       └──────────────────┘   │
│ approved_by      │                                 │
└──────────────────┘                                 │
       │                                              │
       │ (FK)                                         │
       ▼                                              │
┌──────────────────┐                                 │
│   0_users        │                                 │
├──────────────────┤                                 │
│ id (PK)          │                                 │
│ employee_id (FK) │─────────────────────────────────┘
│ role             │
└──────────────────┘
```

---

## 5. File Structure

### 5.1 Module Directory Structure

```
ksf_Leave_UI/
├── ProjectDcs/
│   ├── ProjectDcs/
│   │   ├── Business Requirements.md
│   │   ├── Architecture.md
│   │   ├── Functional Requirements.md
│   │   ├── Use Case.md
│   │   ├── Test Plan.md
│   │   └── UAT Plan.md
│   ├── BABOK/
│   ├── UML/
│   └── RTM/
├── includes/
│   ├── leave_ui_functions.inc
│   ├── leave_display_helpers.inc
│   └── leave_navbar.inc
├── pages/
│   └── leave.php           (Main page controller)
├── tests/
│   └── Unit/
│       └── LeaveUITest.php
├── composer.json
└── README.md
```

### 5.2 Key Files Description

| File | Type | Description |
|------|------|-------------|
| `pages/leave.php` | Page Controller | Main page entry point, handles routing and display |
| `includes/leave_db.inc` | Business Logic | Database access functions from FA_Leave module |
| `includes/leave_ui_functions.inc` | UI Adapter | UI-specific helper functions |

---

## 6. Security Architecture

### 6.1 Authentication Integration
- Uses FrontAccounting session management (`$_SESSION["wa_user"]`)
- Employee ID extracted from authenticated user session
- Manager permissions checked via FrontAccounting permission system

### 6.2 Authorization Matrix

| Permission | Code | Description |
|-----------|------|-------------|
| View Leave | `SA_LEAVE` | Basic access to leave management features |
| Approve Leave | `SA_LEAVEAPPROVE` | Authority to approve/reject requests |

### 6.3 Input Validation
- All user inputs are validated before database operations
- SQL injection prevented via `db_escape()` function
- XSS prevention via FrontAccounting UI functions

---

## 7. Error Handling

### 7.1 Error Categories

| Category | Handling | User Feedback |
|----------|----------|---------------|
| Validation Errors | Display inline with form | "Please enter a valid date" |
| Database Errors | Log and display generic message | "An error occurred. Please try again." |
| Authentication Errors | Redirect to login | Session timeout message |
| Authorization Errors | Display access denied | "You don't have permission to..." |

### 7.2 Error Flow

```
┌─────────────┐
│   Error     │
│  Occurs     │
└─────┬───────┘
      │
      ▼
┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│  Log Error  │────▶│  Handle     │────▶│  Display    │
│  to File    │     │  by Type    │     │  Message    │
└─────────────┘     └─────────────┘     └─────────────┘
                          │
          ┌───────────────┼───────────────┐
          ▼               ▼               ▼
    ┌───────────┐   ┌───────────┐   ┌───────────┐
    │ Validation│   │  Database │   │  System   │
    │  Errors   │   │  Errors   │   │  Errors   │
    └───────────┘   └───────────┘   └───────────┘
```

---

## 8. Performance Considerations

### 8.1 Query Optimization
- Indexed columns used in WHERE clauses
- LIMIT clauses on result sets
- Cached calculations for balance totals

### 8.2 Caching Strategy
- Leave balance cached per user per session
- Query results cached with 5-minute TTL
- Static assets cached via browser

### 8.3 Performance Metrics

| Metric | Target | Measurement |
|--------|--------|------------|
| Page Load Time | < 2 seconds | Time to first byte |
| Database Queries | < 500ms | Query execution time |
| Concurrent Users | 100+ | Load testing |

---

## 9. Integration Points

### 9.1 External System Integration

```
┌──────────────────────────────────────────────────────────────┐
│                    ksf_Leave_UI Module                        │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  ┌──────────────┐     ┌──────────────┐     ┌──────────────┐ │
│  │   Payroll    │     │     HRM      │     │   Reporting  │ │
│  │   System     │     │   System     │     │    Tools     │ │
│  └──────────────┘     └──────────────┘     └──────────────┘ │
│                                                              │
└──────────────────────────────────────────────────────────────┘
         │                  │                  │
         ▼                  ▼                  ▼
    Future Phase        Future Phase       Future Phase
```

---

## 10. Deployment Architecture

### 10.1 Module Installation
1. Copy module files to `/modules/ksf_Leave_UI/`
2. Install database tables via install SQL
3. Add menu entries in FA administration
4. Set up permissions for user roles

### 10.2 Configuration Requirements

| Setting | Value | Description |
|---------|-------|-------------|
| `TB_PREF` | `0_` | FrontAccounting table prefix |
| `page_security` | `SA_LEAVE` | Minimum permission level |
| `leave_types` | `['ANNUAL','SICK','PERSONAL']` | Supported leave types |

---

## 11. UML Class Diagram

### 11.1 Component Architecture

```plantuml
@startuml
package "FrontAccounting Core" {
    class Session {
        +wa_user: User
        +employee_id: int
    }
    
    class Permission {
        +check(string $code): bool
    }
}

package "ksf_Leave_UI" {
    page LeavePage {
        -handleRequest()
        -renderNavBar()
        -renderLeaveRequests()
        -renderPendingRequests()
        -renderBalance()
    }
    
    class LeaveRequestDisplay {
        -formatRequestRow()
        -formatStatusBadge()
    }
    
    class LeaveBalanceDisplay {
        -calculateRemaining()
        -formatBalanceRow()
    }
}

package "FA_Leave" {
    class LeaveService {
        +getRequests(array $filter): array
        +getPendingRequests(): array
        +getBalance(int $empId, string $type, int $year): array
    }
    
    class LeaveRepository {
        +findByEmployee(): array
        +findPending(): array
        +findById(): LeaveRequest
    }
}

LeavePage --> LeaveService : Uses
LeavePage --> Session : Authenticates
LeavePage --> Permission : Authorizes

@enduml
```

---

**Document Owner**: KS Fraser Development Team  
**Review Status**: Pending