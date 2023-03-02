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
 * Plugin strings are defined here.
 *
 * @package     local_textanalysis
 * @author      2022 Aina Palacios
 * @copyright   2022 Aina Palacios & Eurecat.dev
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Sentiment Analysis';

$string['manage'] = 'Manage Sentiment Analysis';
$string['showinnavigation'] = 'Show navegation';
$string['showinnavigation_desc'] = 'When enabled, the site navegation will display a link to Sentiment Analysis';

$string['Analysis'] = 'Analysis';
$string['Graphs'] = 'Graphs';
$string['Posts'] = 'Posts';

// Graph.
$string['Others'] = 'Others';
$string['Sentiment'] = 'Sentiment';
$string['Languages'] = 'Languages';

// Forms.
$string['course'] = "Course: ";
$string['All_courses'] = "All the courses";
$string['Select_courses'] = "Select the course you want to check";
$string['show_bad'] = "Show only the post labeled as negative";
$string['change_neg_threshold'] = "Change the negative threshold. Values between [-1,1]. Recommended: -0.3";
$string['change_pos_threshold'] = "Change the positive threshold Values between [-1,1]. Recommended: 0.3";

$string['show_en'] = "Show the translation to English";
$string['show_en_help'] = "This algorithm translates the posts. If you want to check the translation, select this checkbox";

$string['threshold'] = "Change the threshold";
$string['threshold_help'] = "The most negative polarity is -1 and the most positive 1. We use the threshold to set a limit in negativity or positivity. The usual threshold is ±0.3.";

$string['error_neg_th'] = "Negative threshold must be in range [-1,thresholdPos)";
$string['error_pos_th'] = "Positive threshold must be in range (thresholdNeg, 1]";

$string['avg'] = "Average sentiment";
$string['avg_des'] = "Compare the average of all the curses to the curs selected.";
$string['avg_course'] = "<b>The average of the course selected is: </b>";
$string['avg_all'] = "<b>The average of all the courses is: </b>";


$string['taskUpdate'] = "Updating the posts";


$string['name'] = 'Name';
$string['discussion'] = 'Discussion';
$string["polarity"] = "polarity";
$string["language"] = "language";


$string['printAnalysis'] = "Screenshot analysis";

$string['message'] = 'message';
$string['message_trans'] = 'translated message';
$string['class_id'] = 'class id';
$string['class_name'] = 'class name';

$string['Analytics'] = 'Analytics';

$string['apikey'] = 'APIKey';
$string['apikey_des'] = 'Insert the APIKey';

$string['polarity_des'] = "The polarity is a number between [-1,1] which describes the sentiment, being -1 the most negative and 1 the most positive.";
$string['language_des'] = "Language detected.";
$string['name_des'] = "Click on the name to go to the perfile of the user.";
$string['discussion_des'] = "Click on the name of the discussion to check all the context.";

$string['notFound'] = "Not Found";

$string['pos'] = 'Positive';
$string['neg'] = 'Negative';
$string['neu'] = 'Neutral';
$string['err'] = 'Error';

$string['feedback'] = 'Feedback questionnaires';

