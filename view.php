<!-- ｊQuery ライブラリー -->
<script type="text/javascript" src="fancybox/lib/jquery-1.9.0.min.js"></script>
 
<!-- fancyBox メインとCSSファイル群 -->
<script type="text/javascript" src="fancybox/source/jquery.fancybox.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen" />
<script type="text/javascript">
 
$(document).ready(function() {
    // 基本構成
    $('.fancybox').fancybox({
	openEffect: 'elastic',
	openSpeed:  'slow',
	'type' : 'iframe',
	'width' : '65%',
	'height' : '89%',
	'autoSize' : false
});
});
 
</script>
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
 * Prints a particular instance of office
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_office
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Replace office with the name of your module and remove this line.

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');


require_once($CFG->libdir . '/filelib.php');
require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->libdir . '/pagelib.php');

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... office instance ID - it should be named as the first character of the module.

if ($id) {
    $cm         = get_coursemodule_from_id('office', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $office  = $DB->get_record('office', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $office  = $DB->get_record('office', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $office->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('office', $office->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

$event = \mod_office\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $office);
$event->trigger();

// Print the page header.

$PAGE->set_url('/mod/office/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($office->name));
$PAGE->set_heading(format_string($course->fullname));

/*
 * Other things you may want to set - remove if not needed.
 * $PAGE->set_cacheable(false);
 * $PAGE->set_focuscontrol('some-html-id');
 * $PAGE->add_body_class('office-'.$somevar);
 */

// Output starts here.
echo $OUTPUT->header();
echo $OUTPUT->heading($office->name);



// Conditions to show the intro can change to look for own settings or whatever.
if ($office->intro) {
    echo $OUTPUT->box(format_module_intro('office', $office, $cm->id), 'generalbox mod_introbox', 'officeintro');
}

echo $OUTPUT->box($name1, 'generalbox mod_introbox', 'officeintro');
// Replace the following lines with you own code.



//********************************************************************************************************shre用のDB

$csv  = array();//ここから下、share用データベース開き配列化
$file = 'url_db.csv';
$fp   = fopen($file, "r");
$i = 0;
while (($data = fgetcsv($fp, 0, ",")) !== FALSE) {
  $csv[] = $data;
  $i++;
}
fclose($fp);//ここまで（iは配列の行数。つまり一番ラストの配列はi-1）


///*******************************************************************************************************
$id_lim = required_param('id', PARAM_INT);
$not_ad_num = 0;
$sys_flag = 0;
for($ct = 0 ;$ct <= $i ;$ct++){
	if($csv[$ct][0] == $id_lim){//現在開いているページのidと登録idが一致するものを検索
		$sys_flag = 1;//あれば処理修了用のflag=1
		//echo $csv[$ct][1];//そしてURl表示
	}//ここまで
}

global $USER;
$userid = $USER -> id;

$str_bun = get_string( 'view_head1' , 'office');
$str_bun2 = get_string( 'view_head2' , 'office');
echo "<font size=5><p><b>$str_bun</b></font>&nbsp;&nbsp;$str_bun2</p>";
echo "<hr style='border-top:2px dotted #000000; width:80%;'>";

if($sys_flag == 0){//id_limが最大のidである

for($ct = 0;$ct<=$i;$ct++){
	if($csv[$ct][0]=='url'){
		$num_num = count($csv[$ct]);
		$courseid = $csv[$ct][$num_num-1];//コースidを取得する
		$str = groups_get_user_groups($courseid, $userid);//見ているuserの所属しているグループidの配列
		for($nt1 = 0;$nt1 < count($str[0]);$nt1++){
			for($nt2 = 0;$nt2 < count($csv[$ct])-1;$nt2++){
				$string_gr = (string)$str[0][$nt1];
				if($csv[$ct][$nt2] == $string_gr){
					$url = $csv[$ct_t][$nt2+1];
					echo "<p>&nbsp;&nbsp;● <a href='".$url."' target='_blank'>$str_bun</a></p>";
				}
			}
		}

//		echo $csv[$ct][1];
		$csv[$ct][0]=$id_lim;
	}
}


$office_num = 0;//コース上にあるofficeの数（今開いているものより古いもののみ！）自分も含む
$arr_live_office = array();
for($j = 0;$j <= $id_lim; $j++){
	if(! $ok = get_coursemodule_from_id('office', $j)){
	}else{
		$arr_live_office[] = $j;
		$office_num++;
	}
}//ここまで



for($ct = 0;$ct <= $i-1;$ct++){
	$save_flag = 0;
	for($ct2=0;$ct2 <= $office_num;$ct2++){
		if($csv[$ct][0] == $arr_live_office[$ct2]){
			$save_flag = 1;
		}
	}
	if($save_flag==0){
		unset($csv[$ct]);
	}
}
//$csv = array_values($csv);



}else{//idみて短縮ifの終端 ここから下idがある場合の読み込み、出力
for($ct_t = 0;$ct_t<=$i;$ct_t++){
	if($csv[$ct_t][0]==$id_lim){
		$num_num = count($csv[$ct_t]);
		$courseid = $csv[$ct_t][$num_num-1];//コースidを取得する
		$str = groups_get_user_groups($courseid, $userid);//見ているuserの所属しているグループidの配列
		for($nt1 = 0;$nt1 < count($str[0]);$nt1++){
			for($nt2 = 0;$nt2 < count($csv[$ct_t])-1;$nt2++){
				$string_gr = (string)$str[0][$nt1];
				if($csv[$ct_t][$nt2] == $string_gr){
					$url = $csv[$ct_t][$nt2+1];
					echo "<p>&nbsp;&nbsp;● <a href='".$url."' target='_blank'>$str_bun</a></p>";
				}
			}
		}
	}
}
}//////////////////////////////ここまでidがある場合の読み込み、出力//////////ここまでshareの出力




$file = fopen("url_db.csv", "w");//ここからshareDBの書き込み
for($j=0 ; 0 <= $i ; $i-- ){
  fputcsv($file, $csv[$j]);
  $j++;
}
fclose($file);//ここまでshareDBの書き込み


echo "<br>";
echo "<br>";

$str_bun = get_string( 'view_head3' , 'office');
echo "<font size=5><p><b>$str_bun</b></p></font>";
echo "<hr style='border-top:2px dotted #000000; width:80%;'>";

//********************************************************************************************************view用のDB

$csv_view  = array();//ここから下、view用データベース開き配列化
$file = 'view_db.csv';
$fp   = fopen($file, "r");
$i_view = 0;
while (($data = fgetcsv($fp, 0, ",")) !== FALSE) {
  $csv_view[] = $data;
  $i_view++;
}
fclose($fp);//ここまで（i_viewは配列の行数。つまり一番ラストの配列はi_view-1）


///*******************************************************************************************************

$sys_flag_view = 0;
for($ct_view = 0 ;$ct_view <= $i_view ;$ct_view++){
	if($csv_view[$ct_view][0] == $id_lim){//現在開いているページのidと登録idが一致するものを検索
		$sys_flag_view = 1;//あれば処理修了用のflag=1
		//echo $csv[$ct][1];//そしてURl表示
	}//ここまで
}


if($sys_flag_view == 0){//id_limが最大のidである(一致するidがない場合最大になる)

for($ct_view = 0;$ct_view<=$i_view;$ct_view++){
	if($csv_view[$ct_view][0]=='url'){
		$lim = count($csv_view[$ct_view])/2;
		for($nt1 = 0;$nt1 < $lim-1;$nt1++){
			echo "<br>";
			$url_view = $csv_view[$ct_view][2*$nt1+1];
			$url_name = $csv_view[$ct_view][2*$nt1+2];
			echo "<p>&nbsp;&nbsp;● <a class='fancybox fancybox.iframe' href='$url_view' data-fancybox-type='iframe'>$url_name</a></p>";
		}

//		echo $csv[$ct][1];
		$csv_view[$ct_view][0]=$id_lim;
	}
}


$office_num_view = 0;//コース上にあるofficeの数（今開いているものより古いもののみ！）自分も含む
$arr_live_office_view = array();
for($j = 0;$j <= $id_lim; $j++){
	if(! $ok = get_coursemodule_from_id('office', $j)){
	}else{
		$arr_live_office_view[] = $j;
		$office_num_view++;
	}
}//ここまで


for($ct_view = 0;$ct_view <= $i_view-1;$ct_view++){
	$save_flag_view = 0;
	for($ct2_view=0;$ct2_view <= $office_num_view;$ct2_view++){
		if($csv_view[$ct_view][0] == $arr_live_office_view[$ct2_view]){
			$save_flag_view = 1;
		}
	}
	if($save_flag_view==0){
		unset($csv_view[$ct_view]);
	}
}
$csv_view = array_values($csv_view);



}else{


for($ct_view = 0;$ct_view<=$i_view;$ct_view++){
	
	if($csv_view[$ct_view][0]==$id_lim){
		$lim = count($csv_view[$ct_view])/2;
		for($nt1 = 0;$nt1 < $lim-1;$nt1++){
			$url_view = $csv_view[$ct_view][2*$nt1+1];
			$url_name = $csv_view[$ct_view][2*$nt1+2];
			echo "<p>&nbsp;&nbsp;● <a class='fancybox fancybox.iframe' href='$url_view' data-fancybox-type='iframe'>$url_name</a></p>";
		}
	}
}
}


$file = fopen("view_db.csv", "w");//ここからshareDBの書き込み
for($j=0 ; 0 <= $i_view ; $i_view-- ){
  fputcsv($file, $csv_view[$j]);
  $j++;
}
fclose($file);//ここまでshareDBの書き込み




// Finish the page.
echo $OUTPUT->footer();
