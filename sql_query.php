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

require_once($CFG->dirroot.'/lib/formslib.php');
require_once("$CFG->dirroot/enrol/locallib.php");
require_login();

/**
 * Create an array with course.
 * @return Array .
 */
function get_courses_array() {

    $allcourses2 = array();
    $getcourse2 = get_courses();
    foreach ($getcourse2 as $courses) {
        $allcourses2[$courses->id] = $courses->fullname;
    }

    return $allcourses2;
}

/**
 * Get records.
 * @param Mixed $courseid .
 * @return Object .
 */
function get_post_from_course($courseid) {

    global $DB;

    $posts = [];
    if (is_null($courseid) || $courseid == 0) {

        $posts = $DB->get_records_sql(
            'SELECT *
            FROM {local_textanalysis_forumpost}
            ORDER BY modified DESC;'
        );

    } else {
        $posts = $DB->get_records_sql(
            'SELECT c.*
            FROM {local_textanalysis_forumpost} c
            JOIN {forum_discussions} fd on fd.id = c.discussion
            WHERE fd.course = '.$courseid.'
            ORDER BY c.modified DESC;'
        );

    }

    return $posts;
}

/**
 * Get post negative threshold.
 * @param Mixed $courseid .
 * @param Mixed $thresholdneg .
 * @return Object .
 */
function get_post_from_course_neg_threshold($courseid, $thresholdneg) {

    global $DB;

    $posts = [];

    if (is_null($courseid) || $courseid == 0) {

        $posts = $DB->get_records_sql(
            'SELECT c.*
            FROM {local_textanalysis_forumpost} c
            WHERE c.polarity < '.$thresholdneg.'
            ORDER BY c.modified DESC;'
        );

    } else {
        $posts = $DB->get_records_sql(
            'SELECT c.*
            FROM {local_textanalysis_forumpost} c
            JOIN {forum_discussions} fd on fd.id = c.discussion
            WHERE fd.course = '.$courseid.' AND c.polarity < '.$thresholdneg.'
            ORDER BY c.modified DESC;'
        );

    }

    return $posts;

}
/**
 * Get name field from forum discussion.
 * @param Mixed $id .
 * @return Object .
 */
function get_name_discussion_by_id($id) {

    global  $DB;

    return $DB->get_record_sql(
    'SELECT fd.name
    FROM {forum_discussions} fd
    WHERE fd.id = '.$id);
}

/**
 * Get record with average polarity.
 * @param Mixed $courseid .
 * @return Object .
 */
function get_mean_from_course($courseid) {

    global $DB;

    $mean = [];
    if (is_null($courseid) || $courseid == 0) {

        $mean = $DB->get_record_sql(
            'SELECT id, Avg(polarity) as mean
            FROM  {local_textanalysis_forumpost};');

    } else {
        $mean = $DB->get_record_sql(
            'SELECT fd.course, Avg(c.polarity) as mean
            FROM {local_textanalysis_forumpost} c
            JOIN {forum_discussions} fd on fd.id = c.discussion
            WHERE fd.course = '.$courseid.';'
        );
    }
    return $mean;
}

/** Get record from user table.
 * @param Mixed $userid .
 * @return Object .
 */
function get_name_user($userid) {
    global $DB;
    $name = $DB->get_record_sql('SELECT CONCAT(firstname , " ", lastname) as name from {user} WHERE id = '.$userid.';');
    return $name;
}

/** Get record from course table.
 * @param Mixed $courseid .
 * @return Object .
 */
function get_name_course($courseid) {
    global $DB;
    $name = $DB->get_record_sql('SELECT c.fullname as name from {course} c WHERE c.id = '.$courseid.';');
    return $name;
}

