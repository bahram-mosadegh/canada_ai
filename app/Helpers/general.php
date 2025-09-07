<?php

if (! function_exists('asset_versioned')) {
    function asset_versioned($path)
    {
        $public_path = public_path($path);
        $versioned_path = $path . '?' . sha1_file($public_path);

        return asset($versioned_path);
    }
}
if (! function_exists('gregorian_to_jalali')) {
    function gregorian_to_jalali($gy, $gm, $gd, $mod = '') {
        list($gy, $gm, $gd) = explode('_', tr_num($gy . '_' . $gm . '_' . $gd));
        $g_d_m = array(0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334);
        $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
        $days = 355666 + (365 * $gy) + ((int) (($gy2 + 3) / 4)) - ((int) (($gy2 + 99) / 100)) + ((int) (($gy2 + 399) / 400)) + $gd + $g_d_m[$gm - 1];
        $jy = -1595 + (33 * ((int) ($days / 12053)));
        $days %= 12053;
        $jy += 4 * ((int) ($days / 1461));
        $days %= 1461;
        if ($days > 365) {
            $jy += (int) (($days - 1) / 365);
            $days = ($days - 1) % 365;
        }
        if ($days < 186) {
            $jm = 1 + (int) ($days / 31);
            $jd = 1 + ($days % 31);
        } else {
            $jm = 7 + (int) (($days - 186) / 30);
            $jd = 1 + (($days - 186) % 30);
        }
        return ($mod == '') ? array($jy, $jm, $jd) : $jy . $mod . sprintf("%02d", $jm) . $mod . sprintf("%02d", $jd);
    }
}

if (! function_exists('jalali_to_gregorian')) {
    function jalali_to_gregorian($jy, $jm, $jd, $mod = '') {
        list($jy, $jm, $jd) = explode('_', tr_num($jy . '_' . $jm . '_' . $jd));
        $jy += 1595;
        $days = -355668 + (365 * $jy) + (((int) ($jy / 33)) * 8) + ((int) ((($jy % 33) + 3) / 4)) + $jd + (($jm < 7) ? ($jm - 1) * 31 : (($jm - 7) * 30) + 186);
        $gy = 400 * ((int) ($days / 146097));
        $days %= 146097;
        if ($days > 36524) {
            $gy += 100 * ((int) (--$days / 36524));
            $days %= 36524;
            if ($days >= 365) $days++;
        }
        $gy += 4 * ((int) ($days / 1461));
        $days %= 1461;
        if ($days > 365) {
            $gy += (int) (($days - 1) / 365);
            $days = ($days - 1) % 365;
        }
        $gd = $days + 1;
        $sal_a = array(0, 31, (($gy % 4 == 0 and $gy % 100 != 0) or ($gy % 400 == 0)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        for ($gm = 0; $gm < 13 and $gd > $sal_a[$gm]; $gm++) $gd -= $sal_a[$gm];
        return ($mod == '') ? array($gy, $gm, $gd) : $gy . $mod . sprintf("%02d", $gm) . $mod . sprintf("%02d", $gd);
    }
}

if (! function_exists('tr_num')) {
    function tr_num($str, $mod = 'en', $mf = '٫') {
        $num_a = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.');
        $key_a = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', $mf);
        return ($mod == 'fa') ? str_replace($num_a, $key_a, $str) : str_replace($key_a, $num_a, $str);
    }
}
