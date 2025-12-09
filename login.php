<?php
session_start();
include_once 'Conexao.php';
include_once 'classes/Usuario.php';

$alerta_tipo = ""; 
$alerta_mensagem = "";
$redirecionar = false;

if ($_POST) {
    $database = new Conexao();
    $db = $database->getConnection();
    $user = new Usuario($db);

    $usuario_form = $_POST['usuario'];
    $senha_form = $_POST['senha'];

    if($user->login($usuario_form, $senha_form)){
        $_SESSION['usuario_logado'] = true;
        // Sucesso
        $alerta_tipo = "success";
        $alerta_mensagem = "Bem-vindo de volta, " . $usuario_form . "!";
        $redirecionar = true;
    } else {
        // Erro
        $alerta_tipo = "error";
        $alerta_mensagem = "Usuário ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <div class="geral">
        <h1>Login</h1>

        <form action="login.php" method="POST">

            <div class="form">
                <input type="text" placeholder="Usuário ou Email" name="usuario" required
                value="<?= htmlspecialchars($_POST['usuario'] ?? '') ?>"
                >

                <i class="fa-solid fa-user"></i>
            </div>

            <div class="form">
                <input type="password" placeholder="Senha" name="senha" required
                value="<?= htmlspecialchars($_POST['senha'] ?? '') ?>"
                >

                <i class="fa-solid fa-lock"></i>
            </div>

            <div class="relembre">
                <label><input type="checkbox">Lembrar de mim</label>
                <a href="#">Esqueci a senha</a>
            </div>

            <button class="botao" type="submit">Entrar</button>

            <div class="registro">
                <p>Não tem conta? <a href="cadastro.php">Registrar</a></p>
            </div>
        </form>

    </div>

    <?php if ($alerta_mensagem != ""): ?>
    <script>
        Swal.fire({
            title: '<?php echo $alerta_tipo == "success" ? "Login Realizado!" : "Atenção!"; ?>',
            text: '<?php echo $alerta_mensagem; ?>',
            icon: '<?php echo $alerta_tipo; ?>',
            background: '#1d1b31', // Um fundo escuro para combinar com seu tema (opcional)
            color: '#fff', // Texto branco
            confirmButtonColor: '#6C63FF', // Cor roxa para combinar com seu botão
            confirmButtonText: 'Continuar'
        }).then((result) => {
            <?php if ($redirecionar): ?>
                if (result.isConfirmed) {
                    window.location.href = 'index.php';
                }
            <?php endif; ?>
        });
    </script>
    <?php endif; ?>

</body>
</html>
