<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = "global_jobs" ;
    protected $fillable = [
        'title', 'description',
    ];


    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
