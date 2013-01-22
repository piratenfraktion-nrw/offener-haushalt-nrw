<?php

// NRW Haushalt
class BudgetItem extends CActiveRecord {
 
    public $Summe1;
    public $Summe2;
    public $DifferenzVorjahr;
    public $Kapiteltext;
    private $total;
    public $year;
    public $entry_point;
    public $entry_point_parts = array();
    public $entry_level;
    public $typ;
    public $query_details = array();
    public $query_details_vgl = array();
    public $columns;
    public $criteria;
    public $dataProvider;
    public $budgetType;
    public $category;
    public $titel;

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

	public function get_ods_link() {
		return Yii::app()->baseUrl . "/assets/" . $this->year . ".ods";
	}
	public function get_xls_link() {
		return Yii::app()->baseUrl . "/assets/" . $this->year . ".xls";
	}

	public function init() {
		$_c = Yii::app()->controller;
		$this->year = $_c->params["year"];
		$this->typ = $_c->params["typ"];
		$this->entry_level = $_c->params["entry_level"];
		$this->entry_point_parts[0] = $_c->params["entry_point_part1"];
		$this->entry_point_parts[1] = $_c->params["entry_point_part2"];
		$this->entry_point_parts[2] = $_c->params["entry_point_part3"];
		$this->entry_point_parts[3] = $_c->params["entry_point_part4"];
		$this->budgetType = $_c->params["budget_type"];
		
		// Übersicht über alle Einzelpläne
		if($this->entry_level === 0) {
			$this->query_details["select"] = "Einzelplan, Beschreibung, Beschreibung1, SUM(Wert1) as Summe1, SUM(Wert2) AS Summe2";
			$this->query_details["condition"] = "Typ = '".$this->typ."'";
			$this->query_details["group by"] = "Einzelplan";
			$this->query_details["order by"] = "SUM(Wert1) DESC";
			$this->query_details["total_query"] = "SELECT SUM(Wert1) AS Summe1, SUM(Wert2) AS Summe2 FROM " . $this->tableName()." WHERE " . $this->query_details["condition"];

			$this->columns = array(
        array(
            'name'=>'Einzelplan',
            'type'=>'html',
            'value'=>array($this, 'render_ep'),
            'htmlOptions'=>array('width'=>'500'),
            'headerHtmlOptions'=>array('width'=>'500'),
        ),
        array(
            'name'=>$this->typ . ' (in €)',
            'value'=>'number_format($data->Summe1, 0, "," , ".")',
            'cssClassExpression'=>'"text_align_right"',
            'htmlOptions'=>array('width'=>'120'),
            'headerHtmlOptions'=>array('width'=>'120'),
        ),
        array(
            'name'=>'Anteil',
            'value'=>array($this, "get_percentage"),
            'cssClassExpression'=>'"text_align_right"',
            'htmlOptions'=>array('width'=>'80'),
            'headerHtmlOptions'=>array('width'=>'80'),
        ),
        array(
            'name'=>'Vorjahr +/- (in €)',
            'type'=>'html',
            'value'=>array($this, "get_diff_vorjahr"),
            'cssClassExpression'=>'"text_align_right"',
            'htmlOptions'=>array('width'=>'150'),
            'headerHtmlOptions'=>array('width'=>'150'),
        ),
    );
		// Übersicht über alle Kapitel in einem Einzelplan
		} elseif($this->entry_level === 1) {
			$this->query_details["select"] = "Einzelplan, Kapitel, Kapitelname, CONCAT(Kapitel, ' ', Kapitelname) AS Kapiteltext, SUM(Wert1) as Summe1, SUM(Wert2) AS Summe2";
			$this->query_details["condition"] = "Typ = '".$this->typ."' AND Einzelplan = '" . $this->entry_point_parts[0] . "'";
			$this->query_details["group by"] = "Einzelplan, Kapitel";
			$this->query_details["order by"] = "Summe1 DESC";
			$this->query_details["total_query"] = "SELECT SUM(Wert1) AS Summe1, SUM(Wert2) AS Summe2 FROM " . $this->tableName() . " WHERE " . $this->query_details["condition"];

			$this->columns = array(
        array(
            'name'=>'Kapitel',
            'type'=>'html',
            'value'=>array($this, 'render_kapitel'),
            'htmlOptions'=>array('width'=>'500'),
            'headerHtmlOptions'=>array('width'=>'500'),
        ),
        array(
            'name'=>$this->typ . ' (in €)',
            'value'=>'number_format($data->Summe1, 0, "," , ".")',
            'cssClassExpression'=>'"text_align_right"',
            'htmlOptions'=>array('width'=>'120'),
            'headerHtmlOptions'=>array('width'=>'120'),
        ),
        array(
            'name'=>'Anteil',
            'value'=>array($this, "get_percentage"),
            'cssClassExpression'=>'"text_align_right"',
            'htmlOptions'=>array('width'=>'80'),
            'headerHtmlOptions'=>array('width'=>'80'),
        ),
        array(
            'name'=>'Vorjahr +/- (in €)',
            'type'=>'html',
            'value'=>array($this, "get_diff_vorjahr"),
            'cssClassExpression'=>'"text_align_right"',
            'htmlOptions'=>array('width'=>'150'),
            'headerHtmlOptions'=>array('width'=>'150'),
        ),
    );
		// Übersicht über alle Kategorien in einem Kapitel
		} elseif($this->entry_level === 2) {
			$this->query_details["select"] = "Einzelplan, Kapitel, Kapitelname, CONCAT(Kapitel, ' ', Kapitelname) AS Kapiteltext, SUM(Wert1) as Summe1, SUM(Wert2) AS Summe2, Kategorie, Kategorie_ID";
			$this->query_details["condition"] = "Typ = '".$this->typ."' AND Einzelplan = '" . $this->entry_point_parts[0] . "' AND Kapitel = '".$this->entry_point_parts[1]."'";
			$this->query_details["group by"] = "Einzelplan, Kapitel, Kategorie";
			$this->query_details["order by"] = "Summe1 DESC";
			$this->query_details["total_query"] = "SELECT SUM(Wert1) AS Summe1, SUM(Wert2) AS Summe2 FROM " . $this->tableName() . " WHERE " . $this->query_details["condition"];

			$this->columns = array(
         array(
              'name'=>'Kategorie',
              'type'=>'html',
              'value'=>array($this, "render_kategorie"),
            'htmlOptions'=>array('width'=>'500'),
            'headerHtmlOptions'=>array('width'=>'500'),
            ),
          array(
              'name'=>$this->typ . ' (in €)',
              'value'=>'number_format($data->Summe1, 0, "," , ".")',
              'cssClassExpression'=>'"text_align_right"',
                'htmlOptions'=>array('width'=>'120'),
            'headerHtmlOptions'=>array('width'=>'120'),
          ),
          array(
              'name'=>'Anteil',
             'value'=>array($this, "get_percentage"),
              'cssClassExpression'=>'"text_align_right"',
            'htmlOptions'=>array('width'=>'80'),
            'headerHtmlOptions'=>array('width'=>'80'),
          ),
               array(
              'name'=>'Vorjahr +/- (in €)',
              'type'=>'html',
              'value'=>array($this, "get_diff_vorjahr"),
              'cssClassExpression'=>'"text_align_right"',
            'htmlOptions'=>array('width'=>'150'),
            'headerHtmlOptions'=>array('width'=>'150'),
          ),
        );
		// Übersicht über alle Titel in einer Kategorie
		} elseif($this->entry_level === 3) {
			$this->query_details["select"] = "Einzelplan, Beschreibung, Beschreibung1, Kapitel, Kapitelname, CONCAT(Kapitel, ' ', Kapitelname) AS Kapiteltext, SUM(Wert1) as Summe1, SUM(Wert2) AS Summe2, Kategorie, Kategorie_ID, Titel";
			$this->query_details["condition"] = "Typ = '".$this->typ."' AND Einzelplan = '" . $this->entry_point_parts[0] . "' AND Kapitel = '".$this->entry_point_parts[1]."' AND Kategorie_ID = '".$this->entry_point_parts[2]."'";
			$this->query_details["group by"] = "Einzelplan, Kapitel, Kategorie, Titel";
			$this->query_details["order by"] = "Summe1 DESC";
			$this->query_details["total_query"] = "SELECT SUM(Wert1) AS Summe1, SUM(Wert2) AS Summe2 FROM " . $this->tableName() . " WHERE " . $this->query_details["condition"];

			$this->columns = array(
         array(
              'name'=>'Titel',
              'type'=>'html',
              'value'=>array($this, "render_titel"),
            'htmlOptions'=>array('width'=>'500'),
            'headerHtmlOptions'=>array('width'=>'500'),
            ),
          array(
              'name'=>$this->typ . ' (in €)',
              'value'=>'number_format($data->Summe1, 0, "," , ".")',
              'cssClassExpression'=>'"text_align_right"',
                'htmlOptions'=>array('width'=>'120'),
            'headerHtmlOptions'=>array('width'=>'120'),
          ),
          array(
              'name'=>'Anteil',
             'value'=>array($this, "get_percentage"),
              'cssClassExpression'=>'"text_align_right"',
            'htmlOptions'=>array('width'=>'80'),
            'headerHtmlOptions'=>array('width'=>'80'),
          ),
               array(
              'name'=>'Vorjahr +/- (in €)',
              'type'=>'html',
              'value'=>array($this, "get_diff_vorjahr"),
              'cssClassExpression'=>'"text_align_right"',
            'htmlOptions'=>array('width'=>'150'),
            'headerHtmlOptions'=>array('width'=>'150'),
          ),
        );

		// einzelner Titel
		} elseif($this->entry_level === 4) {
			$this->query_details["select"] = "Einzelplan, Beschreibung, Beschreibung1, Kapitel, Kapitelname, CONCAT(Kapitel, ' ', Kapitelname) AS Kapiteltext, SUM(Wert1) as Summe1, SUM(Wert2) AS Summe2, Kategorie, Kategorie_ID, Titel";
			$this->query_details["condition"] = "Typ = '".$this->typ."' AND Einzelplan = '" . $this->entry_point_parts[0] . "' AND Kapitel = '".$this->entry_point_parts[1]."' AND Titel = '".$this->entry_point_parts[3]."'";
			$this->query_details["group by"] = "Einzelplan, Kapitel, Kategorie, Titel";
			$this->query_details["order by"] = "Summe1 DESC";
			$this->query_details["total_query"] = "SELECT SUM(Wert1) AS Summe1, SUM(Wert2) AS Summe2 FROM " . $this->tableName() . " WHERE " . $this->query_details["condition"];

			$this->columns = array(
         array(
              'name'=>'Titel',
              'type'=>'html',
              'value'=>array($this, "render_titel"),
            'htmlOptions'=>array('width'=>'500'),
            'headerHtmlOptions'=>array('width'=>'500'),
            ),
          array(
              'name'=>$this->typ . ' (in €)',
              'value'=>'number_format($data->Summe1, 0, "," , ".")',
              'cssClassExpression'=>'"text_align_right"',
                'htmlOptions'=>array('width'=>'120'),
            'headerHtmlOptions'=>array('width'=>'120'),
          ),
          array(
              'name'=>'Anteil',
             'value'=>array($this, "get_percentage"),
              'cssClassExpression'=>'"text_align_right"',
            'htmlOptions'=>array('width'=>'80'),
            'headerHtmlOptions'=>array('width'=>'80'),
          ),
               array(
              'name'=>'Vorjahr +/- (in €)',
              'type'=>'html',
              'value'=>array($this, "get_diff_vorjahr"),
              'cssClassExpression'=>'"text_align_right"',
            'htmlOptions'=>array('width'=>'150'),
            'headerHtmlOptions'=>array('width'=>'150'),
          ),
        );

		}

		$this->criteria = new CDbCriteria();
		$this->criteria->condition = $this->query_details["condition"];
		$this->criteria->group = $this->query_details["group by"];
		$this->criteria->select = $this->query_details["select"];
		$this->criteria->order = $this->query_details["order by"];

		$this->dataProvider=new CActiveDataProvider('BudgetItem', array(
		    'criteria' => $this->criteria,
		    'pagination' => array(
		    'pageSize' => 500,  
		    ),          
		));
	}

