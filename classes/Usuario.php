<?php
class Usuario {
    private $conn;
    private $table_name = "usuarios";

    // Atributos normais
    public $id;
    public $nome_completo;
    public $email;
    public $usuario;
    public $senha;
    
    // NOVO: Variável para guardar a mensagem de erro específica
    public $msg_erro = ""; 

    public function __construct($db) {
        $this->conn = $db;
    }


   // Função LOGIN HÍBRIDO (Aceita E-mail ou Usuário)
    public function login($termo_login, $senha_login) {
        
        // A mágica está aqui: usamos OR (ou) para verificar as duas colunas
        // Usamos :termo duas vezes na query, o PDO do MySQL entende isso
        $query = "SELECT id, nome_completo, usuario, senha 
                  FROM " . $this->table_name . " 
                  WHERE email = :termo OR usuario = :termo 
                  LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpeza básica
        $termo_login = htmlspecialchars(strip_tags($termo_login));
        
        // Vinculamos o que a pessoa digitou ao :termo
        $stmt->bindParam(":termo", $termo_login);
        
        $stmt->execute();
        
        // Verifica se achou alguém
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verifica a senha
            if(password_verify($senha_login, $row['senha'])) {
                
                $this->id = $row['id'];
                $this->nome_completo = $row['nome_completo'];
                $this->usuario = $row['usuario'];
                
                return true;
            }
        }
        
        $this->msg_erro = "Login ou senha inválidos.";
        return false;
    }

    // Função CRIAR atualizada
    public function criar() {
        $query = "INSERT INTO " . $this->table_name . " SET nome_completo=:nome, email=:email, usuario=:usuario, senha=:senha";
        $stmt = $this->conn->prepare($query);

        $this->nome_completo = htmlspecialchars(strip_tags($this->nome_completo));
        $this->senha = password_hash($this->senha, PASSWORD_DEFAULT);

        $stmt->bindParam(":nome", $this->nome_completo);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":usuario", $this->usuario);
        $stmt->bindParam(":senha", $this->senha);

        try {
            if($stmt->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            // Se der erro de duplicidade (código 23000)
            if ($e->getCode() == '23000') {
                $erro_tecnico = $e->getMessage();

                // O PHP procura a palavra 'email' dentro do erro do banco
                if (strpos($erro_tecnico, 'email') !== false) {
                    $this->msg_erro = "Este E-mail já está a ser utilizado!";
                } 
                // O PHP procura a palavra 'usuario' dentro do erro
                elseif (strpos($erro_tecnico, 'usuario') !== false) {
                    $this->msg_erro = "Este Nome de Usuário já existe!";
                } 
                else {
                    $this->msg_erro = "Dados duplicados no sistema!";
                }
                return false;
            } else {
                $this->msg_erro = "Erro técnico: " . $e->getMessage();
                return false;
            }
        }
        return false;
    }


}
?>