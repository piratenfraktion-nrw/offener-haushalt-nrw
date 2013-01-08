<?php
$_model = new BudgetItem();
$this->widget('TreemapWidget', array("model" => $_model));
echo '<br/>';
$this->widget('InfoWidget');
?>