	public function vgl_kat() {
		$_kat = mysql_escape_string($this->category); // teilweise führen die Kategorienamen ''
		$_tit = $this->titel;

		$_sql = "";
		if($this->entry_level === 3) {
			$_sql .= "SELECT Einzelplan, SUM(Wert1) AS Summe1 FROM t_" . $this->year;
			$this->query_details_vgl["select"] = "Einzelplan, SUM(Wert1) AS Summe1";
			$_sql .= " WHERE Typ = '" . $this->typ . "' AND Kategorie = '".$_kat."'";
			$this->query_details_vgl["condition"] = "Typ = '" . $this->typ . "' AND Kategorie = '".$_kat."'";
			$_sql .= " GROUP BY Einzelplan, Kategorie";
			$this->query_details_vgl["group by"] = "Einzelplan, Kategorie";
			$_sql .= " ORDER BY SUM(Wert1) DESC";
			$this->query_details_vgl["order by"] = "SUM(Wert1) DESC";
		} else {
			$_sql .= "SELECT Einzelplan, SUM(Wert1) AS Summe1 FROM t_" . $this->year;
			$this->query_details_vgl["select"] = "Einzelplan, SUM(Wert1) AS Summe1";
			$_sql .= " WHERE Typ = '" . $this->typ . "' AND Titel = '".$_tit."'";
			$this->query_details_vgl["condition"] = "Typ = '" . $this->typ . "' AND Titel = '".$_tit."'";
			$_sql .= " GROUP BY Einzelplan, Titel";
			$this->query_details_vgl["group by"] = "Einzelplan, Titel";
			$_sql .= " ORDER BY SUM(Wert1) DESC";
			$this->query_details_vgl["order by"] = "SUM(Wert1) DESC";
		}

		$query = $_sql;
		//$_json = 'var json_kat = eval({';
		$_json = 'var json_kat = {';
				$_json .= "\n";
  		$_json .= '"children": [';
				$_json .= "\n";

		if(Yii::app()->params["caching"] === true) {
			$_cache_result = Yii::app()->cache->get($query);
			if($_cache_result !== false) {
				return $_cache_result;
			}
		}

		$_rows = Yii::app()->db->createCommand($query)->query();
		$_count_rows = count($_rows);
		$_counter = 1;
		if($_count_rows === 0) {
			$_parent_name = $this->typ . " " . $this->year;
		}
    	foreach($_rows as $_row) {
			$_entry_key = Yii::app()->params["baseUrl"] . "/" . $this->year . "/" . $this->typ . "/" . $_row["Einzelplan"];
			$_entry_key_parent = Yii::app()->params["baseUrl"] . "/" . $this->year . "/" . $this->typ;
			$_parent_name = $this->typ . " " . $this->year;

			if($this->entry_level === 0) {
				/*$_json .= "\n";
				$_json .= '{"name": "'.Yii::app()->params["einzelplan_namen"][$this->year][$_row["Einzelplan"]].'",';
				$_json .= "\n";
				$_json .= '"id": "'.$_row["Einzelplan"].'",';
				$_json .= "\n";*/
			} elseif($this->entry_level === 1) {
				/*$_row["Kapitelname"] = $this->strip_foo($_row["Kapitelname"]);
				$_json .= "\n";
				$_json .= '{"name": "'.$_row["Kapitelname"].'",';
				$_json .= "\n";
				$_json .= '"id": "'.$_row["Kapitel"].'",';
				$_json .= "\n";
				$_entry_key .= "_" . str_replace(" ", "---", $_row["Kapitel"]);
				$_parent_name .= ' >> ' . Yii::app()->params["einzelplan_namen"][$this->year][$_row["Einzelplan"]];*/
			} elseif($this->entry_level === 2) {
				/*$_row["Kapitelname"] = $this->strip_krams($_row["Kapitelname"]);
				$_row["Kategorie"] = $this->strip_foo($_row["Kategorie"]);
				$_json .= "\n";
				$_json .= '{"name": "'.$_row["Kategorie"].'",';
				$_json .= "\n";
				$_json .= '"id": "'.$_row["Kategorie_ID"].'",';
				$_json .= "\n";
				$_entry_key .= "_" . str_replace(" ", "---", $_row["Kapitel"]) . "_" . $_row["Kategorie_ID"];
				$_entry_key_parent .= "/" . $_row["Einzelplan"];
				$_parent_name .= ' >> ' . Yii::app()->params["einzelplan_namen"][$this->year][$_row["Einzelplan"]] . " >> " . $_row["Kapiteltext"];*/
			} elseif($this->entry_level === 3 || $this->entry_level === 4) {
				/*$_row["Kapitelname"] = $this->strip_krams($_row["Kapitelname"]);
				$_row["Kategorie"] = $this->strip_foo($_row["Kategorie"]);
				//$_row["Beschreibung1"] = $this->strip_foo($_row["Beschreibung1"]);
				$_row["Beschreibung1"] = $this->strip_krams($_row["Beschreibung"]);
				$_json .= "\n";
				$_json .= '{"name": "'.$_row["Titel"]. ' ' . $_row["Beschreibung1"]  . '",';
				$_json .= "\n";
				$_json .= '"id": "'.$_row["Titel"].'",';
				$_json .= "\n";
				$_entry_key_parent .= "&entry=" . $_row["Einzelplan"] . "_" . str_replace(" ", "---", $_row["Kapitel"]);
				$_parent_name .= ' >> ' . Yii::app()->params["einzelplan_namen"][$this->year][$_row["Einzelplan"]] . " >> " . $_row["Kapiteltext"] . " >> " . $_row["Kategorie"];*/
				$_json .= "\n";
				$_json .= '{"name": "'.Yii::app()->params["einzelplan_namen"][$this->year][$_row["Einzelplan"]]. '",';
				$_json .= "\n";
				$_json .= '"id": "'.$_row["Einzelplan"].'",';
				$_json .= "\n";
				//$_entry_key_parent .= "&entry=" . $_row["Einzelplan"] . "_" . str_replace(" ", "---", $_row["Kapitel"]);
				//$_parent_name .= ' >> ' . Yii::app()->params["einzelplan_namen"][$this->year][$_row["Einzelplan"]] . " >> " . $_row["Kapiteltext"] . " >> " . $_row["Kategorie"];
				$_parent_name .= ' >> Kategorievergleich  \"' . $_kat . '\"';
			}
			//$_json .= '"$color": "#481b79",';
			//$_json .= '"entry_key": "'.$_row["Einzelplan"].'",';
			if($this->entry_level === 3) {
				$_area = $this->euro_dm($_row["Summe1"]);
				if($_area < 0) {
					$_area = $this->euro_dm($_row["Summe1"]) * -1;
				}
				$_json .= '"data": {   '.'"$color": "'.Yii::app()->params["einzelplan_farben"][$_row["Einzelplan"]].'",'.'"value": "'.$this->euro_dm($_row["Summe1"]).'",   "$area": "'.$_area.'", "entry_key_parent": "'.$_entry_key_parent.'" }}';
			} else {
				$_area = $this->euro_dm($_row["Summe1"]);
				if($_area < 0) {
					$_area = $this->euro_dm($_row["Summe1"]) * -1;
				}
				$_json .= '"data": {   '.'"$color": "'.Yii::app()->params["einzelplan_farben"][$_row["Einzelplan"]].'",'.'"value": "'.$this->euro_dm($_row["Summe1"]).'",   "$area": "'.$_area.'",   "entry_key": "'.$_entry_key.'", "entry_key_parent": "'.$_entry_key_parent.'" }}';
				$_json .= "\n";
			}
			if($_counter < $_count_rows) {
				$_json .= ',';
				$_json .= "\n";
			}
			++$_counter;
		}

		$_root_parent = Yii::app()->params["baseUrl"] . "/";
		/*if($this->entry_level === 1) {
		} elseif($this->entry_level === 2) {
			$_root_parent .= "/".$this->year."/" . $this->typ . "/" . $this->entry_point_parts[0];
		} elseif($this->entry_level === 3) {
			$_root_parent .= "/".$this->year."/" . $this->typ . "/" . $this->entry_point_parts[0] . "_" . str_replace(" ", "---", $this->entry_point_parts[1]); 
		}*/
		$_json .= '],';
		$_json .= "\n";
		$_json .= '   "data": { "entry_key_parent" : "'.$_root_parent.'"},';
		$_json .= "\n";
		$_json .= '   "id": "root",';
		$_json .= "\n";
		$_parent_name_full = $_parent_name;
		if(strlen($_parent_name) > 200) {
			$_parent_name = substr($_parent_name, 0, 197) . "...";
		}	
		$_json .= '   "name": "'.$_parent_name . '",';
		$_json .= "\n";
		$_json .= '   "name_full": "'.$_parent_name_full . '"';
		$_json .= "\n";
		$_json .= '};';
		$_json .= "\n";

		if(Yii::app()->params["caching"] === true) {
			Yii::app()->cache->set($query,$_json, 0);
		}
		return $_json;
	}

