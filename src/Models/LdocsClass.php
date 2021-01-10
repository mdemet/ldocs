<?php

namespace Mdemet\Ldocs\Models;

use Illuminate\Database\Eloquent\Model;

class LdocsClass extends Model
{
    public function namespace() {
        return $this->belongsTo("Mdemet\Ldocs\Models\LdocsClassNamespace");
    }
    public function methods() {
        return $this->hasMany("Mdemet\Ldocs\Models\LdocsClassMethod");
    }
}
