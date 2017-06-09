<?php

namespace Cashewdigital;

use Iatstuti\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;

class TaskProof extends Model
{
    use NullableFields;
    use RevisionableTrait;
    protected $revisionCreationsEnabled = true;

    protected $fillable = [
        'task_id',
        'proof_path',
        'notes',
    ];
}
