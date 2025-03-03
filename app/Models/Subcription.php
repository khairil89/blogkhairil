<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Subscription extends Model
{
    protected $fillable = ['member_id', 'plan', 'price', 'start_date', 'end_date'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
