<?php
class ConexaoMysql
{
    private $host = 'localhost';
    //   private $usuario ='root'; 
    private $usuario = 'u555788673_super_admin';
    //  private $senha = 'Pa$$w0rd';
    private $senha = 'Maunze@Conversu@Meld0';
    private $db = 'u555788673_conversu';
    private $conexao;


    public function conectar()
    {
        try {
            $this->conexao = new mysqli($this->host, $this->usuario, $this->senha, $this->db);
            if ($this->conexao->connect_error) {
                throw new Exception("Falha na conexão: " . $this->conexao->connect_error);
            }
        } catch (Exception $e) {
            $this->logError($e->getMessage());
            die("Ocorreu um problema na conexão com a base de dados. Tente novamente mais tarde.");
        }
        return $this->conexao;
    }

    public function fecharConexao()
    {
        $this->conexao->close();
    }

    public function prepare($sql)
    {
        return $this->conexao->prepare($sql);
    }


    public function query($sql)
    {
        return $this->conexao->query($sql);
    }


    private function logError($message)
    {
        error_log($message, 3, 'logfile.log');
    }
}
