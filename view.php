<?php

date_default_timezone_set('Asia/Tokyo');

if (!empty($_POST["todo_text"])) {
    create($_POST["todo_text"]);
} else if (isset($_POST["delete"])) {
    delete($_POST["id"]);
} else if (isset($_POST["update"])) {
    update($_POST["id"], $_POST["text"]);
}


function dbConnect()
{
    try {
        return $dbh = new PDO(
            'mysql:host=localhost;dbname=mytodo;',
            'root',
            'root',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    } catch (PDOException $e) {
        header('Content-Type:text/plain;charset=UTF-8', true, 500);
        exit($e->getMessage());
    }
}

function create($todo)
{
    $sql = "INSERT INTO todo (name) VALUES(:name)";
    $stmt = dbConnect()->prepare($sql);
    $stmt->bindValue(":name", $todo);
    $stmt->execute();
}

function delete($id)
{
    $sql = "DELETE FROM todo WHERE id = ?";
    $stmt = dbConnect()->prepare($sql);
    $stmt->bindValue(1, "$id", PDO::PARAM_INT);
    $stmt->execute();
}

function update($id, $text)
{
    $sql = "UPDATE todo SET name = ? WHERE id = ?";
    $stmt = dbConnect()->prepare($sql);
    $stmt->bindValue(1, $text);
    $stmt->bindValue(2, $id, PDO::PARAM_INT);
    $stmt->execute();
}

function getData()
{
    $sql = "SELECT * FROM todo";
    $stmt = dbConnect()->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}


?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TODO一覧</title>
</head>

<body>
    <h2>TODOリスト</h2>
    <ul>
        <form method="post">
            <?php foreach (getData() as $todo) { ?>
                <li><input type="text" name="text" value="<?php echo $todo["name"]; ?>"></li>
                <input type="hidden" name="id" value="<?php echo $todo["id"]; ?>">
                <button type="submit" name="delete">削除</button>
                <button type="submit" name="update">更新</button>
        </form>
    <?php
            }
    ?>
    </ul>
    <a href="input.html">入力画面に戻る</a>
</body>

</html>
