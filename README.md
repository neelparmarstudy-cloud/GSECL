# GSECL Daily Work LogBook System

A digital prototype developed to solve a real-world problem statement posted by **Gujarat State Electricity Corporation Limited (GSECL)** on the Digital Gujarat hackathon portal. This application modernizes industrial plant operations by replacing slow, manual physical logbooks with a secure, real-time, role-based digital reporting platform.

## 📌 Problem Statement & Solution
* **The Problem:** Previously, GSECL managed machinery inspections, fault reporting, and shift handovers using physical paperwork. This manual approach made data retrieval time-consuming, tracking inefficient, and data entries highly prone to human error or loss.
* **The Solution:** An online, centralized, role-based platform built using PHP and MySQL that digitalizes the entire machine logbook cycle, ensuring continuous monitoring, tracking accountability, and robust record maintenance.

---

## 👥 Role-Based Features

The system implements strict access controls divided into three clear organizational roles:
### 1. Plant Supervisor
* Regularly inspects physical machinery and reports equipment malfunctions or structural anomalies through `report-new-issue.php` and `reportissue.php`.
* Updates operational profiles via `update_profile_plant.php`.

### 2. Shift Engineer
* Accesses live issue boards (`recent-issues.php`, `logbook.php`) for continuous plant floor tracking across variable rotational shift cycles.
* Reviews, acts on, and flags issues as resolved using `resolve-issue.php` and `resolved-issues.php`.

### 3. Admin (Authorized Master Controller)
* **Private Gatekeeping:** Manages strict registration boundaries by reviewing internal employee signup requests via `manage_signup_requests.php` and updating statuses through `approved.php`.
* **Employee Management:** Views employee profiles (`view_users.php`), tracks status, and handles admin profile metrics (`update_profile_admin.php`).
* **Archival Security:** Deactivates/Blocks accounts via `delete_users.php` when employees exit the organization instead of deleting them, ensuring historical logbook integrity remains intact for audits.
* **System Controls:** Maintains industrial equipment entries using `equipment_management.php` and manages overtime task logs via `overtime_records.php`.

---

## 📁 Repository Structure
Based on the project's source directory:

📁 GSECL/
│
├── 📁 signup/               # Stored image attachments from employee registrations
├── 📁 uploads/              # Uploaded media assets (images and videos of machinery issues)
│
├── 📄 dbconnect.php         # Database connection configuration setup
├── 📄 log_book.sql          # Exported MySQL schema database file for quick setup
│
├── 📄 home.php              # Central user dashboard landing page
├── 📄 login.php             # Secure authentication gateway
├── 📄 signup.php            # Registration portal for new plant employees
├── 📄 logout.php            # Destroys active user sessions safely
│
├── 📄 *.php                 # Operational backend logic and application page files
├── 📄 *.css                 # Styling sheets (home.css, login.css, style.css, etc.)
├── 📄 signup.js             # Client-side validation for registration details
└── 📄 .gitignore            # Excludes temporary local media and DB configurations from Git


🛠️ Tech Stack
Backend: PHP
Database: MySQL
Frontend: HTML5, CSS3, JavaScript
Local Server Environment: XAMPP (Apache web server)

⚙️ Installation & Setup Instructions
To host and test this application locally using XAMPP:

1. Setup Your Local Environment
Clone this repository directly into your XAMPP root web directory (typically C:/xampp/htdocs/ on Windows).

Bash
git clone [https://github.com/neelparmarstudy-cloud/GSECL.git](https://github.com/neelparmarstudy-cloud/GSECL.git)
Launch the XAMPP Control Panel and start the Apache and MySQL services.

2. Database Migration
Open your web browser and navigate to: http://localhost/phpmyadmin/.
Create a new empty database named log_book.

Click the Import tab at the top menu, browse for the log_book.sql file located in the root of the project folder, and click Import.

3. Configuration Setup
Open dbconnect.php and ensure your local database credentials align with your XAMPP configuration:

PHP
<?php
$conn = mysqli_connect("localhost", "root", "", "log_book");
?>

4. Open the Application
Go to your browser and run the landing page directory:

Plaintext
http://localhost/GSECL/home.php
