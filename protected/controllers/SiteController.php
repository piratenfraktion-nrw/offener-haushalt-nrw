<?php

class SiteController extends CController {

	public $layout = 'public';
	private $pageTitle = 'Haushaltsdaten NRW - Piratenfraktion im Landtag NRW';
	public $defaultAction = 'budget';
    public $menu = array();
    public $breadcrumbs = array();
    public $params = array();
    public $onloadFunction = "";

	public function setPageTitle($title = "") {
		if(empty($title) === true) {
			$this->pageTitle = 'Haushaltsdaten NRW - Piratenfraktion im Landtag NRW';
		} else {
			$this->pageTitle = $title . ' - Haushaltsdaten NRW - Piratenfraktion im Landtag NRW';
		}
	}

	public function getPageTitle() {
		return $this->pageTitle;
	}

	public function checkParams($budget_type = "regular") {
		// typ - Einnahmen o. Ausgaben
		if(isset($_GET["typ"]) === true) {
			if($_GET["typ"] === "Einnahmen") {
				$this->params["typ"] = "Einnahmen";
			} else if($_GET["typ"] === "Ausgaben") {
				$this->params["typ"] = "Ausgaben";
			} else {
				$this->params["typ"] = "Ausgaben";
			}

		} else {
			$this->params["typ"] = "Ausgaben";
		}

		// year - das Jahr
		if(isset($_GET["year"]) === true) {
			if(isset(Yii::app()->params["jahre"][$_GET["year"]]) === true) {
				$this->params["year"] = $_GET["year"];
			} else {
				$this->params["year"] = "2015";
			}
		} else {
			$this->params["year"] = "2015";
		}

		// entry - kombinierte ID des Titels 
		// entry_level - Tiefe des Eintrags
		$this->params["entry"] = "000";;
		$this->params["entry_level"] = 0;

		$this->params["entry_point_part1"] = null;
		$this->params["entry_point_part2"] = null;
		$this->params["entry_point_part3"] = null;
		$this->params["entry_point_part4"] = null;
		if(isset($_GET["entry"]) === true) {
			$_parts = explode("_", $_GET["entry"]);

			if(isset($_parts[0]) === true) {
				if(preg_match("/^\d{1,2}$/", $_parts[0]) > 0) {
					$this->params["entry_level"] = 1;
					$this->params["entry_point_part1"] = $_parts[0];

					$this->params["entry"] = $_parts[0];
				}
			}

			if(isset($_parts[1]) === true) {
				$_str = str_replace("---", " ", $_parts[1]);
				if(preg_match("/^\d{1,3}\s\d{2,3}$/", $_str) > 0) {
					$this->params["entry_level"] = 2;
					$this->params["entry_point_part2"] = $_str;

					$this->params["entry"] .= "_" . str_replace(" ", "---", $_parts[1]);
				}
			}

			if(isset($_parts[2]) === true) {
				if(preg_match("/^\d{1,4}$/", $_parts[2]) > 0) {
					$this->params["entry_level"] = 3;
					$this->params["entry_point_part3"] = $_parts[2];

					$this->params["entry"] .= "_" . $_parts[2];
				}
			}

			if(isset($_parts[3]) === true) {
				$_str = str_replace("---", " ", $_parts[3]);
				if(preg_match("/^\d{3}\s\d{2}$/", $_str) > 0) {
					$this->params["entry_level"] = 4;
					$this->params["entry_point_part4"] = $_str;

					$this->params["entry"] .= "_" . str_replace(" ", "---", $_parts[3]);
				}
			}

		}

		// budget_type - regulär o. Nachtrag
		$this->params["budget_type"] = $budget_type;
		//var_dump($_GET);
		//var_dump($this->params);
		//die();
	}
	
	public function actions() {
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
		);
	}

	public function actionError() {
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

    public function actionFeedback() {
		$this->checkParams();
		$this->render('feedback');
	}

    public function actionAdmin() {
		//$this->checkParams();
		$this->layout = "admin";
		$this->render('admin');
	}

    public function actionLogin() {
        $model=new LoginForm;

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['LoginForm']))
        {
            $model->attributes=$_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login',array('model'=>$model));
    }

	public function actionHome() {
		$this->checkParams();
        $this->render('home',array());
	}

	public function actionBudget() {
		$this->handleBudgetRequest();
	}

	public function actionSupplementalBudget() {
		$this->handleBudgetRequest("supplemental");
	}

	private function handleBudgetRequest($budget_type = "regular") {
		$this->checkParams($budget_type);

		$model=new BudgetForm;

		if(isset($_POST['BudgetForm'])) {
    		$model->attributes=$_POST['BudgetForm'];
		    $connection=Yii::app()->db;
		    $_entry_point = $this->params["entry"];
		    $_typ = $this->params["typ"];
		    $_name = $_POST["BudgetForm"]["name"];
		    $_email = $_POST["BudgetForm"]["email"];
		    $_telefon = $_POST["BudgetForm"]["telefon"];
		    $_frage = $_POST["BudgetForm"]["frage"];
			$_year = $_POST["hidden_year"];
		    $_datum = time();
		    $_set = true;
		    $_set_array = array("frage");
		    foreach($_set_array as $_f) {
			    if(empty($_POST["BudgetForm"][$_f]) === true) {
				    $_set = false;
			    }
		    }
			$_model_valid = $model->validate();
			if($_model_valid === false) {
				$_set = false;
			}
		    if($_set === true) {
		   		$_sql = "INSERT INTO tbl_comments VALUES ('','".$_entry_point."','".$_typ."','".$_year."','".$_name."','".$_email."','".$_telefon."','".$_frage."', '".$_datum."', 'new')";
			    $_command = $connection->createCommand($_sql);
			    $_ret = $_command->query();
				
				sleep(1);
				$_msg = "Von: " . $_name . " | " . $_telefon . " | " . $_email . "\n";
				$_msg .= $_frage . "\n"; 
				mail(Yii::app()->params["commentEmail"], "Haushalt NRW: Neue Frage im Backend", $_msg);
			    $this->redirect(Yii::app()->params["baseUrl"]."/feedback/" . $_year . "/" . $_typ . "/" . $_entry_point);
		    } else {
			    $this->redirect(Yii::app()->params["baseUrl"]."/" . $_year . "/" . $_typ . "/" . $_entry_point . "/error_name#question_form_error");
		    }
		} else {
			$this->render('budget',array('model'=>$model, 'budget_type' => $budget_type));
		}
	}

}
