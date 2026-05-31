<?php 
include 'db.php'; 
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Моніторинг чергувань медсестер (AJAX)</title>
    <style>
        body { font-family: sans-serif; margin: 40px; background-color: #fafafa; color: #222; }
        h1 { color: #111; border-bottom: 2px solid #333; padding-bottom: 10px; }
        fieldset { margin-bottom: 25px; padding: 15px; border: 1px solid #999; max-width: 600px; background: #fff; }
        legend { font-weight: bold; padding: 0 5px; color: #444; }
        .form-group { margin-bottom: 10px; }
        label { display: inline-block; width: 200px; }
        select { padding: 3px; }
        button { padding: 4px 12px; cursor: pointer; background: #f0f0f0; border: 1px solid #777; }
        button:hover { background: #e0e0e0; }
        #result { margin-top: 25px; padding: 15px; border: 1px solid #333; background: #fff; min-height: 60px; max-width: 600px; }
        ul { padding-left: 20px; }
        li { margin-bottom: 4px; }
    </style>
</head>
<body>

    <h1>Система обліку робочих змін</h1>
    
    <fieldset>
        <legend>Пошук палат за закріпленою медсестрою</legend>
        <form id="nurseForm">
            <div class="form-group">
                <label for="nurse">Вкажіть медсестру:</label>
                <select name="nurse" id="nurse">
                    <?php
                    $stmt = $pdo->query('SELECT id_nurse, name FROM nurse');
                    while ($row = $stmt->fetch()) { 
                        echo "<option value=\"{$row['id_nurse']}\">{$row['name']}</option>";
                    }
                    ?>
                </select>
                <button type="submit">Запит (Text)</button>
            </div>
        </form>
    </fieldset>

    <fieldset>
        <legend>Перелік персоналу за номером відділення</legend>
        <form id="departmentForm">
            <div class="form-group">
                <label for="department">Вкажіть номер відділення:</label>
                <select name="department" id="department">
                    <?php
                    $stmt = $pdo->query('SELECT DISTINCT department FROM nurse ORDER BY department ASC');
                    while ($row = $stmt->fetch()) { 
                        echo "<option value=\"{$row['department']}\">Відділення №{$row['department']}</option>";
                    }
                    ?>
                </select>
                <button type="submit">Запит (XML)</button>
            </div>
        </form>
    </fieldset>

    <fieldset>
        <legend>Графік чергувань за обраною зміною</legend>
        <form id="shiftForm">
            <div class="form-group">
                <label for="shift">Вкажіть робочу зміну:</label>
                <select name="shift" id="shift">
                    <option value="First">First</option>
                    <option value="Second">Second</option>
                    <option value="Third">Third</option>
                </select>
                <button type="submit">Запит (JSON)</button>
            </div>
        </form>
    </fieldset>

    <h2>Результати моніторингу:</h2>
    <div id="result">Інформація відсутня. Оберіть критерії пошуку вище.</div>

    <script>
    const resultDiv = document.getElementById('result');

    document.getElementById('nurseForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const nurseId = document.getElementById('nurse').value;
        
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'nurse_wards.php?nurse=' + nurseId, true);
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                resultDiv.innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    });

    document.getElementById('departmentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const deptId = document.getElementById('department').value;
        
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'department_nurses.php?department=' + deptId, true);
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const xmlDoc = xhr.responseXML;
                const items = xmlDoc.getElementsByTagName('nurse');
                
                if (items.length === 0) {
                    resultDiv.innerHTML = '<h3>Медсестри відділення (формат XML)</h3><p>У цьому відділенні немає медсестер.</p>';
                    return;
                }
                
                let html = '<h3>Медсестри відділення (формат XML)</h3><ul>';
                for (let i = 0; i < items.length; i++) {
                    let name = items[i].getElementsByTagName('name')[0].textContent;
                    let date = items[i].getElementsByTagName('date')[0].textContent;
                    let shift = items[i].getElementsByTagName('shift')[0].textContent;
                    
                    html += `<li><b>${name}</b> — Дата чергування: ${date}, Зміна: ${shift}</li>`;
                }
                html += '</ul>';
                resultDiv.innerHTML = html;
            }
        };
        xhr.send();
    });

    document.getElementById('shiftForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const shiftValue = document.getElementById('shift').value;
        
        fetch(`shift_duties.php?shift=${shiftValue}`)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    resultDiv.innerHTML = '<h3>Чергування у зміну (формат JSON)</h3><p>Жодних чергувань на цю зміну не знайдено.</p>';
                    return;
                }
                
                let html = '<h3>Чергування у зміну (формат JSON)</h3><ul>';
                data.forEach(row => {
                    html += `<li><b>Медсестра:</b> ${row.nurse_name} — <b>Відділення:</b> №${row.department}, <b>Дата:</b> ${row.date}, <b>Палата:</b> ${row.ward_name}</li>`;
                });
                html += '</ul>';
                resultDiv.innerHTML = html;
            })
            .catch(error => {
                resultDiv.innerHTML = '<p style="color:red;">Помилка завантаження JSON даних</p>';
                console.error('Fetch error:', error);
            });
    });
    </script>
</body>
</html>