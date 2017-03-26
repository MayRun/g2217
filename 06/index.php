<?php
/*«адание 6

–еализовать функцию, осуществл¤ющую разбор url-адреса
ƒл¤ решени¤ задачи использовать “ќЋ№ ќ функции дл¤ работы со строками (нельз¤ использовать регул¤рные выражени¤)

–азместить код в одном файле 06/index.php

Ќеобходимые услови¤ сдачи:
- написать функцию my_url_parse(), получающую на вход url-адрес и возвращающую массив следующего вида:
(на примере: protocol://subdomain.domain3.domain2.zone:port/folder/subfolder/../././//../myfolder/script.php?var1=val1&var2=val2)
array(
'protocol'	=> 'protocol',
'domain'	=> 'subdomain.domain3.domain2.zone',
'zone'		=> 'zone',
'2_level_domain' => 'domain2.zone',
'port'		=> 'port',
'raw_folder'	=> 'folder/subfolder/../././//../myfolder/',
'folder'	=> 'myfolder/',
'script_path'	=> 'myfolder/script.php',
'script_name'	=> 'script.php',
'is_php'	=> true,
'parameters' => array(
'var1' => 'val1',
'var1' => 'val1',
),
'is_error'	=>false
)

- люба¤ часть выражени¤ может отсутствовать.
- если отсутствует протокол, то url определ¤етс¤ как относительный путь
(дл¤ subdomain.domain3.domain2.zone:port/folder/subfolder/../../../myfolder/script.php?var1=val1&var2=val2))
'domain'	=> false,
'raw_folder'	=> 'subdomain.domain3.domain2.zone:port/folder/subfolder/../../../myfolder/',
'folder'	=> 'myfolder/'

- если количество поддоменов > 5, устанавливать флаг ошибки (is_error)
- дл¤ пути к файлу на сервере вычислить его действительное (folder) и введенное (raw_folder) значени¤:
учесть следующие конструкции:
./ - остаемс¤ в той же папке
../ - поднимаемс¤ на уровень вверх (но нельз¤ выйти за доменное им¤!)
много // - эквивалентно /
вычисление пути оформить отдельной функцией
- если не указан сценарий, но есть строка параметров, то значит указать сценарий index.php:
(дл¤ myfolder/?var1=val1&var2=val2)
'script_name'	=> 'index.php',

- строка параметров может содержать вопросы:
?var1=is_it_ok?&or=not?
- если параметры в строке параметров повтор¤ютс¤, то правильное значение - в последнем!*/


function my_url_parse($url) //Функция для распарсивания url-адреса
{
	print($url . " : ");
	$my_url_parse_arr = array(
		'protocol' => protocol($url), //Функция для распарсивания протокола
		'domain' => domain($url), //Функция для распарсивания домена
		'zone' => zone_parse($url), //Функция для распарсивания зоны
		'2_level_domain' => domain2_parse($url), //Функция для распарсивания домена второго уровня
		'port' => port($url), //Функция для распарсивания порта
		'raw_folder' => raw_folder($url), //Функция для распарсивания пути
		'folder' => folder($url), //Функция для распарсивания валидного пути
		'script_path' => folder($url) . script_name($url), //Функция для распарсивания пути скрипта
		'script_name' => script_name($url), //Функция для распарсивания имени скрипта
		'is_php' => is_php($url), //Функция указания расширения скрипта(php или нет)
		'parameters' => variable($url), //Функция для распарсивания параметров
		'is_error' => is_error($url) //Функция проверяющая на ошибки
	);
	var_dump($my_url_parse_arr);
	
}


function protocol($url) // Распарсивание протокола
{
	$portocol_index = strpos($url, "://"); //Индекс окончания протокола если он есть
	$dot_position   = strpos($url, "."); //Позиция первой точки после протокола
	if ($portocol_index < $dot_position) {
		return substr($url, 0, $portocol_index); //Если протокол есть выделяем
	} else {
		return FALSE;
	}
}


