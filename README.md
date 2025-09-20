📦 WebApp Release Documentation
----------------------------------
1. Release Overview

Release Version: v1.0.0

Release Date: 20-09-2025

Environment: (Production)

Prepared By: Ranjithbalan


2. Summary

This release delivers the first stable version of the Employee Portal Web Application, which includes key modules for Leave Management, Internal Job Postings, Circulars & Policies, Events, and Feedback.
It also provides master configurations for employee details, organizational structure, and role-based access control.

3. Core Features ✨
📂 Functional Modules

Leave Management – Apply, approve/reject, and track employee leave.

Internal Job Postings (IJP) – Manage job postings, applications, and results.

Circulars & Policies – Publish, manage, and view organizational circulars and policies.

Events – Event scheduling with role-based permissions and calendar integration.

Feedback – Collect and manage employee feedback securely.

⚙️ Master Data Modules

Employee Details – Store and manage employee information.

Units – Define business units in the organization.

Departments – Manage departments under each unit.

Designations – Assign and manage job designations.

Roles & Permissions – Define role-based access using Spatie Permission.

Menu Permissions – Control access to menu items per role/unit.

Holidays – Configure public holidays and organization-wide leave days.

4. Enhancements ⚡

Integrated role-based access (RBAC) with unit-level permissions.

UI improvements for dashboard widgets and calendar.

Optimized database queries for faster leave request retrieval.

5. Bug Fixes 🐛

Nothing as of now 

6. Technical Changes 🔧

Database Changes:
Basic Setup

API Changes:

No requestfor api as of now 

Dependencies:

Laravel 12

Spatie Laravel Permission

FullCalendar (for events)

Maatwebsite/Laravel-Excel (for IJP status imports)

7. Deployment Notes 🚀

Pre-requisites:

PHP 8.2+, MySQL 8+

8. Known Issues ⚠️

Event repeat scheduling not fully implemented.

Feedback module just entry can be done not index page is dev
