# Clinic Appointment Scheduling System

A comprehensive web-based clinic appointment scheduling system built with **PHP**, **MySQL**, and **Blade templating engine** (standalone, without Laravel framework).

## 🌟 Features

### Core Functionality
- ✅ **Complete CRUD Operations** for Patients, Doctors, and Appointments
- ✅ **Prevent Overlapping Schedules** - Real-time availability checking
- ✅ **Advanced Search** - Search appointments by date, doctor, patient, or status
- ✅ **Ajax Live Availability Check** - Real-time time slot availability without page reload

### Security Features
- 🔒 **SQL Injection Prevention** - All queries use prepared statements with PDO
- 🔒 **XSS Protection** - All output is properly escaped using `htmlspecialchars()`
- 🔒 **CSRF Protection** - Token-based protection for all forms
- 🔒 **Input Validation** - Both client-side and server-side validation
- 🔒 **Secure Sessions** - HTTP-only cookies and secure session handling

### Technology Stack
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Template Engine**: Blade (jenssegers/blade - standalone)
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Icons**: Font Awesome 6
- **Architecture**: MVC-inspired structure

## 📁 Project Structure

```
project_root/
├── config/
│   └── db.php                  # Database configuration
├── database/
│   └── schema.sql              # Database schema and sample data
├── includes/
│   ├── blade.php               # Blade template engine setup
│   ├── functions.php           # Business logic functions
│   └── security.php            # Security functions
├── public/
│   ├── index.php               # Homepage
│   ├── patients/               # Patient CRUD operations
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── edit.php
│   │   └── delete.php
│   ├── doctors/                # Doctor CRUD operations
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── edit.php
│   │   └── delete.php
│   ├── appointments/           # Appointment CRUD operations
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── edit.php
│   │   ├── delete.php
│   │   └── search.php
│   └── ajax/
│       └── check_availability.php  # Ajax endpoint for time slots
├── views/                      # Blade templates
│   ├── layout.blade.php
│   ├── home.blade.php
│   ├── patients/
│   ├── doctors/
│   └── appointments/
├── assets/
│   ├── css/
│   │   └── style.css           # Main stylesheet
│   └── js/
│       └── main.js             # Main JavaScript
├── cache/                      # Blade template cache (auto-generated)
├── vendor/                     # Composer dependencies
├── .env                        # Environment configuration
├── .htaccess                   # Apache configuration
└── composer.json               # PHP dependencies
```

## 🚀 Installation & Setup

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (PHP dependency manager)

### Step 1: Install Dependencies

```bash
composer install
```

This will install the Blade templating engine (`jenssegers/blade`).

### Step 2: Configure Database

1. Create a MySQL database:
```sql
CREATE DATABASE clinic_appointment_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Update `.env` file with your database credentials:
```
DB_HOST=localhost
DB_NAME=clinic_appointment_db
DB_USER=root
DB_PASS=your_password
```

3. Import the database schema:
```bash
mysql -u root -p clinic_appointment_db < database/schema.sql
```

Or use phpMyAdmin to import `database/schema.sql`.

### Step 3: Configure Web Server

#### For Apache (XAMPP/WAMP/LAMP)

1. Place the project in your web server's document root (e.g., `htdocs/`)
2. Ensure `mod_rewrite` is enabled
3. The `.htaccess` file is already configured

#### For PHP Built-in Server (Development Only)

```bash
cd public
php -S localhost:8000
```

Then access: `http://localhost:8000`

### Step 4: Set Permissions

Ensure the `cache` directory is writable:

```bash
chmod -R 755 cache/
```

On Windows, no action needed.

### Step 5: Access the Application

Open your browser and navigate to:
- **Apache**: `http://localhost/Clinic Appointment Scheduling System FINALCOURSEWORK/public/`
- **PHP Server**: `http://localhost:8000/`

## 📖 Usage Guide

### Managing Patients

1. Navigate to **Patients** from the main menu
2. Click **Add New Patient** to create a patient record
3. Fill in patient details (name, email, phone, DOB, gender, address)
4. View, edit, or delete patient records from the listing page
5. Use the search box for live filtering

### Managing Doctors

1. Navigate to **Doctors** from the main menu
2. Click **Add New Doctor** to create a doctor record
3. Fill in doctor details (name, email, phone, specialization, qualification)
4. Select available days for the doctor
5. View, edit, or delete doctor records

