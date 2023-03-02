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
require_once('./sql_query.php');
require_login();

/**
 * To get the threshold negative or positive.
 */
class Feedback_view {
    /**
     * @var Mixed $thresholdneg.
     */
    public $thresholdneg;
    /**
     * @var Mixed $thresholdpos.
     */
    public $thresholdpos;
    /**
     * @var Mixed $courseselected.
     */
    public $courseselected;
    /**
     * @var Mixed $onlybad.
     */
    public $onlybad;

    /**
     * To create a construct.
     * @param Mixed $thresholdneg .
     * @param Mixed $thresholdpos .
     * @param Mixed $courseselected .
     * @param Mixed $onlybad .
     * @param Mixed $tranlate .
     * @return Void .
     */
    public function __construct($thresholdneg, $thresholdpos, $courseselected, $onlybad, $tranlate) {
        $this->thresholdneg = $thresholdneg;
        $this->thresholdpos = $thresholdpos;
        $this->courseselected = $courseselected;
        $this->onlybad = $onlybad;
        $this->translate = $tranlate;
    }

    /**
     * To print data.
     * @return String .
     */
    public function printar () {
        global $OUTPUT, $CFG;

        $output = '';

        if ($this->onlybad) {
            $posts = get_post_from_course_neg_threshold($this->courseselected, $this->thresholdneg);
        } else {
            $posts = get_post_from_course($this->courseselected);
        }

        $output .= $OUTPUT->container_start('', 'contenedor');

        $route = $CFG->wwwroot;

        $output .= html_writer::start_tag('div');
        $output .= html_writer::start_tag('table', ['class' => 'table']);
        $output .= html_writer::start_tag('thead');
        $output .= html_writer::start_tag('tr');
        $output .= html_writer::tag('th', '', ['class' => 'col-1']);
        $output .= html_writer::tag('th', get_string('name', 'local_textanalysis'), ['class' => 'col-3 pl-4']);
        $output .= html_writer::tag('th', get_string('discussion', 'local_textanalysis'), ['class' => 'col-4 pl-4']);
        $output .= html_writer::start_tag('th', ['class' => 'col-3 pl-4']);
        $output .= html_writer::tag('span', get_string('polarity', 'local_textanalysis').' / ');
        $output .= html_writer::tag('span', get_string('language', 'local_textanalysis'));

        $output .= html_writer::end_tag('th');
        $output .= html_writer::start_tag('th', ['class' => 'col-1 pl-4']);
        $htmlcontent = '<div class="no-overflow">
            <b>'.get_string('name', 'local_textanalysis').': </b>'.get_string('name_des', 'local_textanalysis').'<br>
            <b>'.get_string('discussion', 'local_textanalysis').': </b>'.get_string('discussion_des', 'local_textanalysis').'<br>
            <b>'.get_string('polarity', 'local_textanalysis').': </b>'.get_string('polarity_des', 'local_textanalysis').'<br>
            <b>'.get_string('language', 'local_textanalysis').': </b>'.get_string('language_des', 'local_textanalysis').'<br>
            </div>';
        $output .= html_writer::start_tag('a',
            ['class' => 'btn btn-link p-0', 'role' => "button",
            'data-container' => "body", 'data-toggle' => "popover", 'data-placement' => "right", "data-content" => $htmlcontent,
            'data-html' => "true", 'tabindex' => "0", 'data-trigger' => "focus"]);
        $output .= html_writer::tag('i', "", ["class" => 'icon fa fa-question-circle text-info fa-fw', 'role' => "img"] );
        $output .= html_writer::end_tag('a');
        $output .= html_writer::end_tag('th');

        $output .= html_writer::end_tag('tr');
        $output .= html_writer::end_tag('thead');
        $output .= html_writer::end_tag('table');
        $output .= html_writer::end_tag('div');

        foreach ($posts as $key => $value) {
            $classpost = $this->getclasspostbynum((float)$value->polarity, $this->thresholdneg, $this->thresholdpos);

            $output .= html_writer::start_tag('div');

            $output .= html_writer::start_tag('div',
            ['class' => 'row '.$classpost,
            'role' => 'alert',
            'type' => "button",
            'data-toggle' => "collapse",
            'data-target' => "#collapse".$value->id,
            'aria-expanded' => "false"]);

            $output .= html_writer::tag('i', '', ['class' => 'col-1 fa fa-chevron-right pull-right']);
            $output .= html_writer::tag('i', '', ['class' => 'col-1 fa fa-chevron-down pull-right']);

            // Name.
            $name = get_name_user($value->userid);
            if (is_object($name)) {
                $output .= html_writer::tag('a', utf8_encode($name->name),
                ['class' => 'col-3',
                'href' => $route.'/user/profile.php?id='.$value->userid]);
            } else {
                $output .= html_writer::tag('a', get_string('notFound', 'local_textanalysis'), ['class' => 'col-3' ]);
            }

            // Discuss.
            $num = intval($value->discussion);
            $namediscuss = get_name_discussion_by_id($value->discussion);
            $output .= html_writer::tag('a',
            utf8_encode($namediscuss->name),
            ['href' => $route.'/mod/forum/discuss.php?d='.$num, 'class' => 'col-4 font-weight-bold', 'target' => '_blank']);

            // Polarity and language.
            $output .= html_writer::tag('span',
            '<b>'.get_string('polarity', 'local_textanalysis').':</b>'.number_format((float)$value->polarity, 2, '.', '').
            '&emsp;<b>'.get_string('language', 'local_textanalysis').':</b>'.$value->language
            , ['class' => 'col-3']);

            $output .= html_writer::end_tag('div');

            // Inside.
            $output .= html_writer::start_tag('div', ['class' => 'collapse', 'id' => "collapse".$value->id]);

            $output .= html_writer::start_tag('div', ['class' => 'card-body']);

            if ($this->translate) {
                $output .= html_writer::tag('div',
                "<b>Traslation: </b>".$value->translation,
                ['class' => 'alert alert-light', 'role' => 'alert']);
            }

            $output .= html_writer::tag('div',
            $this->addclass(utf8_decode($value->textpolarity), $this->thresholdneg, $this->thresholdpos));

            $output .= html_writer::end_tag('div');
            $output .= html_writer::end_tag('div');

            $output .= html_writer::end_tag('div');

        }

        $output .= $OUTPUT->container_end();
        return $output;

    }

