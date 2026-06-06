# WorkNexG API Documentation

**Version:** 1.0  
**Base URL:** `http://localhost:8000/api`  
**Authentication:** Laravel Sanctum (Bearer Token)  
**Content-Type:** `application/json`

---

## Authentication

All protected endpoints require the header:
```
Authorization: Bearer {token}
```

Tokens are obtained via the Login or Register endpoints.

### Demo Login (After `php artisan migrate:fresh --seed`)

Use the seeded organization admin account for quick testing:

```json
{
  "email": "admin@worknexg.test",
  "password": "password"
}
```

Notes:
- Send organization context for protected routes using `X-Organization-Id` or `X-Org-Id`.
- Users are global and can belong to multiple organizations via `organization_users`.

---

## 1. Auth Endpoints

### POST /api/auth/register
**Description:** Register a new organization and initial admin user.  
**Auth Required:** No

**Request Body:**
```json
{
  "company_name": "My Company Ltd",
  "name": "Admin User",
  "email": "admin@company.com",
  "phone": "+966500000000",
  "password": "password123",
  "password_confirmation": "password123",
  "country": "KSA",
  "timezone": "Asia/Riyadh"
}
```

**Validation Rules:**
| Field | Rules |
|-------|-------|
| company_name | required, string, max:255 |
| name | required, string, max:255 |
| email | required, email, unique:users |
| phone | nullable, string, max:30 |
| password | required, min:8, confirmed |
| country | sometimes, string, max:100 |
| timezone | sometimes, string, max:50 |

**201 Success:**
```json
{
  "data": {
    "organization": { "id": "uuid", "name": "My Company Ltd", "country": "KSA", "code": "my-company-ltd-ab12cd" },
    "company": { "id": "uuid", "name": "My Company Ltd", "country": "KSA", "code": "my-company-ltd-ab12cd" },
    "user": { "id": "uuid", "email": "admin@company.com", "status": "active" },
    "token": "1|abc123..."
  }
}
```

**422 Validation Error:**
```json
{
  "message": "The email has already been taken.",
  "errors": { "email": ["The email has already been taken."] }
}
```

---

### POST /api/auth/login
**Description:** Authenticate and receive a token.  
**Auth Required:** No

**Request Body:**
```json
{
  "email": "admin@company.com",
  "password": "password123"
}
```

**Validation Rules:**
| Field | Rules |
|-------|-------|
| email | required, email |
| password | required, string |

**200 Success:**
```json
{
  "data": {
    "user": { "id": "uuid", "name": "Admin User", "email": "admin@company.com", "status": "active" },
    "token": "2|xyz789..."
  }
}
```

**401 Unauthorized:**
```json
{ "message": "Invalid credentials." }
```

---

### POST /api/auth/logout
**Description:** Revoke current token.  
**Auth Required:** Yes

**200 Success:**
```json
{ "message": "logged_out" }
```

---

### GET /api/auth/me
**Description:** Get authenticated user profile.  
**Auth Required:** Yes

**200 Success:**
```json
{
  "data": {
    "id": "uuid",
    "name": "Admin User",
    "email": "admin@company.com",
    "status": "active",
    "last_login_at": "2026-06-06T10:00:00.000000Z"
  }
}
```

---

## 2. Sites Endpoints

### GET /api/sites
**Description:** List all active sites for the tenant.  
**Auth Required:** Yes  
**Role Required:** workforce.access

**200 Success:**
```json
{
  "data": {
    "data": [
      {
        "id": "uuid",
        "org_id": "uuid",
        "name": "Riyadh Main Site",
        "latitude": "24.71360000",
        "longitude": "46.67530000",
        "radius_meters": 150,
        "is_active": true
      }
    ],
    "total": 1, "per_page": 25, "current_page": 1
  }
}
```

---

### POST /api/sites
**Description:** Create a new geofenced work site.  
**Auth Required:** Yes  
**Role Required:** workforce.access

**Request Body:**
```json
{
  "name": "Jeddah Branch",
  "latitude": 21.4858,
  "longitude": 39.1925,
  "radius_meters": 200,
  "is_active": true
}
```

**Validation Rules:**
| Field | Rules |
|-------|-------|
| name | required, string, max:255 |
| latitude | required, numeric |
| longitude | required, numeric |
| radius_meters | required, integer, min:1 |
| is_active | sometimes, boolean |

**201 Success:** Site object.

---

## 3. Employee Endpoints

### GET /api/employees
**Description:** List employees with optional search/filter.  
**Auth Required:** Yes  
**Role Required:** workforce.access

**Query Parameters:**
| Param | Type | Description |
|-------|------|-------------|
| search | string | Search by name, code, or email |
| status | string | Filter by: active, inactive, suspended |
| page | integer | Pagination page |

**200 Success:**
```json
{
  "data": {
    "data": [
      {
        "id": "uuid",
        "employee_code": "EMP-0001",
        "full_name": "Ahmed Al-Qahtani",
        "job_title": "Supervisor",
        "salary_type": "monthly",
        "base_salary": "8000.00",
        "status": "active",
        "site": { "id": "uuid", "name": "Riyadh Main Site" }
      }
    ],
    "total": 1, "per_page": 25
  }
}
```

