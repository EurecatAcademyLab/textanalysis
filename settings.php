<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin version and other meta-data are defined here.
 *
 * @package     local_textanalysis
 * @author      2022 Aina Palacios
 * @copyright   2022 Aina Palacios & Eurecat.dev
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $ADMIN->add(
        'localplugins',
        new admin_category('local_textanalysis_settings',
        new lang_string('pluginname', 'local_textanalysis')));
    $settingspage = new admin_settingpage('managelocaltextanalysis', new lang_string('manage', 'local_textanalysis'));

    if ($ADMIN->fulltree) {
        $settingspage->add(new admin_setting_configcheckbox(
            'local_textanalysis/showinnavigation',
            new lang_string('showinnavigation', 'local_textanalysis'),
            new lang_string('showinnavigation_desc', 'local_textanalysis'),
            1
        ));

        $settingspage->add(
            new admin_setting_configtext('local_textanalysis/apikey',
            new lang_string('apikey', 'local_textanalysis'),
            new lang_string('apikey_des', 'local_textanalysis'), null, PARAM_TEXT));
    }

    $ADMIN->add('localplugins', $settingspage);
}

