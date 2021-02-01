<?php

namespace common\helpers\objectViews;

class DurationView
{
    public static function format(int $durationInSeconds)
    {
        if ($durationInSeconds <= 3600) {
            $mins = floor($durationInSeconds / 60);
            $seconds = $durationInSeconds - ($mins * 60);

            return vsprintf("%d:%d", [
                $mins,
                str_pad($seconds, 2, '0', STR_PAD_LEFT)
            ]);
        } else {
            $hours = floor($durationInSeconds / 3600);
            $mins = floor(($durationInSeconds - ($hours * 3600)) / 60);
            $seconds = $durationInSeconds - ($hours * 3600) - ($mins * 60);

            return vsprintf("%d:%s:%s", [
                $hours,
                str_pad($mins, 2, '0', STR_PAD_LEFT),
                str_pad($seconds, 2, '0', STR_PAD_LEFT),
            ]);
        }
    }
}