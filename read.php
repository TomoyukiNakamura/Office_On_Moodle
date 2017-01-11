<?php
//$page = $_POST["data"];
$rtn_share = $_POST["rtn_share"];
$rtn_view = $_POST["rtn_view"];
$update_id_bool = $_POST["update_id_bool"];
$courseid = $_POST["courseid"];
$rtn_share = json_decode($rtn_share,false);
$rtn_view = json_decode($rtn_view,false);

//$fp = fopen("data.txt","w");
//fwrite($fp,$page+"dekiteru");
//fclose($fp);
//file_put_contents("data.txt",$page);
//$name1 = $page;
//echo $page;

////$json2 = file_get_contents("url_db.json");
//$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
////$arr = json_decode($json2,true);


//$add_arr = array("id"=>"0","url"=>$page);
////$arr = array_merge($arr["db"],array("url"=>$page));


////$json = json_encode($arr,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
//$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
////file_put_contents("url_db.json",var_dump($arr));
//file_put_contents("https://senburi.ss.osaka-kyoiku.ac.jp/moodle/mod/office/url_db.json",$json);


$csv  = array();
$file = 'url_db.csv';
$fp   = fopen($file, "r");
$i = 0;
$ct_n = 0;
while (($data = fgetcsv($fp, 0, ",")) !== FALSE) {
	$csv[] = $data;
	$i++;
}
fclose($fp);//ここまでdb読み込み（$csvにdbが配列化して入っている）

var_dump($csv);
echo "<br>";
echo "<br>";
var_dump(count($csv[0]));
echo "<br>";
echo "<br>";
var_dump($i);

//$rtn_share = array("1","url3","2","url2","3","url1");

$add_arr = array("url");
$lim = count($rtn_share)/2;
for($ctc = 0 ;$ctc < $lim ; $ctc++){
array_push($add_arr,$rtn_share[2*$ctc],$rtn_share[2*$ctc+1]);
}
array_push($add_arr,$courseid);

var_dump($add_arr);


//$update_id_bool = "91";

if($csv[$i-1][0]=="url"){
	$csv[$i-1] = $add_arr;
}else if($update_id_bool == -1){
	$csv[] = $add_arr;
}else{
	for($ct=0;$ct <= $i-1;$ct++){
		if($csv[$ct][0]==$update_id_bool){//更新を行う際のURL保存先検索→発見後そのURLを編集
			$add_arr[0] = $update_id_bool;
			$csv[$ct] = $add_arr;
		}
	}	
}

echo "<br>";
echo "<br>";
var_dump($csv[0]);
echo "<br>";
var_dump($csv[1]);
echo "<br>";
var_dump($csv[2]);

$file = fopen("url_db.csv", "w");
for($j=0 ; 0 <= $i ; $i-- ){
  fputcsv($file, $csv[$j]);
  $j++;
}
fclose($file);//ここまでshare用






$csv_view  = array();
$file = 'view_db.csv';
$fp   = fopen($file, "r");
$i_view = 0;
while (($data = fgetcsv($fp, 0, ",")) !== FALSE) {
	$csv_view[] = $data;
	$i_view++;
}
fclose($fp);//ここまでdb読み込み（$csv_viewにdbが配列化して入っている）一列の例:id,url壱,名前,url弐,名前＊＊＊＊

$add_arr_view = array("url");
for($ct_view = 0 ;$ct_view < count($rtn_view) ; $ct_view++){
	array_push($add_arr_view,$rtn_view[$ct_view]);
}
array_push($add_arr_view,$courseid);



if($csv_view[$i_view-1][0]=="url"){
	$csv_view[$i_view-1] = $add_arr_view;
}else if($update_id_bool == -1){
	$csv_view[] = $add_arr_view;
}else{
	for($ct=0;$ct <= $i_view-1;$ct++){
		if($csv_view[$ct][0]==$update_id_bool){//更新を行う際のURL保存先検索→発見後そのURLを編集
			$add_arr_view[0] = $update_id_bool;
			$csv_view[$ct] = $add_arr_view;
		}
	}	
}

$file = fopen("view_db.csv", "w");
for($j=0 ; 0 <= $i_view ; $i_view-- ){
  fputcsv($file, $csv_view[$j]);
  $j++;
}
fclose($file);//ここまでshare用


?>
