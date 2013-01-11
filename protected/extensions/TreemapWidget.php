<?php

class TreemapWidget extends CWidget {

	private $content;
	public $model;

    public function init() {
		$this->content = '<div id="infovis"></div>';
		$_ua = Yii::app()->request->userAgent;
		if(strpos($_ua, "MSIE 9.0") !== false) {
			Yii::app()->getClientScript()->registerCssFile(Yii::app()->request->baseUrl . '/assets/treemap_container_ie9.css');
		} elseif(strpos($_ua, "MSIE 8.0") !== false) {
			Yii::app()->getClientScript()->registerCssFile(Yii::app()->request->baseUrl . '/assets/treemap_container_ie8.css');
		} elseif(strpos($_ua, "MSIE 7.0") !== false) {
			Yii::app()->getClientScript()->registerCssFile(Yii::app()->request->baseUrl . '/assets/treemap_container_ie7.css');
		} else {
			Yii::app()->getClientScript()->registerCssFile(Yii::app()->request->baseUrl . '/assets/treemap_container.css');
		}
		Yii::app()->getClientScript()->registerCssFile(Yii::app()->request->baseUrl . '/assets/treemap.css');
		Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/assets/jit.js');
		Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/assets/barchart.js');
		Yii::app()->controller->onloadFunction = 'init(json, json_barchart);';

		$_json = "";
        $_json .= $this->model->render_json_treemap();
        $_json .= $this->model->render_json_barchart();
		if($this->model->entry_level === 3 || $this->model->entry_level === 4) {
			Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/assets/treemap.js');
			Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/assets/treemap_kat.js');
			$_json .= $this->model->vgl_kat();
			Yii::app()->controller->onloadFunction = 'init(json, json_barchart, json_kat);';
			//Yii::app()->controller->onloadFunction = 'init_kat(json_kat);';
		} else {
			Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/assets/treemap_baronly.js');
			Yii::app()->controller->onloadFunction = 'init(json, json_barchart);';
		}
		Yii::app()->clientScript->registerScript('infovis_json',$_json,CClientScript::POS_HEAD);
    }
 
    public function run() {
		echo $this->content;
    }

}

?>
