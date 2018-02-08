<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Queue;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $me = Auth::user();
        $whereIAmFirstInLine = collect();
        User::where('id', '!=', Auth::user()->id)->get()->each(function($user) use ($me, $whereIAmFirstInLine) {
            $queue = $user->queueActivatedNotDone()->where('guest_user_id', $me->id)->first();
            if (!is_null($queue)) {
                $whereIAmFirstInLine->push($queue);
            }
        });

        $queueStatusList = collect();
        User::where('id', '!=', Auth::user()->id)->get()->each(function($user) use ($me, $queueStatusList) {
            $queues = $user->queueNotHelped()->where('host_user_id', $user->id)->get();
            $queueNumber = $queues->search(
                function($queueItem) use ($me) {
                    return $queueItem->guest_user_id == $me->id;
                }
            );
            if ($queueNumber === false) {
                return;
            }
            $queueStatusList->push([
                'user' => $user,
                'queueNumber' => ++$queueNumber,
            ]);
        });

        $queueStatusList = $queueStatusList->sortBy('queueNumber');

        return view('home')->with([
            'users' => User::where('id', '!=', Auth::user()->id)->get(),
            'queue' => Auth::user()->queue()->whereNull('done_at')->get(),
            'me' => Auth::user(),
            'whereIAmFirstInLine' => $whereIAmFirstInLine,
            'queueStatusList' => $queueStatusList,
        ]);
    }

    public function join($userId)
    {
        $me = Auth::user();
        if ($userId == $me->id) {
            abort('403', 'You cannot join your own queue');
        }

        $user = User::FindOrFail($userId);
        if ($user->hasUserInQueue($me)) {
            abort('403', 'You are already in this queue');
        }

        $user->addToQueue($me);

        return redirect()->route('home');
    }

    public function leave($userId)
    {
        $me = Auth::user();
        if ($userId == $me->id) {
            abort('403', 'You cannot leave your own queue');
        }

        $user = User::FindOrFail($userId);
        $user->removeFromQueue($me);

        return redirect()->route('home');
    }

    public function activate($userId)
    {
        $me = Auth::user();
        $firstInLine = $me->queueActivatedNotDone->first();
        if (!is_null($firstInLine)) {
            $firstInLine->done();
        }

        $nextInLine = $me->queueNotHelped->where('guest_user_id', $userId)->first();
        if (!is_null($nextInLine)) {
            $nextInLine->activate();
        }

        return redirect()->route('home');
    }

    public function remove($userId)
    {
        $me = Auth::user();
        $queueItem = $me->queueNotHelped->where('guest_user_id', $userId)->first();
        if (!is_null($queueItem)) {
            $queueItem->delete();
        }

        return redirect()->route('home');
    }

    public function done()
    {
        $me = Auth::user();
        $firstInLine = $me->queueActivatedNotDone->first();
        if (!is_null($firstInLine)) {
            $firstInLine->done();
        }

        return redirect()->route('home');
    }
}
