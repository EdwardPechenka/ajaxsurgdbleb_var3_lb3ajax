Инструкция по запуску Лабораторной работы №3 AJAX
Чтобы всё работало корректно, выполни эти 4 простых шага.

Шаг 1. Размещение файлов
Распакуй папку с проектом в директорию твоего локального веб-сервера (OpenServer).

Если у тебя старая версия OpenServer (5.x), это папка OSPanel\domains.

Если новая (6.0), это папка OSPanel\home.

<img width="663" height="37" alt="image" src="https://github.com/user-attachments/assets/897feef5-8723-4755-9ca4-742ae274d5e4" />

Здесь не забудь создать папку ajaxsurgdb.local, это обязательно, и закинь туда все файлы:

<img width="1103" height="410" alt="image" src="https://github.com/user-attachments/assets/c3394bbd-db07-40fc-9a63-c726894ca936" />

Шаг 2. Загрузка базы данных (ОБЯЗАТЕЛЬНО)
Проект не будет работать без базы данных.

Запусти OpenServer и открой phpMyAdmin.

<img width="511" height="562" alt="image" src="https://github.com/user-attachments/assets/f294e7be-3e12-45fe-97b6-b2560237ff13" />

<img width="1919" height="497" alt="image" src="https://github.com/user-attachments/assets/39f0ad62-e295-483f-a451-ce6baeaa968c" />

Перейди во вкладку Импорт (Import) в верхнем меню.

<img width="1919" height="835" alt="image" src="https://github.com/user-attachments/assets/5a5a09e3-5ef7-437f-aad0-57282112d417" />

Выбери файл iteh2lb1var4.sql (он лежит в папке с проектом) и нажми Import.
База данных iteh2lb1var4 и все таблицы с данными создадутся автоматически.

<img width="1494" height="548" alt="image" src="https://github.com/user-attachments/assets/0d6fcfc1-aec2-48a1-a288-c5b08e6ec26b" />

Шаг 3. Настройка подключения (db.php)
Важный момент! Настройки базы зависят от твоей версии OpenServer.
Открой файл db.php в любом редакторе. Сейчас там стоят настройки для OpenServer 6.0:

$host = 'MySQL-8.4';

$db   = 'iteh2lb1var4';

$pass = '';

Если у тебя старая версия OpenServer (или XAMPP), измени эти строки на стандартные:

$host = 'localhost';

$pass = 'root'; (или оставь пустым '', если пароля нет).

Шаг 4. Запуск
Запусти проект (или перезапусти OpenServer, чтобы он увидел новую папку). Открой браузер и перейди по локальному адресу проекта http://ajaxsurgdb.local.

<img width="511" height="545" alt="image" src="https://github.com/user-attachments/assets/7d1095a1-f733-4977-a9a2-e89dbc7f148a" />

Также просмотри, чтобы у тебя были эти версии PHP и MySQL:

<img width="459" height="331" alt="image" src="https://github.com/user-attachments/assets/3487b779-0d0b-4ca3-98af-64e5df07e249" />

Шаг 5. Проверка приложения (Асинхронные AJAX запросы)
Пройдись по всем запросам. При нажатии на кнопки данные должны мгновенно подгружаться в блок ниже без перезагрузки страницы:

Запрос палат медсестры — возвращает готовый HTML-фрагмент (текст).

Запрос персонала отделения — передает XML-документ, который парсится на стороне клиента.

Запрос графика по смене — работает через Fetch API и обрабатывает JSON-массив.

<img width="781" height="692" alt="image" src="https://github.com/user-attachments/assets/72f4ba0e-d3ec-4f84-b7cf-715d9af4db69" />

<img width="844" height="672" alt="image" src="https://github.com/user-attachments/assets/5f1c9d4a-4e47-4dfc-9070-0585404c88d3" />

<img width="805" height="743" alt="image" src="https://github.com/user-attachments/assets/2a6379d9-4db4-4ef8-afff-7456c294effa" />

🛠 Частые ошибки:
Страница перезагружается при отправке: Проверь подключение скрипта в index.php и корректность работы e.preventDefault() в обработчиках событий.

Данные не отображаются: Проверь вкладку Network (Сеть) в консоли разработчика браузера (F12), чтобы убедиться, что файлы nurse_wards.php, department_nurses.php и shift_duties.php возвращают корректные структуры данных без PHP варнингов.

Ошибка SQLSTATE 2002 (Refused): Неверно указан хост БД в db.php под текущую версию OpenServer.
