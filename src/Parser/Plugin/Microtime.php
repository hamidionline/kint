<?php

namespace Kint\Parser\Plugin;

use Kint\Object;

class Microtime extends \Kint\Parser\Plugin
{
    private static $last = null;
    private static $start = null;
    private static $times = 0;

    public function parse(&$var, Object &$o)
    {
        if (!is_string($var) || !preg_match('/0\.[0-9]{8} [0-9]{10}/', $var)) {
            return;
        }

        if ($o->name != 'microtime()' || $o->depth != 0) {
            return;
        }

        list($usec, $sec) = explode(' ', $var);

        $time = (float) $usec + (float) $sec;

        if (self::$last !== null) {
            $last_time = array_sum(array_map('floatval', explode(' ', self::$last)));
            $lap = $time - $last_time;
            ++self::$times;
        } else {
            $lap = null;
            self::$start = $time;
        }

        self::$last = $var;

        if ($lap !== null) {
            $total = $time - self::$start;
            $r = new Object\Representation\Microtime($lap, $total, ($total / self::$times));
        } else {
            $r = new Object\Representation\Microtime();
        }
        $r->contents = $var;

        $o->replaceContentsOrDefault($r);
    }
}