<h1>Fragen freischalten</h1>
<?php

if(empty($_GET["delete"]) === false) {
	$connection=Yii::app()->db;
	$_sql = "DELETE FROM tbl_comments WHERE id = '".$_GET["delete"]."'";
	$command = $connection->createCommand($_sql);
	$data = $command->query();
}

if(empty($_GET["accept"]) === false) {
	$connection=Yii::app()->db;
	$_sql = "UPDATE tbl_comments SET status = 'ok' WHERE id = '".$_GET["accept"]."'";
	$command = $connection->createCommand($_sql);
	$data = $command->query();
}


$_sql = "SELECT id, name, email, telefon, entry_point, typ, year, frage, datum FROM tbl_comments WHERE status = 'new' ORDER BY datum DESC";
$connection=Yii::app()->db;
$command = $connection->createCommand($_sql);
$data = $command->query();
$_output = "";
$_output_counter = 0;
foreach($data as $row) {
    ++$_output_counter;
    $_link = Yii::app()->request->baseUrl."/index.php/site/budget?typ=" . $row["typ"] . "&year=" . $row["year"] . "&entry=" . $row["entry_point"];
    $_output .= 'Frage #'.$row["id"]. '<span class="wp-cpl-date">'.date("d.m.Y H:i", $row["datum"]).' Uhr</span>';
    $_output .= $row["name"]. ' ' . $row["email"] . ' ' . $row["telefon"];
    $_output .= '<p class="wp-cpl-excerpt">'.$row["frage"].'</p>';
    $_output .= '<a href="'.Yii::app()->request->baseUrl.'/index.php/site/admin?accept='.$row["id"].'">freischalten</a> <a href="'.Yii::app()->request->baseUrl.'/index.php/site/admin?delete='.$row["id"].'">l&ouml;schen</a>';
	$_output .= "<br/>";
	$_output .= "<br/>";
}

echo $_output;
?>
<br/>
<br/>