	public function render_json_barchart() {
		$_values = Yii::app()->params["jahre_val"];
		$_sql = "";

		$_kat = mysql_escape_string($this->category); // teilweise führen die Kategorienamen ''
		$_count_values = count($_values);
		$_value_counter = 1;
		foreach($_values as $_value) {
			if($_value === "2004 / 2005") {
				$_value = "2004";
			}
			if($this->entry_level === 0) {
				$_sql .= "SELECT Jahr, Einzelplan, SUM(Wert1) AS Wert FROM t_" . $_value;
				$_sql .= " WHERE Typ = '" . $this->typ . "'";
			} else if($this->entry_level === 1) {
				$_sql .= "SELECT Jahr, Einzelplan, SUM(Wert1) AS Wert FROM t_" . $_value;
				$_sql .= " WHERE Typ = '" . $this->typ . "'";
				$_sql .= " AND Einzelplan = '" . $this->entry_point_parts[0] . "'";
			} else if($this->entry_level === 2) {
				$_sql .= "SELECT Jahr, Einzelplan, SUM(Wert1) AS Wert FROM t_" . $_value;
				$_sql .= " WHERE Typ = '" . $this->typ . "'";
				$_sql .= " AND Einzelplan = '" . $this->entry_point_parts[0] . "'";
				$_sql .= " AND Kapitel = '" . $this->entry_point_parts[1] . "'";
			} else if($this->entry_level === 3) {
				$_sql .= "SELECT Jahr, Einzelplan, SUM(Wert1) AS Wert FROM t_" . $_value;
				$_sql .= " WHERE Typ = '" . $this->typ . "'";
				$_sql .= " AND Einzelplan = '" . $this->entry_point_parts[0] . "'";
				$_sql .= " AND Kapitel = '" . $this->entry_point_parts[1] . "'";
				//$_sql .= " AND Kategorie = '" . $this->entry_point_parts[2] . "'";
				$_sql .= " AND Kategorie = '" . $_kat . "'";
			} else if($this->entry_level === 4) {
				$_sql .= "SELECT Jahr, Einzelplan, SUM(Wert1) AS Wert FROM t_" . $_value;
				$_sql .= " WHERE Typ = '" . $this->typ . "'";
				$_sql .= " AND Einzelplan = '" . $this->entry_point_parts[0] . "'";
				$_sql .= " AND Kapitel = '" . $this->entry_point_parts[1] . "'";
				//$_sql .= " AND Kategorie = '" . $this->entry_point_parts[2] . "'";
				$_sql .= " AND Kategorie = '" . $_kat. "'";
				$_sql .= " AND Titel = '" . $this->entry_point_parts[3] . "'";
			}
	
			if($_value_counter < $_count_values) {
				$_sql .= " UNION ";
			}

			++$_value_counter;
		}

		$_connection=Yii::app()->db;
		$_command = $_connection->createCommand($_sql);
		$_data = $_command->query();
		$_results = array();
		foreach($_data as $_row) {
			if(isset(Yii::app()->params["dm_years"][$_row["Jahr"]]) === true) {
				$_results[$_row["Jahr"]] = $_row["Wert"] * Yii::app()->params["factor_euro_dm"];
			} else {
				$_results[$_row["Jahr"]] = $_row["Wert"];
			}
		}

		$_json = 'var json_barchart = {';
		$_json .= "\n";

		$_json .= '"label": ["' . implode('","', $_values) . '"],';
		$_json .= "\n";
		$_json .= '"values": [';
		$_json .= "\n";

		$_value_counter = 0;
		//$_color = "'#C0C0C0', '#000000'";
		foreach($_values as $_value) {
			$_label = $_value;
			if($_value === "2004 / 2005") {
				$_value = "2004_2005";
				$_label = "2004 / 2005";
			}
			$_json .= '{';
			//$_json .= "\n";
			//$_json .= '"colors": [' . $_color . '],';
			$_json .= "\n";
			$_json .= '"label": "' . $_label . '",';
			$_json .= "\n";
			if(isset($_results[$_value]) === true) {
				$_out_value = $_results[$_value];
			} else {
				$_out_value = 0;
			}
			$_json .= '"values": [' . $_out_value . ']';
			$_json .= "\n";
			$_json .= '}';

			++$_value_counter;
			if($_value_counter < $_count_values) {
				$_json .= ",";
			}
			$_json .= "\n";
		}

		$_json .= ']';
		$_json .= "\n";
		$_json .= '}';
		$_json .= "\n";

		return $_json;
	}

