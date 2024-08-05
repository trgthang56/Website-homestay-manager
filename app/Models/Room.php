<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_kind_of_room',
        'image',
        'name',
        'surface',
        'number',
        'capacity',
        'status',
        'bed',
        'price',
        'room_amenity',
        'bathroom_amenity',
        'description',
    ];

    public function outputkindofroom()
    {
        return $this->hasOne(Kind_of_room::class, 'id', 'id_kind_of_room')->withDefault(['kind_of_room' => '']);
    }
}
