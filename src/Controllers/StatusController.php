<?php

namespace Enigma\Status\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\User;

class StatusController
{

    /**
     * 
     * 
     */
    protected static $_showOnlineCount_result = null;

    /**
     * 
     * 
     */
    protected static $_cachedStatus = null;
    protected static $source = null;

    
    /**
     * 
     * 
     */
    public static function cacheExists()
    {
        return Storage::exists('sockets.json');
    }

    
    /**
     * 
     * 
     */
    public static function loadFromCache()
    {
        return Storage::get('sockets.json');
    }

    
    /**
     * 
     * 
     */
    public static function storeCache($contents)
    {
        //return File::put($file, $contents);
        return Storage::disk('local')->put('sockets.json', $contents);
    }

    
    /**
     * 
     * 
     */
    public static function getCacheTime()
    {
        return Storage::lastModified('sockets.json');
    }

    
    /**
     * 
     * 
     */
    public static function StatusOrCount()
    {
        if (static::$_showOnlineCount_result === null)
        {
            if (isset(static::$_cachedStatus->world))
            {
                $socket = static::$_cachedStatus->world;
            }
            else
            {
                if (self::cacheExists())
                {
                    static::$_cachedStatus = json_decode(static::loadFromCache());
                }
                else
                {
                    static::$_cachedStatus = static::generateServerStatus();
                }
                
                $socket = static::$_cachedStatus->world;
                //$socket = json_decode(self::getServerStatus())->world;
            }

            // Storing the result in a static class property
            // for additional usages during current Request Life Cycle
            static::$_showOnlineCount_result = $socket ? number_format(User::where('loggedin', '>', '0')->count()) : 'OFFLINE';
        }

        return static::$_showOnlineCount_result;
    }

    
    /**
     * 
     * 
     */
    public static function getServerStatus()
    {
        if (static::$_cachedStatus == null)
        {
            if (static::cacheExists())
            {
                if (now()->subMinute()->lessThan(new Carbon(static::getCacheTime())))
                {
                    static::$source = 'load from cache';
                    static::$_cachedStatus = static::loadFromCache();
                }
                else
                {
                    static::$source = 'regenerate';
                    static::$_cachedStatus = static::generateServerStatus();
                }
            }
            else
            {
                // open sockets, store to cache and load
                static::$_cachedStatus = static::generateServerStatus();
            }
        }
        else
        {
            static::$_cachedStatus = static::generateServerStatus();
        }
        return static::$_cachedStatus;
    }

    
    /**
     * 
     * 
     */
    public static function generateServerStatus()
    {
        $channel_socket = [];

        foreach (config('enigma.status.channel') as $key => $value)
        {
            $channel_socket[$key] =+ static::openSocket($value);
        }

        $world_socket = static::openSocket(config('enigma.status.port'));

        $status = [
            'world' => $world_socket,
            'channels' => $channel_socket,
            'time' => now(),
            'cachetime' => now(),
            'source' => static::$source,
            'older' => now()->subMinute()->greaterThan(now())
        ];

        static::storeCache(json_encode($status));
        return $status;
    }

    
    /**
     * 
     * 
     */
    protected static function openSocket(int $port = 8484)
    {
        $socket = false;
        try
        {
            $socket = fsockopen(
                config('enigma.status.ip', '127.0.0.1'),
                $port,
                $errno,
                $errstr,
                config('enigma.status.time_limit', 0.5)
            );
            if ($socket)
            {
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

        return (boolean) $socket;
    }
}