	public function render_json_treemap() {
		$query = "SELECT " . $this->query_details["select"] . " FROM " . $this->tableName() . " WHERE " . $this->query_details["condition"] . " GROUP BY " . $this->query_details["group by"];

		$_json = 'var json = {';
				$_json .= "\n";
  		$_json .= '"children": [';
				$_json .= "\n";

		if(Yii::app()->params["caching"] === true) {
			$_cache_result = Yii::app()->cache->get($query);
			if($_cache_result !== false) {
				return $_cache_result;
			}
		}

		$_rows = Yii::app()->db->createCommand($query)->query();
		$_count_rows = count($_rows);
		$_counter = 1;
		if($_count_rows === 0) {
			$_parent_name = $this->typ . " " . $this->year;
		}
    	foreach($_rows as $_row) {
			$_view = "budget";
			$_entry_key = Yii::app()->params["baseUrl"] . "/" . $this->year . "/" . $this->typ . "/" . $_row["Einzelplan"];
			$_entry_key_parent = Yii::app()->params["baseUrl"] . "/" . $this->year . "/" . $this->typ;
			$_parent_name = $this->typ . " " . $this->year;

			Yii::app()->controller->setPageTitle($_parent_name);
			if($this->entry_level === 0) {
				$_json .= "\n";
				$_json .= '{"name": "'.Yii::app()->params["einzelplan_namen"][$this->year][$_row["Einzelplan"]].'",';
				$_json .= "\n";
				$_json .= '"id": "'.$_row["Einzelplan"].'",';
				$_json .= "\n";
			} elseif($this->entry_level === 1) {
				$_row["Kapitelname"] = $this->strip_foo($_row["Kapitelname"]);
				$_json .= "\n";
				$_json .= '{"name": "'.$_row["Kapitelname"].'",';
				$_json .= "\n";
				$_json .= '"id": "'.$_row["Kapitel"].'",';
				$_json .= "\n";
				$_entry_key .= "_" . str_replace(" ", "---", $_row["Kapitel"]);
				$_parent_name .= ' >> ' . Yii::app()->params["einzelplan_namen"][$this->year][$_row["Einzelplan"]];
				Yii::app()->controller->setPageTitle($_parent_name);
			} elseif($this->entry_level === 2) {
				$_row["Kapitelname"] = $this->strip_krams($_row["Kapitelname"]);
				$_row["Kategorie"] = $this->strip_foo($_row["Kategorie"]);
				$_json .= "\n";
				$_json .= '{"name": "'.$_row["Kategorie"].'",';
				$_json .= "\n";
				$_json .= '"id": "'.$_row["Kategorie"].'",';
				$_json .= "\n";
				$_entry_key .= "_" . str_replace(" ", "---", $_row["Kapitel"]) . "_" . $_row["Kategorie_ID"];
				$_entry_key_parent .= "/" . $_row["Einzelplan"];
				$_parent_name .= ' >> ' . Yii::app()->params["einzelplan_namen"][$this->year][$_row["Einzelplan"]] . " >> " . $_row["Kapiteltext"];
				Yii::app()->controller->setPageTitle($_parent_name);
			} elseif($this->entry_level === 3) {
				$_row["Kapitelname"] = $this->strip_krams($_row["Kapitelname"]);
				$_row["Kategorie"] = $this->strip_foo($_row["Kategorie"]);
				//$_row["Beschreibung1"] = $this->strip_foo($_row["Beschreibung1"]);
				$this->category = $_row["Kategorie"];
				$_row["Beschreibung1"] = $this->strip_krams($_row["Beschreibung"]);
				$_json .= "\n";
				$_json .= '{"name": "'.$_row["Titel"]. ' ' . $_row["Beschreibung1"]  . '",';
				$_json .= "\n";
				$_json .= '"id": "'.$_row["Titel"].'",';
				$_json .= "\n";
				$_entry_key .= "_" . str_replace(" ", "---", $_row["Kapitel"]) . "_" . $_row["Kategorie_ID"] . "_" . str_replace(" ", "---", $_row["Titel"]);
				$_entry_key_parent .= "/" . $_row["Einzelplan"] . "_" . str_replace(" ", "---", $_row["Kapitel"]);
				$_parent_name .= ' >> ' . Yii::app()->params["einzelplan_namen"][$this->year][$_row["Einzelplan"]] . " >> " . $_row["Kapiteltext"] . " >> " . $_row["Kategorie"];
				Yii::app()->controller->setPageTitle($_parent_name);
			} elseif($this->entry_level === 4) {
				$this->titel = $_row["Titel"];
				$_row["Kapitelname"] = $this->strip_krams($_row["Kapitelname"]);
				$_row["Kategorie"] = $this->strip_foo($_row["Kategorie"]);
				//$_row["Beschreibung1"] = $this->strip_foo($_row["Beschreibung1"]);
				$this->category = $_row["Kategorie"];
				$_row["Beschreibung1"] = $this->strip_krams($_row["Beschreibung"]);
				$_json .= "\n";
				$_json .= '{"name": "'.$_row["Titel"]. ' ' . $_row["Beschreibung1"]  . '",';
				$_json .= "\n";
				$_json .= '"id": "'.$_row["Titel"].'",';
				$_json .= "\n";
				$_entry_key .= "_" . str_replace(" ", "---", $_row["Kapitel"]) . "_" . $_row["Kategorie_ID"] . "_" . str_replace(" ", "---", $_row["Titel"]);
				$_entry_key_parent .= "/" . $_row["Einzelplan"] . "_" . str_replace(" ", "---", $_row["Kapitel"]) . "_" . $_row["Kategorie_ID"];
				$_parent_name .= ' >> ' . Yii::app()->params["einzelplan_namen"][$this->year][$_row["Einzelplan"]] . " >> " . $_row["Kapiteltext"] . " >> " . $_row["Kategorie"];
				Yii::app()->controller->setPageTitle($_parent_name);
			}
			//$_json .= '"$color": "#481b79",';
			//$_json .= '"entry_key": "'.$_row["Einzelplan"].'",';
			if($this->entry_level === 4) {
				$_area = $this->euro_dm($_row["Summe1"]);
				if($_area < 0) {
					$_area = $this->euro_dm($_row["Summe1"]) * -1;
				}
				if($_area == 0) {
					$_area = 1;
				}
				$_json .= '"data": {   '.'"$color": "'.Yii::app()->params["einzelplan_farben"][$_row["Einzelplan"]].'",'.'"value": "'.$this->euro_dm($_row["Summe1"]).'",   "$area": "'.$_area.'", "entry_key_parent": "'.$_entry_key_parent.'" }}';
			} else {
				$_area = $this->euro_dm($_row["Summe1"]);
				if($_area < 0) {
					$_area = $this->euro_dm($_row["Summe1"]) * -1;
				}
				if($_area == 0) {
					$_area = 1;
				}
				$_json .= '"data": {   '.'"$color": "'.Yii::app()->params["einzelplan_farben"][$_row["Einzelplan"]].'",'.'"value": "'.$this->euro_dm($_row["Summe1"]).'",   "$area": "'.$_area.'",   "entry_key": "'.$_entry_key.'", "entry_key_parent": "'.$_entry_key_parent.'" }}';
				$_json .= "\n";
			}
			if($_counter < $_count_rows) {
				$_json .= ',';
				$_json .= "\n";
			}
			++$_counter;
		}

		$_json .= '],';
		$_json .= "\n";
		$_json .= '   "data": { "entry_key_parent" : "'.$_entry_key_parent.'"},';
		$_json .= "\n";
		$_json .= '   "id": "root",';
		$_json .= "\n";
		$_parent_name_full = $_parent_name;
		Yii::app()->controller->setPageTitle($_parent_name);
		if(strlen($_parent_name) > 200) {
			$_parent_name = substr($_parent_name, 0, 197) . "...";
		}	
		$_json .= '   "name": "'.$_parent_name . '",';
		$_json .= "\n";
		$_json .= '   "name_full": "'.$_parent_name_full . '"';
		$_json .= "\n";
		$_json .= '};';
		$_json .= "\n";

		if(Yii::app()->params["caching"] === true) {
			Yii::app()->cache->set($query,$_json, 0);
		}
		return $_json;
	}

