@extends('admin.layouts.admin')

@section('title', 'General Jobs - Rozgar Finder')
@section('page-title', 'General Jobs')
@section('page-subtitle', 'Manage PPSC/FPSC and admin posted jobs')

@section('content')
    <div class="admin-card">
        <div class="card-header">
            <h5><i class="fas fa-briefcase me-2" style="color: var(--primary-color);"></i> All Jobs</h5>
            <div class="card-actions">
                <a href="{{ route('admin.jobs.create') }}" class="btn-admin-primary">
                    <i class="fas fa-plus"></i> Add New
                </a>
            </div>
        </div>

        <div class="table-container">
            <div class="table-scroll-wrapper">
                <table class="admin-table" id="jobsTable">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Location</th>
                            <th>Image</th>
                            <th>Apply Link</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobs as $job)
                            <tr id="job-row-{{ $job->id }}">
                                <td class="job-title">{{ $job->title }}</td>
                                <td>{{ $job->category->name ?? 'N/A' }}</td>
                                <td><i class="fas fa-location-dot location-icon"></i> {{ $job->location }}</td>
                                <td>
                                    @if($job->ad_image)
                                        <img src="{{ asset('storage/jobs/' . $job->ad_image) }}" alt="{{ $job->title }}"
                                            style="width: 50px; height: 30px; object-fit: cover; border-radius: 4px;">
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td>
                                    @if($job->apply_link)
                                        <a href="{{ $job->apply_link }}" target="_blank" class="btn btn-sm btn-success"
                                            title="Apply Now">
                                            <i class="fas fa-external-link-alt"></i> Apply
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge-{{ $job->is_active ? 'active' : 'inactive' }}">
                                        {{ $job->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-{{ $job->is_featured ? 'active' : 'inactive' }}">
                                        {{ $job->is_featured ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.jobs.edit', $job) }}" class="btn btn-sm btn-primary"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="deleteJob({{ $job->id }}, '{{ $job->title }}')"
                                            class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="empty-state">No jobs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="scroll-indicator">
                <i class="fas fa-arrow-left me-1"></i> Scroll to see more
            </div>
        </div>

        <div class="mt-3">
            {{ $jobs->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function deleteJob(id, title) {
            if (!confirm(`Are you sure you want to delete job "${title}"?`)) return;

            const row = document.getElementById(`job-row-${id}`);
            row.style.opacity = '0.5';

            fetch(`/admin/jobs/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        row.style.transition = 'all 0.5s ease';
                        row.style.opacity = '0';
                        setTimeout(() => {
                            row.remove();
                            if (typeof window.showToast === 'function') {
                                window.showToast('success', response.message);
                            } else {
                                alert(response.message);
                            }
                        }, 500);
                    } else {
                        if (typeof window.showToast === 'function') {
                            window.showToast('error', response.message);
                        } else {
                            alert(response.message);
                        }
                        row.style.opacity = '1';
                    }
                })
                .catch(() => {
                    if (typeof window.showToast === 'function') {
                        window.showToast('error', 'Failed to delete job.');
                    } else {
                        alert('Failed to delete job.');
                    }
                    row.style.opacity = '1';
                });
        }

        // ✅ DataTables
        $(document).ready(function () {
            if ($.fn.DataTable.isDataTable('#jobsTable')) {
                $('#jobsTable').DataTable().destroy();
            }

            $('#jobsTable').DataTable({
                responsive: true,
                pageLength: 25,
                retrieve: true,
                destroy: true,
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                },
                columnDefs: [
                    { orderable: false, targets: [7] } // Actions column no sorting
                ]
            });
        });
    </script>
@endpush
