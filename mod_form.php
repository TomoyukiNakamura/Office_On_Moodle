<script type="text/javascript" src="https://js.live.net/v7.0/OneDrive.js"></script>
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
 * The main office configuration form
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod_office
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once('../config.php');
require_once($CFG->libdir . '/filelib.php');
require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->libdir . '/pagelib.php');

/**
 * Module instance settings form
 *
 * @package    mod_office
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_office_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {
	 global $out;
        global $CFG;

	 $onedrivehtml = str_replace($_SERVER['DOCUMENT_ROOT'],'',__FILE__);
	 $onedrivehtml = str_replace('mod_form.php','',$onedrivehtml);
	 $onedrivehtml = $onedrivehtml."onedrive.html";
	 
	 $get_app_id = get_config('mod_office','clientid');
	 $re_url = "https://".$_SERVER["HTTP_HOST"].$onedrivehtml;
	 
        $mform = $this->_form;

        // Adding the "general" fieldset, where all the common settings are showed.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field.
	 $str_bun = get_string( 'officename' , 'office');
        $mform->addElement('text', 'name', $str_bun , array('size' => '64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'officename', 'office');

        // Adding the standard "intro" and "introformat" fields.
        if ($CFG->branch >= 29) {
            $this->standard_intro_elements();
        } else {
            $this->add_intro_editor();
        }


        $mform->addElement('header', 'onedrive_share', get_string('onedrive_share','office'));
        $mform->setExpanded('onedrive_share',true);


        // Adding the rest of office settings, spreading all them into this fieldset
        // ... or adding more fieldsets ('header' elements) if needed for better logic.
	 $str_bun = get_string( 'onedrive_share_setumei' , 'office');
        $mform -> addElement ( 'html' , "<table><tr><td width=800px><b>$str_bun</b></td><td>");	 
	 $mform -> addElement ( 'html' , '<div class="row">');
	 $mform -> addElement ( 'html' ,'<input id="downloadLink" type="hidden" value="download" name="actionType" checked="checked"> ');
	 $mform -> addElement ( 'html' , '</div>');
	 $mform -> addElement ( 'html' , '<div class="row">');
	 $mform -> addElement ( 'html' ,'<button onclick="javascript:launchOneDrivePicker();" title="Open to OneDrive" type = "button">');
	 $mform -> addElement ( 'html' ,'<img src="https://js.live.net/v5.0/images/SkyDrivePicker/SkyDriveIcon_white.png" style="vertical-align: middle; height: 16px;">');
	 $mform -> addElement ( 'html' ,'<font class="">Open to OneDrive for ShareLink</font>') ; 	 
	 $mform -> addElement ( 'html' ,'</button>');
	 $mform -> addElement ( 'html' , '</div>');
	 $mform -> addElement ( 'html' , '</td></tr></table>');


	 $mform -> addElement ( 'html' , '<div id="pickerConsole" class="console">');
	 $mform -> addElement ( 'html' , '</div>');

	 $mform -> addElement ( 'html' ,'<script type="text/javascript" src="https://js.live.net/v7.0/OneDrive.js"></script>');

	 $mform -> addElement ( 'html' , '<br>');


	 $mform -> addElement ( 'html' , '<p>');
	 $mform -> addElement ( 'html' ,'<table id = "list">');

	 $str_bun = get_string( 'onedrive_share_filename' , 'office');
	 $str_bun_2 = get_string( 'onedrive_share_group' , 'office');
	 $mform -> addElement ( 'html' ,"<tr><td width='180'><b>&nbsp;$str_bun</b></td><td width='180'><b>&nbsp;$str_bun_2</b></td></tr>");
	 $mform -> addElement ( 'html' ,'</table>');
	 $mform -> addElement ( 'html' ,"<table id = 'list2'></table>");
	 $mform -> addElement ( 'html' , '</p>');

	 $mform -> addElement ( 'html' , '<br>');

	 $str_bun = get_string( 'onedrive_view' , 'office');
        $mform->addElement('header', 'onedrive_view', $str_bun);
        $mform->setExpanded('onedrive_view',true);

	 $str_bun = get_string( 'onedrive_view_setumei' , 'office');
        $mform -> addElement ( 'html' , "<table><tr><td width=800px><b>$str_bun</b></td><td>");	 
	 $mform -> addElement ( 'html' , '<div class="row">');
	 $mform -> addElement ( 'html' ,'<input id="downloadLink" type="hidden" value="download" name="actionType" checked="checked"> ');
	 $mform -> addElement ( 'html' , '</div>');
	 $mform -> addElement ( 'html' , '<div class="row">');
	 $mform -> addElement ( 'html' ,'<button onclick="javascript:launchOneDrivePicker2();" title="Open to OneDrive" type = "button">');
	 $mform -> addElement ( 'html' ,'<img src="https://js.live.net/v5.0/images/SkyDrivePicker/SkyDriveIcon_white.png" style="vertical-align: middle; height: 16px;">');
	 $mform -> addElement ( 'html' ,'<font class="">Open to OneDrive for ViewLink</font>') ; 	 
	 $mform -> addElement ( 'html' ,'</button>');
	 $mform -> addElement ( 'html' , '</div>');
	 $mform -> addElement ( 'html' , '</td></tr></table>');

	 $mform -> addElement ( 'html' , '<br>');


	 $mform -> addElement ( 'html' ,'<table id = "list_view">');
	 $str_bun = get_string( 'onedrive_view_filename' , 'office');
	 $mform -> addElement ( 'html' ,"<tr><td width='180'><b>&nbsp;$str_bun </b></td></tr>");
	 $mform -> addElement ( 'html' ,'</table>');

	 $mform -> addElement ( 'html' ,'<table id = "list_view2"></table>');
	 $mform -> addElement ( 'html' , '<br>');

        // Add standard grading elements.
        $this->standard_grading_coursemodule_elements();

        // Add standard elements, common to all modules.
        $this->standard_coursemodule_elements();

        // Add standard buttons, common to all modules.
        $this->add_action_buttons();


	 global $DB;
	 global $USER;
	 global $PAGE;
	 $courseid   = $PAGE->course->id;
	 $DB->get_record('modules',array('id' =>$moduleid));
	 $groups = $DB->get_records('groups', array('courseid'=>$courseid), 'name');
	 $array = array();
	 $array_id = array();
	 foreach($groups as $gr){
	 	$array[] = $gr -> name;
		$array_id[] = $gr -> id;
	 }


	 $update = '';
	 if(isset($_GET['update'])){
	 	$update = $_GET['update'];
	 }

	 $mform -> addElement ( 'html' ,'<script type="text/javascript">');
	 $mform -> addElement ( 'html' ,"var info = document.getElementById('id_submitbutton2');");
	 $mform -> addElement ( 'html' ,"var submit = document.getElementById('id_submitbutton');");
	 $mform -> addElement ( 'html' ,"submit.setAttribute('onclick','send_share_url();');");
	 $mform -> addElement ( 'html' ,"info.parentNode.removeChild(info);");
	 $mform -> addElement ( 'html' ,'</script>');

	 $mform -> addElement ( 'html' ,'<script type="text/javascript" src="https://js.live.net/v7.0/OneDrive.js"></script>');
	 $mform -> addElement ( 'html' ,'<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>');
	 $mform -> addElement ( 'html' ,'<script type="text/javascript" src="addBeforeUnload.js"></script>');
	 $mform -> addElement ( 'html' ,'<script type="text/javascript">');

$count = count($array);
	 $mform -> addElement ( 'html' ,"var gr_arr = [];
");
	 $mform -> addElement ( 'html' ,"var gr_arr_id = [];
");

for($i=0;$i<$count;$i++){
	 $mform -> addElement ( 'html' ,"gr_arr.push('$array[$i]');
");
	 $mform -> addElement ( 'html' ,"gr_arr_id.push('$array_id[$i]');
");
}
//	 $mform -> addElement ( 'html' ,"alert(gr_arr);
//");
//	 $mform -> addElement ( 'html' ,"alert(gr_arr_id);
//");
////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $mform -> addElement ( 'html' ,"var json_data;");
	 $mform -> addElement ( 'html' ,"var json_data_2;");
	 $mform -> addElement ( 'html' ,"var share_file_name = [];");
	 $mform -> addElement ( 'html' ,"var share_file_url = [];");
	 $mform -> addElement ( 'html' ,"var view_file_name = [];");
	 $mform -> addElement ( 'html' ,"var view_file_url = [];");
	 $mform -> addElement ( 'html' ,"var file_num = 0;");
	 $mform -> addElement ( 'html' ,"var file_num_2 = 0;");
	 $mform -> addElement ( 'html' ,"var share_url = '';
");
//■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■q初期値ここまでp■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
if($update != ''){
	 $mform -> addElement ( 'html' ,"var update_id_bool = $update;
");
}else{
	 $mform -> addElement ( 'html' ,"var update_id_bool = -1;
");
}
	 $mform -> addElement ( 'html' ,"var flag_onedrive = 0;
");

//	 $mform -> addElement ( 'html' ,"alert(update_id_bool);
//");
	 $mform -> addElement ( 'html' ,'function launchOneDrivePicker(){
');
	 $mform -> addElement ( 'html' ,'var odOptions ={
');
	 $mform -> addElement ( 'html' ,"clientId: '$get_app_id',
");
	 $mform -> addElement ( 'html' ,'action: "share",
');
	 $mform -> addElement ( 'html' ,'multiSelect: true,
');
	 $mform -> addElement ( 'html' ,'advanced: {
');
	 $mform -> addElement ( 'html' ,'createLinkParameters: {type: "edit", scope: "anonymous"},
');
	 $mform -> addElement ( 'html' ,"redirectUri: '$re_url'
");
	 $mform -> addElement ( 'html' ,'},
');
	 $mform -> addElement ( 'html' ,"success: 'oneDriveFilePickerSuccess',
");
	 $mform -> addElement ( 'html' ,'cancel: function() {
');
//	 $mform -> addElement ( 'html' ,'alert("キャンセルしているみたい");
//');
	 $mform -> addElement ( 'html' ,'},
');
	 $mform -> addElement ( 'html' ,'error: function(e) {
');
	 $mform -> addElement ( 'html' ,'json_e = JSON.parse(e);
');
//	 $mform -> addElement ( 'html' ,'alert(json_e.error);
//');
	 $mform -> addElement ( 'html' ,'}
');
	 $mform -> addElement ( 'html' ,'}
');
	 $mform -> addElement ( 'html' ,'OneDrive.open(odOptions);
');
	 $mform -> addElement ( 'html' ,'}
');

	 $mform -> addElement ( 'html' ,'function oneDriveFilePickerSuccess(files){
');
	 $mform -> addElement ( 'html' ,"var data =  JSON.stringify(files, null, '  ');
");
	 $mform -> addElement ( 'html' ,'json_data=JSON.parse(data);
');
//	 $mform -> addElement ( 'html' ,'alert(json_data.value[0].permissions[0]["link"]["webUrl"]);
//');//ここ
//	 $mform -> addElement ( 'html' ,'alert(json_data.value[0].permissions[0]["shareId"]);
//');//ここ
//	 $mform -> addElement ( 'html' ,'alert(json_data.value[1].permissions[0]["link"]["webUrl"]);
//');//ここ
//	 $mform -> addElement ( 'html' ,'alert(json_data.value[2].permissions[0]["link"]["webUrl"]);
//');//ここ

	 $mform -> addElement ( 'html' ,'getHtml(json_data);
');
//	 $mform -> addElement ( 'html' ,'alert("成功しているみたい");
//');
	 $mform -> addElement ( 'html' ,'}
');

	 $mform -> addElement ( 'html' ,'function getHtml(json_data){
');
	 $mform -> addElement ( 'html' ,"flag_onedrive = 1;
");
	 $mform -> addElement ( 'html' ,'share_url = json_data.webUrl;//共有するファイルのURL取得
');

	 $mform -> addElement ( 'html' ,'file_num = Object.keys(json_data["value"]).length;');	 
	 $mform -> addElement ( 'html' ,'for(var i = 0 ; i < file_num ; i++){');
	 $mform -> addElement ( 'html' ,'share_file_name.push(json_data.value[i]["name"]);');
	 $mform -> addElement ( 'html' ,'share_file_url.push(json_data.value[i].permissions[0]["link"]["webUrl"]);');
	 $mform -> addElement ( 'html' ,'}');

//	 $mform -> addElement ( 'html' ,'alert(share_file_name);');

//////////////sharelink表示用の部分////////////////////////////////////////////////////////////
	 $mform -> addElement ( 'html' ,'var list = document.getElementById("list2");');
//	 $mform -> addElement ( 'html' ,'var list = document.getElementById("list");');
	 $mform -> addElement ( 'html' ,'list.textContent = null;');

	 $mform -> addElement ( 'html' ,'for(var i = 0 ; i < file_num ; i++){');
	 $mform -> addElement ( 'html' ,'var file_name = json_data.value[i]["name"];');
	 $mform -> addElement ( 'html' ,'var tr = document.createElement("tr");');
	 $mform -> addElement ( 'html' ,'var td = document.createElement("td");');
	 $mform -> addElement ( 'html' ,'td.width = 180;');
	 $mform -> addElement ( 'html' ,'td.textContent = file_name;');
	 $mform -> addElement ( 'html' ,'tr.appendChild(td);');

	 $mform -> addElement ( 'html' ,'var td = document.createElement("td");');
	 $mform -> addElement ( 'html' ,'var form = document.createElement("form");');
	 $mform -> addElement ( 'html' ,'var form_str = "form"+String(i);');
	 $mform -> addElement ( 'html' ,'form.name = form_str;');
	 $mform -> addElement ( 'html' ,'var select = document.createElement("select");');
	 $mform -> addElement ( 'html' ,'var select_str = "select"+String(i);');
	 $mform -> addElement ( 'html' ,'select.name = select_str;');

	 $mform -> addElement ( 'html' ,'var option = document.createElement("option");');
	 $mform -> addElement ( 'html' ,'option.value = "no_select";');
	 $str_bun = get_string( 'select_group' , 'office');
	 $mform -> addElement ( 'html' ,"option.textContent = '$str_bun';");
	 $mform -> addElement ( 'html' ,'select.appendChild(option);');

	 $mform -> addElement ( 'html' ,'for(var j = 1 ; j < gr_arr.length + 1 ; j++){');
	 $mform -> addElement ( 'html' ,'var option = document.createElement("option");');
	 $mform -> addElement ( 'html' ,'option.value = gr_arr_id[j-1];');
	 $mform -> addElement ( 'html' ,'option.textContent = gr_arr[j-1];');
	 $mform -> addElement ( 'html' ,'select.appendChild(option);');
	 $mform -> addElement ( 'html' ,'}');

	 $mform -> addElement ( 'html' ,'form.appendChild(select);');
	 $mform -> addElement ( 'html' ,'td.appendChild(form);');
	 $mform -> addElement ( 'html' ,'tr.appendChild(td);');
	 $mform -> addElement ( 'html' ,'list.appendChild(tr);');
	 $mform -> addElement ( 'html' ,'}');
///////////////////////sharelink表示用ここまで///////////////////////////////////////////////////////////

	 $mform -> addElement ( 'html' ,'}
');





//********************************************************************************************

//ここから下共有選択用OneDriveボタン
	 $mform -> addElement ( 'html' ,'function launchOneDrivePicker2(){
');
	 $mform -> addElement ( 'html' ,'var odOptions ={
');
	 $mform -> addElement ( 'html' ,"clientId: '$get_app_id',
");
	 $mform -> addElement ( 'html' ,'action: "share",
');
	 $mform -> addElement ( 'html' ,'multiSelect: true,
');
	 $mform -> addElement ( 'html' ,'advanced: {
');
	 $mform -> addElement ( 'html' ,'createLinkParameters: {type: "embed", scope: "anonymous"},
');
	 $mform -> addElement ( 'html' ,"redirectUri: '$re_url'
");
	 $mform -> addElement ( 'html' ,'},
');
	 $mform -> addElement ( 'html' ,"success: 'oneDriveFilePickerSuccess2',
");
	 $mform -> addElement ( 'html' ,'cancel: function() {
');
//	 $mform -> addElement ( 'html' ,'alert("キャンセルしているみたい");
//');
	 $mform -> addElement ( 'html' ,'},
');
	 $mform -> addElement ( 'html' ,'error: function(e) {
');
	 $mform -> addElement ( 'html' ,'json_e = JSON.parse(e);
');
//	 $mform -> addElement ( 'html' ,'alert(json_e.error);
//');
	 $mform -> addElement ( 'html' ,'}
');
	 $mform -> addElement ( 'html' ,'}
');
	 $mform -> addElement ( 'html' ,'OneDrive.open(odOptions);
');
	 $mform -> addElement ( 'html' ,'}
');



	 $mform -> addElement ( 'html' ,'function oneDriveFilePickerSuccess2(files){
');
	 $mform -> addElement ( 'html' ,"var data =  JSON.stringify(files, null, '  ');
");
	 $mform -> addElement ( 'html' ,'json_data_2=JSON.parse(data);
');
//	 $mform -> addElement ( 'html' ,'alert(json_data_2.value[0].permissions[0]["link"]["webUrl"]);
//');
	 $mform -> addElement ( 'html' ,'getHtml2(json_data_2);
');
//	 $mform -> addElement ( 'html' ,'alert("成功しているみたい");
//');
	 $mform -> addElement ( 'html' ,'}
');






	 $mform -> addElement ( 'html' ,'function getHtml2(json_data_2){
');
	 $mform -> addElement ( 'html' ,"flag_onedrive = 1;
");
	 $mform -> addElement ( 'html' ,'share_url = json_data_2.webUrl;
');


	 $mform -> addElement ( 'html' ,'file_num_2 = Object.keys(json_data_2["value"]).length;');
//	 $mform -> addElement ( 'html' ,'for(var j = 0 ; j < file_num_2 ; j++){');
//	 $mform -> addElement ( 'html' ,'view_file_name[j] = json_data_2.value[j]["name"];');
//	 $mform -> addElement ( 'html' ,'view_file_url[j] = json_data_2.value[j].permissions[0]["link"]["webUrl"];');
//	 $mform -> addElement ( 'html' ,'}');



//	 $mform -> addElement ( 'html' ,'for(var i = 0 ; i < file_num ; i++){');
//	 $mform -> addElement ( 'html' ,'for(var j = 0 ; j < file_num_2 ; j++){');
//	 $mform -> addElement ( 'html' ,'if(share_file_name[i] == json_data_2.value[j]["name"]){');
//	 $mform -> addElement ( 'html' ,'var hako = view_file_name[i];');
//	 $mform -> addElement ( 'html' ,'view_file_name[i] = json_data_2.value[j]["name"];');
//	 $mform -> addElement ( 'html' ,'view_file_name[j] = hako;');
///	 $mform -> addElement ( 'html' ,'view_file_url[i] = json_data_2.value[j].permissions[0]["link"]["webUrl"];');
//	 $mform -> addElement ( 'html' ,'}');
//	 $mform -> addElement ( 'html' ,'}');
//	 $mform -> addElement ( 'html' ,'}');

//////////////viewlink表示用の部分////////////////////////////////////////////////////////////
	 $mform -> addElement ( 'html' ,'var list_v = document.getElementById("list_view2");');
	 $mform -> addElement ( 'html' ,'list_v.textContent = null;');

	 $mform -> addElement ( 'html' ,'for(var i = 0 ; i < file_num_2 ; i++){');
	 $mform -> addElement ( 'html' ,'var file_name = json_data_2.value[i]["name"];');
//	 $mform -> addElement ( 'html' ,'var file_name = json_data_2.value[i]["name"];');
	 $mform -> addElement ( 'html' ,'var tr = document.createElement("tr");');
	 $mform -> addElement ( 'html' ,'var td = document.createElement("td");');
	 $mform -> addElement ( 'html' ,"td.innerHTML = '&nbsp;' + '&nbsp;' + '● ' + file_name;");
	 $mform -> addElement ( 'html' ,'tr.appendChild(td);');

	 $mform -> addElement ( 'html' ,'var td = document.createElement("td");');
	 $mform -> addElement ( 'html' ,'tr.appendChild(td);');
	 $mform -> addElement ( 'html' ,'list_v.appendChild(tr);');
	 $mform -> addElement ( 'html' ,'}');
///////////////////////viewlink表示用ここまで///////////////////////////////////////////////////////////



	 $mform -> addElement ( 'html' ,'}
');






//***********************************************************************************************

	 $mform -> addElement ( 'html' ,'function send_share_url(){
');
	 $mform -> addElement ( 'html' ,"var rtn_share = [];");
	 $mform -> addElement ( 'html' ,"var rtn_view = [];
");
	 $mform -> addElement ( 'html' ,'if(flag_onedrive==1){
');
	 $mform -> addElement ( 'html' ,'for(var ii=0; ii < file_num; ii++){');
	 $mform -> addElement ( 'html' ,'var form_str = "form"+String(ii);');
	 $mform -> addElement ( 'html' ,'var select_str = "select"+String(ii);');
	 $mform -> addElement ( 'html' ,'rtn_share.push(document.forms[form_str][select_str].value);');
	 $mform -> addElement ( 'html' ,'var strin = json_data.value[ii].permissions[0]["link"]["webUrl"];');
	 $mform -> addElement ( 'html' ,'rtn_share.push(String(strin));');
	 $mform -> addElement ( 'html' ,'}
');
	 $mform -> addElement ( 'html' ,'for(var ii=0; ii < file_num_2; ii++){');
	 $mform -> addElement ( 'html' ,'var strin = json_data_2.value[ii].permissions[0]["link"]["webUrl"];');
	 $mform -> addElement ( 'html' ,'rtn_view.push(String(strin));');
	 $mform -> addElement ( 'html' ,'var strin = json_data_2.value[ii]["name"];');
	 $mform -> addElement ( 'html' ,'rtn_view.push(String(strin));');
	 $mform -> addElement ( 'html' ,'}
');

//	 $mform -> addElement ( 'html' ,'alert(rtn_share);
//');
	 $mform -> addElement ( 'html' ,'var rtn_s = JSON.stringify(rtn_share);
');
//	 $mform -> addElement ( 'html' ,'alert(rtn_view);
//');
	 $mform -> addElement ( 'html' ,'var rtn_v = JSON.stringify(rtn_view);
');
//	 $mform -> addElement ( 'html' ,'alert(rtn_s);
//');
//	 $mform -> addElement ( 'html' ,'alert(rtn_v);
//');
	 $mform -> addElement ( 'html' ,"var courseid = $courseid;
");

	 $mform -> addElement ( 'html' ,'jQuery.ajax({
');
	 $mform -> addElement ( 'html' ,'type: "POST",
');
	 $mform -> addElement ( 'html' ,"url: '/moodle/mod/office/read.php',
");
	 $mform -> addElement ( 'html' ,'cache: false,
');
	 $mform -> addElement ( 'html' ,'data: {
');
	 $mform -> addElement ( 'html' ,'"rtn_share" : rtn_s,
');
	 $mform -> addElement ( 'html' ,'"rtn_view" : rtn_v,
');
	 $mform -> addElement ( 'html' ,'"update_id_bool" : update_id_bool,
');
	 $mform -> addElement ( 'html' ,'"courseid" :courseid 
');
	 $mform -> addElement ( 'html' ,'},
');
	 $mform -> addElement ( 'html' ,'success: function(){
');
//	 $mform -> addElement ( 'html' ,'alert("success!a");
//');
	 $mform -> addElement ( 'html' ,'}
');
	 $mform -> addElement ( 'html' ,'});
');
	 $mform -> addElement ( 'html' ,'}
');
	 $mform -> addElement ( 'html' ,'}
');
	 $mform -> addElement ( 'html' ,'</script>
');
    }
}
?>
