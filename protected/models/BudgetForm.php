<?php

/**
 * QuestionForm class.
 */
class BudgetForm extends CFormModel
{
	public $name;
	public $email;
	public $telefon;
	public $frage;
	public $verifyCode;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('frage', 'required'),
			array('verifyCode', 'captcha', 'allowEmpty'=> !extension_loaded('gd')),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'name'=>'Name (optional)',
			'telefon'=>'Telefon (optional)',
			'email'=>'E-Mail (optional)',
			'frage'=>'Frage',
			'verifyCode'=>'Code',
		);
	}

}
