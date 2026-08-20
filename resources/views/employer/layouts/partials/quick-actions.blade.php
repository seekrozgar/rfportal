{{-- resources/views/employer/layouts/partials/quick-actions.blade.php --}}
<div class="row">
    <div class="col-12">
        <h4 style="margin-bottom: 15px; font-weight: 600; color: #2c3e50;">
            <i class="fa fa-link me-2" style="color: #11998e;"></i> Quick Actions
        </h4>
    </div>
    <div class="col-md-3 col-sm-6">
        <a href="{{ route('employer.jobs.index') }}" class="quick-link-card">
            <span class="icon"><i class="fa fa-list"></i></span>
            <span class="label">My Jobs</span>
            <span class="desc">View all your posted jobs</span>
        </a>
    </div>
    <div class="col-md-3 col-sm-6">
        <a href="{{ route('employer.jobs.create') }}" class="quick-link-card">
            <span class="icon"><i class="fa fa-plus-circle"></i></span>
            <span class="label">Post Job</span>
            <span class="desc">Create a new job listing</span>
        </a>
    </div>
    <div class="col-md-3 col-sm-6">
        <a href="{{ route('employer.applications.index') }}" class="quick-link-card">
            <span class="icon"><i class="fa fa-users"></i></span>
            <span class="label">Applications</span>
            <span class="desc">Review candidate applications</span>
        </a>
    </div>
    <div class="col-md-3 col-sm-6">
        <a href="{{ route('employer.profile.edit') }}" class="quick-link-card">
            <span class="icon"><i class="fa fa-building"></i></span>
            <span class="label">Company Profile</span>
            <span class="desc">Update your company details</span>
        </a>
    </div>
</div>
