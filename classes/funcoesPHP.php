<?php
function verifica($valor) {

	if (get_magic_quotes_gpc()) $valor = stripslashes($valor);
	return str_replace("&quot;", '"', $valor);
}

function salt($qtd = 22) {

	$sequencia = str_shuffle("AaBbCcDdEeFfGgHhIiJjKkLlMmNnPpQqRrSsTtUuVvYyXxWwZz123456789");
	$palavra = substr($sequencia, 0, $qtd);

	return $palavra;
}

function seems_utf8($str) {

	$length = strlen($str);
	for ($i=0; $i < $length; $i++) {
		$c = ord($str[$i]);
		if ($c < 0x80) $n = 0; # 0bbbbbbb
		elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
		elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
		elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
		elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
		elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
		else return false; # Does not match any model
		for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
			if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
				return false;
		}
	}
	return true;
}

function alerta($tipo, $mensagem) {

	return '<p class="alert alert-'.$tipo.'">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				'.$mensagem.'
			</p>';

}

function retira_acentos($string) {

    if ( !preg_match('/[\x80-\xff]/', $string) )
        return $string;

    if (seems_utf8($string)) {
		$chars = array(
		// Decompositions for Latin-1 Supplement
		chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
		chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
		chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
		chr(195).chr(134) => 'AE',chr(195).chr(135) => 'C',
		chr(195).chr(136) => 'E', chr(195).chr(137) => 'E',
		chr(195).chr(138) => 'E', chr(195).chr(139) => 'E',
		chr(195).chr(140) => 'I', chr(195).chr(141) => 'I',
		chr(195).chr(142) => 'I', chr(195).chr(143) => 'I',
		chr(195).chr(144) => 'D', chr(195).chr(145) => 'N',
		chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
		chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
		chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
		chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
		chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
		chr(195).chr(158) => 'TH',chr(195).chr(159) => 's',
		chr(195).chr(160) => 'a', chr(195).chr(161) => 'a',
		chr(195).chr(162) => 'a', chr(195).chr(163) => 'a',
		chr(195).chr(164) => 'a', chr(195).chr(165) => 'a',
		chr(195).chr(166) => 'ae',chr(195).chr(167) => 'c',
		chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
		chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
		chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
		chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
		chr(195).chr(176) => 'd', chr(195).chr(177) => 'n',
		chr(195).chr(178) => 'o', chr(195).chr(179) => 'o',
		chr(195).chr(180) => 'o', chr(195).chr(181) => 'o',
		chr(195).chr(182) => 'o', chr(195).chr(182) => 'o',
		chr(195).chr(185) => 'u', chr(195).chr(186) => 'u',
		chr(195).chr(187) => 'u', chr(195).chr(188) => 'u',
		chr(195).chr(189) => 'y', chr(195).chr(190) => 'th',
		chr(195).chr(191) => 'y',
		// Decompositions for Latin Extended-A
		chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
		chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
		chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
		chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
		chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
		chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
		chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
		chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
		chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
		chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
		chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
		chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
		chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
		chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
		chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
		chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
		chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
		chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
		chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
		chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
		chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
		chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
		chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
		chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
		chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
		chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
		chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
		chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
		chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
		chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
		chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
		chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
		chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
		chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
		chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
		chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
		chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
		chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
		chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
		chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
		chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
		chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
		chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
		chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
		chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
		chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
		chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
		chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
		chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
		chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
		chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
		chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
		chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
		chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
		chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
		chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
		chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
		chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
		chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
		chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
		chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
		chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
		chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
		chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
		// Decompositions for Latin Extended-B
		chr(200).chr(152) => 'S', chr(200).chr(153) => 's',
		chr(200).chr(154) => 'T', chr(200).chr(155) => 't',
		// Euro Sign
		chr(226).chr(130).chr(172) => 'E',
		// GBP (Pound) Sign
		chr(194).chr(163) => '');

		$string = strtr($string, $chars);
	} else {
		// Assume ISO-8859-1 if not UTF-8
		$chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
			.chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
			.chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
			.chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
			.chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
			.chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
			.chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
			.chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
			.chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
			.chr(252).chr(253).chr(255);

		$chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

		$string = strtr($string, $chars['in'], $chars['out']);
		$double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
		$double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
		$string = str_replace($double_chars['in'], $double_chars['out'], $string);
	}
    return secure($string);
}


