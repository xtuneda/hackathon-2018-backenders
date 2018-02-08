<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function queue()
    {
        return $this->hasMany('App\Queue', 'host_user_id');
    }

    public function queueNotHelped()
    {
        return $this->queue()->whereNull('activated_at')->whereNull('done_at');
    }

    public function queueActivatedNotDone()
    {
        return $this->queue()->whereNotNull('activated_at')->whereNull('done_at');
    }

    public function queueNumber(User $user)
    {
        $queues = $user->queueNotHelped()->where('host_user_id', $this->id)->get();
        $queueNumber = $queues->search(
            function($queueItem) use ($me) {
                return $queueItem->guest_user_id == $me->id;
            }
        );
        if ($queueNumber === false) {
            return null;
        }
        return $queueNumber;
    }

    public function hasUserInQueue(User $user)
    {
        return !$this->queue
        ->filter(function($item) use ($user) {
            return $item->guest_user_id == $user->id;
        })->isEmpty();
    }

    public function queueUntilUser(User $user)
    {
        $queueId = $this->queue
        ->where('guest_user_id', $user->id)
        ->first()
        ->id;

        return $this->queue->where('id', '<=', $queueId);
    }

    public function addToQueue(User $user)
    {
        $queue = $this->queue;
        $queueItem = new Queue();
        $queueItem->host_user_id = $this->id;
        $queueItem->guest_user_id = $user->id;

        $queue->push($queueItem);
        $this->queue()->saveMany($queue);
    }

    public function removeFromQueue(User $user)
    {
        $this->queue->where('guest_user_id', $user->id)
        ->first()
        ->delete();
    }
}
