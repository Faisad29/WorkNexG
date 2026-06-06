# WorkNexG — Product Requirements Document (v3)

**Version:** 3.0  
**Last Updated:** 2026-06-07  
**Product:** WorkNexG — Multi-tenant Workforce Management SaaS  
**Target Market:** KSA (Saudi Arabia) — SME to enterprise

---

## 1. Product Overview

WorkNexG is a cloud-based workforce management platform built for the KSA market. It provides multi-tenant HR, attendance, payroll, compliance, and reporting capabilities. Each subscribing company (tenant) operates in a fully isolated data environment; the platform owner manages all tenants through a Super Admin interface.

### Core Modules
- **Authentication & RBAC** — Sanctum token auth, multi-role support
- **Employee Management** — profiles, onboarding, documents
- **Attendance** — GPS check-in/out, shift scheduling, overrides
- **Leave Management** — requests, approvals, balances
- **Payroll** — generation, approval, salary structures
- **Compliance** — Iqama/Visa tracking, expiry alerts
- **Reports** — cross-module analytics and exports
- **Notifications** — in-app and broadcast messaging
- **Company Settings** — org profile, timezone, integrations

---

## 2. Architecture Summary

| Layer | Technology |
|---|---|
| Framework | Laravel 11 (PHP 8.2+) |
| Auth | Laravel Sanctum — API tokens in localStorage |
| Database | SQLite (dev) / PostgreSQL (prod) |
| Frontend | Blade + vanilla JS (SPA-style, `WN` global object) |
| Multi-tenancy | `TenantContext` singleton + `IdentifyTenant` middleware |
| Queue | Sync (dev) / Redis (prod) |

**Authentication pattern:**  
Tokens are stored in `localStorage` (not cookies). All API calls send `Authorization: Bearer {token}`. Blade pages are unauthenticated shells; client-side JS guards routes via `WN.guardRoute()`.

**Tenant isolation:**  
Every request to tenant-scoped API routes passes through `IdentifyTenant` middleware which sets `TenantContext::orgId()`. Queries are automatically scoped to the current org. Super Admin routes bypass this entirely.

---

## 3. User Types & Onboarding

### 3.1 Self-service Registration
Companies register at `/register`. The first user becomes the Company Admin with full access to their tenant.

### 3.2 Employee Invitations
Company Admin or HR Manager invites employees. Employees receive credentials and log in with the `employee` role.

### 3.3 Platform Admin
Provisioned directly in the database by the platform operator (`admin@worknexg.test`). Cannot self-register. Accesses the platform via `/login` selecting "Platform Admin" as the account type.

---

## 4. Role & Permission Matrix

### 4.1 Role Hierarchy

```
Super Admin  (platform-admin)
│  Platform-level — global access to ALL tenants and ALL features
│  Bypasses tenant isolation entirely
│
└── [Tenant Boundary]
    │
    └── Company Admin  (admin)
        │  Full access to their OWN company only
        │
        ├── HR Manager   (hr-manager)   — Employees, Attendance, Leave, Compliance
        ├── Supervisor   (supervisor)   — Team Attendance, Team Leave (read + approve)
        ├── Accountant   (accountant)   — Payroll, Finance Reports
        └── Employee     (employee)     — Own Attendance, Own Payslip, Own Documents
```

### 4.2 Role Slugs

| Role | Slug | Scope | Login Redirect |
|---|---|---|---|
| Super Admin | `platform-admin` | Platform (global) | `/super-admin/organizations` |
| Company Admin | `admin` | Tenant | `/dashboard` |
| HR Manager | `hr-manager` | Tenant | `/employees` |
| Supervisor | `supervisor` | Tenant | `/attendance` |
| Accountant | `accountant` | Tenant | `/payroll` |
| Employee | `employee` | Tenant | `/my-attendance` |

### 4.3 Super Admin Capabilities

Super Admin has unrestricted access to:

| Capability | Detail |
|---|---|
| View all companies | Paginated list with search and status filter |
| Add company | Name, country, timezone |
| Edit company | Name, country, timezone |
| Block company | Sets `status = 'blocked'`; tenant users cannot log in |
| Unblock company | Sets `status = 'active'` |
| Delete company | Guarded — rejected if company has employees |
| Platform reports | Cross-tenant visibility (future) |

**Implementation:** `IsPlatformAdmin` middleware verifies `roles.slug = 'platform-admin'` without requiring a `TenantContext`. Routes prefixed `/api/super-admin/*`.

### 4.4 Company Admin Capabilities

Company Admin has full access within their own tenant:

- Company profile settings
- All employee lifecycle operations (add/edit/block/delete)
- All attendance operations including overrides
- Leave approvals and balance management
- Payroll generation and approval
- Compliance document management
- Notification broadcasting
- Role and permission configuration
- Report access (all modules)

