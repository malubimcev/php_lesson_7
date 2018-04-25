<?php

    $maxFileSize = 100*1024; //ограничение размера файла
    $validFileType = ["json"];//разрешенные форматы файлов
    $errorArray = [];//массив для записи ошибок
    $path = 'files/'; //путь для нового файла
    $fileName = '';
    $result = '';//для вывода результата на страницу
    // если есть отправленные файлы
    if ($_FILES) {
        // валидация размера файла
        $fileName = $_FILES["user_file"]["name"];
        $fileSize = $_FILES["user_file"]["size"];
        $tmpName = $_FILES["user_file"]["tmp_name"];
        if ($fileSize > $maxFileSize) {
            $errorArray[] = "Размер файла превышает допустимый!";
        }
        // валидация формата файла
        $info = pathinfo($fileName);
        $format = $info['extension'];
        if(!in_array($format, $validFileType)) {
            $errorArray[] = "Недопустимый формат файла!";
        }
        // если не было ошибок
        if (empty($errorArray)) {
            // проверяем загружен ли файл
            if (is_uploaded_file($tmpName)) {
                // сохраняем файл
                if (move_uploaded_file($tmpName, $path.$fileName)) {;
                    $result = "файл $fileName сохранен";
                    header('Location: list.php');//редирект на список тестов
                    die();
                }
            } else {
                // Если файл не загрузился
                $errorArray[] = 'Ошибка загрузки!';
            }
        } else {
            $result = 'Ошибки:<br>'.implode(';', $errorArray);
        }
    } else {
        $errorArray[] = 'no file';
    }
?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <title>Загрузка файла</title>
    <link rel="stylesheet" href="css/styles.css"/>
  </head>
  <body>
      <section class="main-container">
        <h1>Загрузка файлов</h1>
        <div class="form-container">
            <form action="<?=$_SERVER['PHP_SELF'];?>" method="POST" enctype="multipart/form-data" class="file-input-form">
              <input type="file" name="user_file"><br>
              <input type="submit" value="Загрузить" class="button select-button"><br>
            </form><br>
        </div>
        <div class="result">
            <?php echo $result; ?>
        </div>
    </section>
  </body>
</html>
