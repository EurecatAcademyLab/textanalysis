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


require_once(__DIR__.'/../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/gdlib.php');
require_once($CFG->dirroot.'/user/lib.php');
require_login();

global $DB, $USER;

$dataformat = required_param('download', PARAM_ALPHA);
$download = required_param('download', PARAM_ALPHA);
$thresholdneg = required_param('thN', PARAM_FLOAT);
$thresholdneg = required_param('thN', PARAM_FLOAT);
$courseselected = required_param('curseSelected', PARAM_INT);
$onlybad = required_param('onlyB', PARAM_BOOL);

// ... id_post, user, message, messange_translation discussion, polarity, language, class_id, class_name.
$columns = array(
    'id_post' => get_string('idnumber'),
    'name' => get_string('name', 'local_textanalysis'),
    'message' => get_string('message', 'local_textanalysis'),
    'message_trans' => get_string('message_trans', 'local_textanalysis'),
    'polarity' => get_string('polarity', 'local_textanalysis'),
    'language' => get_string('language', 'local_textanalysis'),
    'discussion' => get_string('discussion', 'local_textanalysis'),
    'class_id' => get_string('class_id', 'local_textanalysis'),
    'class_name' => get_string('class_name', 'local_textanalysis')
);

$sql = 'SELECT c.idpost as id_post, CONCAT(u.firstname , " ", u.lastname) as name,
    c.message as message, c.translation as message_trans, c.polarity as polarity,
    c.language as language, fd.name as discussion, course.id as class_id, course.fullname as class_name
    FROM {local_textanalysis_forumpost} c
    JOIN {forum_discussions} fd on fd.id = c.discussion
    JOIN {user} u on u.id = c.userid
    JOIN {course} course on course.id = fd.course';


if ($onlybad) {
    if (is_null($courseselected) || $courseselected == 0) {
        $sql .= ' WHERE c.polarity < '.$thresholdneg;

    } else {
        $sql .= ' WHERE fd.course = '.$courseselected.' AND c.polarity < '.$thresholdneg;
    }

} else {
    if (isset($courseselected) || $courseselected != 0) {
        $sql .= ' WHERE fd.course = '.$courseselected;
    }
}
$sql .= ' ORDER BY c.modified DESC;';

if (ob_get_length()) {
    throw new coding_exception("Output can not be buffered before calling download_as_dataformat");
}
$classname = 'dataformat_' . $dataformat . '\writer';
if (!class_exists($classname)) {
    throw new coding_exception("Unable to locate dataformat/$dataformat/classes/writer.php");
}
$format = new $classname;

// The data format export could take a while to generate...
set_time_limit(0);

// Close the session so that the users other tabs in the same session are not blocked.
\core\session\manager::write_close();

// If this file was requested from a form, then mark download as complete (before sending headers).
\core_form\util::form_download_complete();




$filename = date("dmY");
$format->set_filename('sentiment_analysis'.$filename);
$format->send_http_headers();
// This exists to support all dataformats - see MDL-56046.
if (method_exists($format, 'write_header')) {
    $format->write_header($columns);
} else {
    $format->start_output();
    $format->start_sheet($columns);
}

if ($rs = $DB->get_records_sql($sql)) {
    $c = 0;
    foreach ($rs as $key => $value) {
        $row = [];
        $row[] = $value->id_post;
        $row[] = $value->name;
        $row[] = strip_tags($value->message);
        $row[] = $value->message_trans;
        $row[] = $value->polarity;
        $row[] = $value->language;
        $row[] = $value->discussion;
        $row[] = $value->class_id;
        $row[] = $value->class_name;
        $format->write_record($row, $c++);
    }
}


if (method_exists($format, 'write_footer')) {
    $format->write_footer($columns);
} else {
    $format->close_sheet($columns);
    $format->close_output();
}

