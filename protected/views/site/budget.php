<?php
$_model = new BudgetItem();
$_model->budgetType = $budget_type;
$_model->set_total();

$this->widget('TreemapWidget', array("model" => $_model));
echo '<br/>';
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $_model->dataProvider,
    'columns' => $_model->columns,
    'enablePagination' => false,
    'enableSorting' => false,
    'summaryText' => "",
    'itemsCssClass' => "my-grid-view-item",
));
?>

<br/>
<br/>
<h1>Vorjahresvergleich<sup>&#42;</sup></h1>
<div id="infovis2"></div>
<span style="font-size: 10px;">*DM-Werte vor 2002 sind in Euro umgerechnet</span>
<br/>
<span style="font-size: 10px;">*Aufgrund von strukturellen &Auml;nderungen in den Haushaltsplänen stehen u.U. nicht für alle Jahre Daten zur Verfügung</span>
<br/>
<br/>
<br/>

<?php
if($_model->entry_level === 3) {
	$_model->vgl_kat();
	$_content = "";
	$_content .= '<br/>
<br/>
<h1>Kategorievergleich &quot;'.$_model->category.'&quot;<sup>&#42;</sup></h1>
';
	$_content .= '<div id="infovis3"></div>';
	$_content .= "\n";
	$_content .= '<span style="font-size: 10px;">*Vergleich der ausgew&auml;hlten Kategorie mit den anderen Einzelpl&auml;nen</span>';
	$_content .= '<br/><br/><br/>';
	echo $_content;

}
if($_model->entry_level === 4) {
	$_model->vgl_kat();
	$_content = "";
	$_content .= '<br/>
<br/>
<h1>Titelvergleich &quot;'.$_model->titel.'&quot;<sup>&#42;</sup></h1>
';
	$_content .= '<div id="infovis3"></div>';
	$_content .= "\n";
	$_content .= '<span style="font-size: 10px;">*Vergleich des ausgew&auml;hlten Titels mit den anderen Einzelpl&auml;nen</span>';
	$_content .= '<br/><br/><br/>';
	echo $_content;

}

?>

<h1 id="questions">Fragen / Kommentare</h1>
<?php
$_c = Yii::app()->controller;
$_sql = "SELECT id, name, entry_point, typ, frage, datum FROM tbl_comments WHERE entry_point = '".$_c->params["entry"]."' AND year = '".$_c->params["year"]."' AND status = 'ok' ORDER BY datum DESC LIMIT 5";
$connection=Yii::app()->db;
$command = $connection->createCommand($_sql);
$data = $command->query();
$_output = "";
$_output_counter = 0;
foreach($data as $row) {
    ++$_output_counter;
    $_link = Yii::app()->request->baseUrl."/index.php/site/budget?typ=" . $row["typ"] . "&entry=" . $row["entry_point"];
    $_output .= 'Frage #'.$row["id"].'<br/>' . '<span class="wp-cpl-date">'.date("d.m.Y H:i", $row["datum"]).' Uhr</span>';
    $_output .= '<p class="wp-cpl-excerpt">'.$row["frage"].'</p>';
}
if($_output_counter > 0) {
    echo($_output);
} else {
    echo("<br/>");
}
?>
Stell dir vor,du hast Fragen und die Regierung MUSS antworten! ...
Die persönlichen Daten werden nur für eventuelle Rückfragen genutzt. Sie werden nicht veröffentlicht oder weitergegeben und sind optional.

<?php
    $model = new BudgetForm();

    $form=$this->beginWidget('CActiveForm', array(
    'id'=>'question-form',
    'enableAjaxValidation'=>true,
 'htmlOptions'=>array(
        'class'=>'form-vertical',
    ),
)); ?>

<br/>
<span id="question_form_error" style="color: red;">
<?php
if(isset($_GET["error"]) === true) {
    echo "Bitte gib mindestens deine Frage ein!";
}
?>
</span>
<ul id="question_form" class="clearfix">
        <li>
            <div class="control-group">
              <label for="name">
                <?php echo $form->labelEx($model,'name'); ?>
                </label>
                <?php echo $form->textField($model,'name'); ?>
                <?php echo $form->error($model,'name'); ?>
            </div>
        </li>
        <li>
            <div class="control-group">
              <label for="email">
                <?php echo $form->labelEx($model,'email'); ?>
                </label>
                <?php echo $form->textField($model,'email'); ?>
                <?php echo $form->error($model,'email'); ?>
            </div>
        </li>
        <li>
            <div class="control-group">
              <label for="telefon">
                <?php echo $form->labelEx($model,'telefon'); ?>
                </label>
                <?php echo $form->textField($model,'telefon'); ?>
                <?php echo $form->error($model,'telefon'); ?>
            </div>
        </li>
        <li>
            <div class="control-group">
              <label for="frage">
                <?php echo $form->labelEx($model,'frage'); ?>
                </label>
                <?php echo $form->textArea($model,'frage', array('rows'=>5, 'cols'=>15)); ?>
                <?php echo $form->error($model,'frage'); ?>
                <?php echo '<input type="hidden" name="hidden_year" value="'.Yii::app()->controller->params["year"].'"'; ?> 
            </div>
        </li>
        <li>
            <div class="control-group">
              <label for="captcha">
                <?php echo $form->labelEx($model,'verifyCode'); ?>
                </label>
                <?php echo $form->textField($model,'verifyCode'); ?>
			<?php $this->widget("CCaptcha"); ?>
            </div>
        </li>

        <li style="list-style: none;">
            <div class="control-group">
                <?php echo CHtml::submitButton('Abschicken'); ?>
            </div>
        </li>
<?php $this->endWidget(); ?>
</ul>

<?php
$this->widget('InfoWidget');
?>
