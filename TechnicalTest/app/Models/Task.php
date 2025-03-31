<?php
namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'description', 'status', 'due_date', 'assignee_id'];

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function dependencies()
    {
        return $this->belongsToMany(Task::class, 'task_dependcies', 'task_id', 'dependency_id');
    }

    /**
     * Scope to apply filters dynamically
     */
    public function scopeWithFilters(Builder $query, $filters)
    {
        if (Auth::user()->role === 'user') {
            $query->where('assignee_id', Auth::id());
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['assignee_id'])) {
            $query->where('assignee_id', $filters['assignee_id']);
        }

        if (!empty($filters['due_date_from']) && !empty($filters['due_date_to'])) {
            $query->whereBetween('due_date', [$filters['due_date_from'], $filters['due_date_to']]);
        }

        return $query;
    }
}
