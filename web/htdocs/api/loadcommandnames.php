<?php 
    header('Content-type: text/plain; charset= UTF-8');

    if(!isset($_GET['filename'])){
        print('no filename posted\n');
        exit();
    }

    $filename = htmlspecialchars($_GET["filename"], ENT_QUOTES);
    $command = "[loadsendcommands]".$filename;

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

    echo $read;
    
?>