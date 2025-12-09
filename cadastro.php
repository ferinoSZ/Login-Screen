<?php
include_once 'Conexao.php';
include_once 'classes/Usuario.php';

// Variáveis para controlar o alerta
$alerta_tipo = ""; 
$alerta_mensagem = "";
$redirecionar = false;

if ($_POST) {
    $database = new Conexao();
    $db = $database->getConnection();
    $user = new Usuario($db);

    $user->nome_completo = $_POST['nome_completo'];
    $user->email = $_POST['email'];
    $user->usuario = $_POST['usuario'];
    $user->senha = $_POST['senha'];

   if($user->criar()){
        $alerta_tipo = "success";
        $alerta_mensagem = "Parabéns! Cadastro realizado com sucesso.";
        $redirecionar = true;
    } else {
        // MUDANÇA AQUI:
        $alerta_tipo = "error";
        
        // Se a classe guardou uma mensagem específica, usa ela. 
        // Se não, usa uma genérica.
        if ($user->msg_erro != "") {
            $alerta_mensagem = $user->msg_erro;
        } else {
            $alerta_mensagem = "Erro ao cadastrar!";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <link rel="stylesheet" href="cadastro.css">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <div class="geral">
        <h1>Cadastrar</h1>

        <form action="cadastro.php" method="POST">

            <div class="form">
                <input type="text" name="nome_completo" placeholder="Nome completo" required
                value="<?= htmlspecialchars($_POST['nome_completo'] ?? '') ?>"
                >
                
                <i class="fa-solid fa-user"></i>
            </div>

            <div class="form">
                <input type="email" name="email" placeholder="E-mail" required
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                >

                <i class="fa-solid fa-envelope"></i>
            </div>

            <div class="form">
                <input type="text" name="usuario" placeholder="Usuário" required
                value="<?= htmlspecialchars($_POST['usuario'] ?? '') ?>"
                >

                <i class="fa-solid fa-user-plus"></i>
            </div>

            <div class="form">
                <input type="password" name="senha" placeholder="Senha" required
                value="<?= htmlspecialchars($_POST['senha'] ?? '') ?>"
                >
                <i class="fa-solid fa-lock"></i>
            </div>

            <button class="botao" type="submit">Registrar</button>

            <div class="registro">
                <p>Já tem conta? <a href="login.php">Entrar</a></p>
            </div>

        </form>

    </div>

    <?php if ($alerta_mensagem != ""): ?>
    <script>
        Swal.fire({
            title: '<?php echo $alerta_tipo == "success" ? "Sucesso!" : "Erro!"; ?>',
            text: '<?php echo $alerta_mensagem; ?>',
            icon: '<?php echo $alerta_tipo; ?>', // success, error, warning, info
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        }).then((result) => {
            // Se for sucesso, redireciona ao clicar em OK
            <?php if ($redirecionar): ?>
                if (result.isConfirmed) {
                    window.location.href = 'login.php';
                }
            <?php endif; ?>
        });
    </script>
    <?php endif; ?>
</body>

</html>
