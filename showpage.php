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
 * Prints a user page
 *
 * @package    mod_collaborate
 * @copyright  2019 Richard Jones richardnz@outlook.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @see https://github.com/moodlehq/moodle-mod_collaborate
 * @see https://github.com/justinhunt/moodle-mod_collaborate */


require_once('../../config.php');
require_once(dirname(__FILE__).'/lib.php');

// The user page id and the collaborate instance id.
$page = required_param('page', PARAM_TEXT);
$cid = required_param('cid', PARAM_INT);

// Get the information required to check the user can access this page.
$collaborate = $DB->get_record('collaborate', ['id' => $cid], '*', MUST_EXIST);
$courseid = $collaborate->course;
$cm = get_coursemodule_from_instance('collaborate', $cid, $courseid, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
$context = context_module::instance($cm->id);

// Set the page URL.
$PAGE->set_url('/mod/collaborate/showpage.php', ['cid' => $cid, 'page' => $page]);

// Check the user is logged on.
require_login($course, true, $cm);

// Set the page information.
$PAGE->set_title(format_string($collaborate->name));
$PAGE->set_heading(format_string($course->fullname));

// Completion tracking
// Let's consider the activity "viewed" at this point.
$completion = new completion_info($course);
$completion->set_module_viewed($cm);

// Events
// Let's add the module viewed event. This may be seen in the standard log
$event = \mod_collaborate\event\page_viewed::create(['context' => $PAGE->context]);
$event->trigger();

// The renderer performs output to the page.
$renderer = $PAGE->get_renderer('mod_collaborate');

// Call the renderer method to display the collaborate intro content.
$renderer->render_view_page_content($collaborate, $cm);
