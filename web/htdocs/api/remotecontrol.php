<?php 
    header('Content-type: text/plain; charset= UTF-8');

    if(!isset($_GET['filename'])){
        print('no filename posted\n');
        exit();
    }

    if(!isset($_GET['command'])){
        print('no command posted\n');
        exit();
    }

    //引数を取得する
    $filename = htmlspecialchars($_GET["filename"], ENT_QUOTES);
    $sendcommand = htmlspecialchars($_GET["command"], ENT_QUOTES);

    // コマンド文字列作成
    $command = "[sendir]"."sendir"." ".$filename." ".$sendcommand;

    // ソケット通信開始
    $destination = "tcp://remote:29920";
    $socket = stream_socket_client($destination);

    if($socket === false){
        print("Connection faild\n");
        exit();
    }

    // ソケット通信先へコマンドを送信 
    fwrite($socket, $command, strlen($command));

    // コマンドの標準出力を取得する
    $read = fread($socket, 1024);

    $response = array(
        'commandstatus' => $read,
        'filename' => $filename,
        'sendcommand' => $sendcommand
    );
    
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response);
?>