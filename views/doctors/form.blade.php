@extends('layout')

@section('title', isset($doctor) ? 'Edit Doctor' : 'Add New Doctor')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>
            <i class="fas fa-user-md"></i> 
            {{ isset($doctor) ? 'Edit Doctor' : 'Add New Doctor' }}
        </h1>
        <a href="{{ url('doctors/index.php') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Doctors
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ isset($doctor) ? url('doctors/edit.php?id=' . $doctor['id']) : url('doctors/create.php') }}" class="form" id="doctorForm">
                <input type="hidden" name="csrf_token" value="{{ $csrf_token }}">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Full Name <span class="required">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" 
                               value="{{ $doctor['name'] ?? '' }}" required>
                        <span class="error-message" id="name-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="{{ $doctor['email'] ?? '' }}" required>
                        <span class="error-message" id="email-error"></span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone Number <span class="required">*</span></label>
                        <input type="tel" id="phone" name="phone" class="form-control" 
                               value="{{ $doctor['phone'] ?? '' }}" required>
                        <span class="error-message" id="phone-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="specialization">Specialization <span class="required">*</span></label>
                        <input type="text" id="specialization" name="specialization" class="form-control" 
                               value="{{ $doctor['specialization'] ?? '' }}" 
                               placeholder="e.g., Cardiologist, Pediatrician" required>
                        <span class="error-message" id="specialization-error"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="qualification">Qualification <span class="required">*</span></label>
                    <input type="text" id="qualification" name="qualification" class="form-control" 
                           value="{{ $doctor['qualification'] ?? '' }}" 
                           placeholder="e.g., MBBS, MD" required>
                    <span class="error-message" id="qualification-error"></span>
                </div>

                <div class="form-group">
                    <label>Available Days <span class="required">*</span></label>
                    <div class="checkbox-group">
                        @php
                            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                            $selectedDays = isset($doctor) ? explode(',', $doctor['available_days']) : [];
                        @endphp
                        @foreach($days as $day)
                        <label class="checkbox-label">
                            <input type="checkbox" name="available_days[]" value="{{ $day }}" 
                                   {{ in_array($day, $selectedDays) ? 'checked' : '' }}>
                            <span>{{ $day }}</span>
                        </label>
                        @endforeach
                    </div>
                    <span class="error-message" id="days-error"></span>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ isset($doctor) ? 'Update Doctor' : 'Add Doctor' }}
                    </button>
                    <a href="{{ url('doctors/index.php') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script>
// Initialize live form validation
const doctorValidator = new FormValidator('doctorForm', {
    validateOnInput: true,
    validateOnBlur: true,
    showSuccessIcons: true,
    debounceDelay: 300
});

// Add custom validators
doctorValidator.addValidator('name', (value) => {
    if (value.length < 2) {
        return 'Name must be at least 2 characters long';
    }
    if (!/^[a-zA-Z\s.]+$/.test(value)) {
        return 'Name should only contain letters, spaces, and periods';
    }
    return true;
});

doctorValidator.addValidator('email', (value) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(value)) {
        return 'Please enter a valid email address';
    }
    return true;
});

doctorValidator.addValidator('phone', (value) => {
    const phoneRegex = /^[0-9\-\+\(\)\s]{10,20}$/;
    if (!phoneRegex.test(value)) {
        return 'Please enter a valid phone number (10-20 digits)';
    }
    return true;
});

doctorValidator.addValidator('specialization', (value) => {
    if (value.length < 3) {
        return 'Specialization must be at least 3 characters long';
    }
    return true;
});

doctorValidator.addValidator('qualification', (value) => {
    if (value.length < 2) {
        return 'Qualification must be at least 2 characters long';
    }
    return true;
});

// Validate available days checkboxes
const checkboxGroup = document.querySelector('.checkbox-group');
if (checkboxGroup) {
    checkboxGroup.parentElement.setAttribute('data-validate-checkbox', 'true');
    
    const checkboxes = checkboxGroup.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
            const errorEl = document.getElementById('days-error');
            
            if (anyChecked) {
                if (errorEl) errorEl.textContent = '';
            } else {
                if (errorEl) errorEl.textContent = 'Please select at least one available day';
            }
        });
    });
}

// Add validation hints
document.getElementById('name').setAttribute('title', 'Enter doctor\'s full name');
document.getElementById('email').setAttribute('title', 'Enter a valid email address');
document.getElementById('phone').setAttribute('title', 'Enter phone number (10-20 digits)');
document.getElementById('specialization').setAttribute('title', 'e.g., Cardiologist, Pediatrician');
document.getElementById('qualification').setAttribute('title', 'e.g., MBBS, MD, PhD');
</script>
@endsection
