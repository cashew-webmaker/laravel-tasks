<?php

namespace Cashewdigital;

use Iatstuti\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;

class Task extends Model
{
    use RevisionableTrait;
    protected $revisionCreationsEnabled = true;

    // Completed at will be proxy for completion status

    protected $fillable = [
        'name',
        'assignor_id',
        'assignee_id',
        'status',
        'assigned_at',
        'deadline_at',
        'finished_at',
        'reviewed_at',
        'deferred_till',
        'notes',
        'auto_review',
        'recurring_task_id',
    ];

    public static $status = [
        'Active',
        'Finished',
        'Reviewed',
        'Dropped',
    ];

    public static $roles = [
        'observer',
        'member',
    ];

    /** RELATIONSHIPS */

    public function files()
    {
        return $this->hasMany(TaskFile::class);
    }

    public function assignor()
    {
        return $this->belongsTo(User::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class);
    }

    // For now, every "user" is an observer in the pivot table. But the role is open for future expansion
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    }

    /** SPECIAL METHODS */
}
