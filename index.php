<?php

define("PRIPONA", ".html");

/** Vytvoření přátelského URL
 * @param string řetězec v kódování UTF-8, ze kterého se má vytvořit URL
 * @return string řetězec obsahující pouze čísla, znaky bez diakritiky, podtržítko a pomlčku
 * @copyright Jakub Vrána, http://php.vrana.cz/
 */
function friendly_url($nadpis)
{
	$url = $nadpis;
	$url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
	$url = trim($url, "-");
	$url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
	$url = strtolower($url);
	$url = preg_replace('~[^-a-z0-9_]+~', '', $url);
	return $url;
}

function titulek()
{
	global $url;
	return titulekStranky($url);
}

function najitTitulek($obsah)
{
	preg_match_all('/<h1>\s*(.*?)\s*<\/h1>/i', $obsah, $vyskyty);
	return $vyskyty[1][0];
}

function titulekStranky($url)
{
	$stranka = stranka($url);
	return najitTitulek($stranka);
}

function stranka($url)
{
	if ($url == "") {
		$url = "index";
	}
	$stranka = "obsah/" . ($url) . PRIPONA;
	if (!file_exists($stranka)) {
		$stranka = "obsah/chyba" . PRIPONA;
	}
	
	return file_get_contents($stranka);
}

function stranky()
{
	$slugy = array();
	$stranky = glob("obsah/*" . PRIPONA);
	foreach ($stranky as $stranka) {
		$nazev = basename($stranka, PRIPONA);
		if ($nazev == "chyba") continue;
		if ($nazev == "index") $nazev = "";
		$slugy[] = $nazev;
	}
	return $slugy;
}

function obsah()
{
	global $url;
	return stranka($url);
}

function menu($stranky = "")
{
	if (!is_array($stranky)) {
		$stranky = stranky();
	}
	
	foreach ($stranky as $stranka) {
		echo odkaz($stranka);
	}
}

function odkaz($url, $text = "")
{
	if (empty($text)) {
		$text = titulekStranky($url);
	}
	return "<a class='interni' href='./{$url}'>" . $text . "</a> ";
}

function jsonStranky()
{
	global $url;
	$stranka = stranka($url);
	$vystup = array(
		"titulek" => najitTitulek($stranka),
		"obsah" => $stranka
	);
	
	return json_encode($vystup);
}


$url = isset($_GET["q"]) ? friendly_url($_GET["q"]) : "";

// Jde o AJAXový požadavek
if (isset($_GET["js"])) {
	echo jsonStranky();
} // Vložení šablony
else {
	include "layout.php";
}
