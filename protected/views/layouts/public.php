<?php $this->beginContent('/layouts/main'); ?>
		<?php echo $content; ?>
        </div>
                                        </section> <!-- end article section -->
                    </article> <!-- end article -->

                </div> <!-- end #main -->
<div id="sidebar-article" class="fluid-sidebar sidebar span3" role="complementary">
    <div class="author-box">
        <h4>Auswahl</h4>
<?php
echo CHtml::dropDownList('select_typ', Yii::app()->controller->params["typ"], Yii::app()->params["eoa_typen"]);
echo CHtml::dropDownList('select_year', Yii::app()->controller->params["year"], Yii::app()->params["jahre"]);
$_js = '
function chooser_link() {
    var e = document.getElementById("select_typ");
    var typ = e.options[e.selectedIndex].value;

    var e = document.getElementById("select_year");
    var year = e.options[e.selectedIndex].value;

    link="'.Yii::app()->request->baseUrl.'/" + year + "/" + typ;
    window.location.href=link;
}
';
Yii::app()->clientScript->registerScript('chooser_function',$_js,CClientScript::POS_HEAD);
?>
                <a class='comment-reply-link' style="position: relative; left: -50px;" onclick='chooser_link();'>Daten anzeigen</a></div>
<div id="wp_category_post_list_itg-2" class="widget widget_wp_category_post_list_itg">
<h4 class="widgettitle">Letzte Fragen</h4>
<ul class="wp-cpl-widget wp-cpl-theme-0">

<?php
$_sql = "SELECT id, name, entry_point, typ, year, frage, datum FROM tbl_comments WHERE status = 'ok' ORDER BY datum DESC LIMIT 5";
$_connection = Yii::app()->db;
$_command = $_connection->createCommand($_sql); 
$_data = $_command->query();
$_output = "";
$_output_counter = 0;
foreach($_data as $_row) {
	++$_output_counter;
	$_link = Yii::app()->request->baseUrl."/" . $_row["year"] . "/" . $_row["typ"] . "/" . $_row["entry_point"] . "#questions";
	$_output .= '<li class="wp-cpl wp-cpl-even">';
	$_output .= '<a href="'.$_link.'" title="" target="_self">Frage #'.$_row["id"].'</a>';
	$_output .= '<span class="wp-cpl-date">'.date("d.m.Y H:i", $_row["datum"]).' Uhr</span>';
	$_output .= '<p class="wp-cpl-excerpt">'.substr($_row["frage"],0,100).'</p>';
	$_output .= '</li>';
}
if($_output_counter > 0) {
	echo($_output);
}
?>

</ul>
</div>
<?php
	$_model = new BudgetItem();
?>
<span class="chart_infotext">Quelle: <a href="http://www.fm.nrw.de/">Finanzministerium NRW</a></span>
<span class="chart_infotext">Datenexport: <a href="<?php echo $_model->get_xls_link(); ?>" target="_new">XLS</a> | <a href="<?php echo $_model->get_ods_link(); ?>" target="_new">ODS</a></span>
</div>
<?php $this->endContent(); ?>
