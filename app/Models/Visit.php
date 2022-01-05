<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function group() {
        return $this->belongsTo(Group::class);
    }
}
