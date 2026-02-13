# 🚀 ERP System

### Modern Laravel-Based Business Management Platform

> A secure, scalable, automation-driven ERP system built with Laravel — designed for service businesses.

---

# ✨ Overview

This ERP system centralizes business operations including CRM, Delivery, Finance, Renewals, Attendance, and Owner Insights — all in one secure platform.

Built with:

* Clean Laravel Architecture
* Strong Business Rule Enforcement
* Performance Optimization
* Security-First Design
* Automation-Driven Workflow

---

# 🏗️ Tech Stack

| Layer          | Technology                 |
| -------------- | -------------------------- |
| Backend        | Laravel                    |
| Authentication | Jetstream + Fortify        |
| Authorization  | Spatie Permission          |
| UI             | Blade + Tailwind CSS       |
| Queue          | Database Driver            |
| Cache          | Database / Redis Supported |
| Scheduler      | Laravel Task Scheduling    |
| Database       | MySQL / MariaDB            |
| Notifications  | Laravel Notifications      |

---

# 📦 Core Modules

## 🧲 CRM

* Leads Management
* Deal Pipeline
* Activities & Follow-ups
* Client Management
* Client Contacts & Notes

## 📋 Delivery

* Projects
* Task Board
* Task Templates
* Time Logs (Single Running Timer Enforced)
* Project Notes & Files

## 💰 Finance

* Invoices
* Invoice Items
* Payments (Auto Status Sync: unpaid → partial → paid)
* Tax Rules
* Expenses
* Terms & Conditions

## 🔄 Renewals

* Service Management
* Renewal Due Automation
* Invoice Generation for Renewals
* Renewal History Tracking

## 🕒 Attendance

* Attendance Records
* Present / Late / Leave / Absent Tracking
* Office Timing Configuration
* Device & Location Logging

## 📊 Owner Dashboard

* Revenue Overview
* Pending Invoices
* Due Renewals
* Follow-up Insights
* Performance Summary

---

# 🤖 Automation System

## Daily Reminder Command

```
php artisan erp:daily-reminders
```

Handles:

* Renewal Due Reminders
* Invoice Due Reminders
* Follow-up Reminders

Supports:

```
--dry-run
```

Uses cache locking to prevent duplicate execution.

---

# 🔐 Security Architecture

* Form Request Validation
* Permission-Based Authorization
* Soft Deletes Enabled
* Indexed Status Fields
* CSRF Protection
* Mass Assignment Protection
* Secure Password Hashing
* Two-Factor Authentication Ready

---

# 🧠 Business Rules Enforced

✔ Deal Won → Client + Project + Default Tasks + Optional Advance Invoice

✔ Payments Auto Update Invoice Status

✔ Only One Running Timer Per User

✔ Renewal System Driven by `next_renewal_at`

✔ Reminder Logs Prevent Duplicate Notifications

---

# 📂 Project Structure

```
app/
 ├── Models
 ├── Http/
 │    ├── Controllers
 │    ├── Requests
 ├── Console/Commands
 ├── Notifications

database/
 ├── migrations
 ├── seeders

resources/views/
 ├── crm
 ├── delivery
 ├── finance
 ├── renewals
 ├── attendance
```

Follows Laravel Best Practices:

* Route Model Binding
* Transaction Safety
* Clean Controllers
* Eager Loading to Prevent N+1
* Indexed Foreign Keys

---

# ⚙️ Installation

## 1️⃣ Clone Repository

```
git clone https://github.com/your-repo/erp.git
cd erp
```

## 2️⃣ Install Dependencies

```
composer install
npm install && npm run build
```

## 3️⃣ Environment Setup

```
cp .env.example .env
php artisan key:generate
```

Configure database inside `.env`.

## 4️⃣ Run Migrations & Seeders

```
php artisan migrate --seed
```

## 5️⃣ Storage Link

```
php artisan storage:link
```

## 6️⃣ Start Server

```
php artisan serve
```

---

# ⏰ Scheduler Setup (Production)

Add this to your server crontab:

```
* * * * * php /path-to-project/artisan schedule:run >> /dev/null 2>&1
```

---

# 🚀 Production Optimization

Before going live:

```
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Recommended:

* Enable OPcache
* Use Redis for cache
* Use Supervisor for queues
* Disable APP_DEBUG
* Enable HTTPS

---

# 🧪 QA Checklist

* [ ] Lead Follow-up Sync Working
* [ ] Invoice Partial → Paid Status Transition
* [ ] Renewal Due Reminder Triggering
* [ ] Timer Stop Calculates Accurate Seconds
* [ ] Role-Based Access Restriction Working
* [ ] Attendance Late Calculation Valid

---

# 🌍 Environment Configuration (Important)

```
APP_ENV=production
APP_DEBUG=false
QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=database
```

---

# 🛡️ Production Hardening

* Use HTTPS Only
* Protect Sensitive Routes with Middleware
* Configure Proper File Permissions
* Monitor Failed Jobs Table
* Enable Rate Limiting

---

# 🌟 Why This ERP?

✔ Clean & Scalable Architecture
✔ Modular Design
✔ Automation-Driven
✔ Business Rule Enforced
✔ Secure by Design
✔ Production Ready

---

# 📄 License

Private Business ERP System
All Rights Reserved.

---

# 👨‍💻 Maintained By

Japan Bangladesh IT

---

> Designed for modern service businesses who value structure, automation, and control.