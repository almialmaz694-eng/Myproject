# Online Job Portal System

A full-stack, responsive web application designed for connecting employers with job seekers. Built as part of the Web Programming course assignment.

---

## 📌 Project Overview

* **Course Code:** CoSc3091 - Web Programming
* **Project Title:** Online Job Portal (Employers & Job Seekers)
* **Student Name:** Haylamlak Gebreeyesus
* **Department:** Computer Science
* **Technology Stack:** HTML5, CSS3, JavaScript (ES6), PHP, MariaDB / MySQL

The **Online Job Portal** allows employers to publish job vacancies and view incoming applications, while job seekers can search available positions, submit applications with resume uploads, and track their application history.

---

## ✨ Key Features

* **Role-Based Authentication:** Distinct registration and login flows for `employer` and `jobseeker` accounts.
* **Security & Input Validation:** Passwords encrypted using `password_hash()`. Prepared statements prevent SQL injection, and dual-layer (JS + PHP) form validation ensures data integrity.
* **Employer Dashboard:** Employers can post new job openings (title, company, location, description) and track applications submitted by job seekers.
* **Job Seeker Dashboard:** Job seekers can browse dynamic listings, upload digital resumes (`.pdf`/`.doc`), and review their application statuses.
* **Contact & Feedback Form:** Saves user messages directly into the database.
* **Mobile Responsive UI:** Flexbox/Grid layouts with a slide-in right sidebar navigation drawer for smaller viewports.

---

## 📁 Directory Structure

```text
job-portal/
├── config/
│   └── db.php         # MariaDB/MySQL database connection
├── css/
│   └── style.css      # Custom stylesheet & mobile responsive rules
├── js/
│   └── main.js        # Mobile drawer toggle & form validation
├── includes/
│   ├── header.php     # Global navigation header
│   └── footer.php     # Global page footer
├── uploads/           # Directory for uploaded applicant resumes
├── database.sql       # Database schema & initial table creation script
├── index.php          # Homepage landing page
├── about.php          # Student profile & project overview
├── jobs.php           # Job vacancy listings & application form
├── contact.php        # Contact form
├── login.php          # User sign-in page
├── register.php       # Account registration page
├── dashboard.php      # Context-aware user dashboard
└── logout.php        # Session termination script