### Booking Appointments

1. Navigate to **Appointments** → **Book Appointment**
2. Select a patient and doctor
3. Choose an appointment date
4. **Ajax Feature**: Time slots load automatically based on doctor and date
5. Available slots are shown in green, booked slots are disabled
6. Select an available time slot and provide reason for visit
7. Submit to book the appointment

### Searching Appointments

1. Navigate to **Appointments** → **Advanced Search**
2. Filter by:
   - Appointment Date
   - Doctor
   - Patient
   - Status (Scheduled/Completed/Cancelled)
3. Use multiple criteria for advanced searches
4. Results display in a table with full details

## 🔐 Security Features Implemented

### 1. SQL Injection Prevention
- All database queries use **PDO prepared statements**
- Parameters are bound separately from SQL queries
- Example:
```php
$stmt = $pdo->prepare("SELECT * FROM patients WHERE id = ?");
$stmt->execute([$id]);
```

### 2. XSS (Cross-Site Scripting) Prevention
- All user input is sanitized using `htmlspecialchars()`
- Output escaping in Blade templates: `{{ $variable }}`
- Example:
```php
function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
```

### 3. CSRF (Cross-Site Request Forgery) Protection
- Token generation for all forms
- Token verification on form submission
- Example:
```php
// Generate token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Verify token
hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
```

### 4. Input Validation
- Client-side validation using JavaScript
- Server-side validation for all inputs
- Email, phone, date, and time format validation

## 🎨 Design Features

- **Modern UI**: Gradient backgrounds, card-based layout
- **Responsive Design**: Works on desktop, tablet, and mobile
- **Smooth Animations**: Hover effects, transitions, and micro-interactions
- **Color-coded Status**: Visual indicators for appointment status
- **Icon Integration**: Font Awesome icons throughout
- **Professional Typography**: Inter font family

## 🔧 Key Technologies & Techniques

### Blade Templating (Standalone)
- Template inheritance with `@extends`
- Sections with `@section` and `@yield`
- Loops with `@foreach`
- Conditionals with `@if`
- Automatic XSS protection with `{{ }}`

### Ajax Implementation
- Real-time availability checking
- Fetch API for asynchronous requests
- JSON response handling
- Live form validation

### Database Design
- Normalized structure (3NF)
- Foreign key constraints
- Unique constraints for preventing duplicates
- Indexes for performance

## 📊 Sample Data

The system comes with sample data:
- 5 Patients
- 5 Doctors (various specializations)
- 5 Sample Appointments

You can delete or modify this data after installation.

## 🐛 Troubleshooting

### Blade Templates Not Rendering
- Ensure `cache/` directory exists and is writable
- Clear cache: `rm -rf cache/*` (or delete manually)

### Database Connection Error
- Verify `.env` file has correct credentials
- Ensure MySQL service is running
- Check database name exists

### Ajax Not Working
- Check browser console for JavaScript errors
- Ensure correct paths in Ajax requests
- Verify server is processing PHP files

### CSS/JS Not Loading
- Check file paths in `layout.blade.php`
- Ensure files exist in `assets/` directory
- Clear browser cache

## 📝 Assignment Requirements Checklist

✅ **PHP & MySQL**: All backend logic in PHP, data in MySQL  
✅ **Full CRUD**: Create, Read, Update, Delete for all entities  
✅ **Search Feature**: Advanced multi-criteria search implemented  
✅ **Security**:
  - ✅ SQL Injection prevention (prepared statements)
  - ✅ XSS prevention (output escaping)
  - ✅ CSRF protection (token-based)  
✅ **Ajax Feature**: Live time slot availability checking  
✅ **Template Engine**: Blade templating (standalone)  
✅ **Professional Structure**: Organized MVC-inspired architecture  

## 🎓 Learning Outcomes

This project demonstrates:
- Secure PHP development practices
- Database design and normalization
- Template engine usage without frameworks
- Ajax implementation for better UX
- Form validation (client & server)
- Session management
- RESTful-like URL structure

## 📄 License

This project is created for educational purposes as part of a web development course.

## 👨‍💻 Author

Created as a final coursework project for demonstrating PHP, MySQL, and web security concepts.

---

**Note**: This is a standalone implementation using Blade templating without the Laravel framework, as requested. All features are implemented from scratch with a focus on security and best practices.
