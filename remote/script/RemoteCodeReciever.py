# ソケット通信し、受信したコマンドを実行する

import socket
import subprocess
import os
import json


with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
    # IPアドレスとポートを指定
    s.bind(('remote', 29920))
    
    # 接続する
    s.listen(1)

    # 接続するまでループする
    while True:
        # アクセスを受信した場合、コネクションとアドレスを取得する
        conn, addr = s.accept()
        with conn:
            while True:
                # 受信したコマンド文字列を取得する
                commandData = conn.recv(1024)
                if not commandData:
                    break
                print('received_commandData : {}, addr: {}'.format(commandData, addr))

                # バイト文字列を文字列型にデコードする
                commandDataText = commandData.decode('utf-8')
                print('received_commandData : {}, addr: {}'.format(commandDataText, addr))

                # 赤外線送信コマンド要求
                if '[sendir]' in commandDataText:
                    # 先頭の識別子を除外する
                    commandDataText = commandDataText.replace("[sendir]", "")

                    # 赤外線送信コマンドを実行する
                    p = subprocess.Popen(commandDataText, stdout=subprocess.PIPE, shell=True)
                    out = p.stdout.read()                
                    print('commandData : {}, out: {}'.format(commandDataText, out))

                    # 末端の改行コードを除去する
                    out = out.rstrip()

                    # 標準出力がない場合は「success」を格納する
                    out = b'success' if len(out) == 0 else out

                    # 送信先に値を返却する
                    conn.sendall(out)

                # 赤外線ファイル読み込み要求
                elif '[loadfiles]' in commandDataText:
                    # ファイル一覧を取得する
                    files = [filename for filename in os.listdir("/remote/source/") if not filename.startswith('.')]

                    # 「,」区切りで結合する
                    fileListText = ','.join(files)
                    print('fileListText : {}'.format(fileListText))
                    
                    # 送信先に値を返却する
                    conn.sendall(fileListText.encode('utf-8'))

                # 赤外線ファイルコマンド名読み込み要求
                elif '[loadsendcommands]' in commandDataText:

                    # 先頭の識別子を除外する
                    commandDataText = commandDataText.replace("[loadsendcommands]", "")

                    fileName = commandDataText

                    #JSON形式で読み込む
                    f = open('/remote/source/' + fileName, 'r')
                    json_data = json.load(f)

                    commandNameList = []
                    for key in json_data:
                        commandNameList.append(key)

                    # 送信先に値を返却する
                    commandNameListText = ','.join(commandNameList)
                    conn.sendall(commandNameListText.encode('utf-8'))

                # 赤外線登録コマンド要求
                if '[addir]' in commandDataText:
                    # 先頭の識別子を除外する
                    commandDataText = commandDataText.replace("[addir]", "")

                    # 赤外線送信コマンドを実行する
                    p = subprocess.Popen(commandDataText, stdout=subprocess.PIPE, shell=True)
                    out = p.stdout.read()   
                    rtn = p.wait()             
                    print('commandData : {}, out: {}, rtn: {}'.format(commandDataText, out, rtn))

                    # 末端の改行コードを除去する
                    out = out.rstrip()

                    # 標準出力がない場合は「success」を格納する
                    out = b'success' if rtn == 0 else b'error'

                    # 送信先に値を返却する
                    conn.sendall(out)