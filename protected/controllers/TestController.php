<?php

class TestController extends CController {

	public $layout = 'empty_layout';

	public function actionError() {
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	public function actionTest1() {
		$this->render("test");
	}
		

}
