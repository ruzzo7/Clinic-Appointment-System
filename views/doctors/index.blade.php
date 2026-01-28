@extends('layout')

@section('title', 'Doctors - Clinic Appointment System')

@section('content')
<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-user-md"></i> Doctor Management</h1>
        <a href="{{ url('doctors/create.php') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Doctor
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>All Doctors</h2>
            <div class="search-box">
                <input type="text" id="doctorSearch" placeholder="Search doctors..." class="form-control">
                <i class="fas fa-search"></i>
            </div>
        </div>
        <div class="card-body">
            @if(count($doctors) > 0)
            <div class="table-responsive">
                <table class="table" id="doctorsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Specialization</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Available Days</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($doctors as $doctor)
                        <tr>
                            <td>{{ $doctor['id'] }}</td>
                            <td>{{ $doctor['name'] }}</td>
                            <td><span class="badge badge-primary">{{ $doctor['specialization'] }}</span></td>
                            <td>{{ $doctor['email'] }}</td>
                            <td>{{ $doctor['phone'] }}</td>
                            <td class="available-days">{{ $doctor['available_days'] }}</td>
                            <td class="actions">
                                <a href="{{ url('doctors/edit.php?id=' . $doctor['id']) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="deleteDoctor({{ $doctor['id'] }}, '{{ $doctor['name'] }}')" class="btn btn-sm btn-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-user-md"></i>
                <p>No doctors found. Add your first doctor to get started.</p>
                <a href="{{ url('doctors/create.php') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Doctor
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script>
// Live search functionality
document.getElementById('doctorSearch')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#doctorsTable tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Delete doctor with confirmation
function deleteDoctor(id, name) {
    const url = `{{ url('doctors/delete.php') }}?id=${id}`;
    const message = `Are you sure you want to delete Dr. "${name}"? This will also remove their schedule and cannot be undone.`;
    openDeleteModal(url, message);
}
</script>
@endsection
