<?php

const STOPWORDS = [
	'a', 'agora', 'ainda', 'alguém', 'algum', 'alguma', 'algumas', 'alguns', 'ampla', 'amplas', 'amplo', 'amplos', 'ante', 'antes', 'ao', 'aos', 'após', 'aquela', 'aquelas', 'aquele', 'aqueles', 'aquilo','as', 'até', 'através', 'cada', 'coisa', 'coisas', 'com', 'como', 'contra', 'contudo', 'da', 'daquele', 'daqueles', 'das', 'de', 'dela', 'delas', 'dele', 'deles', 'depois', 'dessa', 'dessas', 'desse', 'desses', 'desta', 'destas', 'deste', 'deste', 'destes', 'deve', 'devem', 'devendo', 'dever', 'deverá', 'deverão', 'deveria', 'deveriam', 'devia', 'deviam', 'disse', 'disso', 'disto', 'dito', 'diz', 'dizem', 'do', 'dos', 'e', 'é', 'ela', 'elas', 'ele', 'eles', 'em', 'enquanto', 'entre', 'era', 'essa', 'essas', 'esse', 'esses', 'esta', 'está', 'estamos', 'estão', 'estas', 'estava', 'estavam', 'estávamos', 'este', 'estes', 'estou', 'eu', 'fazendo', 'fazer', 'feita', 'feitas', 'feito', 'feitos', 'foi', 'for', 'foram', 'fosse', 'fossem', 'grande', 'grandes', 'há', 'isso', 'isto', 'já', 'la', 'lá', 'lhe', 'lhes', 'lo', 'mas', 'me', 'mesma', 'mesmas', 'mesmo', 'mesmos', 'meu', 'meus', 'minha', 'minhas', 'muita', 'muitas', 'muito', 'muitos', 'na', 'não', 'nas', 'nem', 'nenhum', 'nessa', 'nessas', 'nesta', 'nestas', 'ninguém', 'no', 'nos', 'nós', 'nossa', 'nossas', 'nosso', 'nossos', 'num', 'numa', 'nunca', 'o', 'os', 'ou', 'outra', 'outras', 'outro', 'outros', 'para', 'pela', 'pelas', 'pelo', 'pelos', 'pequena', 'pequenas', 'pequeno', 'pequenos', 'per', 'perante', 'pode', 'pude', 'podendo', 'poder', 'poderia', 'poderiam', 'podia', 'podiam', 'pois', 'por', 'porém', 'porque', 'posso', 'pouca', 'poucas', 'pouco', 'poucos', 'primeiro', 'primeiros', 'própria', 'próprias', 'próprio', 'próprios', 'quais', 'qual', 'quando', 'quanto', 'quantos', 'que', 'quem', 'são', 'se', 'seja', 'sejam', 'sem', 'sempre', 'sendo', 'será', 'serão', 'seu', 'seus', 'si', 'sido', 'só', 'sob', 'sobre', 'sua', 'suas', 'talvez', 'também', 'tampouco', 'te', 'tem', 'tendo', 'tenha', 'ter', 'teu', 'teus', 'ti', 'tido', 'tinha', 'tinham', 'toda', 'todas', 'todavia', 'todo', 'todos', 'tu', 'tua', 'tuas', 'tudo', 'última', 'últimas', 'último', 'últimos', 'um', 'uma', 'umas', 'uns', 'vendo', 'ver', 'vez', 'vindo', 'vir', 'vos', 'vós'
];

function leArquivo($arquivo){
    $fp = fopen($arquivo, "r");
    $conteudo = fread($fp, filesize($arquivo));
    fclose($fp);
    return $conteudo;
}

function converte(String$term, $tp) {
    if ($tp == "1") $palavra = strtr(strtoupper($term),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
    elseif ($tp == "0") $palavra = strtr(strtolower($term),"ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß","àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ");
    return $palavra;
}

function retiraStopWords($texto){ 
	$arraySemStopWord = [];
	foreach ($texto as $string){
		if(!in_array($string, STOPWORDS)){
			array_push($arraySemStopWord, $string);
		}
	}
	return $arraySemStopWord;
}

function limpaString($string){
	$string = converteString($string, 0);
	$string = str_replace(".", "", $string);
	$string = str_replace("-", " ", $string);
	$string = trim($string);
	return $string;
}

function concatenaVocabularioIsolado($array, $arrayConcatenado){
	foreach ($array as $conteudo){
		if(!in_array($conteudo, $arrayConcatenado)){
			array_push($arrayConcatenado, $conteudo);
		}		
	}
	return $arrayConcatenado;
}

function criaVocabularioIsolado($arrayA, $arrayB){
	$arrayConcatenado = [];
	$arrayConcatenado = concatenaVocabularioIsolado($arrayA, $arrayConcatenado);
	$arrayConcatenado = concatenaVocabularioIsolado($arrayB, $arrayConcatenado);
	return $arrayConcatenado;
}


function concatenaVocabularioSequencial($array, $arrayConcatenado){
	for ($i = 1; $i <sizeof($array); $i++) {
	   $string = $array[$i-1]." ".$array[$i];
	   if(!in_array($string, $arrayConcatenado)){
	   	array_push($arrayConcatenado, $string);
	   }
	}
	return $arrayConcatenado;
}

function criaVocabularioSequencial($arrayA, $arrayB){
	$arrayConcatenado = [];
	$arrayConcatenado = concatenaVocabularioSequencial($arrayA, $arrayConcatenado);
	$arrayConcatenado = concatenaVocabularioSequencial($arrayB, $arrayConcatenado);

	return $arrayConcatenado;
}

function criaVetorPalavras($palavras, $vocabulario){
	$produtosFiltrados = array_filter($palavras, function($palavra) {
	  return in_array($palavra, $vocabulario) === true;
	});

}

function imprimeVocabulario($vocabulario){
	foreach ($vocabulario as $key => $value) {
		echo $key.". ".$value."<br>";
	}
}

$arquivo1 = "texto1.txt";
$arquivo2 = "texto2.txt";
 
$texto1 = leArquivo($arquivo1);
$texto2 = leArquivo($arquivo2);

$palavrasArquivo1 = explode(" ", limpaString($texto1));
$palavrasArquivo2 = explode(" ", limpaString($texto2));

$palavrasArquivo1 = retiraStopWords($palavrasArquivo1);
$palavrasArquivo2 = retiraStopWords($palavrasArquivo2);

$vocabularioPalavrasIsoladas = criaVocabularioIsolado($palavrasArquivo1, $palavrasArquivo2);
$vocabularioPalavrasSequenciais = criaVocabularioSequencial($palavrasArquivo1, $palavrasArquivo2);

// criaVetorPalavras($palavrasArquivo1, $vocabularioPalavrasIsoladas);
echo '<p align=center>Vocabulário texto 1:</p>';
imprimeVocabulario($vocabularioPalavrasIsoladas);
echo '<p align=center>Vetor de palavras texto 1:</p>';
echo '<p align=center>Vetor de palavras texto 2:</p>';

echo  '<br><br><br>';

echo '<p align=center>Vocabulário texto 2:</p>';
imprimeVocabulario($vocabularioPalavrasSequenciais);
echo '<p align=center>Vetor de palavras texto 1:</p>';
echo '<p align=center>Vetor de palavras texto 2:</p>';

?>
