<?php
function writeLog($data)//функция записи в лог для отладки
{
    $log = file_get_contents("log.txt");
    $data = (string)$data;
    $log .= strftime("%c", time())." >> ".$data.";\r\n";
    file_put_contents("log.txt", $log);
}

    $maxFileSize = 100*1024; //ограничение размера файла
    $validFileType = ["json"];//разрешенные форматы файлов
    $errorArray = [];//массив для записи ошибок
    $path = 'files/'; //путь для нового файла
    $fileName='';
    //$randName = md5(time().mt_rand(0, 9999));// имя нового файла
    // если есть отправленные файлы
    writeLog('=start=');//пишем в лог для отладки
    $result = '';//для вывода результата на страницу
    if ($_FILES) {
        // валидация размера файла
        $fileName = $_FILES["user_file"]["name"];
        $fileSize = $_FILES["user_file"]["size"];
        $tmpName = $_FILES["user_file"]["tmp_name"];
        writeLog("fileName=".$fileName);//пишем в лог для отладки
        writeLog("fileSize=".$fileSize);//пишем в лог для отладки
        writeLog("tmpName=".$tmpName);//пишем в лог для отладки
        if ($fileSize > $maxFileSize) {
            $errorArray[] = "Размер файла превышает допустимый!";
        }
        // валидация формата файла
        $info = pathinfo($fileName);
        $format = $info['extension'];
        writeLog("format=".$format);//пишем в лог для отладки
        if(!in_array($format, $validFileType)) {
            $errorArray[] = "Недопустимый формат файла!";
        }
        // если не было ошибок
        if (empty($errorArray)) {
            // проверяем загружен ли файл
            if (is_uploaded_file($tmpName)) {
                // сохраняем файл
                //move_uploaded_file($tmpName, $path.$randName.".$format");
                move_uploaded_file($tmpName, $path.$fileName);
                $result = "файл $fileName сохранен";
                writeLog($result.': '.$path.$fileName);//пишем в лог для отладки
                writeLog('=file saved=');//пишем в лог для отладки
                header('Location: list.php');//редирект на список тестов
            } else {
                // Если файл не загрузился
                $errorArray[] = 'Ошибка загрузки!';
            }
        } else {
            $result = 'Ошибки:<br>'.implode(';', $errorArray);
        }
    } else {
        writeLog('no file');
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
