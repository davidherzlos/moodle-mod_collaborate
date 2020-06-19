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
 * Plugin administration pages are defined here.
 *
 * @package     mod_collaborate
 * @category    admin
 * @copyright   2020 David Ordonez <davidherzlos@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Require the lib
require_once($CFG->dirroot . '/mod/collaborate/lib.php');

if ($ADMIN->fulltree) {
    // Header
    $name = get_string('collaboratesettings', 'mod_collaborate');
    $description = '';
    $header = new admin_setting_heading('settings_header', $name, $description);
    $settings->add($header);

    // Enable reports for teachers
    $name = 'mod_collaborate/enablereports';
    $visiblename = get_string('enablereports', 'mod_collaborate');
    $description = get_string('enablereports_desc', 'mod_collaborate');
    $setting = new admin_setting_configcheckbox($name, $visiblename, $description, 0);
    $settings->add($setting);

}
