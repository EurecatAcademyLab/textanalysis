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

// Include config.php.
require_once(__DIR__.'/../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/gdlib.php');
require_once($CFG->dirroot.'/user/lib.php');
require_once('./forum_posts.php');
require_once('./show_post.php');
require_once('./show_fbq.php');
require_once('./graphs.php');
require_once('./selector_forms.php');
require_once('./sql_query.php');
require_once('./header.php');
require_login();


// Globals.
global $CFG, $OUTPUT, $USER, $SITE, $PAGE;

$PAGE->requires->css( new moodle_url($CFG->wwwroot . '/local/textanalysis/css/style.css'));

// Include our function library.
$pluginname = 'textanalysis';

$homeurl = new moodle_url('/');
require_login();

if (!is_siteadmin() && $datos->teacher == 0) {
    redirect($homeurl, "This feature is only available for site administrators.", 5);
}

// URL Parameters.
// There are none.

// Heading.
// --------------------------------------------.

$title = get_string('pluginname', 'local_'.$pluginname);
$heading = get_string('pluginname', 'local_'.$pluginname);
$url = new moodle_url('/local/'.$pluginname.'/');
if ($CFG->version >= 2013051400) { // Moodle 2.5+.
    $context = context_system::instance();
} else {
    $context = get_system_context();
}

$PAGE->set_pagelayout('admin');
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title($title);
$PAGE->set_heading($heading);

if (!$site = get_site()) {
    error("Site isn't defined!");
}

if (!empty($USER->newadminuser)) {
    ignore_user_abort(true);
    $PAGE->set_course($SITE);
    $PAGE->set_pagelayout('maintenance');
} else {
    $PAGE->set_context(context_system::instance());
    $PAGE->set_pagelayout('admin');
}

$renderer = $PAGE->get_renderer('core_enrol');

echo $OUTPUT->header();

$output = "";

$dform = new select_course();

$courseselected = null;
$thresholdneg = -0.3;
$thresholdpos = 0.3;
$onlybad = 0;
$translation = 0;

if ($fromform = $dform->get_data()) {
    require_sesskey();
    $courseselected = $fromform->course;
    $onlybad = $fromform->onlybad;
    $translation = $fromform->show_en;

    $thresholdneg = $fromform->thresholdneg;
    $thresholdpos = $fromform->thresholdpos;

    $dform->display();

} else {
    $dform->display();
}

$output .= html_header($courseselected);

$output .= html_writer::start_tag('div', ['class' => 'pt-2']);
$output .= html_writer::start_tag('ul', ["class" => 'nav nav-tabs', 'role' => "tablist"]);
    $output .= html_writer::start_tag('li', ['class' => 'nav-item waves-effect waves-light']);
        $output .= html_writer::tag('a', get_string('Posts', 'local_textanalysis'), ['class' => 'nav-link active',
        'data-toggle' => "tab", 'href' => "#postsTab"]);
        $output .= html_writer::end_tag('li');
        $output .= html_writer::start_tag('li', ['class' => 'nav-item waves-effect waves-light']);
        $output .= html_writer::tag(
            'a',
            get_string('feedback', 'local_textanalysis'),
            ['class' => 'nav-link', 'data-toggle' => "tab", 'href' => "#feedbackQ"]);
        $output .= html_writer::end_tag('li');
        $output .= html_writer::start_tag('li', ['class' => 'nav-item waves-effect waves-light']);
        $output .= html_writer::tag(
            'a',
            get_string('Analytics', 'local_textanalysis'),
            ['class' => 'nav-link', 'data-toggle' => "tab", 'href' => "#graph"]);
        $output .= html_writer::end_tag('li');
        $output .= html_writer::end_tag('ul');
        $output .= html_writer::end_tag('div');

        $output .= html_writer::start_tag('div', ['class' => 'tab-content']);
        $output .= html_writer::start_tag('div', ['class' => 'tab-pane fade show active', 'id' => 'postsTab']);
        $output .= html_writer::start_tag('div', ['class' => 'p-1']);
        $post = new Post_view($thresholdneg, $thresholdpos, $courseselected, $onlybad, $translation);
        $output .= utf8_decode($post->printar());
        $output .= html_writer::end_tag('div');
        $output .= $OUTPUT->download_dataformat_selector(
            get_string('userbulkdownload', 'admin'),
            'download_posts.php',
            'download',
            array('thN' => $thresholdneg, 'curseSelected' => $courseselected, 'onlyB' => $onlybad));
        $output .= html_writer::end_tag('div');
        $output .= html_writer::start_tag('div', ['class' => 'tab-pane fade show active', 'id' => 'feedbackQ']);
        $output .= html_writer::start_tag('div', ['class' => 'p-1']);
        $fbq = new Feedback_view($thresholdneg, $thresholdpos, $courseselected, $onlybad, $translation);
        $output .= utf8_decode($fbq->printar());
        $output .= html_writer::end_tag('div');

        // TO DO.
        $output .= $OUTPUT->download_dataformat_selector(
            get_string('userbulkdownload', 'admin'),
            'download_posts.php',
            'download',
            array('thN' => $thresholdneg, 'curseSelected' => $courseselected, 'onlyB' => $onlybad));
        $output .= html_writer::end_tag('div');
        $output .= html_writer::start_tag('div', ['class' => 'tab-pane fade', 'id' => 'graph']);
        $outputgraphs = graphposts($thresholdneg, $thresholdpos , $courseselected);
        $output .= $outputgraphs;
        $output .= html_writer::tag('i', '', ['class' => 'fa fa-print']);
        $output .= html_writer::tag(
            'a',
            '  '.  get_string('printAnalysis', 'local_textanalysis'),
            ['href' => '#', 'class' => 'mt-3', 'onclick' => 'print()', 'role' => 'button']);

        $output .= html_writer::end_tag('div');

        $output .= html_writer::end_tag('div');

        echo $output;

        echo $OUTPUT->footer();


