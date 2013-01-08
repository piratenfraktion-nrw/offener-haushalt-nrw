<?php
$model = new LoginForm();
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>


<h1>Login</h1>

<ul class="clearfix">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableAjaxValidation'=>true,
)); ?>

	<li class="control-group">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</li>

	<li class="control-group">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
		<p class="hint">
		</p>
	</li>

	<li style="list-style: none;" class="control-group">
		<?php echo CHtml::submitButton('Login'); ?>
	</li>

<?php $this->endWidget(); ?>
</ul><!-- form -->
