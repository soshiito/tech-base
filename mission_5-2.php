<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
$dsn='mysql:dbname=データベース名;host=localhost';
$user = 'ユーザー名';
$pass = 'パスワード';
$pdo = new PDO($dsn, $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
$sql = "CREATE TABLE IF NOT EXISTS テーブル名"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "postedAt TEXT,"
    . "password char(16)"
    .");";
$stmt = $pdo->query($sql);
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>自分の大学のここがオススメ！</title>
    </head>

    <body>
        <h1>自分の大学のここがオススメ！</h1>
        <p>名前は32字以内、パスワードは16字以内でお願いします</p>



        <form method="POST" action="mission_5-2.php">

            <h3>投稿フォーム</h3>
            <label>名前：</label><br />
            <input type="text" name="user" /><br />
            <label>コメント：</label><br />
            <input type="text" name="message" /><br />
            <label>パスワード：</label><br />
            <input type="text" name="pass1" /><br />
            <input type="submit" value="送信" /><br>

            <h3>削除フォーム</h3>
            削除対象番号<br>
            <input type="text" name="delete"><br>
            <label>パスワード：</label><br />
            <input type="text" name="pass2" ><br />
            <input type="submit" value="削除"><br>

            <h3>編集フォーム</h3>
            編集対象番号<br>
            <input type="text" name="edit"><br>
            <label>名前：</label><br />
            <input type="text" name="users" /><br />
            <label>コメント：</label><br />
            <input type="text" name="messages" /><br />
            <label>パスワード：</label><br />
            <input type="text" name="pass3" ><br />
            <input type="submit" value="送信" /><br>
        </form>

        <?php
        if(!empty($_POST["message"]) && !empty($_POST["user"]) && !empty($_POST["pass1"])){
            $message = ($_POST["message"]);
            $user = ($_POST["user"]);
            $pass1 = ($_POST["pass1"]);
            $postedAt = date("Y-m-d H:i:s");
            $sql = $pdo -> prepare("INSERT INTO テーブル名 (name, comment, postedAt, password) VALUES (:name, :comment, :postedAt, :password)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':postedAt', $posted, PDO::PARAM_STR);
            $sql -> bindParam(':password', $password, PDO::PARAM_STR);
            $name = $user;
            $comment = $message;
            $posted = $postedAt;
            $password = $pass1;
            $sql -> execute();
            $sql = 'SELECT * FROM テーブル名';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].',';
                echo $row['postedAt'].'<br>';
            }
        }elseif (!empty($_POST["delete"]) && !empty($_POST["pass2"])) {
            $id = ($_POST["delete"]);
            $pass2 = ($_POST["pass2"]);
            $sql = 'SELECT * FROM テーブル名';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                if($id == $row['id'] && $pass2 == $row['password']){
                    $sql = 'delete from テーブル名 where id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                }else{
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].',';
                    echo $row['name'].',';
                    echo $row['comment'].',';
                    echo $row['postedAt'].'<br>';
                }
            }
        }elseif(!empty($_POST["edit"])&&!empty($_POST["users"])&&!empty($_POST["messages"]) && !empty($_POST["pass3"])){
            $pass3 = ($_POST["pass3"]);
            $num = intval(mb_convert_kana($_POST["edit"], 'n'));
            $users = $_POST["users"];
            $messages = $_POST["messages"];
            $postedAt = date("Y-m-d H:i:s");
            $sql = 'SELECT * FROM テーブル名';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                if($num == $row['id'] && $pass3 == $row['password']){
                    $id = $num; //変更する投稿番号
                    $name = $users;
                    $comment = $messages;
                    $posted = $postedAt;
                    $password = $pass3;//変更したい名前、変更したいコメントは自分で決めること
                    $sql = 'update テーブル名 set name=:name,comment=:comment,postedAt=:postedAt,password=:password where id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->bindParam(':postedAt', $posted, PDO::PARAM_STR);
                    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                    $stmt->execute();
                    echo $row['id'].',';
                    echo $row['name'].',';
                    echo $row['comment'].',';
                    echo $row['postedAt'].'<br>';
                }else{
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].',';
                    echo $row['name'].',';
                    echo $row['comment'].',';
                    echo $row['postedAt'].'<br>';
                }
            }
        }else{
            $sql = 'SELECT * FROM テーブル名';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].',';
                echo $row['postedAt'].'<br>';
            }
        }
        ?>




    </body>
</html>
