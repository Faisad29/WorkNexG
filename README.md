# Workforce Management System for KSA

Laravel modular monolith scaffold for a multi-tenant workforce management SaaS.

## Modules

- Tenant and auth
- Company and site management
- Employee management
- Attendance
- Payroll
- Leave
- Compliance
- Audit
- Notifications
- Billing

## Architecture Rules

- Every tenant-owned table includes `org_id`.
- Controllers stay thin and delegate to services.
- Request validation lives in form request classes.
- Salary is deterministic and calculated from stored inputs.
- Critical actions are written to the audit log.

## Suggested Runtime Stack

- Laravel 11+
- PostgreSQL
- Redis queues
- Laravel Sanctum or JWT
