<?php

class InfoVisTreemap extends CComponent {

	// FIXME: data
	// FIXME: render_json
	// FIXME: caching??
 
	public $data;

	public function render_json() {
		$_json = 'var json = eval({';
		$_json .= "\n";
  		$_json .= '"children": [';
		$_json .= "\n";

    	foreach($this->data["tiles"] as $_tile) {
			$_json .= "\n";
			$_json .= '{"name": "' . $_tile["name"] . '",';
			$_json .= "\n";
			$_json .= '"id": "' . $_tile["id"] . '",';
			$_json .= "\n";
			$_json .= '"data": {   ';
			$_json .= '"$color": "' . $_tile["color"] . '",';
			$_json .= '"value": "' . $_tile["value"] . '",';
			$_json .= '"$area": "' . $_tile["area"] . '",';
			$_json .= '"entry_key": "' . $_tile["entry_key"] . '",';
			$_json .= '"entry_key_parent": "' . $_tile["entry_key_parent"] . '"';
			$_json .= '}';
			$_json .= '}';
            $_json .= "\n";
		}


		$_json .= '],';
		$_json .= "\n";
		$_json .= '   "data": { "entry_key_parent" : "' . $this->data["root_url"] . '"},';
		$_json .= "\n";
		$_json .= '   "id": "root",';
		$_json .= "\n";
		$_json .= '   "name": "'.$this->data["root_name"] . '",';
		$_json .= "\n";
		$_json .= '   "name_full": "' . $this->data["root_name_full"] . '"';
		$_json .= "\n";
		$_json .= '});';
		$_json .= "\n";

		return $_json;
	}

}
