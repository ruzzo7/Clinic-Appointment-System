-- Clinic Appointment Scheduling System Database Schema

-- Create database
CREATE DATABASE IF NOT EXISTS clinic_appointment_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE clinic_appointment_db;

-- Patients table
CREATE TABLE IF NOT EXISTS patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    address TEXT,
    date_of_birth DATE NOT NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Doctors table
CREATE TABLE IF NOT EXISTS doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    qualification VARCHAR(200) NOT NULL,
    available_days VARCHAR(100) NOT NULL COMMENT 'Comma-separated days: Monday,Tuesday,etc',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_specialization (specialization)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Appointments table
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    reason TEXT NOT NULL,
    status ENUM('scheduled', 'completed', 'cancelled') DEFAULT 'scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
    INDEX idx_appointment_date (appointment_date),
    INDEX idx_patient_id (patient_id),
    INDEX idx_doctor_id (doctor_id),
    INDEX idx_status (status),
    UNIQUE KEY unique_appointment (doctor_id, appointment_date, appointment_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data for testing

-- Sample Patients
INSERT INTO patients (name, email, phone, address, date_of_birth, gender) VALUES
('John Doe', 'john.doe@email.com', '555-0101', '123 Main St, City', '1985-05-15', 'male'),
('Jane Smith', 'jane.smith@email.com', '555-0102', '456 Oak Ave, City', '1990-08-22', 'female'),
('Robert Johnson', 'robert.j@email.com', '555-0103', '789 Pine Rd, City', '1978-12-10', 'male'),
('Emily Davis', 'emily.davis@email.com', '555-0104', '321 Elm St, City', '1995-03-18', 'female'),
('Michael Brown', 'michael.b@email.com', '555-0105', '654 Maple Dr, City', '1982-07-25', 'male');

-- Sample Doctors
INSERT INTO doctors (name, email, phone, specialization, qualification, available_days) VALUES
('Dr. Sarah Wilson', 'dr.wilson@clinic.com', '555-1001', 'General Physician', 'MBBS, MD', 'Monday,Tuesday,Wednesday,Thursday,Friday'),
('Dr. James Anderson', 'dr.anderson@clinic.com', '555-1002', 'Cardiologist', 'MBBS, DM Cardiology', 'Monday,Wednesday,Friday'),
('Dr. Lisa Martinez', 'dr.martinez@clinic.com', '555-1003', 'Pediatrician', 'MBBS, MD Pediatrics', 'Tuesday,Thursday,Saturday'),
('Dr. David Lee', 'dr.lee@clinic.com', '555-1004', 'Orthopedic', 'MBBS, MS Orthopedics', 'Monday,Tuesday,Thursday,Friday'),
('Dr. Maria Garcia', 'dr.garcia@clinic.com', '555-1005', 'Dermatologist', 'MBBS, MD Dermatology', 'Wednesday,Thursday,Friday,Saturday');

-- Sample Appointments
INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, reason, status) VALUES
(1, 1, '2026-02-01', '09:00:00', 'Regular checkup', 'scheduled'),
(2, 2, '2026-02-01', '10:00:00', 'Heart consultation', 'scheduled'),
(3, 3, '2026-02-02', '11:00:00', 'Child vaccination', 'scheduled'),
(4, 4, '2026-02-02', '14:00:00', 'Knee pain', 'scheduled'),
(5, 5, '2026-02-03', '15:00:00', 'Skin rash', 'scheduled');