---

### POST /api/employees
**Description:** Create a new employee record.  
**Auth Required:** Yes

**Request Body:**
```json
{
  "site_id": "uuid",
  "employee_code": "EMP-0002",
  "full_name": "Sara Al-Otaibi",
  "phone": "+966501234567",
  "email": "sara@company.com",
  "nationality": "Saudi",
  "job_title": "HR Manager",
  "role": "hr",
  "salary_type": "monthly",
  "base_salary": 9500.00,
  "join_date": "2026-01-01",
  "contract_end_date": "2027-01-01",
  "status": "active"
}
```

**Validation Rules:**
| Field | Rules |
|-------|-------|
| employee_code | required, string, max:50, unique per company |
| full_name | required, string, max:255 |
| salary_type | required, in:monthly,daily,hourly,project_based |
| base_salary | required, numeric, min:0 |
| status | required, in:active,inactive,suspended |

**201 Success:** Employee object.

---

### GET /api/employees/{id}
**Description:** Get full employee details including documents and attendance.  
**Auth Required:** Yes

**200 Success:** Employee with relations.

---

### PATCH /api/employees/{id}
**Description:** Update employee fields (partial).  
**Auth Required:** Yes

**200 Success:** Updated employee object.

---

### DELETE /api/employees/{id}
**Description:** Soft-deactivate an employee (sets status to inactive).  
**Auth Required:** Yes

**200 Success:**
```json
{ "message": "Employee deactivated." }
```

---

## 4. Attendance Endpoints

### GET /api/attendance
**Description:** List attendance records.  
**Auth Required:** Yes

**Query Parameters:**
| Param | Type | Description |
|-------|------|-------------|
| date | date | Filter by specific date (YYYY-MM-DD) |
| month | string | Filter by month (YYYY-MM) |
| employee_id | uuid | Filter by employee |
| status | string | present, absent, late, half_day |

**200 Success:** Paginated attendance records.

---

### POST /api/attendance/check-in
**Description:** Record employee check-in with GPS validation.  
**Auth Required:** Yes

**Request Body:**
```json
{
  "employee_id": "uuid",
  "site_id": "uuid",
  "attendance_date": "2026-06-06",
  "check_in_time": "2026-06-06T08:00:00",
  "check_in_latitude": 24.7136,
  "check_in_longitude": 46.6753,
  "idempotency_key": "unique-key-123",
  "override_request_id": null
}
```

**Validation Rules:**
| Field | Rules |
|-------|-------|
| employee_id | required, uuid, exists:employees |
| site_id | required, uuid, exists:sites |
| attendance_date | required, date |
| check_in_time | required, date |
| check_in_latitude | required, numeric |
| check_in_longitude | required, numeric |
| idempotency_key | nullable, string, max:64 |

**201 Success:** Attendance record object.

**422 Outside Radius:**
```json
{ "message": "Employee is outside the allowed site radius." }
```

---

### POST /api/attendance/check-out
**Description:** Record employee check-out.  
**Auth Required:** Yes

**Request Body:**
```json
{
  "employee_id": "uuid",
  "attendance_date": "2026-06-06",
  "check_out_time": "2026-06-06T17:00:00",
  "check_out_latitude": 24.7136,
  "check_out_longitude": 46.6753,
  "work_hours": 9.0,
  "overtime_hours": 1.0
}
```

**200 Success:** Updated attendance record.

---

### POST /api/attendance/overrides
**Description:** Submit a manual attendance override request.  
**Auth Required:** Yes

**Request Body:**
```json
{
  "employee_id": "uuid",
  "attendance_date": "2026-06-05",
  "reason": "Device was offline during check-in"
}
```

**201 Success:** Override request object with status `override_request`.

---

### GET /api/attendance/overrides
**Description:** List pending override requests.  
**Auth Required:** Yes

**200 Success:** Paginated override requests.

---

### POST /api/attendance/overrides/{id}/approve
**Description:** Approve an override request.  
**Auth Required:** Yes  
**Role Required:** supervisor or admin

**200 Success:** Updated override request with status `approved`.

---

### POST /api/attendance/overrides/{id}/reject
**Description:** Reject an override request.  
**Auth Required:** Yes

**200 Success:** Updated override request with status `rejected`.

---

## 5. Payroll Endpoints

### GET /api/payroll
**Description:** List all payroll runs for the tenant.  
**Auth Required:** Yes

**200 Success:** Paginated payroll records.

---

### POST /api/payroll/generate
**Description:** Generate payroll for a given month/year. Calculates salary for all active employees based on attendance.  
**Auth Required:** Yes  
**Role Required:** admin, accountant

**Request Body:**
```json
{
  "month": 6,
  "year": 2026
}
```

**Validation Rules:**
| Field | Rules |
|-------|-------|
| month | required, integer, between:1,12 |
| year | required, integer, min:2024 |

**201 Success:**
```json
{
  "data": {
    "id": "uuid",
    "month": 6,
    "year": 2026,
    "total_employees": 5,
    "total_amount": "42500.00",
    "status": "generated",
    "generated_at": "2026-06-06T10:00:00.000000Z"
  }
}
```

