<?php

//dois modos possiveis -> local | produção
$modo = 'local';

if ($modo == 'local'){
    //credenciais para o modo local
    $servidor = "localhost";
    $usuario = "root";
    $senha = "";
    $banco = "login";
}

if ($modo == 'producao'){
    //credenciais para o modo de producao
  $servidor = " ";
  $usuario = " ";
  $senha = " ";
  $banco = " ";
}

try{              //tenta a conexao com o banco de dados
    $pdo = new PDO("mysql:host=$servidor;dbname=$banco", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); //variaveis para mostrar erros
    //echo "banco conectado com sucesso";
}catch(PDOException $erro){
    echo "Falha ao se conectar ao banco!!";
}


function limparPost($dados){
    $dados = trim($dados);//o trim limpa os espaçoes em brancos
    $dados = stripslashes($dados);//tira as barras
    $dados = htmlspecialchars($dados);//tira os caracteres especiais do html
    return $dados;
}
?>