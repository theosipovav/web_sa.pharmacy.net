<?php
/**
 * PHP скрипт для парсинга сайта
 * Идентификатор сканируемого ресура = 1
 * 
 */

// Убираем максимальное время в секундах, в течение которого скрипт должен полностью загрузиться
ini_set('MAX_EXECUTION_TIME', '-1');

// Подключение библиотеки PHP Simple HTML DOM Parser
require_once("../lib/parser/simple_html_dom.php");

$nSourceId = 1;                         // Идентификатор сканируемого ресура
$dateCurrent = date('Y-m-d H:i:s');     // Текущая дата
$nLogId = 0;                            // Идентификатор текущего (создаваемого) лога, для записи результата парсинга
$nLogCount = 0;                         // Количество загруженных объектов (записей)
$nLogMissedCount = 0;                   // Количество пропущенных объектов (записей)
$sLogMsg = "";                          // Результат парсинга

// Подключение к базе данных MySql
$aConfig = include "../../config.php";
$pdoConnection = new PDO('mysql:host='.$aConfig["host"].';dbname='.$aConfig["dbname"], $aConfig["user"], $aConfig["password"]);

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
$nPageNum = 0;
$nLogCount = 0;
while (true) {
    // Динамическая генерация адреса ресурса, с учетом нумерации страниц
    $sUrlParsing = 'https://366.ru/c/lekarstva/?page=' . $nPageNum . '&q=%3Apriority-desc';

    // Чтение и сканирование содержимого страницы
    @$htmlPage = file_get_html($sUrlParsing);
    if ($htmlPage == null) {
        break;
    }
    $htmlPage_tables = $htmlPage->find('div.c-prod-item-list div.c-prod-item');
    if ($htmlPage_tables == null) {
        break;
    } else {
        $nPageNum++;
    }
    foreach ($htmlPage_tables as $item) {
        // Получение наименование найденного объекта
        @$sObjectName = $item->find('div.c-prod-item__title', 0)->innertext;
        if (isset($sObjectName)) {
            $sObjectName = trim($sObjectName);
            $sObjectName = str_replace("'", "*", $sObjectName);
            $sObjectName = str_replace("\\", "|", $sObjectName);
        } else {
            continue;
        }

        // Получение цены найденного объекта
        @$nObjectPrice = $item->find('span.b-price span     ', 0)->getAllAttributes()["content"];
        if (isset($nObjectPrice)) {
            $nObjectPrice = preg_replace("/[^0123456789.,]/", '', $nObjectPrice);
            $nObjectPrice = trim($nObjectPrice);
            $nObjectPrice = trim($nObjectPrice, ".");
            $nObjectPrice = str_replace("'", "*", $nObjectPrice);
            $nObjectPrice = str_replace("\\", "|", $nObjectPrice);
        } else {
            continue;
        }

        // Получение дополнительной информации у найденного объекта
        $sObjectInfo = "";
        @$substr = $item->find('div.c-prod-item__manufacturer div', 0)->innertext;
        if (isset($substr)) {
            $substr = trim($substr);
            $sObjectInfo .= "Компания: " . $substr . "<br>";
            $substr = null;
        }
        @$substr = $item->find('div.c-prod-item__manufacturer div', 1)->innertext;
        if (isset($substr)) {
            $substr = trim($substr);
            $sObjectInfo .= "Страна: " . $substr . "<br>";
            $substr = null;
        }
        @$substr = $item->find('div.c-prod-item__manufacturer div', 2)->innertext;
        if (isset($substr)) {
            $substr = trim($substr);
            $sObjectInfo .= "Категория: " . $substr . "<br>";
            $substr = null;
        }
        $sObjectInfo = str_replace("'", "*", $sObjectInfo);
        $sObjectInfo = str_replace("\\", "|", $sObjectInfo);

        // Добавление строки в таблицу отсканируемых объектов
        $pdoQuery = "INSERT INTO `scan_object` (`id`, `source_id`, `name`, `price`, `info`, `log_id`, `url`) VALUES (NULL, '$nSourceId', '$sObjectName', '$nObjectPrice', '$sObjectInfo', '$nLogId', '$sUrlParsing')";
        $pdoRes = $pdoConnection->query($pdoQuery);
        if ($pdoRes == false) {
            $sLogMsg = "Ошибка при добавление строки в таблицу отсканированного объекта: $sObjectName ($sUrlParsing).\n";
            $nLogMissedCount++;
            continue;
        } else {
            $nLogCount++;
        }
    }
}

// Обновление данных(количество загруженных объектов и результат парсинга) в созданной ранее записи лога 
if ($sLogMsg == "") {
    $sLogMsg = "Ошибок нет";
}
$pdoQuery = "UPDATE `scan_log` SET `count` = '$nLogCount', `missed` = '$nLogMissedCount', `msg` = '$sLogMsg' WHERE `scan_log`.`id` = $nLogId";
$pdoRes = $pdoConnection->query($pdoQuery);
if ($pdoRes == false) {
    // Критическая ошибка, операция прасинга прервана
    print("-1");
    exit();
}

// Вывод результата(количество загруженных объектов)
print($nLogCount);