**409 Conflict (already locked):**
```json
{ "message": "Payroll is locked and cannot be recalculated." }
```

---

### GET /api/payroll/my-items
**Description:** Get payroll items for the authenticated user's employee record (matched by email).  
**Auth Required:** Yes

**200 Success:** Paginated payroll items with payroll period.

---

### POST /api/payroll/{id}/approve
**Description:** Approve a generated payroll.  
**Auth Required:** Yes  
**Role Required:** admin

**200 Success:** Payroll with status `approved`.

---

### POST /api/payroll/{id}/lock
**Description:** Lock an approved payroll (prevents further modification).  
**Auth Required:** Yes

**200 Success:** Payroll with status `locked`.

---

### POST /api/payroll/{id}/pay
**Description:** Mark a locked payroll as paid.  
**Auth Required:** Yes

**200 Success:** Payroll with status `paid`.

---

## 6. Leave Endpoints

### GET /api/leave
**Description:** List leave requests.  
**Auth Required:** Yes

**Query Parameters:**
| Param | Type | Description |
|-------|------|-------------|
| status | string | pending, approved, rejected |

---

### POST /api/leave
**Description:** Submit a leave request.  
**Auth Required:** Yes

**Request Body:**
```json
{
  "employee_id": "uuid",
  "leave_type": "annual",
  "start_date": "2026-07-01",
  "end_date": "2026-07-07",
  "reason": "Family vacation"
}
```

**Validation Rules:**
| Field | Rules |
|-------|-------|
| employee_id | required, uuid, exists:employees |
| leave_type | required, string, max:50 |
| start_date | required, date |
| end_date | required, date, after_or_equal:start_date |
| reason | nullable, string |

**201 Success:** Leave request with status `pending`.

---

### POST /api/leave/{id}/approve
**Auth Required:** Yes | **Role:** supervisor, hr, admin

**200 Success:** Leave request with status `approved`.

---

### POST /api/leave/{id}/reject
**Auth Required:** Yes

**200 Success:** Leave request with status `rejected`.

---

## 7. Compliance / Documents Endpoints

### GET /api/documents
**Description:** List compliance documents.  
**Auth Required:** Yes

**Query Parameters:**
| Param | Type | Description |
|-------|------|-------------|
| type | string | iqama, visa, passport, contract, certificate |
| employee_id | uuid | Filter by employee |

**200 Success:** Paginated document records with expiry status.

---

### POST /api/documents
**Description:** Upload/register a compliance document.  
**Auth Required:** Yes

**Request Body:**
```json
{
  "employee_id": "uuid",
  "type": "iqama",
  "document_number": "2380001234",
  "file_url": "https://storage.example.com/docs/iqama.pdf",
  "issue_date": "2024-01-01",
  "expiry_date": "2026-12-31",
  "metadata": { "issuing_authority": "MOI" }
}
```

**Validation Rules:**
| Field | Rules |
|-------|-------|
| employee_id | required, uuid, exists:employees |
| type | required, in:iqama,visa,passport,contract,certificate |
| document_number | nullable, string, max:100 |
| expiry_date | nullable, date |

**201 Success:** Document record.

---

## 8. Notification Endpoint

### POST /api/notifications/send
**Description:** Send a notification via email, SMS, or WhatsApp.  
**Auth Required:** Yes

**Request Body:**
```json
{
  "channel": "email",
  "recipient": "employee@company.com",
  "message": "Your Iqama expires in 7 days. Please renew."
}
```

**Validation Rules:**
| Field | Rules |
|-------|-------|
| channel | required, in:email,sms,whatsapp |
| recipient | required, string |
| message | required, string |

**200 Success:**
```json
{ "message": "queued" }
```

---

## 9. Subscriptions Endpoint

### GET /api/subscriptions
**Auth Required:** Yes

**200 Success:** Paginated subscriptions with plan details.

---

### POST /api/subscriptions
**Description:** Subscribe company to a billing plan.  
**Auth Required:** Yes

**Request Body:**
```json
{
  "plan_id": "uuid",
  "start_date": "2026-01-01",
  "end_date": "2027-01-01"
}
```

**201 Success:** Subscription record.

---

## Standard Error Responses

**401 Unauthenticated:**
```json
{ "message": "Unauthenticated." }
```

**403 Forbidden:**
```json
{ "message": "Forbidden." }
```

**404 Not Found:**
```json
{ "message": "No query results for model [...]" }
```

**409 Conflict:**
```json
{ "message": "Payroll is locked and cannot be recalculated." }
```

**422 Validation Failed:**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": ["Validation error message."]
  }
}
```

**500 Server Error:**
```json
{ "message": "Server Error" }
```

---

## Roles and Permissions

| Role | Access Level |
|------|-------------|
| admin | Full access to all modules |
| hr | Employees, Compliance, Leave, Attendance |
| supervisor | Attendance view, override approval |
| accountant | Payroll only |
| employee | My records only (attendance, payroll, documents) |

All API routes require the `workforce.access` permission which is granted to any authenticated tenant user with a valid `role`.
