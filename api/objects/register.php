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

    private $documentoFoto;
    private $documentoVinculo;


    private $dataRegistro;
    private $dataHomologado;

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
                    $value =  preg_replace("/[^a-zA-ZáàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ\s]/", "", $value);
                    $value = ucwords($value);
                    break;
                case "rg":
                    $value = preg_replace("/[^0-9]/", "", $value);
                    break;
                case "cpf":
                    $value = preg_replace("/[^0-9]/", "", $value);

                    if (strlen($value) != 11 || preg_match('/(\d)\1{10}/', $value)) {
                        $value = null;
                        break;
                    }

                    for ($t = 9; $t < 11; $t++) {
                        for ($d = 0, $c = 0; $c < $t; $c++) {
                            $d += $value[$c] * (($t + 1) - $c);
                        }
                        $d = ((10 * $d) % 11) % 10;
                        if ($value[$c] != $d) {
                            $value = null;
                            break;
                        }
                    }

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

    public function getCPF()
    {
        return $this->cpf;
    }

    public function getName()
    {
        return $this->nome;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function get_pending_registers()
    {
        $query = "SELECT registro.id, registro.nome, cpf, rg, orgaoEmissor, sexo.nome AS sexo, estado.nome AS estado, matricula, email, ddd, telefone, curso, atuacao.nome AS atuacao, municipioReside, localAtuacao, documentoFoto, documentoVinculo, dataRegistro FROM registro JOIN estado ON estado.id = registro.estado JOIN sexo ON sexo.id = registro.sexo JOIN atuacao ON registro.atuacao = atuacao.id WHERE homologado IS NULL ORDER BY dataRegistro ASC";

        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            return $stmt;
        }

        return false;
    }

    public function get_accepted_registers()
    {
        $query = "SELECT registro.id, registro.nome, cpf, rg, orgaoEmissor, sexo.nome AS sexo, estado.nome AS estado, matricula, email, ddd, telefone, curso, atuacao.nome AS atuacao, municipioReside, localAtuacao, documentoFoto, documentoVinculo, dataRegistro FROM registro JOIN estado ON estado.id = registro.estado JOIN sexo ON sexo.id = registro.sexo JOIN atuacao ON registro.atuacao = atuacao.id WHERE homologado='1' ORDER BY dataRegistro ASC";

        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            return $stmt;
        }

        return false;
    }

    public function get_rejected_registers()
    {
        $query = "SELECT registro.id, registro.nome, cpf, rg, orgaoEmissor, sexo.nome AS sexo, estado.nome AS estado, matricula, email, ddd, telefone, curso, atuacao.nome AS atuacao, municipioReside, localAtuacao, documentoFoto, documentoVinculo, dataRegistro FROM registro JOIN estado ON estado.id = registro.estado JOIN sexo ON sexo.id = registro.sexo JOIN atuacao ON registro.atuacao = atuacao.id WHERE homologado='0' ORDER BY dataRegistro ASC";

        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            return $stmt;
        }

        return false;
    }

    public function accept_register()
    {
        $query = "UPDATE registro SET homologado = '1', dataHomologado = CURRENT_TIMESTAMP WHERE registro.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);


        if ($stmt->execute()) {
            return true;
        }

        return false;
    }


    public function reject_register()
    {
        $query = "UPDATE registro SET homologado = '0' WHERE registro.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function new_register()
    {
        // query to insert record
        $query = "INSERT INTO registro (nome, orgaoEmissor, curso, municipioReside, localAtuacao, cpf, rg, matricula, email, ddd, telefone, sexo, estado, atuacao, documentoFoto, documentoVinculo) VALUES (:nome, :orgaoEmissor, :curso, :municipioReside, :localAtuacao, :cpf, :rg, :matricula, :email, :ddd, :telefone, :sexo, :estado, :atuacao, :documentoFoto, :documentoVinculo)";


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
        $stmt->bindParam(":documentoFoto", $this->documentoFoto);
        $stmt->bindParam(":documentoVinculo", $this->documentoVinculo);

        try {
            if ($stmt->execute()) {
                return [true, "genericSuccess"];
            }
            return [false, getMessage("genericFailure")];
        } catch (Exception $e) {
            $message = "";
            echo var_dump($e);
            if (strpos($e->getMessage(), "Duplicate") && strpos($e->getMessage(), "cpf")) {
                $message = "CPF já existe na base de dados! Caso não tenha feito cadastro, entre em contato conosco!";
            } else {
                $message = "Dado preenchidos de forma incorreta! Por favor, revise o formulário.";
            }
            return [false, $message];
        }
    }
}
