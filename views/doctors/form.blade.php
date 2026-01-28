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
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="{{ $doctor['email'] ?? '' }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone Number <span class="required">*</span></label>
                        <input type="tel" id="phone" name="phone" class="form-control" 
                               value="{{ $doctor['phone'] ?? '' }}" required>
                    </div>

                    <div class="form-group">
                        <label for="specialization">Specialization <span class="required">*</span></label>
                        <input type="text" id="specialization" name="specialization" class="form-control" 
                               value="{{ $doctor['specialization'] ?? '' }}" 
                               placeholder="e.g., Cardiologist, Pediatrician" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="qualification">Qualification <span class="required">*</span></label>
                    <input type="text" id="qualification" name="qualification" class="form-control" 
                           value="{{ $doctor['qualification'] ?? '' }}" 
                           placeholder="e.g., MBBS, MD" required>
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
// Client-side validation
document.getElementById('doctorForm').addEventListener('submit', function(e) {
    let isValid = true;
    
    // Clear previous errors
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
    
    // Validate at least one day is selected
    const checkedDays = document.querySelectorAll('input[name="available_days[]"]:checked');
    if (checkedDays.length === 0) {
        document.getElementById('days-error').textContent = 'Please select at least one available day';
        isValid = false;
    }
    
    if (!isValid) {
        e.preventDefault();
    }
});
</script>
@endsection
