<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'job_id',
        'seeker_id',
        'cover_letter',
        'resume_path',
        'status',
    ];

    /**
     * Get the job that the application belongs to.
     */
    public function job()
    {
        return $this->belongsTo(JobPosting::class, 'job_id');
    }


    /**
     * Get the seeker (user) that made the application.
     */
    public function seeker()
    {
        return $this->belongsTo(User::class, 'seeker_id');
    }
}
