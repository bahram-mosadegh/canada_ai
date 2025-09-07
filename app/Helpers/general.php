<?php

if (! function_exists('asset_versioned')) {
    function asset_versioned($path)
    {
        $public_path = public_path($path);
        $versioned_path = $path . '?' . sha1_file($public_path);

        return asset($versioned_path);
    }
}
