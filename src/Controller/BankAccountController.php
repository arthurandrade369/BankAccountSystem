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

        return $bankAccount;
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
                echo "Erro: Não foi possível acessar os dados do banco de dados";
            }
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e;
        }
    }

    public function cashDeposit($accountNumber, $value)
    {
        // if ($bankAccount->getIsOpen()) {
        //     if (empty($value)) return;
        //     return $bankAccount->setBalance($bankAccount->getBalance() + $value);
        // } else {
        //     echo "Conta Inativa!";
        // }
    }

    public function casWithdraw($bankAccount, $value)
    {
        if ($bankAccount->getIsOpen()) {
            if ($value > $bankAccount->getBalance()) {
                echo "Saldo insuficiente!";
                return;
            } else {
                return $bankAccount->setBalance($bankAccount->getBalance() - $value);
            }
        } else {
            echo "Conta Inativa!";
        }
    }

    public function montlyPayment($bankAccount)
    {
        if ($bankAccount->getType() == "CC") {
            $bankAccount->setBalance();
        }
    }

    public function closeAccount()
    {
    }
}