	public function set_total() {
		$_rows = Yii::app()->db->createCommand($this->query_details["total_query"])->query();

    	foreach($_rows as $_row) {
        	$this->Summe1 = $this->euro_dm($_row["Summe1"]);
        	$this->total = $this->Summe1;
        	$this->Summe2 = $this->euro_dm($_row["Summe2"]);
    	}
	}

	public function get_total($format = false) {
		if($format === true) {
			return number_format($this->total, 0, ",", ".");
		} else {
			return $this->total;
		}
	}

	public function render_ep($data, $row) {
		$_value = '<a href="'.Yii::app()->params["baseUrl"].'/' . $this->year . "/" . $this->typ . "/" . $data->Einzelplan . '" class="list_a" style="color: #000000;"><span style="color: '.Yii::app()->params["einzelplan_farben"][$data->Einzelplan].'; font-weight: bold;">' .  $data->Einzelplan ." " . '</span>' . Yii::app()->params["einzelplan_namen"][$this->year][$data->Einzelplan] . "</a>";

		return $_value;
	}

	public function render_kapitel($data, $row) {
		if($data->Summe1 > 0) {
			$_value = '<a href="'.Yii::app()->params["baseUrl"].'/' . $this->year . '/' . $this->typ . "/" . $data->Einzelplan . '_' . str_replace(" ", "---", $data->Kapitel) . '" class="list_a" style="color: #000000;">' .  $data->Kapiteltext . "</a>";
		} else {
			$_value = $data->Kapiteltext;
		}

		return $_value;
	}