function nomear_pasta($string) {

	$string = retira_acentos($string);
	$string = strtolower($string);
	$string = str_replace(".", " ",$string);
	$string = preg_replace("/[^a-zA-Z0-9\s]/", "", $string);
	$string = str_replace(" ", "_",$string);
	return $string;
}


function nomear_url($string) {

	$string = retira_acentos($string);
	$string = strtolower($string);
	$string = str_replace(".", " ",$string);
	$string = preg_replace("/[^a-zA-Z0-9\s]/", "", $string);
	$string = str_replace(" ", "-",$string);
	return $string;
}


function secure($campo){

	$txt=array("ftp://",
		"include",
		"require",
		"<?",
		"<?php",
		"<?=",
		"SELECT",
		"DELETE",
		"UPDATE",
		"INSERT",
		"DROP",
		"TRUNCATE",
		"UNION",
		"º",
		"–",
		"ª",
		"á",
		"é",
		"í",
		"ó",
		"ú",
		"Á",
		"É",
		"Í",
		"Ó",
		"Ú",
		"ã",
		"õ",
		"Ã",
		"Õ",
		"à",
		"À",
		"ç",
		"Ç",
		"â",
		"ê",
		"ô",
		"Â",
		"Ê",
		"Ô",
		"'",
		"`",
		"‘",
		"’",
		'"',
		"“",
		"”",
		"—",
		"~");
	$txttroca=array("",
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		"&ordm;",
		"-",
		"&ordf;",
		"&aacute;",
		"&eacute;",
		"&iacute;",
		"&oacute;",
		"&uacute;",
		"&Aacute;",
		"&Eacute;",
		"&Iacute;",
		"&Oacute;",
		"&Uacute;",
		"&atilde;",
		"&otilde;",
		"&Atilde;",
		"&Otilde;",
		"&agrave;",
		"&Agrave;",
		"&ccedil;",
		"&Ccedil;",
		"&acirc;",
		"&ecirc;",
		"&ocirc;",
		"&Acirc;",
		"&Ecirc;",
		"&Ocirc;",
		"&prime;",
		"&prime;",
		"&prime;",
		"&prime;",
		"&quot;",
		"&quot;",
		"&quot;",
		"&mdash;",
		"");

	$str = str_replace($txt,$txttroca,$campo);

	if (!is_numeric($str)) {
		$str = get_magic_quotes_gpc() ? stripslashes($str) : $str;
	}

	return $str;
}





function trataAspas($campo) {

	$txt = str_replace('"', "&quot;", $campo);
	return $txt;
}

function formata_data($data){

	if(!empty($data)) {

		$dat = explode ("-", $data);
		$dt = $dat[2]."/".$dat[1]."/".$dat[0];

	} else {

		$dt = "";

	}

	return $dt;
}


function formata_data_banco($data){

	$data = trim($data);

	if($data != "") {

		$dat = explode ("/", $data);
		$dt = $dat[2]."-".$dat[1]."-".$dat[0];
	}

	return $dt;
}


function formata_data_busca($data){

	$dat = explode ("/", $data);
	$dt = $dat[2]."-".$dat[1]."-".$dat[0];
	return $dt;
}


function formata_data_hora($data_hora){

	if(!empty($data_hora)) {

		$quebra = explode(" ", $data_hora);

		$dat = explode ("-", $quebra[0]);
		$dt = $dat[2]."/".$dat[1]."/".$dat[0];
		$data = $dt." ".$quebra[1];

	} else {

		$data = "";

	}

	return $data;
}


function formata_valor($valor) {

	if(empty($valor)) $valor = "";
	else $valor = number_format($valor, 2, ',', '.');
	return verifica($valor);
}


function formata_valor_banco($valor) {

	$valor = trim($valor);

	if($valor == "") $valor = 0;
	else {
		$valor = str_replace(".","",$valor);
		$valor = str_replace(",",".",$valor);
	}
	return verifica($valor);
}


function grava_cep($cep) {

	$cep = str_replace("-","",$cep);
	return $cep;
}


function grava_cnpj($cnpj) {

	$cnpj = preg_replace('#[^0-9]#', '', $cnpj);
	return $cnpj;
}


