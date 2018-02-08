<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    public function host()
    {
        return $this->belongsTo('App\User', 'id', 'host_user_id');
    }

    public function guest()
    {
        return $this->hasOne('App\User', 'id', 'guest_user_id');
    }

    public function done()
    {
        $this->done_at = Carbon::now();
        $this->save();
    }

    public function activate()
    {
        $this->activated_at = Carbon::now();
        $this->save();
    }

    public function isActivated()
    {
        return !is_null($this->activated_at);
    }
}