### 4.5 Scoped Role Permissions

See `docs/RoleMatrix.md` for the full permission matrix across all modules.

**Summary of scoped role access:**

| Module | HR Manager | Supervisor | Accountant | Employee |
|---|---|---|---|---|
| Employees | Full CRUD | View team | ❌ | Own only |
| Attendance | Full + override | Team view/approve | ❌ | Own only |
| Leave | Full + approve | Team approve | Submit own | Submit own |
| Payroll | View only | ❌ | Full | Own payslip |
| Compliance | Full | View | ❌ | Own docs |
| Reports | All | Team only | Finance | ❌ |
| Notifications | Send/manage | ❌ | ❌ | Receive |
| Settings | View only | ❌ | ❌ | ❌ |

### 4.6 Role Storage

- Roles are stored in the `roles` table. System roles have `org_id = NULL`.
- User-role assignments are in `user_roles` (pivot) and always carry an `org_id`.
- The `role_slug` field in the login API response is the canonical role identifier used by the JS layer (`WN.role()`).

---

## 5. API Design

### 5.1 Authentication Flow

```
POST /api/auth/register   — Create org + admin user
POST /api/auth/login      — Returns { token, user: { role_slug, redirect_to, ... } }
POST /api/auth/logout     — Revokes current token
```

The login response includes `redirect_to` (computed from `role_slug`) which the login page uses to navigate the user to the correct section.

### 5.2 Tenant-scoped Routes

All tenant routes require:
- `auth:sanctum` — valid bearer token
- `tenant` — sets `TenantContext` from `X-Organization-Id` header or user's primary org
- `permission:{name}` — optional per-route permission gate

### 5.3 Super Admin Routes

```
GET    /api/super-admin/organizations
POST   /api/super-admin/organizations
GET    /api/super-admin/organizations/{id}
PUT    /api/super-admin/organizations/{id}
DELETE /api/super-admin/organizations/{id}
POST   /api/super-admin/organizations/{id}/block
POST   /api/super-admin/organizations/{id}/unblock
```

Middleware: `auth:sanctum` + `platform-admin` only. No tenant middleware.

---

## 6. Frontend Architecture

### 6.1 Blade + JS Hybrid

Pages are Blade shells with no server-rendered data. All content is loaded via JS `WN.api()` calls after the page mounts.

### 6.2 WN Global Object (`resources/views/partials/scripts.blade.php`)

```javascript
WN.token()      // Bearer token from localStorage
WN.user()       // Parsed user object from localStorage
WN.role()       // role_slug || role || 'employee'
WN.api(method, url, body)  // Authenticated fetch wrapper
WN.guardRoute() // Redirect to /login if no token
```

### 6.3 Role-based Sidebar

Sidebar items use `data-roles="role-slug"` HTML attributes. `applyRoleVisibility()` in `scripts.blade.php` hides/shows elements based on `WN.role()` after `DOMContentLoaded`.

```html
<a href="/super-admin/organizations" data-roles="platform-admin">Companies</a>
<a href="/employees"                 data-roles="admin hr">Employees</a>
<a href="/my-attendance"             data-roles="employee">My Attendance</a>
```

---

## 7. Key Business Rules

1. **Tenant isolation is enforced at the middleware layer.** No cross-tenant data leaks even if an API endpoint omits a scope — `TenantContext` is set globally per request.
2. **Super Admin bypasses all tenant context.** `IsPlatformAdmin` does not require or set `TenantContext`.
3. **Companies with employees cannot be deleted.** The delete endpoint returns HTTP 422 if `employees_count > 0`.
4. **A blocked company's users cannot log in.** The login service checks `organization.status = 'active'` (to be enforced — currently blocks redirect, future: enforce at login).
5. **Role assignment always carries an `org_id`** in `user_roles`. A user can have different roles in different companies.
6. **`platform-admin` role has `org_id = NULL`** in the `roles` table (it is a system-level role).

---

## 8. Demo Credentials (Post-seed)

| Role | Email | Password | Redirect |
|---|---|---|---|
| Super Admin | `admin@worknexg.test` | `password` | `/super-admin/organizations` |
| Supervisor | `supervisor@worknexg.test` | `password` | `/attendance` |
| Employee | `employee@worknexg.test` | `password` | `/my-attendance` |

Run seed: `php artisan migrate:fresh --seed`

---

## 9. Planned Enhancements (Backlog)

- [ ] Block company → also reject login attempts for that org's users
- [ ] Super Admin → impersonate any company admin
- [ ] Custom role builder UI per tenant
- [ ] Multi-org user dashboard (users belonging to multiple companies)
- [ ] Webhook / integration API for payroll systems
- [ ] Arabic language (RTL) support
- [ ] Mobile app (React Native)