	public function render_kategorie($data, $row) {
		$data->Kategorie = $this->strip_foo($data->Kategorie);
		if($data->Summe1 > 0) {
			$_value = '<a href="'.Yii::app()->params["baseUrl"].'/' . $this->year . '/' . $this->typ . '/' . $data->Einzelplan . '_' . str_replace(" ", "---", $data->Kapitel) . '_' . $data->Kategorie_ID . '" class="list_a" style="color: #000000;">' .  $data->Kategorie . "</a>";
		} else {
			$_value = $data->Kategorie;
		}

		return $_value;
	}

	public function strip_foo($str) {
		$_splits = array(
			"1.",
			"Die Ausgaben",
			"Siehe",
		);

		$_stop = false;
		foreach($_splits as $_split) {
			if($_stop === true) {
				continue;
			}
			$_pos = strpos($str, $_split);
			if($_pos !== false) {
				$str = substr_replace($str, "", $_pos, strlen($str));
				$_stop = true;
				//goto end;
			}
		}

		//end:

		$str = str_replace("NEWLINE", "", $str);
		return $str;

	}

	public function render_titel($data, $row) {
		$_value = $this->strip_krams($data->Titel) . " " . $this->strip_krams($data->Beschreibung);
		if($this->entry_level === 3) {
			$_value = '<a href="'.Yii::app()->params["baseUrl"].'/' . $this->year . '/' . $this->typ . '/' . $data->Einzelplan . '_' . str_replace(" ", "---", $data->Kapitel) . '_' . $data->Kategorie_ID . "_" . str_replace(" ", "---", $data->Titel) . '" class="list_a" style="color: #000000;">' .  $_value . "</a>";
		}

		return $_value;
	}

