<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Http\Controllers\Controller;

class ServerController extends Controller
{

    private static $_showOnlineCount_result = null;

    public static function showOnlineCount()
    {
        if (self::$_showOnlineCount_result === null) {
            $socket = self::getChannelStatus(config('server.port', 8484));
            // Storing the result in a static class property
            // for additional usages during current Request Life Cycle
            self::$_showOnlineCount_result = $socket ? number_format(User::where('loggedin', '>', '0')->count()) : 'OFFLINE';
        }

        return self::$_showOnlineCount_result;
    }


    public static function getChannelStatus(int $port = 8484)
    {
        $socket = false;
        try
        {
            $socket = fsockopen(
                config('server.ip', '127.0.0.1'),
                $port,
                $errno,
                $errstr,
                config('server.time_limit', 0.5)
            );
            if ($socket) {
                fclose($socket);
                $socket = true;
            }
        }
        catch (Exception $e)
        {
            // if this code block runs, $socket failed and is still set to false,
            // let's set it to false manualy for clarity and readability
            $socket = false;
        }

        return $socket;
    }
}
