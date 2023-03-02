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

$string['pluginname'] = 'Anàlisis de Sentiment';


$string['manage'] = 'Manage Sentiment Analisis';
$string['showinnavigation'] = 'Show navegation';
$string['showinnavigation_desc'] = 'When enabled, the site navegation will display a link to Sentiment Analysis';


$string['Analysis'] = 'Anàlisis';
$string['Graphs'] = 'Gràfiques';
$string['Posts'] = 'Posts';

// Graphs.
$string['Others'] = 'Altres';
$string['Sentiment'] = 'Sentiment';
$string['Languages'] = 'Idiomes';


// Forms.
$string['course'] = "Curs: ";
$string['All_courses'] = "Tots els cursos";
$string['Select_courses'] = "Selecciona el curs que vols visualitzar";
$string['show_bad'] = "Mostra nomès els posts classificats com a negatius";
$string['change_neg_threshold'] = "Modifica el llindar negatiu. Valors entre [-1,1]. Recommendat: -0.3";
$string['change_pos_threshold'] = "Modifica el llindar positiu. Valors entre [-1,1]. Recomendat: 0.3";

$string['show_en'] = "Mostra la traducció en anglès";
$string['show_en_help'] = "Aquest algoritme tradueix a anglès. Si vols visualitzar la traducció, seleciona la casella";

$string['threshold'] = "Modfica el llindar";
$string['threshold_help'] = "La polaritat més negativa és -1, i la més positiva 1.Utilitzem el llindar per definir el limit de la negativitat i positivitat. Valors normals recomenats són ±0.3";

$string['error_neg_th'] = "El llindar negatiu ha d'estar entre [-1,thresholdPos)";
$string['error_pos_th'] = "El llindar positiu ha d'estar entre (thresholdNeg, 1]";

$string['avg'] = "Mitjana de sentiment";
$string['avg_des'] = "Compara la mitjana del teu curs amb la mitjana global.";
$string['avg_course'] = "<b>La mitjana del curs seleccionat és: </b>";
$string['avg_all'] = "<b>LA mitjana de tots els cursos és: </b>";


$string['taskUpdate'] = "Cargant els nous posts";

$string['name'] = 'Nom';
$string['discussion'] = 'Discussió';
$string["polarity"] = "polaritat";
$string["language"] = "idioma";


$string['printAnalysis'] = "Captura pantalla anàlisis";

$string['message'] = 'missatge';
$string['message_trans'] = 'missatge traduït';
$string['class_id'] = 'classe id';
$string['class_name'] = 'classe nom';

$string['Analytics'] = 'Anàlisis';

$string['apikey'] = 'APIKey';
$string['apikey_des'] = "Posa l'APIKey";

$string['polarity_des'] = "La polaritat és un nombre entre [-1,1] que descriu el sentiment, sent -1 el més negatiu i 1 el més positiu.";
$string['language_des'] = "Idioma detectat.";
$string['name_des'] = "Clica al nom per visualitzar el perfil de l'usuari.";
$string['discussion_des'] = "Clica al nom de la discussió per visualitzar el contexst.";


$string['notFound'] = "No trobat";

$string['pos'] = 'Positiu';
$string['neg'] = 'Negatiu';
$string['neu'] = 'Neutre';
$string['err'] = 'Error';

$string['feedback'] = 'Feedback questionaris';