function domain($url) //Функция для парсинга domain
{
	$dot_position         = strpos($url, "."); //Позиция первой точки (если есть)
	$slash_position       = strpos($url, "/"); //Позиция первого слеша
	$url_whitout_protocol = ""; //Url без протокола
	$portocol_index = strpos($url, "://"); //Индекс окончания протокола если он есть
	if (protocol($url) === FALSE) {
		$url_whitout_protocol = $url; //Если нет протокола, то весь url без протокола
	}
	
	elseif ($portocol_index < $dot_position) {
		$url_whitout_protocol = substr($url, $portocol_index + 3); // Если есть, то вырезаем его
	}
	$doubledot = strpos($url_whitout_protocol, ":"); //Позиция двоеточия, если есть
	$slash_position = strpos($url_whitout_protocol, "/"); // Позиция слеша(расматриваем возможные разделители)
	if($doubledot <= $slash_position) // Если есть порт, то до двоеточия
	{
		return substr($url_whitout_protocol, 0, $doubledot);
	}
	else{ //Если нет, то до слеша
	return substr($url_whitout_protocol, 0, strpos($url_whitout_protocol, "/"));
	}
}


function zone_parse($url) //Распарсивание зоны
{
	$index   = strripos(domain($url), '.') + 1; //Индекс последней точки в домене
	$zone    = substr(domain($url), $index);//ВЫделяем зону
	$zonelen = strlen($zone);// Длина зоны
	if ($zonelen != 0) { // Если длина нулевая то зоны нет
		return $zone;
	} else {
		return FALSE;
	}
}

function domain2_parse($url) //Распарсивание домена 2 уровня
{
	
	$domains_str    = substr(domain($url), 0, strripos(domain($url), '.')); //Выделяем домен
	$domain_2_index = strripos($domains_str, '.'); // Последняя точка в домене
	if (strlen($domains_str) == 0) { //Длина домена
		return FALSE;
	}
	if ($domain_2_index === FALSE) {
		return $domains_str . '.' . zone_parse($url); //Вырезаем из строки
	} else {
		return substr($domains_str, $domain_2_index + 1) . '.' . zone_parse($url);
	}
}

function port($url)//Распарсивание порта url-адреса
{
	if (strpos($url, "url") !== FALSE) { //Если в url'e есть параметр url, то отрезаем
		$url = substr($url,0 , strpos($url, "url")); 
	}
	$protocol            = protocol($url); //Получаем протокол
	$wo_ptotocol         = substr($url, strlen($protocol) + 3); // Отрезаем протокол
	$position_double_dot = strpos($wo_ptotocol, ":"); //Позиция : в url без протокола
	if ($position_double_dot === FALSE) {
		return FALSE;
		
	}
	$position_slash = strpos($wo_ptotocol, "/"); // Позиция слеша
	if (($position_slash > strpos($url, "?")) && ($position_double_dot > strpos($url, "?"))) {
		return FALSE;
	}
	$port = substr($wo_ptotocol, $position_double_dot + 1, $position_slash - $position_double_dot - 1);//Выделяем порт
	return $port;
	
}


function raw_abs_folder($url)
{
	$raw_folder = raw_folder($url);
	if (protocol($url) === FALSE) {
		$url = "http://" . $url;
		return domain($url) . $raw_folder;
	} else {
		return $raw_folder;
	}
	
}
function raw_folder($url) //Распарсивание пути
{
	$url_whitout_protocol = "";//url без протокола
	$folder_wo_script     = ""; //без скрипта
	$portocol_index       = strpos($url, "://"); //выделяем протокол
	$dot_position         = strpos($url, "?"); //выделяем ?
	
	if ($portocol_index < $dot_position) {
		$url_whitout_protocol = substr($url, $portocol_index + 3); // Вырезаем протокол
	}
	if (strpos($url_whitout_protocol, "/?") == strpos($url_whitout_protocol, "/")) {//Если /? то путь /
		return "/";
	}
	$begin_raw_folder = strpos($url_whitout_protocol, "/");// Путь начинается с /
	if ($begin_raw_folder === FALSE) {
		return "/";
	}
	$end_raw_folder = strpos($url_whitout_protocol, "?");//Путь заканчивается ?
	
	
	$raw_folder  = substr($url_whitout_protocol, $begin_raw_folder, $end_raw_folder - $begin_raw_folder);//Вырезаем путь
	$dot_index   = strrpos($raw_folder, ".");
	$slash_index = strrpos($raw_folder, "/");
	if ($dot_index > $slash_index) {
		$folder_wo_script = substr($raw_folder, 1, $slash_index);
	} else {
		$folder_wo_script = $raw_folder;
	}
	return $folder_wo_script;
	
}
function folder($url) // Распарсивание пути
{
	$folder_arr     = array();//Масств
	$temp       = "";//Временная переменная
	$raw_folder = raw_folder($url);//получаем путь
	$count_dot  = 0;//Количесто точек
	for ($i = 0; $i < strlen($raw_folder) - 1; ++$i) {
		if (($raw_folder[$i] == ".") && ($raw_folder[$i] == ".")) {
			$count_dot = $count_dot + 2;
		}
		if ($raw_folder[$i] == "/") {
			while ($raw_folder[$i] == "/") {//Если несколько слешей пропускаем повторные
				++$i;
			}
			if (($count_dot == 0) && (strlen($temp))) {//Если нет точек записываем название папки
				array_push($folder_arr, $temp);
				$temp = "";
			}
			if ($count_dot == 2) {//Если 2 точки то выходим из последней папки
				array_pop($folder_arr);
				$count_dot = 0;
				
			}
			
		}
		if (($raw_folder[$i] != ".") && ($raw_folder[$i] != "/")) {
			$temp .= $raw_folder[$i];
		}
		
	}
	return implode("/", $folder_arr) . "/";// Соединяем массив в строку
}

