<?php

namespace Mdemet\Ldocs\Models;

use Illuminate\Database\Eloquent\Model;

class LdocsClassMethod extends Model
{
    public function class() {
        return $this->belongsTo("Mdemet\Ldocs\Models\LdocsClass");
    }
}
