<?php

namespace Enigma\Status\Controllers;

//use Status;
use Exception;
use Enigma\Status\Controllers\StatusController as Status;
use Illuminate\Routing\Controller;

class PageController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        if (!config('enigma.status.status_enabled', false))
        {
            $title = 'Server Status';
            return view('pages.disabled')->with(compact('title'));
        }

        Status::getServerStatus();
        $status = json_decode(Status::loadFromCache());
        $world_status = $status->world;
        $channel = $status->channels;
        $count = Status::StatusOrCount();

        return view('status::status')->with(compact('world_status', 'channel', 'count'));
    }

    /*public function showOld()
    {
        $channel = [];
        foreach (config('server.channel') as $key => $value) {
            $channel[$key] =+ Status::getChannelStatus($value);
        }
        $world_status = Status::getChannelStatus(config('server.port'));

        return view('pages.status')->with(compact('channel', 'world_status'));
    }

    public function test()
    {
        $test = json_decode(Status::getServerStatus());
        $count = Status::showOnlineCount();

        return view('pages.test')->with(compact('test', 'count'));
    }*/
}
