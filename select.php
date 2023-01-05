<?php

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}

//1.  DB接続します
try {
  //Password:MAMP='root',XAMPP=''
  $pdo = new PDO('mysql:dbname=gs_db;charset=utf8;host=localhost','root','');
} catch (PDOException $e) {
  exit('DBConnectError'.$e->getMessage());
}

//２．データ取得SQL作成
$stmt = $pdo->prepare("SELECT * FROM gs_bm_table;");
$status = $stmt->execute();

//３．データ表示
$view="";
if ($status==false) {
    //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

}else{
  //elseの中は、SQL実行成功した場合
  //Selectデータの数だけ自動でループしてくれる
  //FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
  https://images-fe.ssl-images-amazon.com/images/P/4532260655.09.MZZZZZZZ // 長辺が160px

  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    // $view .= '<p>'. $result['id']. ' , '. h($result['name']). ' , '. h($result['url']). ' , '. $result['comment']. '</p>';
    $isbnIndex = strpos(h($result['url']), '/dp/'); //'/dp/'の開始バイト数を取得
    $urllen = strlen(h($result['url']));
    $isbn = substr(h($result['url']), $isbnIndex + 4, 10);
    // var_dump($isbn);
    // if ($isbn = null){
    //   $img = '画像は見つかりませんでした';
    // }else{
      // $imgurl = 'https://images-fe.ssl-images-amazon.com/images/P/'.$isbn.'.09.MZZZZZZZ';
    // }

    $shorturl = 'https://www.amazon.co.jp/dp/'. $isbn;
    $str = '<p>'. $result['id']. ' , '. h($result['name']). ' , '.'<a href="'. $shorturl.'" target="_blank" rel="noopener noreferrer">'. $shorturl. '</a>'. ' , '. $result['comment']. ' , '.'<button onclick="location.href=\'detail.php?id=' . $result['id'] . '\'">編集</button>'.' , '.'<button onclick="location.href=\'delete.php?id=' . $result['id'] . '\'">削除</button>'.'</p>';
    $ary = explode(",", $str); //文字列を配列に変換
    $view .= '<tr>';
      for($i = 0; $i < sizeof($ary); $i++){
        $view .= "<td> {$ary[$i]} </td>";
      }
    
    $view .='</tr>';
  }
}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>お気に入り本一覧表示</title>
<link rel="stylesheet" href="css/range.css">
<link href="css/bootstrap.min.css" rel="stylesheet">
<style>div{padding: 10px;font-size:16px;}</style>
</head>
<body id="main">
<!-- Head[Start] -->
<header>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
      <a class="navbar-brand" href="index.php">データ登録</a>
      </div>
    </div>
  </nav>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<div>
    <div class="container jumbotron">
    <h2>お気に入り本一覧</h2>
        <table border="1">
            <tr>
                <th>id</th>
                <th>title</th>
                <th>url</th>
                <th>comment</th>
                <th>編集</th>
                <th>削除</th>
            </tr>
      <?= $view ?>
    </div>
</div>
<!-- Main[End] -->

</body>
</html>
