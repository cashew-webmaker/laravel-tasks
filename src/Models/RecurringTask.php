<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;

class RecurringTask extends Model
{
    use RevisionableTrait;

    protected $fillable = [
        'recurring_type',
        'day_of_week',
        'day_of_month',
        'month_of_year',
        // From Tasks Table
        'name',
        'assignor_id',
        'assignee_id',
        'assigned_at',
        'deadline_at',
        'notes',
        'auto_review',
        'observers', //json
    ];

    public static $recurring_type = [
        'Daily',
        'Weekly',
        'Monthly',
        'Yearly',
    ];

    public function getAssignedAtAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function getDeadlineAtAttribute($value)
    {
        return Carbon::parse($value);
    }
}
