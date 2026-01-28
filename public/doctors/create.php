<?php
/**
 * Create Doctor Page
 */

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/security.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/blade.php';

startSecureSession();
requireLogin(); // Require authentication


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Invalid security token. Please try again.');
        redirect(url('doctors/create.php'));
    }
    
    // Sanitize and validate input
    $availableDays = isset($_POST['available_days']) && is_array($_POST['available_days']) 
        ? implode(',', array_map('sanitizeInput', $_POST['available_days']))
        : '';
    
    $data = [
        'name' => sanitizeInput($_POST['name'] ?? ''),
        'email' => sanitizeInput($_POST['email'] ?? ''),
        'phone' => sanitizeInput($_POST['phone'] ?? ''),
        'specialization' => sanitizeInput($_POST['specialization'] ?? ''),
        'qualification' => sanitizeInput($_POST['qualification'] ?? ''),
        'available_days' => $availableDays
    ];
    
    // Server-side validation
    $errors = [];
    
    if (empty($data['name']) || strlen($data['name']) < 2) {
        $errors[] = 'Name must be at least 2 characters long';
    }
    
    if (!validateEmail($data['email'])) {
        $errors[] = 'Invalid email address';
    }
    
    if (!validatePhone($data['phone'])) {
        $errors[] = 'Invalid phone number';
    }
    
    if (empty($data['specialization'])) {
        $errors[] = 'Specialization is required';
    }
    
    if (empty($data['qualification'])) {
        $errors[] = 'Qualification is required';
    }
    
    if (empty($data['available_days'])) {
        $errors[] = 'Please select at least one available day';
    }
    
    // If no errors, create doctor
    if (empty($errors)) {
        if (createDoctor($data)) {
            setFlashMessage('success', 'Doctor added successfully!');
            redirect(url('doctors/index.php'));
        } else {
            setFlashMessage('error', 'Failed to add doctor. Email may already exist.');
            redirect(url('doctors/create.php'));
        }
    } else {
        setFlashMessage('error', implode('<br>', $errors));
    }
}

// Get flash message if any
$flash = getFlashMessage();

// Render view
renderView('doctors.form', [
    'csrf_token' => generateCSRFToken(),
    'flash' => $flash
]);
