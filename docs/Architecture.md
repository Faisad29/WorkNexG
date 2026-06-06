# WorkNexG — Architecture Documentation

## Overview

WorkNexG is a multi-tenant Workforce Management SaaS built on **Laravel 11** with a Domain-Driven Design (DDD) structure. It targets the KSA market with support for Arabic timezone defaults, Iqama/Visa compliance tracking, and localized payroll rules.

---

## Technology Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 11 (PHP 8.2+) |
| API Auth | Laravel Sanctum (token-based) |
| Database | SQLite (dev) / PostgreSQL (prod) |
| Queue | Sync (dev) / Redis (prod) |
| Cache | File (dev) / Redis (prod) |
| Frontend | Blade + vanilla JS (SPA-style) |
| Build | Vite |

---

## Domain Structure

```
app/
├── Domain/
│   ├── Auth/           # User model, AuthService, login/register
│   ├── Attendance/     # Check-in/out, GPS validation, overrides
│   ├── Audit/          # AuditLog model + Observers for all entities
│   ├── Billing/        # Plans, Subscriptions
│   ├── Company/        # Company (Tenant) model
│   ├── Compliance/     # Documents, expiry tracking, alerts
│   ├── Employee/       # Employee CRUD
│   ├── Leave/          # Leave requests, approval
│   ├── Notification/   # Email/SMS/WhatsApp abstraction
│   ├── Payroll/        # Payroll generation, approval workflow
│   ├── Shared/         # TenantContext, BelongsToCompany trait
│   └── Site/           # Work site geofencing
├── Http/Middleware/    # IdentifyTenant, SetTenantContext
├── Policies/           # Employee, Payroll, Leave policies
└── Providers/          # AppServiceProvider, AuthServiceProvider, EventServiceProvider

modules/
├── Auth/               # Role/Permission models + CheckPermission middleware
└── Tenant/             # Modules-layer alias models
```

---

## Multi-Tenancy Design

**Approach:** Shared database, row-level tenant isolation.

**TenantContext** (`App\Domain\Shared\TenantContext`) is a singleton bound to the container. Every request through the `tenant` middleware sets the `org_id` from the authenticated user.

**BelongsToCompany** trait adds a global Eloquent scope to all tenant-scoped models:
```php
static::addGlobalScope('company', function (Builder $builder): void {
    $builder->where('org_id', app(TenantContext::class)->companyId());
});
```

Models using this trait: `Employee`, `Site`, `AttendanceRecord`, `Payroll`, `PayrollItem`, `LeaveRequest`, `Document`, `AuditLog` (manual), `Subscription`.

---

## Authentication Flow

```
POST /api/auth/login
  → AuthController → AuthService::login()
  → Validates credentials + user_type match
  → Creates Sanctum token with ability matching user_type
  → Returns {user, token}

API Request
  → auth:sanctum middleware validates token
  → tenant middleware sets TenantContext from user.org_id
  → permission middleware checks user.hasPermission()
  → Controller → Service → Model
```

---

## Payroll Calculation Engine

Salary types supported:
- **Monthly**: base + overtime + bonuses - deductions
- **Daily**: daily_rate × attendance_days + overtime - deductions
- **Hourly**: hourly_rate × total_hours + overtime - deductions
- **Project-Based**: project_amount + bonuses - penalties

All calculations use **integer cents** internally to avoid floating-point errors:
```php
$cents = (int) round($amount * 100);
$amount = number_format($cents / 100, 2, '.', '');
```

Payroll workflow: `draft → generated → approved → locked → paid`

---

## Attendance & GPS Validation

Check-in validates GPS coordinates using the **Haversine formula**:
```
distance = 2R × arcsin(√(sin²(Δlat/2) + cos(lat1)cos(lat2)sin²(Δlon/2)))
```
If `distance > site.radius_meters` and no approved override exists → 422 abort.

Override workflow: `override_request → approved | rejected`

---

## Event System

| Event | Listeners |
|-------|-----------|
| AttendanceMarked | RecomputeAttendanceMetrics |
| PayrollGenerated | QueuePayrollNotification |
| DocumentExpiring | SendDocumentExpiryNotification |
| NotificationSent | PersistNotificationAudit |

---

## Audit Logging

All model mutations (created/updated/deleted) are automatically captured by Eloquent Observers registered in `AppServiceProvider`. Observers extend `BaseAuditObserver` with silent catch to never break the main flow.

---

## Scheduled Jobs

Defined in `routes/console.php`:
```php
Schedule::job(new ProcessComplianceExpiryAlertsJob())->dailyAt('08:00');
```

Checks documents expiring in 30, 15, and 7 days and dispatches `DocumentExpiring` events.

---

## Security Design

- **RBAC**: `user.role` field + `hasPermission()` via `CheckPermission` middleware
- **Tenant isolation**: Global Eloquent scope on all tenant models
- **Mass assignment**: All models use explicit `$fillable`
- **Tokens**: Sanctum short-lived tokens; `ability` array matches `user_type`
- **Validation**: All inputs validated via FormRequest classes
- **Passwords**: Bcrypt via `Hash::make()`
- **Audit trail**: Every create/update/delete logged to `audit_logs`
