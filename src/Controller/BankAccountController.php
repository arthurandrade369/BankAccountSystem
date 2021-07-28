<?php
require_once("../Entity/BankAccount.php");
require_once("../../config/connection-db.php");

class BankAccountController
{
    // private $pdo;

    // public function __construct($pdo)
    // {
    //     $this->pdo = $pdo;
    // }
    public function openAccount($name, $type)
    {
        if (empty($name) || empty($type)) return;
        $bankAccount = new BankAccount();
        $bankAccount->setName($name);
        $bankAccount->setType($type);
        $bankAccount->setAccountNumber(intval(mt_rand(1000, 9999)));
        $bankAccount->setName($_POST["name"]);
        if ($bankAccount->getType() === "CC") {
            $bankAccount->setBalance(50.00);
        } else if ($bankAccount->getType() === "CP") {
            $bankAccount->setBalance(150.00);
        }
        $bankAccount->setIsOpen(true);

        $accountNumber = $bankAccount->getAccountNumber();
        $balance = $bankAccount->getBalance();
        $isOpen = $bankAccount->getIsOpen();

        try {
            $sql = "INSERT INTO account VALUES (:account, :name, :type,:balance, :isOpen)";
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":account", $accountNumber);
            $p_sql->bindValue(":name", $name);
            $p_sql->bindValue(":type", $type);
            $p_sql->bindValue(":balance", $balance);
            $p_sql->bindValue(":isOpen", $isOpen);
            $p_sql->execute();
            echo "Cadastrado com successo!<br>";
            echo "Sua conta é: " . $accountNumber . "!<br>";
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e;
        }
        self::showAccount($bankAccount);
    }

    public function showAccount($accountNumber)
    {
        try {
            $sql = "SELECT * FROM account WHERE accountnumber = :account LIMIT 1";
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":account", $accountNumber);
            $p_sql->execute();
            $aws = $p_sql->fetch(PDO::FETCH_ASSOC);
            if ($aws) {
                echo "Nome do cliente: " . $aws['name'] . "<br>";
                if ($aws['type'] === "CC") {
                    echo "Tipo de conta: Conta Corrente<br>";
                } else {
                    echo "Tipo de conta: Conta Poupança<br>";
                }
                echo "Saldo: R$" . $aws['balance'] . "<br>";
                if ($aws['isopen']) {
                    echo "Status: Ativo<br>";
                } else {
                    echo "Status: Inativo<br>";
                }
            } else {
                echo "Erro: Conta inexistente.";
            }
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e;
        }
    }

    public function cashDeposit($accountNumber, $value)
    {
        try {
            $sql = "SELECT * FROM account WHERE accountnumber = :account LIMIT 1";
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":account", $accountNumber);
            $p_sql->execute();
            $aws = $p_sql->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die($sql . "<br>" . $e);
        }
        if ($aws) {
            try {
                $sql = "UPDATE account SET balance = balance + :value WHERE accountnumber = :account LIMIT 1";
                $p_sql = Connection::getInstance()->prepare($sql);
                $p_sql->bindValue(":value", $value);
                $p_sql->bindValue(":account", $accountNumber);
                $p_sql->execute();
                echo "Deposito realizado com sucesso!<br>";
            } catch (PDOException $e) {
                echo $sql . "<br>" . $e;
            }
            self::showAccount($accountNumber);
        } else {
            echo "Error: Conta não existe";
        }
    }

    public function cashWithdraw($accountNumber, $value)
    {
        try {
            $sql = "SELECT * FROM account WHERE accountnumber = :account LIMIT 1";
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":account", $accountNumber);
            $p_sql->execute();
            $aws = $p_sql->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e;
        }
        if ($aws['isopen']) {
            if ($value > $aws['balance']) {
                echo "Saldo insuficiente!";
            } else {
                try {
                    $sql = "UPDATE account SET balance = balance - :value WHERE accountnumber = :account LIMIT 1";
                    $p_sql = Connection::getInstance()->prepare($sql);
                    $p_sql->bindValue(":value", $value);
                    $p_sql->bindValue(":account", $accountNumber);
                    $p_sql->execute();
                    echo "Saque realizado com sucesso!<br>";
                } catch (PDOException $e) {
                    echo $sql . "<br>" . $e;
                }
                self::showAccount($accountNumber);
            }
        } else {
            echo "Conta Inativa!";
        }
    }

    public function montlyPayment($accountNumber)
    {
        try {
            $sql = "SELECT * FROM account WHERE accountnumber = :account LIMIT 1";
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":account", $accountNumber);
            $p_sql->execute();
            $aws = $p_sql->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e;
        }
        if ($aws) {
            if ($aws['isopen']) {
                switch ($aws['type']) {
                    case 'CC':
                        $balance = 12;
                        break;
                    case 'CP':
                        $balance = 20;
                        break;
                    default:
                        return "Tipo de conta invalida!";
                        break;
                }
                try {
                    $sql = "UPDATE account SET balance = balance - $balance WHERE accountnumber = :account LIMIT 1";
                    $p_sql = Connection::getInstance()->prepare($sql);
                    $p_sql->bindValue(":account", $accountNumber);
                    $p_sql->execute();
                    echo "Pagamento realizado com sucesso<br>";
                } catch (PDOException $e) {
                    echo $sql . "<br>" . $e;
                }
                self::showAccount($accountNumber);
            } else {
                return "Conta Inativa!";
            }
        } else {
            echo "Erro: Conta inexistente.";
        }
    }

    public function closeAccount($accountNumber)
    {
        try {
            $sql = "SELECT * FROM account WHERE accountnumber = :account LIMIT 1";
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":account", $accountNumber);
            $p_sql->execute();
            $aws = $p_sql->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e;
        }
        if ($aws) {
            if ($aws['balance'] > 0) {
                echo "Impossivel fechar conta! <br> Ainda possui saldo.";
            } elseif ($aws['balance'] < 0) {
                echo "Impossivel fechar conta! <br> Ainda resta pendencia na conta.";
            } else {
                try {
                    $sql = "UPDATE account SET isopen = 0 WHERE accountnumber = :account LIMIT 1";
                    $p_sql = Connection::getInstance()->prepare($sql);
                    $p_sql->bindValue(":account", $accountNumber);
                    $p_sql->execute();
                    echo "Situação alterada para Inativa!<br>";
                } catch (PDOException $e) {
                    echo $sql . "<br>" . $e;
                }
                self::showAccount($accountNumber);
            }
        } else {
            echo "Error: Conta não existe";
        }
    }

    public function reOpenAccount($accountNumber)
    {
        try {
            $sql = "SELECT * FROM account WHERE accountnumber = :account LIMIT 1";
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":account", $accountNumber);
            $p_sql->execute();
            $aws = $p_sql->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e;
        }
        if (!$aws['isopen']) {
            try {
                $sql = "UPDATE account SET isopen = 1 WHERE accountnumber = :account LIMIT 1";
                $p_sql = Connection::getInstance()->prepare($sql);
                $p_sql->bindValue(":account", $accountNumber);
                $p_sql->execute();
                echo "Situação alterada para Ativa!<br>";
            } catch (PDOException $e) {
                echo $sql . "<br>" . $e;
            }
            self::showAccount($accountNumber);
        } else {
            echo "Conta já está ativa!";
        }
    }
}
