@extends('layout')

@section('title', isset($appointment) ? 'Edit Appointment' : 'Book New Appointment')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>
            <i class="fas fa-calendar-check"></i> 
            {{ isset($appointment) ? 'Edit Appointment' : 'Book New Appointment' }}
        </h1>
        <a href="{{ url('appointments/index.php') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Appointments
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ isset($appointment) ? url('appointments/edit.php?id=' . $appointment['id']) : url('appointments/create.php') }}" class="form" id="appointmentForm">
                <input type="hidden" name="csrf_token" value="{{ $csrf_token }}">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="patient_id">Patient <span class="required">*</span></label>
                        <select id="patient_id" name="patient_id" class="form-control" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                            <option value="{{ $patient['id'] }}" 
                                    {{ (isset($appointment) && $appointment['patient_id'] == $patient['id']) ? 'selected' : '' }}>
                                {{ $patient['name'] }} ({{ $patient['email'] }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="doctor_id">Doctor <span class="required">*</span></label>
                        <select id="doctor_id" name="doctor_id" class="form-control" required>
                            <option value="">Select Doctor</option>
                            @foreach($doctors as $doctor)
                            <option value="{{ $doctor['id'] }}" 
                                    data-specialization="{{ $doctor['specialization'] }}"
                                    {{ (isset($appointment) && $appointment['doctor_id'] == $doctor['id']) ? 'selected' : '' }}>
                                {{ $doctor['name'] }} - {{ $doctor['specialization'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="appointment_date">Appointment Date <span class="required">*</span></label>
                        <input type="date" id="appointment_date" name="appointment_date" class="form-control" 
                               value="{{ $appointment['appointment_date'] ?? '' }}" 
                               min="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="appointment_time">Appointment Time <span class="required">*</span></label>
                        <select id="appointment_time" name="appointment_time" class="form-control" required disabled>
                            <option value="">Select date and doctor first</option>
                        </select>
                        <div id="time-loading" class="loading-indicator" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Checking availability...
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="reason">Reason for Visit <span class="required">*</span></label>
                    <textarea id="reason" name="reason" class="form-control" rows="3" required>{{ $appointment['reason'] ?? '' }}</textarea>
                </div>

                @if(isset($appointment))
                <div class="form-group">
                    <label for="status">Status <span class="required">*</span></label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="scheduled" {{ $appointment['status'] === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="completed" {{ $appointment['status'] === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $appointment['status'] === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                @else
                <input type="hidden" name="status" value="scheduled">
                @endif

                <div id="availability-message" class="alert" style="display: none;"></div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i> {{ isset($appointment) ? 'Update Appointment' : 'Book Appointment' }}
                    </button>
                    <a href="{{ url('appointments/index.php') }}" class="btn btn-secondary">
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
// Ajax: Live check for available time slots
let currentAppointmentId = {{ isset($appointment) ? $appointment['id'] : 'null' }};

function checkAvailability() {
    const doctorId = document.getElementById('doctor_id').value;
    const date = document.getElementById('appointment_date').value;
    const timeSelect = document.getElementById('appointment_time');
    const loading = document.getElementById('time-loading');
    
    if (!doctorId || !date) {
        timeSelect.disabled = true;
        timeSelect.innerHTML = '<option value="">Select date and doctor first</option>';
        return;
    }
    
    // Show loading
    loading.style.display = 'block';
    timeSelect.disabled = true;
    
    // Fetch available time slots using Ajax
    fetch(`{{ url('ajax/check_availability.php') }}?doctor_id=${doctorId}&date=${date}${currentAppointmentId ? '&exclude_id=' + currentAppointmentId : ''}`)
        .then(response => response.json())
        .then(data => {
            loading.style.display = 'none';
            
            if (data.success) {
                timeSelect.innerHTML = '<option value="">Select a time slot</option>';
                
                data.slots.forEach(slot => {
                    const option = document.createElement('option');
                    option.value = slot.time;
                    option.textContent = slot.formatted + (slot.available ? '' : ' (Booked)');
                    option.disabled = !slot.available;
                    
                    // Pre-select current time if editing
                    @if(isset($appointment))
                    if (slot.time === '{{ $appointment['appointment_time'] }}') {
                        option.selected = true;
                    }
                    @endif
                    
                    timeSelect.appendChild(option);
                });
                
                timeSelect.disabled = false;
                
                // Show availability message
                const availableCount = data.slots.filter(s => s.available).length;
                const message = document.getElementById('availability-message');
                message.className = availableCount > 0 ? 'alert alert-success' : 'alert alert-warning';
                message.textContent = `${availableCount} time slot(s) available on this date`;
                message.style.display = 'block';
            } else {
                timeSelect.innerHTML = '<option value="">No slots available</option>';
                
                const message = document.getElementById('availability-message');
                message.className = 'alert alert-error';
                message.textContent = data.message || 'Error loading time slots';
                message.style.display = 'block';
            }
        })
        .catch(error => {
            loading.style.display = 'none';
            console.error('Error:', error);
            timeSelect.innerHTML = '<option value="">Error loading slots</option>';
        });
}

// Trigger availability check when doctor or date changes
document.getElementById('doctor_id').addEventListener('change', checkAvailability);
document.getElementById('appointment_date').addEventListener('change', checkAvailability);

// Initial load if editing
@if(isset($appointment))
window.addEventListener('load', checkAvailability);
@endif

// Form validation
document.getElementById('appointmentForm').addEventListener('submit', function(e) {
    const timeSelect = document.getElementById('appointment_time');
    if (!timeSelect.value) {
        e.preventDefault();
        alert('Please select an available time slot');
    }
});
</script>
@endsection
