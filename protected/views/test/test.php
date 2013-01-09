<?php
/*$_db = Yii::app()->db;
$_table = "t_2013";
$_sql = "UPDATE ".$_table." SET Kategorie_ID = '0'";
$_db->createCommand($_sql)->query();

$_sql = "SELECT * FROM ".$_table." WHERE Kategorie_ID = '0'";


$_rows = $_db->createCommand($_sql)->query();
$_kat_id = 1;
$_last_kat = "";
foreach($_rows as $_row) {
	if($_last_kat !== $_row["Kategorie"]) {
		++$_kat_id;
	}

	$_sql = "UPDATE ".$_table." SET Kategorie_ID = '".$_kat_id."' WHERE Einzelplan = '".$_row["Einzelplan"]."' AND Kapitel = '".$_row["Kapitel"]."' AND Titel = '".$_row["Titel"]."' LIMIT 1";
	$_db->createCommand($_sql)->query();
	$_last_kat = $_row["Kategorie"];
}*/

?>
