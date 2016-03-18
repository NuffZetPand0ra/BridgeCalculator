<?php
namespace Nuffy\Bridgify;

require_once 'conf.php';
require_once 'pbnFormatter.php';
require_once 'bridgeHelper.php';
class bcalc{
	private $formatter = null;
	private $bcalc_path = BCALC_PATH;
	public $tricks = false;
	function __construct($formatter){
		$this->formatter = $formatter;
	}
	function calculateTricks($trump = "a", $flags = "-q"){
		$trump = strtolower($trump);
		$return = false;
		$options = "-e e -t ".$trump." -l ".$this->formatter->dealer." -d PBN -c \"".$this->formatter->dealToString()."\"";
		// echo "<pre>";var_dump($this->bcalc_path." "." ".$options." ".$flags);echo "</pre>";die();
		$return = shell_exec($this->bcalc_path." "." ".$options." ".$flags);
		if(!$return) return false;
		$matches = false;
		$pattern = "/[NSEW]\s+([0-9]+)\s+([0-9]+)\s+([0-9]+)\s+([0-9]+)\s+([0-9]+)/";
		preg_match_all($pattern,$return,$matches);
		array_shift($matches);
		$tricks = array();
		$suits = array("C","D","H","S","NT");
		$hands = array("N","S","E","W");
		foreach($suits as $s=>$suit){
			foreach($hands as $h=>$hand){
				$tricks[$hand][$suit] = $matches[$s][$h];
			}
		}
		$this->tricks = $tricks;
	}
	function __toString(){
		return $this->formatHTML();
	}
	function formatHTML($table_class = "bcalc-tricks"){
		if(!$this->tricks) $this->calculateTricks();
		$string = "
			<table class=\"".$table_class."\">
				<thead class=\"".$table_class."-header\">
					<tr>
						<td></td>
						<td class=\"c\">".bridgeHelper::getColorSymbol("c")."</td>
						<td class=\"d\">".bridgeHelper::getColorSymbol("d")."</td>
						<td class=\"h\">".bridgeHelper::getColorSymbol("h")."</td>
						<td class=\"s\">".bridgeHelper::getColorSymbol("s")."</td>
						<td class=\"nt\">".bridgeHelper::getColorSymbol("nt")."</td>
					</tr>
				</thead>
				<tbody>
		";
		foreach($this->tricks as $hand=>$row){
			$string .= "
				<tr class=\"handrow ".strtolower($hand)."_hand\">
					<td class=\"hand-letter\">".$hand."</td>
			";
			foreach($row as $s=>$suit){
				$string .= "<td class=\"".strtolower($s)." tricks\">".$suit."</td>";
			}
			$string .= "</tr>";
		}
		$string .= "</tbody></table>";
		return $string;
	}
}