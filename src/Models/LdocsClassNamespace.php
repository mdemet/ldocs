<?php

namespace Mdemet\Ldocs\Models;

use Illuminate\Database\Eloquent\Model;

class LdocsClassNamespace extends Model
{
    public function type() {
        return $this->belongsTo("Mdemet\Ldocs\Models\LdocsClassType");
    }

    public function classes() {
        return $this->hasMany("Mdemet\Ldocs\Models\LdocsClass");
    }

    public function activeClasses() {
        return $this->hasMany("Mdemet\Ldocs\Models\LdocsClass")->where('active', 1);
    }    
}
