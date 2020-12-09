 <?php
    //データベース接続
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    //テーブル作成


    $sql = "CREATE TABLE IF NOT EXISTS tbtest"
        . " ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "posdate TEXT,"
        . "pass TEXT"
        . ");";
    $stmt = $pdo->query($sql);

    //テーブルの存在確認
    $sql = 'SHOW TABLES';
    $result = $pdo->query($sql);
    foreach ($result as $row) {
        echo $row[0];
        echo '<br>';
    }
    echo "<hr>";

    //編集要素取得機能
    if (!empty($_POST["edit"]) && !empty($_POST["editpass"])) {
        $edit = $_POST["edit_id"];
        $editpass = $_POST["editpass"];
        $stmt = $pdo->prepare("SELECT * FROM tbtest");
        $stmt->execute();
        foreach ($stmt as $e) {
            if ($edit == $e['id'] && $editpass == $e['pass']) {
                $editnumber = $e['id'];
                $editname = $e['name'];
                $editcomment = $e['comment'];
            }
        }
    }

    if (!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"])) {
        //編集か新規か判断
        //ここで受け取った値をテーブルに入れていく
        if (empty($_POST["number"])) {

            //書き込み
            $sql = $pdo->prepare("INSERT INTO tbtest (name, comment, posdate, pass) VALUES (:name, :comment, :posdate, :pass)");
            $sql->bindParam(':name', $name, PDO::PARAM_STR);
            $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql->bindParam(':posdate', $posdate, PDO::PARAM_STR);
            $sql->bindParam(':pass', $pass, PDO::PARAM_STR);
            //新規投稿内容
            $name = $_POST["name"];
            $comment = $_POST["comment"]; //好きな名前、好きな言葉は自分で決めること
            $posdate = date("Y/m/d H:i:s");
            $pass = $_POST["pass"];
            $sql->execute();

            //insertをブラウザで確認
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row) {
                //$rowの中にはテーブルのカラム名が入る
            }
            //投稿成功を示す
            $succes_messa = '送信が完了しました';
        } elseif (!empty($_POST["number"])) {
            $stmt = $pdo->prepare('SELECT * FROM tbtest');
            $stmt->execute();
            foreach ($stmt as $ed) {
                if ($_POST["number"] == $ed['id'] && $_POST["pass"] = $ed['pass']) {
                    //変更したい名前、変更したいコメントは自分で決めること
                    $id = $_POST["number"];
                    $name = $_POST["name"];
                    $comment = $_POST["comment"];
                    $posdate = date("Y/m/d H:i:s");
                    $pass = $_POST["pass"];
                    $sql = 'UPDATE tbtest SET name=:name,comment=:comment,posdate=:posdate,pass=:pass WHERE id=:id';
                    $stmt2 = $pdo->prepare($sql);
                    $stmt2->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt2->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt2->bindParam(':posdate', $posdate, PDO::PARAM_STR);
                    $stmt2->bindParam(':pass', $pass, PDO::PARAM_STR);
                    $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt2->execute();
                }
            }
        }
    }



    //デリートで指定したメッセージの削除
    if (!empty($_POST["del"]) && !empty($_POST["delepass"]) && !empty($_POST["delete"])) {
        $delete = $_POST["delete"];
        $delpass = $_POST["delepass"];
        $id = $delete;
        $stmt = $pdo->prepare("SELECT * FROM tbtest");
        $stmt->execute();
        foreach ($stmt as $f) {
            if ($id == $f['id'] && $delpass == $f['pass']) {
                $sql = 'delete from tbtest where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                echo "削除しました";
            }
        }
    }
    $pdo = null;

    ?>

 <!DOCTYPE html>
 <html lang="ja">

 <head>
     <meta charset="UTF-8">
     <title>mission5-1</title>
 </head>

 <body>

     <form action="" method="POST">
         <label for="name">名前</label>
         <input type="text" name="name" placeholder="名前" value="<?php if (isset($editname)) {
                                                                    echo $editname;
                                                                } ?>"><br>
         <label for="comment">コメント</label>
         <input type="text" name="comment" placeholder="コメント" value="<?php if (isset($editcomment)) {
                                                                            echo $editcomment;
                                                                        } ?>"><br>
         <input type="text" name="pass" placeholder="パスワード">
         <input type="submit" name="submit" value="送信"><br>
         <label for="delete">削除対象番号</label>
         <input type="number" name="delete" placeholder="削除番号"><br>
         <input type="text" name="delepass" placeholder="パスワード"><br>
         <input type="submit" name="del" value="削除"><br>
         <label for="edit_id">編集対象番号</label>
         <input type="number" name="edit_id" placeholder="編集番号"><br>
         <input type="text" name="editpass" placeholder="パスワード"><br>
         <input type="submit" name="edit" value="編集"><br>
         <input type="hidden" name="number" value="<?php if (isset($editnumber)) {
                                                        echo $editnumber;
                                                    } ?>">
     </form>
     <?php
        //表示機能
        //データベース接続
        $dsn = 'mysql:dbname=tb221002db;host=localhost';
        $user = 'tb-221002';
        $password = 'phEeVxextR';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        $sql = 'SELECT * FROM tbtest';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row) {
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'] . ',';
            echo $row['name'] . ',';
            echo $row['comment'] . ',';
            echo $row['posdate'] . ',';
            echo $row['pass'] . '<br>';
            echo "<hr>";
        }
        $pdo = null;
        ?>
 </body>

 </html>