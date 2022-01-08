<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $audience
 * @property string $name
 * @property int $time
 * @property int $type
 * @property int $weekday
 * @property int $weekType
 * @property int $teacher_id
 */
class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'audience',
        'name',
        'time',
        'type',
        'weekday',
        'weektype',
        'teacher_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'teacher_id'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }

    public function visitedBy() {
        return $this->belongsToMany(Group::class, Visit::class, 'subject_id', 'group_id');
    }
}
