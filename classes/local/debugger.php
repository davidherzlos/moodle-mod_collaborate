<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Simple debugging class
 *
 * @package    mod_collaborate
 * @copyright  2020 David OC davidherzlos@gmail.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_collaborate\local;

defined('MOODLE_INTERNAL') || die();

class debugger {

    public static function log($message, $value, $backtrace = false) {
        global $CFG;
        $file = fopen($CFG->dirroot.'/mod/collaborate/debugging.log', 'a');

        if ($backtrace) {
            $exception = new \Exception();
            $trace = explode("\n", $exception->getTraceAsString());
            $trace = array_reverse($trace);
            array_shift($trace); // remove {main}
            array_pop($trace); // remove call to this method
            $length = count($trace);

            $result = array();
            for ($i = 0; $i < $length; $i++) {
                $result[] = ($i + 1)  . ')' . substr($trace[$i], strpos($trace[$i], ' ')); // replace '#someNum' with '$i)', set the right ordering
                }
            $backtrace = implode("\n", $result);
            }

        if ($file) {
            fwrite($file, print_r($message . ': ', true));
            fwrite($file, "\n");
            fwrite($file, "\n");
            fwrite($file, print_r($value, true));
            fwrite($file, "\n");
            fwrite($file, print_r($backtrace ? $backtrace . "\n \n" : "\r", true));
            fwrite($file, "------------------------------------------------");
            fwrite($file, "\n");
            fclose($file);
        }
    }
}
