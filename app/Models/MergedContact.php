<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MergedContact extends Model
{
    protected $fillable = ['master_contact_id', 'merged_contact_id'];

    public function master()
    {
        return $this->belongsTo(Contact::class, 'master_contact_id');
    }

    public function merged()
    {
        return $this->belongsTo(Contact::class, 'merged_contact_id');
    }
}
