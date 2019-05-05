<?php
/**
 * PHP скрипт для парсинга сайта
 * Идентификатор сканируемого ресура = 3
 * 
 */

// Убираем максимальное время в секундах, в течение которого скрипт должен полностью загрузиться
ini_set('MAX_EXECUTION_TIME', '-1');

// Подключение библиотеки PHP Simple HTML DOM Parser
require_once("../lib/parser/simple_html_dom.php");

$nSourceId = 3;                         // Идентификатор сканируемого ресура
$dateCurrent = date('Y-m-d H:i:s');     // Текущая дата
$nLogId = 0;                            // Идентификатор текущего (создаваемого) лога, для записи результата парсинга
$nLogCount = 0;                         // Количество загруженных объектов (записей)
$sLogMsg = "";                          // Результат парсинга

// Подключение к базе данных MySql
$pdoConnection = new PDO('mysql:host=localhost;dbname=sa.pharmacy.net', 'administrator', '611094');

// Добавление строки в таблицу логов
$pdoQuery = "INSERT INTO `scan_log` (`id`, `source_id`, `date`) VALUES (NULL, '$nSourceId', '$dateCurrent');";
$pdoRes = $pdoConnection->query($pdoQuery);
if ($pdoRes == false) {
    // Критическая ошибка, операция прасинга прервана
    print("-1");
    exit();
}

// Получение идентификатора записи нового (созданного) лога
$pdoQuery = "SELECT id FROM `scan_log` ORDER BY id DESC LIMIT 1";
$pdoRes = $pdoConnection->query($pdoQuery);
$pdoResArray = $pdoRes->fetchAll();
$nLogId = $pdoResArray[0]["id"];
if ($nLogId == "") {
    // Критическая ошибка, операция прасинга прервана
    print("-1");
    exit();
}

// Сканирование(парсинг) ресурса
$nPageNum = 1;
$nLogCount = 0;
while (true) {
    // Динамическая генерация адреса ресурса, с учетом нумерации страниц
    $sUrlParsing = 'https://vseapteki.ru/catalog/39-lekarstva_i_bad/?page=' . $nPageNum;

    // Чтениеи и сканирование содержимого страницы
    @$htmlPage = file_get_html($sUrlParsing);
    if ($htmlPage == null) {
        break;
    } else {
        $nPageNum++;
    }
    $aHtmlArticle = $htmlPage->find('article');
    foreach ($aHtmlArticle as $article) {
        // Получение наименование найденного объекта
        @$sObjectName = $article->find('header', 0)->find('h3', 0)->plaintext;
        if (isset($sObjectName)) {
            $sObjectName = trim($sObjectName);
            $sObjectName = str_replace("'", "*", $sObjectName);
        } else {
            continue;
        }

        // Получение цены найденного объекта
        @$nObjectPrice = $article->find('footer', 0)->find('div', 0)->plaintext;
        if (isset($nObjectPrice)) {
            $nObjectPrice = preg_replace("/[^a-z\d]/", ' ', $nObjectPrice);
            $nObjectPrice = trim($nObjectPrice);
            $nObjectPrice = str_replace("'", "*", $nObjectPrice);
        } else {
            continue;
        }

        // Получение дополнительной информации найденного объекта
        $sObjectInfo = "";
        @$substr = $article->find('header', 0)->find('div', 0)->plaintext;
        if (isset($substr))
        {
            $sObjectInfo .= trim($substr) . "<br>";
        }
        @$substr = $article->find('header', 0)->find('div', 1)->plaintext;
        if (isset($substr))
        {
            $sObjectInfo .= trim($substr) . "<br>";
        }
        $sObjectInfo = trim($sObjectInfo);
        $sObjectInfo = str_replace("'", "*", $sObjectInfo);

        $pdoQuery = "INSERT INTO `scan_object` (`id`, `cource_id`, `name`, `price`, `info`, `log_id`) VALUES (NULL, '$nSourceId', '$sObjectName', '$nObjectPrice', '$sObjectInfo', '$nLogId')";
        $pdoRes = $pdoConnection->query($pdoQuery);
        if ($pdoRes == false) {
            print("-1");
            exit();
        } else {
            $nLogCount++;
        }
    }
}
print($nLogCount);
