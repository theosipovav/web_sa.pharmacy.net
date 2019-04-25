<?php
	require_once '../lib/parser/simple_html_dom.php';
	date_default_timezone_set('UTC');
	$_DateTime = new DateTime(date("Y-m-d"));
	$date_0 = $_DateTime->format('Y-m-d');
	$date_1 = $_DateTime-> modify("+1 day") -> format('Y-m-d');
	$date_2 = $_DateTime-> modify("+1 day") -> format('Y-m-d');
	$date_3 = $_DateTime-> modify("+1 day") -> format('Y-m-d');
	@$_MySQLConnect = new mysqli("localhost", "root", "", "goodtimepnz");
	if ($_MySQLConnect->connect_errno){
		$_ConnectError = $_MySQLConnect->connect_error;
		mysqli_close($_MySQLConnect);
		exit();
	}
	else
	{
		/* Парсим расписание "Высшая лига" */
		@$site_parsing = file_get_html('http://vlkino.ru/schedule/full/#page-content');
		if ($site_parsing == false){
			echo "Ошибка при считывание данных для расписание кинотеатра <Высшая лига>" . "<br>";
		}
		else {
			$_MySQLConnect->query("TRUNCATE TABLE `goodtimepnz`.`schedule_vl`");
			if ($_MySQLConnect == false){
				echo "Ошибка при подключение к таблице [goodtimepnz].[schedule_vl]" . "<br>";
			}
			else {
				$site_parsing_tables = $site_parsing -> find('div[id=schedule-grid] div.row');
				for ($n = 0; $n < count($site_parsing_tables); $n++) {
					$_film_time = "";
					$_film_time_day = "";
					$_film_time_day_0 = "";
					$_film_time_day_1 = "";
					$_film_time_day_2 = "";
					$_film_time_day_3 = "";
					$_list_films = str_get_html("<html><body>$site_parsing_tables[$n]</body></html>");
					$_film_name = $_list_films -> find('div.movie-name', 0)->plaintext;
					$_list_film_days = $_list_films -> find('div.column');
					for ($i = 0; $i < count($_list_film_days); $i++) {
						$_film_date = new DateTime(date("d-m-Y"));
						$_film_date->modify("+$i day");
						$date = $_film_date->format('d-m-Y');
						$_list_film_times = str_get_html("<html><body>$_list_film_days[$i]</body></html>");
						$_film_times = $_list_film_times->find('a.session-time');
						for ($j = 0; $j < count($_film_times); $j++) {
							$_film_time = $_film_times[$j] -> plaintext;
							$_film_time_day = $_film_time_day . $_film_time . "<br>";
						}
						if ($i == 0) $_film_time_day_0 = $_film_time_day;
						elseif ($i == 1) $_film_time_day_1= $_film_time_day;
						elseif ($i == 2) $_film_time_day_2= $_film_time_day;
						elseif ($i == 3) $_film_time_day_3= $_film_time_day;
						$_film_time = "";
						$_film_time_day = "";
					}
					$_MySQLConnect->query("
							INSERT INTO `goodtimepnz`.`schedule_vl` (`id`, `film_name`, `film_time_day_0`, `film_time_day_1`, `film_time_day_2`, `film_time_day_3`)
							VALUES ('', '$_film_name', '$_film_time_day_0', '$_film_time_day_1', '$_film_time_day_2', '$_film_time_day_3')");
				}
				$_DateTime_Now = new DateTime(date("Y-m-d H:i:s"));
				$_DateTime_NowWrite= $_DateTime_Now->format('Y-m-d H:i:s');
				echo "<div>($_DateTime_NowWrite) Парсер расписания кинотеатра: Высшая лига - выполнен.</div>";
			}
		}
		/* Парсим расписание "Современник" */
		$site_parsing = file_get_html('https://afisha.yandex.ru/penza/cinema/places/sovremennik-551798371f7d154a12ddf1dc?place-schedule-preset=today');
		if ($site_parsing == false){
			echo "Ошибка при считывание данных для расписание кинотеатра <Современник>" . "<br>";
		}
		else{
			$_MySQLConnect->query("TRUNCATE TABLE `goodtimepnz`.`schedule_sovremennik`");
			if ($_MySQLConnect == false){
				echo "Ошибка при подключение к таблице [goodtimepnz].[schedule_sovremennik]" . "<br>";
			}
			else{
				/* Парсер для сегодняшней даты + 0 дней */
				$site_parsing = file_get_html('https://afisha.yandex.ru/penza/cinema/places/sovremennik-551798371f7d154a12ddf1dc?place-schedule-date=' . "$date_0");
				if ($site_parsing == false){
					echo "Ошибка при считывание данных для расписание кинотеатра <Современник>" . "<br>";
				}
				else{
					$site_parsing_seances = $site_parsing -> find('div.schedule-grid__row');
					for($n = 0; $n < count($site_parsing_seances); $n++){
						$_film_name = $site_parsing_seances[$n] -> find('h3.schedule-event__title', 0) -> plaintext;
						$_film_genres = null;
						$_film_genres = $site_parsing_seances[$n] -> find('li.tags__item');
						$_film_genre = "";
						for($i = 0; $i < count($_film_genres); $i++) {
							$_film_genre = $_film_genre . ($_film_genres[$i] -> plaintext) . "<br>";
						}
						$_film_times = null;
						$_film_times = $site_parsing_seances[$n] -> find('div.schedule-sessions__item');
						$_film_time = "";
						for($i = 0; $i < count($_film_times); $i++) {
							$_film_time = $_film_time . ($_film_times[$i] -> plaintext) . "<br>";
						}
						$_MySQLConnect->query("
								INSERT INTO `goodtimepnz`.`schedule_sovremennik` (`id`, `film_time_day`, `film_name`, `film_genre`, `film_time`)
								VALUES ('', '$date_0', '$_film_name', '$_film_genre', '$_film_time')");
					}
				}
				/* Парсер для сегодняшней даты + 1 дней */
				$site_parsing = file_get_html('https://afisha.yandex.ru/penza/cinema/places/sovremennik-551798371f7d154a12ddf1dc?place-schedule-date=' . "$date_1");
				if ($site_parsing == false){
					echo "Ошибка при считывание данных для расписание кинотеатра <Современник>" . "<br>";
				}
				else{
					$site_parsing_seances = $site_parsing -> find('div.schedule-grid__row');
					for($n = 0; $n < count($site_parsing_seances); $n++){
						$_film_name = $site_parsing_seances[$n] -> find('h3.schedule-event__title', 0) -> plaintext;
						$_film_genres = null;
						$_film_genres = $site_parsing_seances[$n] -> find('li.tags__item');
						$_film_genre = "";
						for($i = 0; $i < count($_film_genres); $i++) {
							$_film_genre = $_film_genre . ($_film_genres[$i] -> plaintext) . "<br>";
						}
						$_film_times = null;
						$_film_times = $site_parsing_seances[$n] -> find('div.schedule-sessions__item');
						$_film_time = "";
						for($i = 0; $i < count($_film_times); $i++) {
							$_film_time = $_film_time . ($_film_times[$i] -> plaintext) . "<br>";
						}
						$_MySQLConnect->query("
								INSERT INTO `goodtimepnz`.`schedule_sovremennik` (`id`, `film_time_day`, `film_name`, `film_genre`, `film_time`)
								VALUES ('', '$date_1', '$_film_name', '$_film_genre', '$_film_time')");
					}
				}
				/* Парсер для сегодняшней даты + 2 дней */
				$site_parsing = file_get_html('https://afisha.yandex.ru/penza/cinema/places/sovremennik-551798371f7d154a12ddf1dc?place-schedule-date=' . "$date_2");
				if ($site_parsing == false){
					echo "Ошибка при считывание данных для расписание кинотеатра <Современник>" . "<br>";
				}
				else{
					$site_parsing_seances = $site_parsing -> find('div.schedule-grid__row');
					for($n = 0; $n < count($site_parsing_seances); $n++){
						$_film_name = $site_parsing_seances[$n] -> find('h3.schedule-event__title', 0) -> plaintext;
						$_film_genres = null;
						$_film_genres = $site_parsing_seances[$n] -> find('li.tags__item');
						$_film_genre = "";
						for($i = 0; $i < count($_film_genres); $i++) {
							$_film_genre = $_film_genre . ($_film_genres[$i] -> plaintext) . "<br>";
						}
						$_film_times = null;
						$_film_times = $site_parsing_seances[$n] -> find('div.schedule-sessions__item');
						$_film_time = "";
						for($i = 0; $i < count($_film_times); $i++) {
							$_film_time = $_film_time . ($_film_times[$i] -> plaintext) . "<br>";
						}
						$_MySQLConnect->query("
								INSERT INTO `goodtimepnz`.`schedule_sovremennik` (`id`, `film_time_day`, `film_name`, `film_genre`, `film_time`)
								VALUES ('', '$date_2', '$_film_name', '$_film_genre', '$_film_time')");
					}
				}
				/* Парсер для сегодняшней даты + 3 дней */
				$site_parsing = file_get_html('https://afisha.yandex.ru/penza/cinema/places/sovremennik-551798371f7d154a12ddf1dc?place-schedule-date=' . "$date_3");
				if ($site_parsing == false){
					echo "Ошибка при считывание данных для расписание кинотеатра <Современник>" . "<br>";
				}
				else{
					$site_parsing_seances = $site_parsing -> find('div.schedule-grid__row');
					for($n = 0; $n < count($site_parsing_seances); $n++){
						$_film_name = $site_parsing_seances[$n] -> find('h3.schedule-event__title', 0) -> plaintext;
						$_film_genres = null;
						$_film_genres = $site_parsing_seances[$n] -> find('li.tags__item');
						$_film_genre = "";
						for($i = 0; $i < count($_film_genres); $i++) {
							$_film_genre = $_film_genre . ($_film_genres[$i] -> plaintext) . "<br>";
						}
						$_film_times = null;
						$_film_times = $site_parsing_seances[$n] -> find('div.schedule-sessions__item');
						$_film_time = "";
						for($i = 0; $i < count($_film_times); $i++) {
							$_film_time = $_film_time . ($_film_times[$i] -> plaintext) . "<br>";
						}
						$_MySQLConnect->query("
								INSERT INTO `goodtimepnz`.`schedule_sovremennik` (`id`, `film_time_day`, `film_name`, `film_genre`, `film_time`)
								VALUES ('', '$date_3', '$_film_name', '$_film_genre', '$_film_time')");
					}
				}
				$_DateTime_Now = new DateTime(date("Y-m-d H:i:s"));
				$_DateTime_NowWrite= $_DateTime_Now->format('Y-m-d H:i:s');
				echo "<div>($_DateTime_NowWrite) Парсер расписания кинотеатра: Современник - выполнен.</div>";
			}
		}
		/* Парсим расписание "5 Звезд" */
		$site_parsing = file_get_html('https://afisha.yandex.ru/penza/cinema/places/piat-zviozd-penza?place-schedule-preset=today');
		if ($site_parsing == false){
			echo "Ошибка при считывание данных для расписание кинотеатра <5 Звезд>" . "<br>";
		}
		else{
			$_MySQLConnect->query("TRUNCATE TABLE `goodtimepnz`.`schedule_5zvezd`");
			if ($_MySQLConnect == false){
				echo "Ошибка при подключение к таблице [goodtimepnz].[schedule_5zvezd]" . "<br>";
			}
			else{
				/* Парсер для сегодняшней даты + 0 дней */
				$site_parsing = file_get_html('https://afisha.yandex.ru/penza/cinema/places/piat-zviozd-penza?place-schedule-date=' . "$date_0");
				if ($site_parsing == false){
					echo "Ошибка при считывание данных для расписание кинотеатра <5 Звезд>" . "<br>";
				}
				else{
					$site_parsing_seances = $site_parsing -> find('div.schedule-grid__row');
					for($n = 0; $n < count($site_parsing_seances); $n++){
						$_film_name = $site_parsing_seances[$n] -> find('h3.schedule-event__title', 0) -> plaintext;
						$_film_genres = null;
						$_film_genres = $site_parsing_seances[$n] -> find('li.tags__item');
						$_film_genre = "";
						for($i = 0; $i < count($_film_genres); $i++) {
							$_film_genre = $_film_genre . ($_film_genres[$i] -> plaintext) . "<br>";
						}
						$_film_times = null;
						$_film_times = $site_parsing_seances[$n] -> find('div.schedule-sessions__item');
						$_film_time = "";
						for($i = 0; $i < count($_film_times); $i++) {
							$_film_time = $_film_time . ($_film_times[$i] -> plaintext) . "<br>";
						}
						$_MySQLConnect->query("
								INSERT INTO `goodtimepnz`.`schedule_5zvezd` (`id`, `film_time_day`, `film_name`, `film_genre`, `film_time`)
								VALUES ('', '$date_0', '$_film_name', '$_film_genre', '$_film_time')");
					}
				}
				/* Парсер для сегодняшней даты + 1 дней */
				$site_parsing = file_get_html('https://afisha.yandex.ru/penza/cinema/places/piat-zviozd-penza?place-schedule-date=' . "$date_1");
				if ($site_parsing == false){
					echo "Ошибка при считывание данных для расписание кинотеатра <5 Звезд>" . "<br>";
				}
				else{
					$site_parsing_seances = $site_parsing -> find('div.schedule-grid__row');
					for($n = 0; $n < count($site_parsing_seances); $n++){
						$_film_name = $site_parsing_seances[$n] -> find('h3.schedule-event__title', 0) -> plaintext;
						$_film_genres = null;
						$_film_genres = $site_parsing_seances[$n] -> find('li.tags__item');
						$_film_genre = "";
						for($i = 0; $i < count($_film_genres); $i++) {
							$_film_genre = $_film_genre . ($_film_genres[$i] -> plaintext) . "<br>";
						}
						$_film_times = null;
						$_film_times = $site_parsing_seances[$n] -> find('div.schedule-sessions__item');
						$_film_time = "";
						for($i = 0; $i < count($_film_times); $i++) {
							$_film_time = $_film_time . ($_film_times[$i] -> plaintext) . "<br>";
						}
						$_MySQLConnect->query("
								INSERT INTO `goodtimepnz`.`schedule_5zvezd` (`id`, `film_time_day`, `film_name`, `film_genre`, `film_time`)
								VALUES ('', '$date_1', '$_film_name', '$_film_genre', '$_film_time')");
					}
				}
				/* Парсер для сегодняшней даты + 2 дней */
				$site_parsing = file_get_html('https://afisha.yandex.ru/penza/cinema/places/piat-zviozd-penza?place-schedule-date=' . "$date_2");
				if ($site_parsing == false){
					echo "Ошибка при считывание данных для расписание кинотеатра <5 Звезд>" . "<br>";
				}
				else{
					$site_parsing_seances = $site_parsing -> find('div.schedule-grid__row');
					for($n = 0; $n < count($site_parsing_seances); $n++){
						$_film_name = $site_parsing_seances[$n] -> find('h3.schedule-event__title', 0) -> plaintext;
						$_film_genres = null;
						$_film_genres = $site_parsing_seances[$n] -> find('li.tags__item');
						$_film_genre = "";
						for($i = 0; $i < count($_film_genres); $i++) {
							$_film_genre = $_film_genre . ($_film_genres[$i] -> plaintext) . "<br>";
						}
						$_film_times = null;
						$_film_times = $site_parsing_seances[$n] -> find('div.schedule-sessions__item');
						$_film_time = "";
						for($i = 0; $i < count($_film_times); $i++) {
							$_film_time = $_film_time . ($_film_times[$i] -> plaintext) . "<br>";
						}
						$_MySQLConnect->query("
								INSERT INTO `goodtimepnz`.`schedule_5zvezd` (`id`, `film_time_day`, `film_name`, `film_genre`, `film_time`)
								VALUES ('', '$date_2', '$_film_name', '$_film_genre', '$_film_time')");
					}
				}
				/* Парсер для сегодняшней даты + 3 дней */
				$site_parsing = file_get_html('https://afisha.yandex.ru/penza/cinema/places/piat-zviozd-penza?place-schedule-date=' . "$date_3");
				if ($site_parsing == false){
					echo "Ошибка при считывание данных для расписание кинотеатра <5 Звезд>" . "<br>";
				}
				else{
					$site_parsing_seances = $site_parsing -> find('div.schedule-grid__row');
					for($n = 0; $n < count($site_parsing_seances); $n++){
						$_film_name = $site_parsing_seances[$n] -> find('h3.schedule-event__title', 0) -> plaintext;
						$_film_genres = null;
						$_film_genres = $site_parsing_seances[$n] -> find('li.tags__item');
						$_film_genre = "";
						for($i = 0; $i < count($_film_genres); $i++) {
							$_film_genre = $_film_genre . ($_film_genres[$i] -> plaintext) . "<br>";
						}
						$_film_times = null;
						$_film_times = $site_parsing_seances[$n] -> find('div.schedule-sessions__item');
						$_film_time = "";
						for($i = 0; $i < count($_film_times); $i++) {
							$_film_time = $_film_time . ($_film_times[$i] -> plaintext) . "<br>";
						}
						$_MySQLConnect->query("
								INSERT INTO `goodtimepnz`.`schedule_5zvezd` (`id`, `film_time_day`, `film_name`, `film_genre`, `film_time`)
								VALUES ('', '$date_3', '$_film_name', '$_film_genre', '$_film_time')");
					}
				}
				$_DateTime_Now = new DateTime(date("Y-m-d H:i:s"));
				$_DateTime_NowWrite = $_DateTime_Now->format('Y-m-d H:i:s');
				echo "<div>($_DateTime_NowWrite) Парсер расписания кинотеатра: 5 Звезд - выполнен.</div>";
			}
		}
		/* Парсим расписание "Ролликс" */
		$site_parsing = file_get_html('https://afisha.yandex.ru/penza/cinema/places/roliks-551798371f7d154a12ddf1db?place-schedule-preset=today');
		if ($site_parsing == false){
			echo "Ошибка при считывание данных для расписание кинотеатра <Ролликс>" . "<br>";
		}
		else{
			$_MySQLConnect->query("TRUNCATE TABLE `goodtimepnz`.`schedule_rollex`");
			if ($_MySQLConnect == false){
				echo "Ошибка при подключение к таблице [goodtimepnz].[schedule_rollex]" . "<br>";
			}
			else{
				/* Парсер для сегодняшней даты + 0 дней */
				$site_parsing = file_get_html('https://afisha.yandex.ru/penza/cinema/places/roliks-551798371f7d154a12ddf1db?place-schedule-date=' . "$date_0");
				if ($site_parsing == false){
					echo "Ошибка при считывание данных для расписание кинотеатра <5 Звезд>" . "<br>";
				}
				else{
					$site_parsing_seances = $site_parsing -> find('div.schedule-grid__row');
					for($n = 0; $n < count($site_parsing_seances); $n++){
						$_film_name = $site_parsing_seances[$n] -> find('h3.schedule-event__title', 0) -> plaintext;
						$_film_genres = null;
						$_film_genres = $site_parsing_seances[$n] -> find('li.tags__item');
						$_film_genre = "";
						for($i = 0; $i < count($_film_genres); $i++) {
							$_film_genre = $_film_genre . ($_film_genres[$i] -> plaintext) . "<br>";
						}
						$_film_times = null;
						$_film_times = $site_parsing_seances[$n] -> find('div.schedule-sessions__item');
						$_film_time = "";
						for($i = 0; $i < count($_film_times); $i++) {
							$_film_time = $_film_time . ($_film_times[$i] -> plaintext) . "<br>";
						}
						$_MySQLConnect->query("
								INSERT INTO `goodtimepnz`.`schedule_rollex` (`id`, `film_time_day`, `film_name`, `film_genre`, `film_time`)
								VALUES ('', '$date_0', '$_film_name', '$_film_genre', '$_film_time')");
					}
				}
				/* Парсер для сегодняшней даты + 1 дней */
				$site_parsing = file_get_html('https://afisha.yandex.ru/penza/cinema/places/roliks-551798371f7d154a12ddf1db?place-schedule-date=' . "$date_1");
				if ($site_parsing == false){
					echo "Ошибка при считывание данных для расписание кинотеатра <5 Звезд>" . "<br>";
				}
				else{
					$site_parsing_seances = $site_parsing -> find('div.schedule-grid__row');
					for($n = 0; $n < count($site_parsing_seances); $n++){
						$_film_name = $site_parsing_seances[$n] -> find('h3.schedule-event__title', 0) -> plaintext;
						$_film_genres = null;
						$_film_genres = $site_parsing_seances[$n] -> find('li.tags__item');
						$_film_genre = "";
						for($i = 0; $i < count($_film_genres); $i++) {
							$_film_genre = $_film_genre . ($_film_genres[$i] -> plaintext) . "<br>";
						}
						$_film_times = null;
						$_film_times = $site_parsing_seances[$n] -> find('div.schedule-sessions__item');
						$_film_time = "";
						for($i = 0; $i < count($_film_times); $i++) {
							$_film_time = $_film_time . ($_film_times[$i] -> plaintext) . "<br>";
						}
						$_MySQLConnect->query("
								INSERT INTO `goodtimepnz`.`schedule_rollex` (`id`, `film_time_day`, `film_name`, `film_genre`, `film_time`)
								VALUES ('', '$date_1', '$_film_name', '$_film_genre', '$_film_time')");
					}
				}
				/* Парсер для сегодняшней даты + 2 дней */
				$site_parsing = file_get_html('https://afisha.yandex.ru/penza/cinema/places/roliks-551798371f7d154a12ddf1db?place-schedule-date=' . "$date_2");
				if ($site_parsing == false){
					echo "Ошибка при считывание данных для расписание кинотеатра <5 Звезд>" . "<br>";
				}
				else{
					$site_parsing_seances = $site_parsing -> find('div.schedule-grid__row');
					for($n = 0; $n < count($site_parsing_seances); $n++){
						$_film_name = $site_parsing_seances[$n] -> find('h3.schedule-event__title', 0) -> plaintext;
						$_film_genres = null;
						$_film_genres = $site_parsing_seances[$n] -> find('li.tags__item');
						$_film_genre = "";
						for($i = 0; $i < count($_film_genres); $i++) {
							$_film_genre = $_film_genre . ($_film_genres[$i] -> plaintext) . "<br>";
						}
						$_film_times = null;
						$_film_times = $site_parsing_seances[$n] -> find('div.schedule-sessions__item');
						$_film_time = "";
						for($i = 0; $i < count($_film_times); $i++) {
							$_film_time = $_film_time . ($_film_times[$i] -> plaintext) . "<br>";
						}
						$_MySQLConnect->query("
								INSERT INTO `goodtimepnz`.`schedule_rollex` (`id`, `film_time_day`, `film_name`, `film_genre`, `film_time`)
								VALUES ('', '$date_2', '$_film_name', '$_film_genre', '$_film_time')");
					}
				}
				/* Парсер для сегодняшней даты + 3 дней */
				$site_parsing = file_get_html('https://afisha.yandex.ru/penza/cinema/places/roliks-551798371f7d154a12ddf1db?place-schedule-date=' . "$date_3");
				if ($site_parsing == false){
					echo "Ошибка при считывание данных для расписание кинотеатра <5 Звезд>" . "<br>";
				}
				else{
					$site_parsing_seances = $site_parsing -> find('div.schedule-grid__row');
					for($n = 0; $n < count($site_parsing_seances); $n++){
						$_film_name = $site_parsing_seances[$n] -> find('h3.schedule-event__title', 0) -> plaintext;
						$_film_genres = null;
						$_film_genres = $site_parsing_seances[$n] -> find('li.tags__item');
						$_film_genre = "";
						for($i = 0; $i < count($_film_genres); $i++) {
							$_film_genre = $_film_genre . ($_film_genres[$i] -> plaintext) . "<br>";
						}
						$_film_times = null;
						$_film_times = $site_parsing_seances[$n] -> find('div.schedule-sessions__item');
						$_film_time = "";
						for($i = 0; $i < count($_film_times); $i++) {
							$_film_time = $_film_time . ($_film_times[$i] -> plaintext) . "<br>";
						}
						$_MySQLConnect->query("
								INSERT INTO `goodtimepnz`.`schedule_rollex` (`id`, `film_time_day`, `film_name`, `film_genre`, `film_time`)
								VALUES ('', '$date_3', '$_film_name', '$_film_genre', '$_film_time')");
					}
				}
				$_DateTime_Now = new DateTime(date("Y-m-d H:i:s"));
				$_DateTime_NowWrite = $_DateTime_Now->format('Y-m-d H:i:s');
				echo "<div>($_DateTime_NowWrite) Парсер расписания кинотеатра: Ролликс - выполнен.</div>";
			}
		}
		/* Парсим данные для раздела "Прочий досуг" */
		$site_parsing = file_get_html('http://penza-afisha.ru/');
		if ($site_parsing == false){
			echo "Ошибка при считывание данных для раздела <Прочий досуг>" . "<br>";
		}
		else {
			$_MySQLConnect->query("TRUNCATE TABLE `goodtimepnz`.`other_leisure`");
			if ($_MySQLConnect == false){
				echo "Ошибка при подключение к таблице [goodtimepnz].[other_leisure]" . "<br>";
			}
			else{
				/* Парсер для подраздела: Ледовые катки */
				$site_parsing = file_get_html('http://penza-afisha.ru/org.php?id=40');
				if ($site_parsing == false){
					echo "<div>Ошибка при считываняия данных для раздела: Ледовые катки</div>";
				}
				else{
					$site_parsing_tables = $site_parsing -> find('div.text_col1 table');
					$_IRName = "";
					$_IRAddress = "";
					for ($n=0; $n<count($site_parsing_tables); $n++)
					{
						$_IR = $site_parsing_tables[$n] -> find('tr');
						$_leisure_name = $_IR[0] -> plaintext;
						$_leisure_adress = $_IR[1] -> plaintext;
						$_MySQLConnect->query("INSERT INTO `goodtimepnz`.`other_leisure` (`id`, `leisure_type`, `leisure_name`, `leisure_address`) VALUES (NULL, '1', '$_leisure_name', '$_leisure_adress')");
					}
				}
				/* Парсер для подраздела: Пейтбольные клубы */
				$site_parsing = file_get_html('http://penza-afisha.ru/org.php?id=58');
				if ($site_parsing == false){
					echo "<div>Ошибка при считываняия данных для раздела: Ледовые катки</div>";
				}
				else{
					/* Парсер для подраздела: Ледовые катки */
					$site_parsing_tables = $site_parsing -> find('div.text_col1 table');
					$_IRName = "";
					$_IRAddress = "";
					for ($n=0; $n<count($site_parsing_tables); $n++)
					{
						$_IR = $site_parsing_tables[$n] -> find('tr');
						$_leisure_name = $_IR[0] -> plaintext;
						$_leisure_adress = $_IR[1] -> plaintext;
						$_MySQLConnect->query("INSERT INTO `goodtimepnz`.`other_leisure` (`id`, `leisure_type`, `leisure_name`, `leisure_address`) VALUES (NULL, '2', '$_leisure_name', '$_leisure_adress')");
					}
				}
				/* Парсер для подраздела: Парки */
				$site_parsing = file_get_html('http://penza-afisha.ru/org.php?id=7');
				if ($site_parsing == false){
					echo "<div>Ошибка при считываняия данных для раздела: Ледовые катки</div>";
				}
				else{
					/* Парсер для подраздела: Ледовые катки */
					$site_parsing_tables = $site_parsing -> find('div.text_col1 table');
					$_IRName = "";
					$_IRAddress = "";
					for ($n=0; $n<count($site_parsing_tables); $n++)
					{
						$_IR = $site_parsing_tables[$n] -> find('tr');
						$_leisure_name = $_IR[0] -> plaintext;
						$_leisure_adress = $_IR[1] -> plaintext;
						$_MySQLConnect->query("INSERT INTO `goodtimepnz`.`other_leisure` (`id`, `leisure_type`, `leisure_name`, `leisure_address`) VALUES (NULL, '3', '$_leisure_name', '$_leisure_adress')");
					}
				}
				$_DateTime_Now = new DateTime(date("Y-m-d H:i:s"));
				$_DateTime_NowWrite = $_DateTime_Now->format('Y-m-d H:i:s');
				echo "<div>($_DateTime_NowWrite) Парсер для раздела: Прочий досуг - выполнен.</div>";
			}
		}
	}
	mysqli_close($_MySQLConnect);
?>