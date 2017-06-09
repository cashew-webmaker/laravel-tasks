<?php

namespace Cashewdigital;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class TaskFile extends Model
{
    use RevisionableTrait;
    use SoftDeletes;

    // We will be using dropbox for our files. We dont need them hot. br
    protected $fillable = [
        'task_id',
        'path',
        'owner_id',
    ];

    protected $dates = ['deleted_at'];

    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}
