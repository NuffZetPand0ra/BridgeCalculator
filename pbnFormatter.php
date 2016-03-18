<?php
namespace Nuffy\Bridgify;

require_once 'bridgeHelper.php';
class pbnFormatter{
	private $input = null;
	public $board = null;
	public $dealer = null;
	public $deal = null;
	public $vulnerable = null;
	public $event = null;
	
	function __construct($input){
		$this->input = trim($input);
		// $this->input = str_replace(chr(157),"E",$this->input);
		$this->input = str_replace("V","W",$this->input);
		$this->input = str_replace("Ø","E",$this->input);
		$this->getGameInfo($this->input);
	}
	function getGameInfo($input = false){
		if(!$input) $input = $this->input;
		$matches = array();
		$pattern = "/^([0-9]+).+\n([NSEW]+)\/([NSEWAleIng]+)/u";
		// $pattern = "/Ø/";
		// echo "<pre>";var_dump($this->input);echo "</pre>";die();
		preg_match($pattern, $this->input, $matches);
		if(count($matches)<1){
			throw new Exception("Couldn't read game info (boardnumber, dealer, vul-stuff)\n".$this->input);
		}
		$this->board = $matches[1];
		$dk_hands = array("/N/","/V/","/Ø/","/S/");
		$eng_hands = array("N","W","E","S");
		// $this->dealer = preg_replace($dk_hands,$eng_hands,$matches[2]);
		$this->dealer = $matches[2];
		$dk_vulnerable = array("/Alle/","/Ingen/","/NS/","/ØV/");
		$eng_vulnerable = array("All","None","NS","EW");
		// $this->vulnerable = preg_replace($dk_vulnerable,$eng_vulnerable,$matches[3]);
		$this->vulnerable = $matches[3];
		$this->deal = $this->extractHands();
	}
	function extractHands(){
		$colors = array("S","H","R","K");
		$eng_colors = array("S","H","D","C");
		$hands = array("N","W","E","S");
		$deal = array();
		foreach($colors as $color_key=>$color){
			$pattern = "/(?<![NE])".$color." ([EKDBT0-9]+)?/";
			$matches = array();
			preg_match_all($pattern, $this->input, $matches);
			// echo "<pre>";var_dump($matches);echo "</pre>";
			// echo "<hr>";
			foreach($matches[1] as $i=>$match){
				$patterns = array("/E/","/K/","/D/","/B/");
				$replacements = array("A","K","Q","J");
				$deal[$hands[$i]][$eng_colors[$color_key]] = preg_replace($patterns, $replacements, $match);
			}
		}
		return $deal;
		// echo "<pre>";var_dump($deal);echo "</pre>";
	}
	function dealToString(){
		$deal = $this->deal;
		$string = "N:";
		$order = array("N","E","S","W");
		foreach($order as $o){
			$string .= implode(".",$deal[$o])." ";
		}
		return trim($string);
	}
	function getPBN(){
		$string = '';
		if($this->event && strlen($this->event)>0) $string .= '[Event "'.$this->event.'"]'."\n";
		$string .= '[Board "'.$this->board.'"]'."\n";
		$string .= '[Dealer "'.$this->dealer.'"]'."\n";
		$string .= '[Vulnerable "'.$this->vulnerable.'"]'."\n";
		$string .= '[Deal "'.$this->dealToString().'"]'."\n";
		return $string;
	}
	function __toString(){
		return $this->getBoardHTML();
	}
	function getBoardHTML($id = ""){
		$string = "<div id=\"".$id."\">";
		$string .= "<ol class=\"board\">";
		foreach($this->deal as $symbol=>$hand){
			$string .= "<li>".$this->getHandHtml($symbol)."</li>";
		}
		$string .= "</ol></div>";
		return $string;
	}
	private function getHandHtml($h = "N"){
		$string = "<ol class=\"hand\">";
		$h = strtoupper($h);
		foreach($this->deal[$h] as $suit=>$cards){
			$string .= "<li class=\"".$suit."-suit\"><span class=\"suit-symbol\">".bridgeHelper::getColorSymbol($suit)."</span>".$cards."</li>";
		}
		$string .= "</ol>";
		return $string;
	}
}