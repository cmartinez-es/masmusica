<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<title>No Te Quedes Sin Música - ¡Descubre tus nuevas canciones favoritas!</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style type="text/css">
html, body, div, span, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, code,
del, dfn, em, img, q, dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td {margin: 0; padding: 0; border: 0; font-weight: inherit; font-style: inherit; font-size: 100%; font-family: inherit; vertical-align: baseline;}
table { border-collapse: separate; border-spacing: 0; }
caption, th, td { text-align: left; font-weight: normal; }
table, td, th { vertical-align: middle; }
blockquote:before, blockquote:after, q:before, q:after { content: ""; }
blockquote, q { quotes: "" ""; }
a img { border: none; }

body {background: #eee; color:#333; font-family: "Lucida Grande", "Lucida Sans", Helvetica, Arial; font-size: 14px;}
#page , #resultados{width:730px;margin:30px auto;background-color: #fff;padding: 20px; border: 1px solid #ccc;}
#header {text-align:center;}
p.info {display:block; padding:1em; background-color:#eee; color:#3F3F3F; border:1px #3F3F3F solid; margin:1em;}
form p input {position:absolute;left:50%;}
form{padding: 15px 0; margin:1em;}
label span {font-size:.8em; color:#aaa;}
#Similitudes {clear:both;}
#Similitudes dt {background-color:#ddd;color:#3F3F3F;}
#Similitudes dt, #Similitudes dd {padding:.3em;}
#Similitudes dt a {padding-left:20px;float:right;margin-top:0px;}
#Similitudes dd {margin-bottom: 10px; border: 1px solid #ddd;}
h1{
font-family:Arial Black, Helvetica, sans-serif;
font-size:40px;
letter-spacing:-4.5px;
color:#ec008c;
text-transform:uppercase;}
h2{
font-family: Helvetica, sans-serif;
font-size:20px;
letter-spacing:-1.5px;
color:#333;
font-weight: bold;}
#formulario form p{
margin-bottom: 15px;}
</style>
</head>
<body>
	<div id="page">
		<div id="header">
			<h1>No Te Quedes Sin Música</h1>
			<h2>¡Descubre tus nuevas canciones favoritas!</h2>
		</div>
		<div id="formulario">
			<p class="info">
				Busca canciones similares a tus canciones favoritas introduciendo el <strong>Artista</strong> y la <strong>Canción</strong>.<br />
				Busca artistas similares introduciendo únicamente el <strong>Artista</strong>.
			</p>
			<form action="" method="post">
				<p>
					<label for="artist">Artista <span>(ej: Modest Mouse)</span>:</label>
					<input name="artist" id="artist" value="" />
				</p>
				<p>
					<label for="track">Canción: <span>(ej: Dashboard)</span>:</label>
					<input name="track" id="track" value="" />
				</p>
				<p>
					<input type="submit" value="¡A culturizarse!" />
				</p>
			</form>
		</div>
</div>
<div id="resultados">
		<div id="Similitudes">
<?php
function cleanString($str) {
	return str_replace(" ", "%20", $str);
}

function getXML($url){
	if (function_exists("file_get_contents")){
		$xml = file_get_contents($url);
	} else {
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
		$xml = curl_exec($ch);
		curl_close($ch);
		
	}
	$doc = new DOMDocument();
	$doc->loadXML($xml);
	return $doc;
}

$songs = array();
if ($_POST) {
	$artist = $track = false;
	
	if (!$_POST["track"] || $_POST['track'] == '') {
		if($_POST["artist"] && !$_POST['artist'] == ''){
		$url = 'http://ws.audioscrobbler.com/1.0/artist/'.cleanString($_POST["artist"]).'/similar.xml';
		$artist = true;
		}
	} else {
		if($_POST["artist"] && !$_POST['artist'] == ''){
		$url = 'http://ws.audioscrobbler.com/1.0/track/'.cleanString($_POST['artist']).'/'.cleanString($_POST['track']).'/similar.xml';
		$track = true;
		}
	}
if ($url){
$xml = getXML($url);
}
if ($artist) $nodes=$xml->getElementsByTagName('artist') ; 
if ($track) $nodes=$xml->getElementsByTagName('track') ; 
	if ($nodes->length !=0) { ?>
		<dl class="<?=($track)?'track':'artist'?>">
		<?php 	
		foreach ($nodes as $node){ ?>
			<dt><? echo $node->getElementsByTagName('name')->item(0)->nodeValue;
				if ($track) echo '<a href="http://www.google.es/search?hl=es&q=-inurl:(htm|html|php)+intitle:.index+of.+%2B.last+modified.+%2B.parent+directory.+%2Bdescription+%2Bsize+%2B(wma|mp3)+.'.$node->getElementsByTagName('name')->item(1)->nodeValue.' '.$node->getElementsByTagName('name')->item(0)->nodeValue.'&btnG=Buscar+con+Google&meta=">Buscar mp3</a>';
				if ($artist) echo '<a href="http://www.google.es/search?hl=es&q=-inurl:(htm|html|php)+intitle:.index+of.+%2B.last+modified.+%2B.parent+directory.+%2Bdescription+%2Bsize+%2B(wma|mp3)+.'.$node->getElementsByTagName('name')->item(0)->nodeValue.'&btnG=Buscar+con+Google&meta=">Buscar mp3</a>';
				?></dt>	
			<dd><?=($track)?$node->getElementsByTagName('name')->item(1)->nodeValue:'<img src="'.$node->getElementsByTagName('image')->item(0)->nodeValue.'" alt="'.$node->getElementsByTagName('name')->item(0)->nodeValue.'" />'?></dd>
		<?php } ?>
		</dl>
<?php } else { ?>
	<h3>No se han encontrado similitudes. ¡Prueba con otra búsqueda!</h3>
<?php } ?>
		</div>
<?php } else{ ?>
<h3>¡A que esperas! Todavía no has introducido ninguna búsqueda.</h3>
<?php } ?>
	</div>
</body>
</html>
