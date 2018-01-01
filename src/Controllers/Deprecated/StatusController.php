<?php

namespace App\Http\Controllers\Pages;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ServerController as Server;

class StatusController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $channel = [];
        foreach (config('server.channel') as $key => $value) {
            $channel[$key] =+ Server::getChannelStatus($value);
        }
        $world_status = Server::getChannelStatus(config('server.port'));

        return view('pages.status')->with(compact('channel', 'world_status'));
    }
}
