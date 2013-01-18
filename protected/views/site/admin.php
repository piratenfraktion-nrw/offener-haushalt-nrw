<h1>Fragen freischalten</h1>
<?php
$_connection = Yii::app()->db;
$_sql = "SELECT value FROM config WHERE name = 'security_code' LIMIT 1";
$_command = $_connection->createCommand($_sql);
$_data = $_command->query();
foreach($_data as $_row) {
	if($_row["value"] !== $_GET["code"]) {
		die();
	}
}

if(isset($_GET["action"]) === true) {
	if($_GET["action"] === "delete" || $_GET["action"] === "accept") {
		$_action = $_GET["action"];
	} else {
		die();
	}
} else {
	$_action = "";
}

$_id = "";
if(isset($_GET["id"]) === true) {
	if(is_numeric($_GET["id"]) === true) {
		$_id = $_GET["id"];
	}
}

if($_action === "delete") {
	$_sql = "UPDATE tbl_comments SET status = 'deleted' WHERE id = '".$_id."'";
	$_command = $_connection->createCommand($_sql);
	$_data = $_command->query();
}

if($_action === "accept") {
	$_sql = "UPDATE tbl_comments SET status = 'ok' WHERE id = '".$_id."'";
	$_command = $_connection->createCommand($_sql);
	$_data = $_command->query();
}


$_sql = "SELECT id, name, email, telefon, entry_point, typ, year, frage, datum FROM tbl_comments WHERE status = 'new' ORDER BY datum DESC";
$_command = $_connection->createCommand($_sql);
$_data = $_command->query();
$_output = "";
$_output_counter = 0;
foreach($_data as $_row) {
    ++$_output_counter;
    $_link = Yii::app()->params["baseUrl"]."/" . $_row["year"] . "/" . $_row["typ"] . "/" . $_row["entry_point"];
    $_output .= 'Frage #'.$_row["id"]. '<span class="wp-cpl-date">'.date("d.m.Y H:i", $_row["datum"]).' Uhr</span>';
    $_output .= $_row["name"]. ' ' . $_row["email"] . ' ' . $_row["telefon"];
    $_output .= '<p class="wp-cpl-excerpt">'.$_row["frage"].'</p>';
    $_output .= '<a href="'.Yii::app()->params["baseUrl"].'/admin/'.$_GET["code"].'/accept/'.$_row["id"].'">freischalten</a> <a href="'.Yii::app()->params["baseUrl"].'/admin/'.$_GET["code"].'/delete/'.$_row["id"].'">l&ouml;schen</a>';
	$_output .= "<br/>";
	$_output .= "<br/>";
}

echo $_output;
?>
<br/>
<br/>
