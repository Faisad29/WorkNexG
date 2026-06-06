# WorkNexG — Permission Matrix

> **Key:** ✅ Full access · 👁 View only · ✏️ Create/Edit (no delete) · ❌ No access · 🔒 Own records only

All scoped roles (HR Manager → Employee) operate **within their own company tenant only**.  
Super Admin bypasses tenant isolation and has global platform-level access.

---

## Platform Management

| Permission | Super Admin | Company Admin | HR Manager | Supervisor | Accountant | Employee |
|---|:---:|:---:|:---:|:---:|:---:|:---:|
| View all companies | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Add company | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Edit company | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Block / Unblock company | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Delete company | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Platform-wide reports | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |

---

## Roles & Permissions Management

| Permission | Super Admin | Company Admin | HR Manager | Supervisor | Accountant | Employee |
|---|:---:|:---:|:---:|:---:|:---:|:---:|
| View roles | ✅ | ✅ | 👁 | ❌ | ❌ | ❌ |
| Create / Edit roles | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Delete roles | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Assign role to user | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Configure permissions | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |

---

## Employee Management

| Permission | Super Admin | Company Admin | HR Manager | Supervisor | Accountant | Employee |
|---|:---:|:---:|:---:|:---:|:---:|:---:|
| View employee list | ✅ | ✅ | ✅ | 👁 | ❌ | ❌ |
| Add employee | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| Edit employee profile | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| Block / Deactivate employee | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| Delete employee | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Import / Export employees | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| View own profile | ✅ | ✅ | ✅ | ✅ | ✅ | 🔒 |

---

## Attendance Management

| Permission | Super Admin | Company Admin | HR Manager | Supervisor | Accountant | Employee |
|---|:---:|:---:|:---:|:---:|:---:|:---:|
| View all attendance | ✅ | ✅ | ✅ | 👁 (team) | ❌ | ❌ |
| Check-in / Check-out | ✅ | ✅ | ✅ | ✅ | ❌ | 🔒 |
| Override attendance record | ✅ | ✅ | ✅ | ✏️ | ❌ | ❌ |
| Configure shifts / sites | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| GPS validation rules | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| Attendance reports | ✅ | ✅ | ✅ | 👁 (team) | ❌ | 🔒 |
| View own attendance | ✅ | ✅ | ✅ | ✅ | ✅ | 🔒 |

---

## Leave Management

| Permission | Super Admin | Company Admin | HR Manager | Supervisor | Accountant | Employee |
|---|:---:|:---:|:---:|:---:|:---:|:---:|
| View all leave requests | ✅ | ✅ | ✅ | 👁 (team) | ❌ | ❌ |
| Submit leave request | ✅ | ✅ | ✅ | ✅ | ✅ | 🔒 |
| Approve / Reject leave | ✅ | ✅ | ✅ | ✏️ (team) | ❌ | ❌ |
| Configure leave types | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| View leave balances | ✅ | ✅ | ✅ | 👁 (team) | ❌ | 🔒 |
| Adjust leave balances | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |

---

## Payroll Management

| Permission | Super Admin | Company Admin | HR Manager | Supervisor | Accountant | Employee |
|---|:---:|:---:|:---:|:---:|:---:|:---:|
| View payroll records | ✅ | ✅ | 👁 | ❌ | ✅ | ❌ |
| Generate payroll | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ |
| Approve / Reject payroll | ✅ | ✅ | ❌ | ❌ | ✏️ | ❌ |
| Configure salary structures | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ |
| Export payroll | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ |
| View own payslip | ✅ | ✅ | ✅ | ✅ | ✅ | 🔒 |

---

## Compliance Management

| Permission | Super Admin | Company Admin | HR Manager | Supervisor | Accountant | Employee |
|---|:---:|:---:|:---:|:---:|:---:|:---:|
| View compliance records | ✅ | ✅ | ✅ | 👁 | ❌ | ❌ |
| Add / Edit compliance record | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| Upload documents | ✅ | ✅ | ✅ | ❌ | ❌ | 🔒 |
| Set expiry alerts | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| Delete compliance record | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| View own documents | ✅ | ✅ | ✅ | ✅ | ✅ | 🔒 |

---

## Reports

| Permission | Super Admin | Company Admin | HR Manager | Supervisor | Accountant | Employee |
|---|:---:|:---:|:---:|:---:|:---:|:---:|
| Attendance reports | ✅ | ✅ | ✅ | 👁 (team) | ❌ | ❌ |
| Payroll reports | ✅ | ✅ | 👁 | ❌ | ✅ | ❌ |
| Compliance reports | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| Employee reports | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| Platform-wide reports | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Export any report | ✅ | ✅ | ✅ | ❌ | ✅ | ❌ |

---

## Notifications

| Permission | Super Admin | Company Admin | HR Manager | Supervisor | Accountant | Employee |
|---|:---:|:---:|:---:|:---:|:---:|:---:|
| View notifications | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Send notifications | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| Manage templates | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| Broadcast to all | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |

---

## Company Settings

| Permission | Super Admin | Company Admin | HR Manager | Supervisor | Accountant | Employee |
|---|:---:|:---:|:---:|:---:|:---:|:---:|
| View settings | ✅ | ✅ | 👁 | ❌ | ❌ | ❌ |
| Edit org profile | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Timezone / locale config | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Integrations / API keys | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Manage sites / locations | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |

---

## Role Slugs (used in code)

| Role Label | Slug | Scope |
|---|---|---|
| Super Admin | `platform-admin` | Platform (global) |
| Company Admin | `admin` | Tenant |
| HR Manager | `hr-manager` | Tenant |
| Supervisor | `supervisor` | Tenant |
| Accountant | `accountant` | Tenant |
| Employee | `employee` | Tenant |
