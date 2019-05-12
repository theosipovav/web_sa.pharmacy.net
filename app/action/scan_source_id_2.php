<?php
/**
 * PHP скрипт для парсинга сайта
 * Идентификатор сканируемого ресура = 2
 * 
 */

// Убираем максимальное время в секундах, в течение которого скрипт должен полностью загрузиться
ini_set('MAX_EXECUTION_TIME', '-1');

// Подключение библиотеки PHP Simple HTML DOM Parser
require_once("../lib/parser/simple_html_dom.php");

$nSourceId = 2;                         // Идентификатор сканируемого ресура
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
$nPageNum = 1;
$nLogCount = 0;
while (true) {
    // Динамическая генерация адреса ресурса, с учетом нумерации страниц
    $sUrlParsing = 'https://apteka.ru/catalog/section/lekarstvennye-preparaty/?PAGEN_1=' . $nPageNum;

    // Чтениеи и сканирование содержимого страницы
    @$site_parsing = file_get_html($sUrlParsing);
    if ($site_parsing == null) {
        break;
    } else {
        $nPageNum++;
    }
    $htmlCatalogList = $site_parsing->find('div.catalog-list', 0);
    $aHtmlArticle = $htmlCatalogList->find('article');
    foreach ($aHtmlArticle as $article) {
        // Получение наименование найденного объекта
        @$sObjectName = $article->find('span.h2-style', 0)->children(0)->plaintext;
        if (isset($sObjectName)) {
            $sObjectName = trim($sObjectName);
            $sObjectName = str_replace("'", "*", $sObjectName);
            $sObjectName = str_replace("\\", "|", $sObjectName);
        } else {
            $nLogMissedCount++;
            continue;
        }

        // Получение цены найденного объекта
        @$nObjectPriceData = $article->find('div.price', 0);
        if (isset($nObjectPriceData)) {
            @$nObjectPrice = $nObjectPriceData->find('span', 0)->plaintext;
            if (isset($nObjectPrice)) {
                $nObjectPrice = preg_replace("/[^0123456789.,]/", '', $nObjectPrice);
                $nObjectPrice = trim($nObjectPrice);
                $nObjectPrice = trim($nObjectPrice, ".");
                $nObjectPrice = str_replace("'", "*", $nObjectPrice);
                $nObjectPrice = str_replace("\\", "|", $nObjectPrice);
            } else {
                $nLogMissedCount++;
                continue;
            }
        } else {
            $nLogMissedCount++;
            continue;
        }

        // Получение дополнительной информации найденного объекта
        $sObjectInfo = "";
        @$substr = $article->find('div.item_additional-info', 0)->children(0)->plaintext;
        if (isset($substr)) {
            $sObjectInfo .= trim($substr) . "<br>";
        }
        @$substr = $article->find('div.item_additional-info', 0)->children(1)->plaintext;
        if (isset($substr)) {
            $sObjectInfo .= trim($substr) . "<br>";
        }
        @$substr = $article->find('div.preparation', 0)->plaintext;
        if (isset($substr)) {
            $sObjectInfo .= trim($substr) . "<br>";
        }
        $sObjectInfo = trim($sObjectInfo);
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
