
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン中</title>
    <style>
    body {
            background: linear-gradient(to right, rgba(192,192,192,1) 0%, rgba(128,128,128,1) 50%);
            font-family: "Roboto", sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-page {
            width: 100%;
            max-width: 360px;
            padding: 8% 0;
            margin: auto;
        }
        .form {
            position: relative;
            z-index: 1;
            background: #FFFFFF;
            padding: 45px;
            text-align: center;
            box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
            border-radius: 10px;
        }
        .form h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
    </style>
</head>
<body>
<?php
session_start();
$ep_no = $_POST['ep_no'];
try {
    $dsn = "mysql:dbname=bbadb;host=localhost;charset=utf8mb4";
    $username = "root";
    $options = [];
    $pdo = new PDO($dsn, $username);
} catch (PDOException $e) {
    $msg = $e->getMessage();
}

$sql = "SELECT * FROM employee WHERE ep_no = :ep_no";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':ep_no', $ep_no);
$stmt->execute();
$member = $stmt->fetch(PDO::FETCH_ASSOC);
//指定したハッシュがパスワードにマッチしているかチェック
if (password_verify($_POST['password'], $member['password'])) {
    //DBのユーザー情報をセッションに保存
    $_SESSION['ep_no'] = $member['ep_no'];
    $_SESSION['name'] = $member['ep_name'];
    $_SESSION['tenpo_no'] = $member['tenpo_no']; // tenpo_noをセッションに保存
    $_SESSION['logged_in'] = true;
    $_SESSION['yakusyoku'] = $member['yakusyoku']; 
    $msg = 'ログイン中';
    $link = '<a href="index.php">ホーム</a>';
    header("Refresh: 1.5; URL=index.php");
} else {
    $msg = '従業員番号もしくはパスワードが間違っています。';
    $link = '<a href="login.php">戻る</a>';
}
?>
    <div class="login-page">
        <div class="form">
            <form class="login-form" action="login code.php" method="post">
                <h1><?php echo $msg; ?></h1>
                <?php echo $link; ?>
            </form>
        </div>
    </div>
</body>
</html>
