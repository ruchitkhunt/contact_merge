<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'is_active'];

    public function customFields()
    {
        return $this->hasMany(ContactCustomField::class);
    }

    public function mergedContacts()
    {
        return $this->hasMany(MergedContact::class, 'master_contact_id');
    }
}
