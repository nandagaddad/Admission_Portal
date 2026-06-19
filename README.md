# Admission Portal

A web-based College Admission Portal developed using PHP, MySQL, JavaScript, AJAX, and Bootstrap. The system allows administrators to manage student admission records efficiently through a structured dashboard and student management modules.

## Features

* Admin Login Authentication
* Dashboard Interface
* Add New Student Records
* View Student List
* Filter Students by Admission Year using AJAX
* Responsive User Interface with Bootstrap
* MVC-Based Project Structure
* MySQL Database Integration
* Session Management

## Technologies Used

### Frontend

* HTML5
* CSS3
* Bootstrap 5
* JavaScript
* AJAX

### Backend

* PHP

### Database

* MySQL

### Development Environment

* XAMPP
* Apache Server

## Project Structure

```
Admission_Portal/
│
├── Config/
│   └── Database.php
│   └── Database.sql   
│
├── Controllers/
│   ├── AuthController.php
│   ├── StudentController.php
│   ├── StudentSearchYearController.php
│   └── StudentsListController.php
│
├── Models/
│   ├── Admin.php
│   └── Student.php
│
├── Views/
│   ├── auth/
│   ├── dashboard/
│   ├── layouts/
│   └── students/
│
├── ajax/
│   └── filterYear.php
│
└── assets/
    └── js/
        └── student-search.js
```

## Installation

1. Clone the repository

```bash
git clone https://github.com/your-username/Admission_Portal.git
```

2. Move the project folder to the XAMPP htdocs directory.

3. Start Apache and MySQL from the XAMPP Control Panel.

4. Create a database named:

```sql
admission_db
```

5. Import the database SQL file.

6. Configure database credentials in:

```
Config/Database.php
```

7. Open the project in a browser:

```
http://localhost/Admission_Portal/Views/auth/login.php
```

## Functional Modules

### Authentication Module

* Secure admin login
* Session-based access control
* Logout functionality

### Student Management Module

* Add student details
* View student records
* Filter records by admission year

### Dashboard Module

* Centralized administration panel
* Navigation to all major functionalities

## Learning Outcomes

* Implemented MVC architecture in PHP.
* Developed CRUD operations using PHP and MySQL.
* Integrated AJAX for dynamic data filtering.
* Applied Bootstrap for responsive web design.
* Gained practical experience with database connectivity and session management.

## Author
Nanda Gaddad

