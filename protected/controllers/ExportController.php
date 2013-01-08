<?php

class ExportController extends Controller
{
	public $layout='column1';

	/**
	 * Displays the foobar page
	 */
	public function actionCSV()
	{
		header('Content-type: text/csv');
		Yii::import('ext.ECSVExport');
		$model = new Haushaltstitel();      
        $model->init_params();
		$criteria = new CDbCriteria();
		$criteria->condition = $model->query_details["condition"];
		$criteria->group = $model->query_details["group by"];
		$dataProvider=new CActiveDataProvider('Haushaltstitel', array(
    		'criteria'=>$criteria,
                'pagination'=>array(
                        'pageSize'=>500,
                ),
		));

		$csv = new ECSVExport($dataProvider);        
		echo $csv->toCSV();                   
		Yii::app()->end();
	}

	public function actionJSON()
	{
		header('Content-type: application/json');
		$model = new Haushaltstitel();
		$model->init_params();
        //$query = "SELECT " . $model->query_details["select"] . " FROM " . $model->tableName() . " WHERE " . $model->query_details["condition"] . " GROUP BY " . $model->query_details["group by"];
        $query = "SELECT * FROM " . $model->tableName() . " WHERE " . $model->query_details["condition"] . " GROUP BY " . $model->query_details["group by"];
		$_data = Yii::app()->db->createCommand($query)->query();

  		echo CJSON::encode($_data);
		Yii::app()->end();
	}

}
