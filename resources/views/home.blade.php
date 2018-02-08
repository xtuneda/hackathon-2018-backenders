@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-default">

                <div class="card-header text-center">Status

                </div>

                <div class="card-body">
                    @if ($whereIAmFirstInLine->isEmpty() && $queueStatusList->isEmpty())
                        <l>You are <b>not</b> in any queue at the moment</l>
                    @endif
                    <ul>
                        @foreach ($whereIAmFirstInLine as $queueItem)
                        <li>
                            <b>It's your turn at {{ $queueItem->host->name }}'s queue</b>
                            <a href="{{ route('leave', $queueItem->host->id) }}">Leave</a>
                        </li>
                        @endforeach
                    </ul>
                    <ul>
                        @foreach ($queueStatusList as $list)
                        <li>
                            @if ($list['queueNumber'] == 1)
                            You are next in line at {{ $list['user']->name }}'s queue
                            @else
                            You have queue number {{ $list['queueNumber'] }} at {{ $list['user']->name }}'s queue
                            @endif
                            <a href="{{ route('leave', $list['user']->id) }}">Leave</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-3">
        <div class="col-md-4">
            <div class="card card-default">
                <div class="card-header text-center">Connected users</div>

                <div class="card-body">
                    <ul>
                        @foreach ($users as $user)
                        <li>
                            {{ $user->name }}
                            @if (!$user->hasUserInQueue($me))
                            <a href="{{ route('join', $user->id) }}">Join</a>
                            @endif
                            <ol>
                                @if ($user->hasUserInQueue($me))

                                @foreach ($user->queueUntilUser($me) as $item)
                                <li>
                                    @if ($item->guest->id == $me->id)
                                    {{ $item->guest->name }} (<b>Jag</b>)
                                    @else
                                    {{ $item->guest->name }}
                                    @endif
                                </li>
                                @endforeach

                                @else

                                @foreach ($user->queueNotHelped as $item)
                                <li>
                                    @if ($item->guest->id == $me->id)
                                    {{ $item->guest->name }} (<b>Jag</b>)
                                    @else
                                    {{ $item->guest->name }}
                                    @endif
                                </li>
                                @endforeach

                                @endif

                            </ol>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-default">
                <div class="card-header text-center">My queue</div>

                <div class="card-body">
                    <ol>
                        @foreach ($queue as $item)
                        <li>
                            {{ $item->guest->name }}
                            @if (!$item->isActivated())
                            <a href="{{ route('activate', $item->guest->id) }}">Activate</a>
                            <a href="{{ route('remove', $item->guest->id) }}" class="pull-right">Remove</a>
                            @else
                            <b>Currently being helped</b>
                            <a href="{{ route('done') }}">Done</a>
                            @endif
                        </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
