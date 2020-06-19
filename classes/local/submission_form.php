<?php
/**
 * Form for student submissions.
 *
 * @package   mod_collaborate
 * @copyright 2018 Richard Jones https://richardnz.net
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_collaborate\local;
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');
require_once($CFG->libdir . '/filelib.php');

class submission_form extends \moodleform {
    public function definition() {
        global $DB;
        $mform = $this->_form;
        $context = $this->_customdata['context'];
        $options = collaborate_editor::get_editor_options($context);
        $mform->addElement('editor', 'submission_editor', get_string('submission', 'mod_collaborate'), null, $options);
        // Remember stick with this naming style.
        $mform->setType('submission_editor', PARAM_RAW);
        $mform->addElement('hidden', 'cid', $this->_customdata['cid']);
        $mform->setType('cid', PARAM_INT);
        $mform->addElement('hidden', 'page', $this->_customdata['page']);
        $mform->setType('page', PARAM_TEXT);
        // Add a save button.
        $this->add_action_buttons(false, get_string('submissionsave', 'mod_collaborate'));
    }
    // Standard Moodle function for editor area preprocessing.
    function data_preprocessing(&$default_values) {
        if ($this->current->instance) {
            $context = $this->context;
            $options = collaborate_editor::get_editor_options($context);
            $default_values = (object) $default_values;
            $default_values = file_prepare_standard_editor(
                    $default_values,
                    'submission',
                    $options,
                    $context,
                    'mod_collaborate',
                    'submission',
                    $default_values->id);
        }
    }
}
