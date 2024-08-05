<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_customer',
        'id_room',
        'id_service',
        'set_for_other',
        'name',
        'phone',
        'email',
        'number_of_guest',
        'guest_detail',
        'number_of_room',
        'total',
        'special_requirement',
        'check_in_date',
        'check_out_date',
        'status',
        'payment_method',
        'payment_status',
        'checkInOut',
        'accepted_date',
        'timestamp'
    ];

    public function outputkindofroom()
    {
        return $this->hasOne(Kind_of_room::class, 'id', 'id_kind_of_room')->withDefault(['kind_of_room' => '']);

    }

    public function outputroom()
    {
        return $this->hasOne(Room::class, 'id', 'id_room')->withDefault(['name' => '']);
    }
    public function outputservice()
    {
        return $this->hasOne(Service::class, 'id', 'id_service')->withDefault(['name' => '']);
    }
}