function grava_cpf($cpf) {

	$cpf = preg_replace('#[^0-9]#', '', $cpf);
	return $cpf;
}


function grava_fone($fone) {

	$fone = preg_replace('#[^0-9]#', '', $fone);
	return $fone;
}


function formata_cep($cep) {

	$a = substr($cep, 0, 5);
	$b = substr($cep, 5, 3);
	$c = "$a-$b";
	return verifica($c);
}


function formata_cpf($cpf) {
	//123.456.789-10
	$a = substr($cpf, 0, 3);
	$b = substr($cpf, 3, 3);
	$c = substr($cpf, 6, 3);
	$d = substr($cpf, 9, 2);
	$e = "$a.$b.$c-$d";
	return verifica($e);
}


function formata_cnpj($cnpj) {
	//12.345.678/9012-34
	$a = substr($cnpj, 0, 2);
	$b = substr($cnpj, 2, 3);
	$c = substr($cnpj, 5, 3);
	$d = substr($cnpj, 8, 4);
	$e = substr($cnpj, 12, 2);
	$f = "$a.$b.$c/$d-$e";
	return verifica($f);
}


function formata_fone($fone) {
	//(44) 3456-7890
	$a = substr($fone, 0, 2);
	$b = substr($fone, 2, 4);
	$c = substr($fone, 6, 4);
	$e = "($a) $b-$c";
	return verifica($e);
}

function formata_data_limite_ativacao($data) {

	$a = substr($data, 0, 4);
	$b = substr($data, 4, 2);
	$c = substr($data, 6, 2);
	$d = "$c/$b/$a";
	return verifica($d);
}


function data_br(){

	$semana_br=array("Domingo","Segunda-feira","Ter&ccedil;a-feira","Quarta-feira","Quinta-feira","Sexta-feira","S&acute;bado");
	$semana=date("w", time());
	$mes_br=array("Janeiro","Fevereiro","Mar&ccedil;o","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro");
	$mes=date("n", time());
	$dia=date("d", time());
	$ano=date("Y", time());
	$retornar=$semana_br[$semana] . ", " . $dia . " de " . $mes_br[$mes-1]. " de " . $ano;

	return $retornar;
}


function valorPorExtenso($valor=0) {

	$singular = array("CENTAVO", "REAL", "MIL", "MILH&Atilde;O", "BILH&Atilde;O", "TRILH&Atilde;O", "QUATRILH&Atilde;O");
	$plural = array("CENTAVOS", "REAIS", "MIL", "MILH&Otilde;ES", "BILH&Otilde;ES",
	"TRILH&Otilde;ES", "QUATRILH&Otilde;ES");

	$c = array("", "CEM", "DUZENTOS", "TREZENTOS", "QUATROCENTOS",
	"QUINHENTOS", "SEISCENTOS", "SETECENTOS", "OITOCENTOS", "NOVECENTOS");
	$d = array("", "DEZ", "VINTE", "TRINTA", "QUARENTA", "CINQUENTA",
	"SESSENTA", "SETENTA", "OITENTA", "NOVENTA");
	$d10 = array("DEZ", "ONZE", "DOZE", "TREZE", "QUATORZE", "QUINZE",
	"DEZESSEIS", "DEZESSETE", "DEZOITO", "DEZENOVE");
	$u = array("", "UM", "DOIS", "TR&Ecirc;S", "QUATRO", "CINCO", "SEIS",
	"SETE", "OITO", "NOVE");

	$z=0;

	$valor = number_format($valor, 2, ".", ".");
	$inteiro = explode(".", $valor);
	for($i=0;$i<count($inteiro);$i++)
		for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
			$inteiro[$i] = "0".$inteiro[$i];

	// $fim identifica onde que deve se dar junÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¯ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¿ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â½ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¯ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¿ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â½o de centenas por "e" ou por "," ;)
	$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
	for ($i=0;$i<count($inteiro);$i++) {
		$valor = $inteiro[$i];
		$rc = (($valor > 100) && ($valor < 200)) ? "CENTO" : $c[$valor[0]];
		$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
		$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

		$r = $rc.(($rc && ($rd || $ru)) ? " E " : "").$rd.(($rd &&
$ru) ? " E " : "").$ru;
		$t = count($inteiro)-1-$i;
		$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
		if ($valor == "000")$z++; elseif ($z > 0) $z--;
		if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " DE " : "").$plural[$t];
		if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) &&
