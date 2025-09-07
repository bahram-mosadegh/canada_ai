<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $guarded = [];

    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getBeautifyAttribute()
    {
        $data = $this->data;

        if (json_validate($data)) {
            $log = json_decode($data);
            $output = '';
            foreach ($log as $key => $segment) {
                $output .= '<b>'.__('message.'.$key).':</b>';
                foreach ($segment as $part_name => $part) {
                    if (!is_numeric($part_name)) {
                        $part_name_label = ' <b>'.__('message.'.$part_name).'</b>';
                    } else {
                        $part_name_label = ' <b>'.__('message.'.$key).'</b>';
                    }

                    foreach ($part as $action_name => $action) {
                        if ($action_name == 'add') {
                            $output .= $part_name_label.' "'.static::str_beautifier($action[0]).'" '.__('message.created');
                        } elseif ($action_name == 'remove') {
                            $output .= $part_name_label.' "'.static::str_beautifier($action[0]).'" '.__('message.removed');
                        } elseif ($action_name == 'edit') {
                            $from = array_key_first((array)$action);
                            $output .= $part_name_label.' '.sprintf(__('message.changed_from_to'), static::str_beautifier($from), static::str_beautifier($action->$from));
                        } elseif($action_name == 'other') {
                            $output .= ' '.static::str_beautifier($action[0]);
                        } else {
                            $output .= $part_name_label.' '.static::str_beautifier($action[0]);
                        }
                    }
                }
                $output .= '<br>';
            }
            return $output;
        } else {
            return '';
        }
    }

    public static function str_beautifier($string = '', $str_max_len = 150)
    {
        $string = strip_tags($string);
        if (strlen($string) > $str_max_len) {
            $string = mb_substr($string, 0, $str_max_len).'...';
        }
        return $string;
    }
}
