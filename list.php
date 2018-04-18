<?php
//list.php

    //функция получения имени теста из файла
    function get_test_name($fileName)
    {
        $json = file_get_contents($fileName);
        $data = json_decode($json, true);
        return $data['testName'];
    }

    $fileList = scandir('files/');//перечень файлов
    $testList = [];//список тестов
    foreach ($fileList as $key => $value) {
        if ($key > 1) {
            $testList[] = get_test_name($value);
        }
    }

?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="css/styles.css"/>
  </head>
  <body>
      <section class="main-container">
        <h1>Список тестов</h1>
        <ol class="file-list">
          <?php foreach ($testList as $test) {
            echo "<li>$test</li>";
          } ?>
        </ol>
        <div class="form-container">
          <form action="test.php" method="GET" class="file-input-form">
            <label for="test_number" class="label">Выберите номер теста:</label>
            <input type="text" name="test_number">
            <input type="submit" value="Загрузить тест" class="button select-button" />
          </form>
        </div>
     </section>
  </body>
</html>