    /**
     * To print table.
     * @param Mixed $string .
     * @param Mixed $thresholdneg .
     * @param Mixed $thresholdpos .
     * @return String .
     */
    public function addclass($string, $thresholdneg, $thresholdpos) {
        $doc = new DOMDocument();
        @$doc->loadHTML(($string), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $ps = $doc->getElementsByTagName('p');
        foreach ($ps as $p) {
            $num = (float)$p->getAttribute('data-value');
            $p->setAttribute('class', $this->getclassbynum($num, $thresholdneg, $thresholdpos).'Post');
        }
        return $doc->saveHTML();
    }

    /**
     * To get class by num.
     * @param Mixed $num .
     * @param Mixed $thresholdneg .
     * @param Mixed $thresholdpos .
     * @return String .
     */
    public function getclassbynum($num, $thresholdneg, $thresholdpos) {
        if ($num < $thresholdneg) {
            return "neg";
        } else if ($num > $thresholdpos) {
            return "pos";
        } else {
            return "neu";
        }
    }

    /**
     * To get class post by num.
     * @param Mixed $num .
     * @param Mixed $thresholdneg .
     * @param Mixed $thresholdpos .
     * @return String .
     */
    public function getclasspostbynum($num, $thresholdneg, $thresholdpos) {
        if ($num < $thresholdneg) {
            return "alert alert-danger";
        } else if ($num > $thresholdpos) {
            return "alert alert-success";
        } else {
            return "alert alert-light";
        }
    }
}

