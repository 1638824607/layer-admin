<?php
    header("Access-Control-Allow-Origin: *");
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    $allowedExts = array("gif", "jpeg", "jpg", "png");
    $temp        = explode(".", $_FILES["file"]["name"]);
    $extension   = end($temp);     // 获取文件后缀名

    $code = 1;

    if((($_FILES["file"]["type"] == "image/gif")
            || ($_FILES["file"]["type"] == "image/jpeg")
            || ($_FILES["file"]["type"] == "image/jpg")
            || ($_FILES["file"]["type"] == "image/pjpeg")
            || ($_FILES["file"]["type"] == "image/x-png")
            || ($_FILES["file"]["type"] == "image/png"))
    && ($_FILES["file"]["size"] < 2048000)   // 小于 2000 kb
    && in_array($extension, $allowedExts))
    {
        if($_FILES["file"]["error"] > 0) {
            $msg = "错误：: " . $_FILES["file"]["error"];
        }else
        {
//            echo "上传文件名: " . $_FILES["file"]["name"] . "<br>";
//            echo "文件类型: " . $_FILES["file"]["type"] . "<br>";
//            echo "文件大小: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
//            echo "文件临时存储的位置: " . $_FILES["file"]["tmp_name"] . "<br>";

            // 判断当期目录下的 upload 目录是否存在该文件
            // 如果没有 upload 目录，你需要创建它，upload 目录权限为 777
            if(! is_dir('upload')) {
                mkdir('upload', 0777);
                chmod('upload', 0777);
            }

            if(file_exists("upload/" . $_FILES["file"]["name"])) {
                $msg = $_FILES["file"]["name"] . " 文件已经存在。";
            }
            else {
                // 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
                move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
                chmod("upload/" . $_FILES["file"]["name"], 0777);
                $msg = "上传成功。文件存储在: " . "upload/" . $_FILES["file"]["name"];
                $code = 0;
            }
        }
    }else {
        $msg = "非法的文件格式";
    }

    echo json_encode(['code' => $code, 'msg' => $msg]);
    exit();

?>