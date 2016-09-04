<?php 
// Добавлять в отчет все PHP ошибки (см. список изменений)
error_reporting(E_ALL);
include 'simple_html_dom.php';

function __autoload($name) {
    include 'Classes/'.$name . '.php';
}

?>