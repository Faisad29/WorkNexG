# WorkNexG PRD - Implementation Ready

## 1. Product Summary

WorkNexG is a multi-tenant workforce management SaaS for Saudi SMEs. It replaces Excel and WhatsApp-based HR operations with employee management, attendance capture, payroll automation, leave workflows, compliance alerts, and audit logging.

## 2. Product Principles

- Multi-tenant by default.
- All operational data belongs to a company.
- Salary is calculated from rules, not manually edited as the source of truth.
- All important actions are auditable.
- Mobile-first and Arabic-first, with English support.
- Keep MVP simple and expand compliance later.

## 3. User Model

Use a hybrid user model inside one authentication system.

- Platform users: global admins used by the SaaS owner only.
- Tenant users: users tied to one company, including HR, admin, supervisor, accountant, and employee accounts.

Implementation rule:

- Users must have `user_type` = `platform` or `tenant`.
- Platform users have `org_id = null`.
- Tenant users must always have a valid `org_id`.

Authorization rule:

- Platform users can manage companies, subscriptions, billing, and system monitoring.
- Tenant users can only access data within their company.

## 4. Core Modules

### 4.1 Employee Management

Features:

- Add, edit, deactivate, and view employees.
- Store employee identity, role, site assignment, salary type, and contract dates.
- Store employee documents.

Required employee fields:

- org_id
- employee_code
- full_name
- phone
- email
- nationality
- job_title
- role
- salary_type
- base_salary
- join_date
- contract_end_date
- status

### 4.2 Sites and Geofencing

Sites must be first-class records.

Required site fields:

- org_id
- name
- latitude
- longitude
- radius_meters
- is_active

Attendance check-in must validate GPS distance against the assigned site radius when geofencing is enabled.

### 4.3 Attendance

Attendance methods:

- GPS check-in/check-out
- QR-based site attendance
- Supervisor manual override
- Offline sync with conflict handling

Attendance status values:

- present
- absent
- late
- half_day
- overtime
- adjusted
- rejected

Required attendance fields:

- org_id
- employee_id
- site_id
- attendance_date
- check_in_time
- check_out_time
- check_in_latitude
- check_in_longitude
- check_out_latitude
- check_out_longitude
- status
- work_hours
- overtime_hours
- is_manual_override
- sync_status

Attendance rules:

- Prevent check-in outside the allowed radius unless a supervisor override is applied.
- Auto-close a missing checkout after a configurable number of hours.
- Deduplicate repeated attendance events.
- Flag suspicious GPS behavior.
- Log every attendance edit.

### 4.4 Payroll

Payroll is rule-based and calculated from attendance, salary type, deductions, bonuses, and leave.

Payroll status values:

- draft
- generated
- under_review
- approved
- locked
- paid
- cancelled

Payroll workflow:

- HR generates payroll for a month.
- System calculates line items for each employee.
- HR reviews results.
- Admin approves.
- Approved payroll becomes locked.
- Export becomes available after approval.

Salary types:

- monthly
- daily
- hourly
- project_based

Calculation rules:

- Monthly: base salary + overtime + bonus - deductions.
- Daily: daily rate x present days + overtime - deductions.
- Hourly: hourly rate x total hours + overtime - deductions.
- Project based: project amount - penalties + bonuses.

Important rule:

- Do not store final salary as a manually edited value. Store inputs and calculate the final result.

### 4.5 Leave

Leave status values:

- pending
- approved
- rejected
- cancelled

Leave workflow:

- Employee requests leave.
- HR reviews.
- Manager or admin approves or rejects.
- Approved leave affects payroll and attendance.

### 4.6 Documents and Compliance

Use one unified documents model.

Document types:

- iqama
- visa
- passport
- contract
- certificate

Required document fields:

- org_id
- employee_id
- type
- document_number
- file_url
- issue_date
- expiry_date
- metadata

Compliance rules for MVP:

- Track iqama expiry.
- Track visa expiry.
- Track passport expiry.
- Send alerts at 30, 15, and 7 days before expiry.

### 4.7 Notifications

Supported channels:

- WhatsApp
- SMS
- Email

Primary MVP channel:

- WhatsApp

Notification events:

- attendance confirmation
- leave status changes
- document expiry alerts
- payroll status changes

### 4.8 Audit

Use one canonical audit log table.

Required audit fields:

- org_id
- user_id
- action
- entity_type
- entity_id
- old_data
- new_data
- ip_address
- created_at

Audit rule:

- Every create, update, delete, approval, login, and manual override must be logged.

## 5. MVP Scope

Build first:

- Employee management
- Sites and geofencing
- GPS and QR attendance
- Basic payroll
- Leave workflow
- Compliance expiry alerts
- WhatsApp notifications
- Audit logging
- Admin dashboard

Defer to later phases:

- Advanced analytics
- Predictive insights
- Government integrations
- SAP and Oracle integrations
- Advanced compliance automation

## 6. Phase Plan

### Phase 1

- Core employee management
- Attendance capture
- Payroll generation
- Leave workflow
- Compliance reminders
- Audit trail

### Phase 2

- Saudization tracking
- Working hour rule enforcement
- WPS export support
- Advanced compliance dashboards

### Phase 3

- ERP integrations
- Government integrations
- Fraud detection
- Workforce prediction

## 7. Non-Functional Requirements

- Arabic and English support.
- RTL support.
- Mobile-first UX.
- Offline attendance capability.
- Secure authentication.
- Role-based access control.
- Multi-tenant isolation.
- Audit logs for critical actions.
- Scalable architecture.

## 8. Data Ownership Rules

- Every operational record belongs to exactly one company.
- `org_id` is mandatory on tenant-owned tables.
- Platform-only tables must not mix with tenant-scoped operational data.
- Site, employee, attendance, leave, payroll, document, notification, and audit records must be tenant-scoped.

## 9. Suggested Core Tables

- companies
- users
- roles
- permissions
- role_permissions
- user_roles
- sites
- employees
- employee_documents
- attendance_records
- shifts
- employee_shifts
- payrolls
- payroll_items
- leave_requests
- documents
- notifications
- audit_logs
- subscriptions
- plans

## 10. Key Business Rules

- Employees cannot check in outside the allowed site radius unless overridden.
- Payroll must be generated from attendance and rules, not by manual total entry.
- Attendance edits require an audit log entry.
- Payroll cannot be approved without review.
- Expiring documents must trigger alerts.
- Suspicious attendance events must be flagged.

## 11. Success Criteria for MVP

- HR can add and manage employees.
- Employees can check in and out from mobile.
- Payroll can be generated for a month with calculated totals.
- Leave requests can be approved or rejected.
- Document expiry alerts are sent on time.
- Every critical action is logged.

## 12. Implementation Note

The system should be built as a modular monolith so it can scale later without splitting into microservices too early. Keep the initial release simple, reliable, and focused on reducing HR workload.