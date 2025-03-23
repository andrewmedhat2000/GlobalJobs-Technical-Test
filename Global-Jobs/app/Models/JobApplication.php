<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'user_id', 'job_id' , 'cover_letter','file_path',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the job that the user applied for.
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
