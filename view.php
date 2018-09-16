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
 * Prints a particular instance of widget
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_widget
 * @copyright  2018 Richard Jones richardnz@outlook.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @see https://github.com/moodlehq/moodle-mod_newmodule
 * @see https://github.com/justinhunt/moodle-mod_widget */

require_once('../../config.php');
require_once(dirname(__FILE__).'/lib.php');

// We need the course module id (id) or
// the widget instance id (n).
$id = optional_param('id', 0, PARAM_INT);
$n  = optional_param('n', 0, PARAM_INT);

// Determine page action (redirect or not).
$action = optional_param('action', 'view', PARAM_ALPHA);
if ($id) {
    $cm = get_coursemodule_from_id('widget', $id, 0, false,
            MUST_EXIST);
    $course = $DB->get_record('course',
            array('id' => $cm->course), '*', MUST_EXIST);
    $widget = $DB->get_record('widget',
            array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $widget = $DB->get_record('widget', array('id' => $n), '*',
            MUST_EXIST);
    $course = $DB->get_record('course',
            array('id' => $widget->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('widget', $widget->id,
            $course->id, false, MUST_EXIST);
} else {
    // Moodle Developer debugging called.
    debugging('Error: No course_module ID or instance ID',
            DEBUG_DEVELOPER);
}

require_login($course, true, $cm);

// Record the module viewed event for logging.
$event = \mod_widget\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $widget);
$event->trigger();

// Print the page header.
$PAGE->set_url('/mod/widget/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($widget->name));
$PAGE->set_heading(format_string($course->fullname));

// The renderer performs output to the page.
$renderer = $PAGE->get_renderer('mod_widget');

// Check for intro page content.
if (!$widget->intro) {
    $widget->intro = '';
}

if ($action == 'course') {
    redirect($PAGE->course, 'Back to course', 2);
}
// Start the page, call renderer to show content.
echo $OUTPUT->header();
echo $renderer->fetch_view_page_content($widget, $cm);

// Sample JS modal. Return to course button.
$PAGE->requires->js_call_amd('mod_widget/simple_modal',
        'confirm', ['modalform']);
$action_url = new moodle_url('view.php', ['n' => $widget->id,
        'action' => 'course']);

echo "<form method=\"post\" action=$action_url id=\"modalform\">";
echo '<br />';
echo '<input type="submit" class="btn btn-primary"
        value="'.get_string("return_course", "mod_widget") .
        '" />';
echo "</form>";

echo $OUTPUT->footer();