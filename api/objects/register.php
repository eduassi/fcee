<?php
class Register
{

    // database connection and table name
    private $conn;

    // object properties
    private $id;

    private $nome;
    private $orgaoEmissor;
    private $curso;

    private $municipioReside;
    private $localAtuacao;

    private $cpf;
    private $rg;
    private $matricula;

    private $email;

    private $ddd;
    private $telefone;

    private $sexo;
    private $estado;
    private $atuacao;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    //// SETTERS
    public function __set($property, $value)
    {
        if (property_exists($this, $property) && (!empty($value) || $value == 0)) {
            $value = htmlspecialchars(strip_tags($value));
            switch ($property) {
                case "nome":
                case "orgaoEmissor":
                case "curso":
                    $value =  preg_replace("/[^a-zA-Z\s]/", "", $value);
                    $value = ucwords($value);
                    break;
                case "rg":
                    $value = preg_replace("/[^0-9]/", "", $value);
                    if (strlen($value) == 11) {
                        $value = substr($value, 0, 3) . "." . substr($value, 3, 3) . "." . substr($value, 6, 3) . "-" . substr($value, 9, 2);
                    }
                    break;
                case "cpf":
                    $value = preg_replace("/[^0-9]/", "", $value);
                    $value = substr($value, 0, 3) . "." . substr($value, 3, 3) . "." . substr($value, 6, 3) . "-" . substr($value, 9, 2);
                    break;
                case "ddd":
                case "telefone":
                    $value = preg_replace("/[^0-9]/", "", $value);
                    break;
                default:
                    break;
            }
            if (empty($value)) {
                $value = NULL;
            }
            $this->$property = $value;
        }
    }


    public function new_register()
    {
        // query to insert record
        $query = "INSERT INTO registro (nome, orgaoEmissor, curso, municipioReside, localAtuacao, cpf, rg, matricula, email, ddd, telefone, sexo, estado, atuacao) VALUES (:nome, :orgaoEmissor, :curso, :municipioReside, :localAtuacao, :cpf, :rg, :matricula, :email, :ddd, :telefone, :sexo, :estado, :atuacao)";


        // prepare query
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":orgaoEmissor", $this->orgaoEmissor);
        $stmt->bindParam(":curso", $this->curso);
        $stmt->bindParam(":municipioReside", $this->municipioReside);
        $stmt->bindParam(":localAtuacao", $this->localAtuacao);
        $stmt->bindParam(":cpf", $this->cpf);
        $stmt->bindParam(":rg", $this->rg);
        $stmt->bindParam(":matricula", $this->matricula);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":ddd", $this->ddd);
        $stmt->bindParam(":telefone", $this->telefone);
        $stmt->bindParam(":sexo", $this->sexo);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":atuacao", $this->atuacao);

        try {
            if ($stmt->execute()) {
                return [true, "genericSuccess"];
            }
            return [false, getMessage("genericFailure")];
        } catch (Exception $e) {
            $message = "";
            if(strpos($e->getMessage(), "Duplicate") && strpos($e->getMessage(), "cpf")){
                $message = "CPF já existe na base de dados!";
            }else{
                $message = "Algum dado preenchido de forma incorreta!";
            }
            return [false, $message];
        }
    }
}
