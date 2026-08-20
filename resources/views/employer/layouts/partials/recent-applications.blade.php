{{-- resources/views/employer/layouts/partials/recent-applications.blade.php --}}
<div class="dashboard-card mt-3">
    <h5 style="font-weight: 600; margin-bottom: 15px;">
        <i class="fa fa-clock me-2" style="color: #11998e;"></i> Recent Applications
    </h5>
    @if(isset($recentApplications) && $recentApplications->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Job</th>
                        <th>Applied On</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentApplications as $application)
                        <tr>
                            <td>{{ $application->seeker->name ?? 'N/A' }}</td>
                            <td>{{ $application->job->title ?? 'N/A' }}</td>
                            <td>{{ $application->created_at->format('M d, Y') }}</td>
                            <td>
                                <span class="badge badge-{{ $application->status }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('employer.applications.show', $application) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-muted">No applications received yet.</p>
    @endif
</div>