	public function get_percentage($data, $row) {
		$_span = "";
		$_value = $this->nonZeroDivision($this->euro_dm($data->Summe1), $this->get_total());
		$_value = $_value * 100;
		$_value = number_format($_value, 2, ",", ".") . "%";
		return $_value;
	}

	public function get_diff_vorjahr($data, $row) {
		$_value = $this->euro_dm($data->Summe1) - $this->euro_dm($data->Summe2);
		//$_value = $this->nonZeroDivision($_value, $data->Summe2);

		$_span = "";
		if($_value < 0) {
			$_span = '<span style="color: red;">';
		} else {
			$_span = '<span style="color: green;">';
		}
		//$_value = $_value * 100;
		$_value = number_format($_value, 0, ",", ".");
		return $_span . $_value . "</span>";
	}

    public function tableName() {
        return "t_" . Yii::app()->controller->params["year"];
    }

	public function nonZeroDivision($dividend, $divisor) {
		if($divisor == 0 || $divisor == null) {
			return 0;
		} else {
			return $dividend / $divisor;
		}
	}

	public function euro_dm($value = 0) {
		$_euro_dm = Yii::app()->params["factor_euro_dm"];

		$_dm_years = Yii::app()->params["dm_years"];
		if(isset($_dm_years[$this->year]) === true) {
			return $value * $_euro_dm;
		} else {
			return $value;
		}
	}

	public function strip_krams($str) {
		$str = str_replace(". ", "", $str);
		$str = str_replace('"', "", $str);
		$str = str_replace("'", "", $str);
		$str = str_replace("-", "", $str);
		$str = str_replace(".", "", $str);

		$str = $this->strip_foo($str);
		
		return $str;
	}

}