function script_name($url) // Название скрипта
{
	$folder = raw_folder($url);//Получаем путь
	$begin  = strpos($url, $folder); //Начало пути в url
	if ($begin === FALSE) { //Если путь длины 0
		return "index.php";
	}
	$index = strpos($url, "/?"); //Если нет пути
	if ($index !== FALSE) {
		return "index.php";
	}
	$end = strpos($url, "?");// Конец 
	if ($end <= $begin) {
		return "index.php";
	}
	$script = substr($url, $begin + strlen($folder), $end - $begin - strlen($folder)); //Вырезаем имя
	if (strlen($script) != 0) {
		return $script;
	} else {
		return "index.php";
	}
	
	
}
function is_php($url) //Проверка файла скипта на расширение
{
	$script       = script_name($url); //Вырезаем название скрипта
	$dot_position = strpos($script, ".");
	$extension    = substr($script, $dot_position + 1); //Выделяем расширение
	if ($extension == "php") {//Если php TRUE
		return TRUE;
	} else {
		return FALSE;
	}
}
function variable($url) //Функция для получения переменных
{
	if (strpos($url, "url") !== FALSE) {//Если есть url, то рекурсия
		my_url_parse(substr($url, strpos($url, "url") + 4));
		$url = substr($url, 0, strpos($url, "url") - 1);//Вырезаем до url
	}
	
	$variables = array(); //Массив var=val
	$var       = array(); //Массив [var]=val
	$pos_var   = strpos($url, "?");// Находим ?
	$vars_str  = substr($url, $pos_var + 1); //Вырезаем все после ?
	$vars      = explode("&", $vars_str); // Делим массив
	$count_var = count($vars);// Количество переменных
	for ($i = 0; $i < $count_var; ++$i) {
		array_push($variables, explode("=", $vars[$i]));
		$temp       = $variables[$i][0]; // Получаем название переменной
		$var[$temp] = $variables[$i][1]; // Получаем значение
		if ($temp == "url") {
			my_url_parse($var[$temp]); 
		}
	}
	
	return $var;
}


function is_error($url) //Функция проверяюшая если ошибки в url-адресe
{
	if (substr_count(domain($url), ".") >= 5) {
		return TRUE;
	} else {
		return FALSE;
	}
}

$url1 = 'http://http.ru/folder/subfolder/../././script.php?var1=val1&var2=val2';
$url2 = 'https://http.google.com/folder//././?var1=val1&var2=val2';
$url3 = 'ftp://mail.ru/?hello=world&url=https://http.google.com/folder//././?var1=val1&var2=val2';
$url4 = 'mail.ru/?hello=world&url=https://http.google.com/folder//././?var1=val1&var2=val2';
$url5 = '?mail=ru';
$url6 = 'http://dom.dom.domain2.com:8080/folder/subfolder/./myfolder/script.php?var1=val1&var2=val2?var1=val1&var2=val2';

my_url_parse($url1);
my_url_parse($url2);
my_url_parse($url3);
my_url_parse($url4);
my_url_parse($url5);
my_url_parse($url6);