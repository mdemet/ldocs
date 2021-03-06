<?php

namespace Mdemet\Ldocs\Models;

use Illuminate\Database\Eloquent\Model;

class LdocsClassType extends Model
{

    public function namespaces() {
        return $this->hasMany("Mdemet\Ldocs\Models\LdocsClassNamespace");
    }

    public function activeNamespaces() {
        return $this->hasMany("Mdemet\Ldocs\Models\LdocsClassNamespace")->where('active', 1);
    }
}
