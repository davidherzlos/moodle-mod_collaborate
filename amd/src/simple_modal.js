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
 * Example JavaScript modal
 *
 * @module     block_superiframe
 * @copyright  2018 Flash Gordon http://www.flashgordon.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery', 'core/str', 'core/modal_factory', 'core/notification'], function($, Str, ModalFactory, Notification) {
    return {
        /**
         * Just a simple example
         *
         * @param {String} formid HTML id of form
         */
        confirm: function(formid) {
            // Prepare modal for display in case of problems.
            var modalPromise = Str.get_strings([
                {key: 'modal_title', component: 'mod_widget'},
                {key: 'modal_body', component: 'mod_widget'},
            ]).then(function(strings) {
                return ModalFactory.create({
                    type: ModalFactory.types.SAVE_CANCEL,
                    title: strings[0],
                    body: strings[1],
                });
            }).catch(Notification.exception);

            var form = $('#' + formid);
            form.submit(function(e) {

                    e.preventDefault();
                    // Display the modal.
                    return modalPromise.then(function(modal) {
                        modal.setSaveButtonText('Continue');
                        modal.show();
                        return false;
                    });

                return true;
            });
        }
    };
});
