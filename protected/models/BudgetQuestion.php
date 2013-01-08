<?php

class BudgetQuestion extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'tbl_comments';
	}

	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array();
		return array(
			array('title, content, status', 'required'),
			array('status', 'in', 'range'=>array(1,2,3)),
			array('title', 'length', 'max'=>128),
			array('tags', 'match', 'pattern'=>'/^[\w\s,]+$/', 'message'=>'Tags can only contain word characters.'),
			array('tags', 'normalizeTags'),

			array('title, status', 'safe', 'on'=>'search'),
		);
	}


	public function getLatest($limit = 5) {

		//return CList();
	}
}
