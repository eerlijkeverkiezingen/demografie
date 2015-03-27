<?php 
class afgeleid {
	private function _get_location($item=NULL){ return dirname(dirname(__FILE__)).'/data/_datalist_/'.($item === NULL ? NULL : strtolower($item).'.json');}
	private function _get_datalist(){
		$set = array();
		$list = dir($this->_get_location());
		foreach($list as $file){ if(preg_match("#^([^\.]+)\.json$#i", $file, $buffer)){ $set[] = strtolower($buffer[1]); } }
		return $set;
	}
	private function _get_topologie(){ return dirname(dirname(__FILE__)).'/data/topologie.json'; }
	
	function afgeleid(){}
	function genereer($item=NULL){
		//*fix*/ $item = preg_replace("#[^a-z-]#", "", $item);
		$set = array();
		switch(strtolower($item)){
			case "gemeente": case "bijzondere-gemeente": case "provincie": case "ministerie": case "staten-generaal": case "vertegenwoordiging": case "bestuursorgaan": case "overheid":   
				if(file_exists( $this->_get_location($item) )){ $set = json_decode(file_get_contents($this->_get_location($item)), TRUE); if(!is_array($set)){ $set = array(); } }
				$set = array_merge($set, $this->extract_topologie(strtolower($item)) );			
				break;
			case "waterschap":
				if(file_exists( $this->_get_location($item) )){ $set = json_decode(file_get_contents($this->_get_location($item)), TRUE); if(!is_array($set)){ $set = array(); } }
				$set = array_merge($set, $this->extract_topologie('waterschap'), $this->extract_topologie('hoogheemraadschap'), $this->extract_topologie('wetterskip') );
				break;
			case "partij": 
				if(file_exists( $this->_get_location($item) )){ $set = json_decode(file_get_contents($this->_get_location($item)), TRUE); if(!is_array($set)){ $set = array(); } }
				$set = array_merge($set, $this->extract_kiesraad_register() );
				break;
			case "plaatsnaam":
				if(file_exists( $this->_get_location($item) )){
					$raw = file_get_contents($this->_get_location($item));
					$set = json_decode($raw, TRUE);
					//$this->json_errors($raw);
					//print 'RAW: '; print_r($raw);
					//if(!is_array($set)){ $set = array(); }
				}
				break;
			default:
		}
		//print_r($set);
		$set = array_unique($set);
		asort($set);
		$set = array_values($set);
		//print_r($set);
		
		$out = $this->opslaan($item, $set);
		//print_r($out);
		return $set;
	}
	function extract_topologie($item=NULL){
		//*fix*/ $item = preg_replace("#[^a-z-]#", "", $item);
		$set = array();
		$raw = file_get_contents($this->_get_topologie());
		//$this->json_errors($raw);
		//print $item.': ';
		$raw = json_decode($raw, TRUE);
		//print_r($raw);
		foreach($raw['items'] as $i=>$topo){
			if(strtolower($topo['titel-van-lichaam']) == strtolower($item)){
				//print $topo['titel-van-lichaam'].'='.$topo['naam'].' ';
				$set[] = $topo['naam']; 
			}			
		}
		//print_r($set);
		return $set;
	}
	function extract_kiesraad_register($item=NULL){
		//*fix*/ $item = preg_replace("#[^a-z-]#", "", $item);
		$set = $raw = array();
		foreach(array('eerste-kamer','tweede-kamer','europa') as $titelvanlichaam){
			if($item === NULL || strtolower($item) == $titelvanlichaam){ $raw = array_merge($raw, json_decode(file_get_contents(dirname($this->_get_location()).'/politieke partij/kiesraad-register-'.$titelvanlichaam.'.json'), TRUE)); }
		}
		foreach($raw as $i=>$topo){
			$set[] = $topo['naam']; 
		}
		//print_r($set);
		return $set;
	}
	function opslaan($item, $waarde=array()){
		/*fix*/ $item = preg_replace("#[^a-z-]#", "", $item);
		$result = json_encode($waarde); //, JSON_PRETTY_PRINT
		$result = $this->prettyPrint($result);
		//$this->json_errors($result);
		$r = file_put_contents($this->_get_location($item), $result);
		//print_r($r);
		print $result;
	}
	
	function json_errors($json){
		// Define the errors.
		$constants = get_defined_constants(true);
		$json_errors = array();
		foreach ($constants["json"] as $name => $value) {
			if (!strncmp($name, "JSON_ERROR_", 11)) {
				$json_errors[$value] = $name;
			}
		}

		// Show the errors for different depths.
		foreach (range(4, 3, -1) as $depth) {
			var_dump(json_decode($json, true, $depth));
			echo 'Last error: ', $json_errors[json_last_error()], PHP_EOL, PHP_EOL;
		}
	}
	function prettyPrint( $json ){
		$result = '';
		$level = 0;
		$in_quotes = false;
		$in_escape = false;
		$ends_line_level = NULL;
		$json_length = strlen( $json );

		for( $i = 0; $i < $json_length; $i++ ) {
			$char = $json[$i];
			$new_line_level = NULL;
			$post = "";
			if( $ends_line_level !== NULL ) {
				$new_line_level = $ends_line_level;
				$ends_line_level = NULL;
			}
			if ( $in_escape ) {
				$in_escape = false;
			} else if( $char === '"' ) {
				$in_quotes = !$in_quotes;
			} else if( ! $in_quotes ) {
				switch( $char ) {
					case '}': case ']':
						$level--;
						$ends_line_level = NULL;
						$new_line_level = $level;
						break;

					case '{': case '[':
						$level++;
					case ',':
						$ends_line_level = $level;
						break;

					case ':':
						$post = " ";
						break;

					case " ": case "\t": case "\n": case "\r":
						$char = "";
						$ends_line_level = $new_line_level;
						$new_line_level = NULL;
						break;
				}
			} else if ( $char === '\\' ) {
				$in_escape = true;
			}
			if( $new_line_level !== NULL ) {
				$result .= "\n".str_repeat( "\t", $new_line_level );
			}
			$result .= $char.$post;
		}

		return $result;
	}
}

print '<pre>';
$afgeleid = new afgeleid();
print_r( $afgeleid );
$out = $afgeleid->genereer((isset($_GET['item']) ? $_GET['item'] : 'provincie'));
print_r( $out );
print '</pre>';
?>