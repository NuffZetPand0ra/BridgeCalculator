<?php
class bridgeHelper{
	static function getColorSymbol($suit){
		$suit = strtolower($suit);
		switch(true){
			case $suit == "c" || $suit == "k":
				$code = "&clubs;";
				break;
			case $suit == "d" || $suit == "r":
				$code = "&diams;";
				break;
			case $suit == "h":
				$code = "&hearts;";
				break;
			case $suit == "s":
				$code = "&spades;";
				break;
			case $suit == "n" || $suit == "nt" || $suit == "ut":
				$code = "NT";
				break;
		}
		return $code;
	}
}