($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " E ") : " ") . $r;
	}

	return($rt ? $rt : "ZERO");
}


function formata_data_extenso($strDate) {

	// Array com os dia da semana em portugues;
	//$arrDaysOfWeek = array('Domingo', 'Segunda-feira', 'Terca-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sabado');
	// Array com os meses do ano em portugues;
	$arrMonthsOfYear = array(1 => 'Janeiro', 'Fevereiro', 'Mar&ccedil;o', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
	// Descobre o dia da semana
	$intDayOfWeek = date('w',strtotime($strDate));
	// Descobre o dia do mes
	$intDayOfMonth = date('d',strtotime($strDate));
	// Descobre o mes
	$intMonthOfYear = date('n',strtotime($strDate));
	// Descobre o ano
	$intYear = date('Y',strtotime($strDate));
	// Formato a ser retornado
	return $intDayOfMonth . ' de ' . $arrMonthsOfYear[$intMonthOfYear] . ' de ' . $intYear;
}


function roundsize($size){

    $i=0;
    $iec = array("B", "Kb", "Mb", "Gb", "Tb");
    while (($size/1024)>1) {

    	$size=$size/1024;
    	$i++;
    }
    return(round($size,1)." ".$iec[$i]);
}

//Funcao que lista todos os arquivos e subpastas de uma determinada pasta e informa o tamanho total
function diretorio($path) {

	if ($dir = opendir($path)) {
		while (false !== ($file = readdir($dir))) {
			if (is_dir($path."/".$file)) {
				if ($file != '.' && $file != '..') {
					diretorio($path."/".$file);
					$total_pastas++;
				}
			}
			else {

				$filesize = filesize ($path.'/'.$file);
				$tamanho_total = $tamanho_total + filesize ($path.'/'.$file);
				$tamanho_arquivo++;
			}
		}
		closedir($dir);
	}
	return $tamanho_total;
}


//Funcao que informa o tamanho total consumido em uma determinada pasta
function get_dir_size($dir_name){

	$dir_size =0;
	if (is_dir($dir_name)) {
		if ($dh = opendir($dir_name)) {
			while (($file = readdir($dh)) !== false) {
				if($file !="." && $file != ".."){
					if(is_file($dir_name."/".$file)){
						$dir_size += filesize($dir_name."/".$file);
					}
					/* check for any new directory inside this directory */
					if(is_dir($dir_name."/".$file)){
						$dir_size +=  get_dir_size($dir_name."/".$file);
					}
				}
			}
		}
	}
	closedir($dh);
	$size1 = $dir_size/1024;
	$size2 = $size1/1024;
	return round($size2);
}

function resumo_artigo($texto, $c) {

	$artigo = strip_tags($texto);

	$resumo = substr($artigo,'0',$c);
	$last = strrpos($resumo," ");
	$resumo = substr($resumo,0,$last);

	$artigo = (strlen($artigo) < $c ? $artigo : $resumo."...");
	return $artigo;
}

function idVideo($video) {

	$url = parse_url($video);
	if($url['host'] == "youtube.com" || $url['host'] == "www.youtube.com") {

		parse_str(parse_url($video, PHP_URL_QUERY), $array);
		return $array['v'];

	} else if($url['host'] == "youtu.be") {

		$array = explode("/", $video);
		return array_pop($array);

	}

}

function video($link) {

	//Por: Jonathan Rodrigues Carvalho - 03/03/2014
	$url = parse_url($link);
	if($url['host'] == "vimeo.com") {

		$id = preg_replace('#[^0-9]#', '', $url['path']);
		$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$id.php"));
		$img = $hash[0]['thumbnail_medium'];
		$title = $hash[0]['title'];
		$embed = "//player.vimeo.com/video/$id?byline=0&amp;portrait=0&amp;badge=0";

	} else {

		$id = idVideo($link);

		$hash = file_get_contents("http://youtube.com/get_video_info?video_id=$id");
		parse_str($hash, $arr);
		if(!empty($arr['iurl'])) $img = $arr['iurl'];
		else if(!empty($id)) $img = "http://i1.ytimg.com/vi/$id/default.jpg";
		else $img = "";
		$title = $arr['title'];
		$embed = "http://www.youtube.com/embed/$id";

	}

	return array("img" => $img, "title" => $title, "embed" => $embed);
}

$banco = array("001" => "Banco do Brasil", "341" => "Banco Ita&uacute;");

//FUNCAO QUE REGISTRA OS LOGS DE ACESSO DO USUARIO
function logAcesso($tipoAcesso, $tela, $id) {

	$ip = $_SERVER["REMOTE_ADDR"];

	try {

		$stConf = Conexao::chamar()->prepare("SELECT id
											    FROM controle_log_acesso
											   WHERE DATE(data_acesso) = CURDATE()
												 AND id_cliente = :id_cliente
												 AND id_usuario = :id_usuario
												 AND id_menu = :id_menu
												 AND id_registro = :id_registro
												 AND ip = :ip
												 AND tipo_acesso = :tipo_acesso");

		$stConf->execute(array("id_cliente" => $_COOKIE["id_cliente"], "id_usuario" => $_COOKIE["id_usuario"], "id_menu" => $tela, "id_registro" => $id, "ip" => $ip, "tipo_acesso" => "A"));
		$conf = $stConf->fetchAll(PDO::FETCH_ASSOC);

		if($tipoAcesso != "A" || count($conf) < 1) {

			$stGrava = Conexao::chamar()->prepare("INSERT INTO controle_log_acesso (id_cliente,
																				    id_usuario,
																				    id_menu,
																				    id_registro,
																				    data_acesso,
																				    tipo_acesso,
																				    ip)
														VALUES (:id_cliente,
																:id_usuario,
																:id_menu,
																:id_registro,
																NOW(),
																:tipo_acesso,
																:ip)");

			$grava = $stGrava->execute(array("id_cliente" => $_COOKIE["id_cliente"], "id_usuario" => $_COOKIE["id_usuario"], "id_menu" => $tela, "id_registro" => $id, "tipo_acesso" => "A", "ip" => $ip));

		}


	} catch (PDOException $e) {

		echo "<script>console.log('Erro: ".addslashes($e->getMessage())."')</script>";

	}

	return ($grava ? true : false);
}


function senhaSegura($senha) {

	$maiuscula = "ABCDEFGHIJKLMNOPQRSTUVYXWZ";
	$minuscula = "abcdefghijklmnopqrstuvyxwz";
	$numero = "0123456789";
	$caracterEspecial = "!@#%&*_+=";

	$verificaMaiuscula = false;
	$verificaMinuscula = false;
	$verificaNumero = false;
	$verificaEspecial = false;

	$arraySenha = str_split($senha);
	$arrayMaiuscula = str_split($maiuscula);
	$arrayMinuscula = str_split($minuscula);
	$arrayNumero = str_split($numero);
	$arrayCaracterEspecial = str_split($caracterEspecial);

	foreach ($arraySenha as $i) {

		$verificaMaiuscula = in_array($i, $arrayMaiuscula);
		$verificaMinuscula = in_array($i, $arrayMinuscula);
		$verificaNumero = in_array($i, $arrayNumero);
		$verificaEspecial = in_array($i, $arrayCaracterEspecial);

		if($verificaMaiuscula == true) $resultMaiuscula = true;
		if($verificaMinuscula == true) $resultMinuscula = true;
		if($verificaNumero == true) $resultNumero = true;
		if($verificaEspecial == true) $resultEspecial = true;

	}

	if ($resultMaiuscula && $resultMinuscula && $resultNumero && $resultEspecial) {

		return true;
	} else {
		return false;
	}

}


function dif_datas($dt_inicial, $dt_final){

	list($dia_i, $mes_i, $ano_i) = explode("/", $dt_inicial); //Data inicial
	list($dia_f, $mes_f, $ano_f) = explode("/", $dt_final); //Data final
	$mk_i = mktime(0, 0, 0, $mes_i, $dia_i, $ano_i); // obtem tempo unix no formato timestamp
	$mk_f = mktime(0, 0, 0, $mes_f, $dia_f, $ano_f); // obtem tempo unix no formato timestamp

	$diferenca = $mk_f - $mk_i; //Acha a diferenca entre as datas
	$diferenca = $diferenca / 86400;

	return $diferenca;
}


function validaCPF($cpf) {

	$cpf = str_replace(".","",$cpf);
	$cpf = str_replace("-","",$cpf);

	if(!is_numeric($cpf) || $cpf == "") {

		return false;
	} else {

		//VERIFICA
		if( ($cpf == '11111111111') || ($cpf == '22222222222') ||
				($cpf == '33333333333') || ($cpf == '44444444444') ||
				($cpf == '55555555555') || ($cpf == '66666666666') ||
				($cpf == '77777777777') || ($cpf == '88888888888') ||
				($cpf == '99999999999') || ($cpf == '00000000000') ) {
			$status = false;
		}

		else {
			//PEGA O DIGITO VERIFIACADOR
			$dv_informado = substr($cpf, 9,2);

			for($i=0; $i<=8; $i++) {
				$digito[$i] = substr($cpf, $i,1);
			}

			//CALCULA O VALOR DO 10Ã‚Âº DIGITO DE VERIFICAÃƒâ€¡Ãƒâ€šO
			$posicao = 10;
			$soma = 0;

			for($i=0; $i<=8; $i++) {
				$soma = $soma + $digito[$i] * $posicao;
				$posicao = $posicao - 1;
			}

			$digito[9] = $soma % 11;

			if($digito[9] < 2) {
				$digito[9] = 0;
			}
			else {
				$digito[9] = 11 - $digito[9];
			}

			//CALCULA O VALOR DO 11Ã‚Âº DIGITO DE VERIFICAÃƒâ€¡ÃƒÆ’O
			$posicao = 11;
			$soma = 0;

			for ($i=0; $i<=9; $i++) {
				$soma = $soma + $digito[$i] * $posicao;
				$posicao = $posicao - 1;
			}

			$digito[10] = $soma % 11;

			if ($digito[10] < 2) {
				$digito[10] = 0;
			}
			else {
				$digito[10] = 11 - $digito[10];
			}

			//VERIFICA SE O DV CALCULADO Ãƒâ€° IGUAL AO INFORMADO
			$dv = $digito[9] * 10 + $digito[10];
			if ($dv != $dv_informado) {
				return false;
			}
			else
				return true;
		}//FECHA ELSE
	}
}

function validaCNPJ($str) {

	if (!preg_match('|^(\d{2,3})\.?(\d{3})\.?(\d{3})\/?(\d{4})\-?(\d{2})$|', $str, $matches))
		return false;

	array_shift($matches);

	$str = implode('', $matches);
	if (strlen($str) > 14)
		$str = substr($str, 1);

	$sum1 = 0;
	$sum2 = 0;
	$sum3 = 0;
	$calc1 = 5;
	$calc2 = 6;

	for ($i=0; $i <= 12; $i++) {
		$calc1 = $calc1 < 2 ? 9 : $calc1;
		$calc2 = $calc2 < 2 ? 9 : $calc2;

		if ($i <= 11)
			$sum1 += $str[$i] * $calc1;

		$sum2 += $str[$i] * $calc2;
		$sum3 += $str[$i];
		$calc1--;
		$calc2--;
	}

	$sum1 %= 11;
	$sum2 %= 11;

	return ($sum3 && $str[12] == ($sum1 < 2 ? 0 : 11 - $sum1) && $str[13] == ($sum2 < 2 ? 0 : 11 - $sum2)) ? $str : false;
}

function assinatura_digital($arquivo, $idCliente, $root, $id_certificado) {

	$erro = "";

	$cliente = mysql_fetch_assoc(mysql_query("SELECT cliente.*,
													 municipio.nome municipio,
													 estado.nome estado
												FROM cliente
										   LEFT JOIN municipio ON cliente.id_municipio = municipio.id
										   LEFT JOIN estado ON municipio.id_estado = estado.id
											   WHERE cliente.id = '$idCliente'")) or die(mysql_error());

	$certificado = mysql_fetch_assoc(mysql_query("SELECT * FROM di_certificado WHERE id = '$id_certificado'"));

	$ext = array_pop(explode(".", $arquivo));

	if($ext == "pdf" || $ext == "PDF") {

		$conf  = "#MyPDFSigner personal configuration file\r\n";
		$conf .= "#" . date("D M d Y H:i:s") . "\r\n";
		$conf .= "name=\r\n";
		$conf .= "certsdir=\r\n";
		$conf .= "certfile=/var/www1/ingabyte.com.br/www/sistema/certs/certificado/$certificado[arquivo]\r\n";
		$conf .= "tsauser=\r\n";
		$conf .= "sigrect=[-250 -60 -20 -20]\r\n";
		$conf .= "sigreason=\r\n";
		$conf .= "sigimage=\r\n";
		$conf .= "tsaurl=\r\n";
		$conf .= "tsapasswd=\r\n";
		$conf .= "sigtext=Assinado Digitalmente por\\:\\r\\n%N\\r\\n%R\\r\\nLocal\\: %L\r\n";
		$conf .= "slotindex=\r\n";
		$conf .= "sigcontact=\r\n";
		$conf .= "sigloc=\r\n";
		$conf .= "library=\r\n";
		$conf .= "extrarange=13290\r\n";
		$conf .= "certpasswd=afb93575d721943b\r\n";
		$conf .= "certstore=PKCS12 KEYSTORE FILE\r\n";

		$arquivo_conf = fopen("$root/www/sistema/certs/configuracao/mypdfsigner_$idCliente.conf", "w+");
		fwrite($arquivo_conf,$conf);
		fclose($arquivo_conf);

		$inputPDF = "$root/www/sistema/arquivos/temp/$arquivo";
		$outputPDF = "$root/www/sistema/arquivos/temp/signed-$arquivo";
		$location = $cliente['municipio'] ." - ". $cliente['estado'] . " \r\nAssinado em ".date("d/m/Y H:i:s");
		$reason = "PUBLICACAO OFICIAL DO MUNICIPIO";
		$contactInfo = "";
		$certify = TRUE;
		$visible = TRUE;
		$title = "Assinado Por $certificado[titular]" ;
		$author = $cliente['razao_social'];
		$subject = "DOCUMENTO OFICIAL DO MUNICIPIO";
		$keywords = "KryptoKoder, PKCS#12, PDF";
		$confFile = "/var/www1/ingabyte.com.br/www/sistema/certs/configuracao/mypdfsigner_$idCliente.conf";

		mypdfsigner_sign($inputPDF, $outputPDF, $location, $reason, $contactInfo, $certify, $visible, $title, $author, $subject, $keywords, $confFile);

		if(filesize($outputPDF) == 0 || !file_exists($outputPDF)) {

			unlink($outputPDF);
			unlink($inputPDF);
			$erro .= "O arquivo selecionado nao pode ser assinado!";

		} else {

			$novo = "$root/www/sistema/arquivos/$idCliente/signed-$arquivo";
			$mudar = copy($outputPDF, $novo);

			if($mudar) {

				unlink($outputPDF);
				unlink($inputPDF);

			} else {

				$erro .= "Erro ao mover os arquivos assinados!";
			}
		}

	} else {

		rename("$root/www/sistema/arquivos/temp/$arquivo", "$root/www/sistema/arquivos/$idCliente/signed-$arquivo");
		unlink("$root/www/sistema/arquivos/temp/$arquivo");

	}

	if(empty($erro)) return "signed-$arquivo";
	else {
		return $erro;
	}
}



function statusPagseguro($status){
	$retorno = "";
	switch ($status) {
     case "0":
       $retorno = "Pendente";
        break;
     case "1":
       $retorno = "Aguardando pagamento";
        break;
     case "2":
       $retorno = "Em an&aacute;lise";
        break;
     case "3":
       $retorno = "Paga";
        break;
     case "4":
       $retorno = "Disponivel";
        break;
     case "5":
       $retorno = "Em disputa";
        break;
     case "6":
       $retorno = "Devolvida";
        break;
     case "7":
       $retorno = "Cancelada";
        break;
	}


  return $retorno;
}

function statusCred($status){
	$retorno = "";
	switch ($status) {
     case "A":
       $retorno = "Em An&aacute;lise";
        break;
     case "O":
       $retorno = "Aprovado";
        break;
     case "P":
       $retorno = "Pendente";
        break;
	}


  return $retorno;
}



function corCred($status){
	$retorno = "";
	switch ($status) {
     case "A":
       $retorno = "#98c1e0";
        break;
     case "O":
       $retorno = "#5fcc3c";
        break;
     case "P":
       $retorno = "#e6e851";
        break;
	}


  return $retorno;
}

function gerarHash($parametro){
	
		$constante = 'donahelena';
		
		$parametro = $parametro.date(DATE_ATOM,mktime());
		
		return hash('sha512', $contante . $parametro);
	   
	
	}