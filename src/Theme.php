<?php

namespace Enigma\Theme;

class Theme
{

    /**
     * Get the current Theme, with an optional Page.
     *
     * @param  string|null $page
     * @return string
     */
    public static function make($page = null)
    {
        if ($page != null)
        {
            return config('enigma.theme.name', 'default').'.'.$page;
        }
        
        return config('enigma.theme.name', 'default');
    }
}
