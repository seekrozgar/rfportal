{{-- resources/views/employer/layouts/partials/stats-cards.blade.php --}}
<div class="row">
    <div class="col-md-3 col-sm-6">
        <div class="dashboard-card text-center">
            <div class="card-icon"><i class="fa fa-briefcase"></i></div>
            <h3 class="card-number">{{ $totalJobs ?? 0 }}</h3>
            <p class="card-label">Total Jobs</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="dashboard-card text-center">
            <div class="card-icon"><i class="fa fa-users"></i></div>
            <h3 class="card-number">{{ $totalApplications ?? 0 }}</h3>
            <p class="card-label">Total Applications</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="dashboard-card text-center">
            <div class="card-icon"><i class="fa fa-eye"></i></div>
            <h3 class="card-number">{{ $totalViews ?? 0 }}</h3>
            <p class="card-label">Total Views</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="dashboard-card text-center">
            <div class="card-icon"><i class="fa fa-star"></i></div>
            <h3 class="card-number">{{ $featuredJobs ?? 0 }}</h3>
            <p class="card-label">Featured Jobs</p>
        </div>
    </div>
</div>
