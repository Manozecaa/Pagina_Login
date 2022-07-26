<?php

require ('config/conexao.php'); //fazendo a conexao com o banco de dados

//verificar se a postagem existe de acordo com os campos
if (isset ($_POST['nome_completo']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['repete_senha'])){

    //verificar se todos os campos foram preenchidos
    if(empty($_POST['nome_completo']) or empty($_POST['email']) or  empty($_POST['senha']) or empty($_POST['repete_senha'])){
        $erro_geral = "Todos os campos são obrigatorios!";

    }else {
        //recebe os valosres do POST e os limpa
        $nome = limparPost($_POST['nome_completo']);
        $email = limparPost($_POST['email']);
        $senha = limparPost($_POST['senha']);
        $senha_cript = sha1($senha);//criptografa a senha passada pelo usuario
        $repete_senha = limparPost($_POST['repete_senha']);
        $checkbox = limparPost($_POST['termos']);

        //faz a validação do campo de nome
        if (!preg_match("/^[a-zA-Z-' ]*$/",$nome)) {
            $erro_nome = "Somente permitido letras e espaços em branco!";
        }

        //faz a validação do email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erro_email= "Formato de email invalido!";
        }

        //verifica se senha tem mais de 6 digitos
        if (strlen($senha) < 6 ){
            $erro_senha = "Senha deve ter mais de 6 caracteres!";
        }

        //verifica se senhas sao iguais
        if($senha !== $repete_senha){
            $erro_repete_senha = "As senhas não são iguais!";
        }

        //verifica se o checkbox foi marcado
        if($checkbox !== "ok"){
            $erro_checkbox = "Desativado";
        }

        if (!isset($erro_geral) && !isset($erro_nome) && !isset($erro_email) && !isset($erro_senha) && !isset($erro_repete_senha) && !isset($erro_checkbox)){

            // verifica o usuario ja esta cadastrado no banco
            $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email=? LIMIT 1");
            $sql->execute(array($email));
            $usuario = $sql->fetch();

            //se nao existir o usuario, adicionar no banco
            if(!$usuario){
                $recupera_senha = "";
                $token = "";
                $status = "novo";
                $data_cadastro = date("d/m/Y");
                $sql = $pdo->prepare("INSERT INTO usuarios VALUES (null, ?, ?, ?, ?, ?, ?, ?)");
                if ($sql->execute(array($nome, $email, $senha_cript, $recupera_senha, $token, $status, $data_cadastro))){
                    header('location: index.php?result=ok');
                }

            }else{//ja existe usuario, apresentar erro
                $erro_geral = "Usuario ja cadastrado!";
            }
        }
    }
}

?>


<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/style.css" rel="stylesheet">
    <link  rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <title>Cadastrar</title>
</head>
<body>

    <form method="post">
        <h1>Cadastrar</h1>

        <?php if(isset($erro_geral)){ ?>
            <div class="erro-geral animate__animated animate__bounce">
               <?php echo $erro_geral ?>
            </div>
        <?php } ?>

        <div class="input-group ">
            <img class="input-icon" src="icons/nome.png" >
            <input  <?php if(isset($erro_geral )or isset($erro_nome)){echo 'class = "erro_input" ';}?> name="nome_completo" type="text" placeholder="Nome Completo" <?php if (isset($_POST["nome_completo"])){echo "value='" .$_POST['nome_completo']. "'";}?> required>
            <?php if(isset($erro_nome)){?>
            <div class="erro"><?php echo  $erro_nome; ?></div>
            <?php } ?>
        </div>
        <div class="input-group">
            <img class="input-icon" src="icons/email.png">
            <input  <?php if(isset($erro_geral) or isset($erro_email)){echo 'class = "erro_input" ';}?> name="email" type="email" placeholder="Seu melhor email" <?php if (isset($_POST["email"])){echo "value='" .$_POST['email']. "'";}?> required>
            <?php if(isset($erro_email)){?>
            <div class="erro"><?php echo  $erro_email; ?></div>
            <?php } ?>
        </div>
        <div class="input-group">
            <img class="input-icon" src="icons/password.png">
            <input  <?php if(isset($erro_geral) or isset($erro_senha)){echo 'class = "erro_input" ';}?> name="senha" type="password" placeholder="Digite uma senha de 6 digitos" <?php if (isset($_POST["senha"])){echo "value='" .$_POST['senha']. "'";}?> required>
            <?php if(isset($erro_senha)){ ?>
            <div class="erro"><?php echo  $erro_senha; ?></div>
            <?php } ?>
        </div>
        <div class="input-group">
            <img class="input-icon" src="icons/confirm-senha.png">
            <input  <?php if(isset($erro_geral) or isset($erro_repete_senha)){echo 'class = "erro_input" ';}?> name="repete_senha" type="password" placeholder="Confirme sua Senha"  <?php if (isset($_POST["repete_senha"])){echo "value='" .$_POST['repete_senha']. "'";}?> required>
            <?php if(isset($erro_repete_senha)){ ?>
            <div class="erro"><?php echo  $erro_repete_senha; ?></div>
            <?php } ?>
        </div>

        <div  <?php if(isset($erro_geral) or isset($erro_checkbox)){echo 'class = " input-group erro_input" ';}else {echo 'class= "input_group" ';}?> >
            <input type="checkbox" id="termos" name="termos" value="ok" required>
            <label for="termos">Ao se cadastrar você concorda com a nossa <a class="link" href="#">Politica de Privacidade</a>  e <a class="link" href="#">Termos de uso</a>.</label>
        </div>

        <button class="btn-blue" type="submit">Cadastrar</button>
        <a href="index.php">Ja tem cadastro?</a>

    </form>

</body>
</html>