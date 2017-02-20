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
 * Settings used by the lesson module, were moved from mod_edit
 *
 * @package mod_lesson
 * @copyright  2009 Sam Hemelryk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or late
 **/

defined('MOODLE_INTERNAL') || die;



if ($ADMIN->fulltree) {
    // Administration header!
	$settings->add(new admin_setting_heading('mod_office/view_administration',get_string('setting_head','office'),''));
	$str_url ='';
	$str_lang = get_string( 'setting_lang' , 'office');
	if($str_lang == 'ja'){
		$str_url = "https://onedrive.live.com/embed?cid=D557A6FBB4E13997&resid=D557A6FBB4E13997%21108&authkey=AAkTYjnjexHbpOA&em=2";
	}else{
		$str_url = "https://onedrive.live.com/embed?cid=D557A6FBB4E13997&resid=D557A6FBB4E13997%21107&authkey=AJSjxJzDs6EfY10&em=2";
	}
	$settings->add(new admin_setting_configtext('mod_office/clientid','clientID', get_string('setting_setumei','office')."
<iframe width='800' height='500' src=$str_url></iframe>
",''));

//    $mform -> addElement ( 'html' ,'');
//      $mform -> addElement ( 'html' ,'<p>aaa</p>');

}
