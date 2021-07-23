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
        // $bankAccount->setAccountNumber(date("Y") . intval(mt_rand(001, 999)));
        $bankAccount->setName($_POST["name"]);
        if ($bankAccount->getType() === "CC") {
            $bankAccount->setBalance(50.00);
        } else if ($bankAccount->getType() === "CP") {
            $bankAccount->setBalance(150.00);
        }
        $bankAccount->setIsOpen(true);

        $balance = $bankAccount->getBalance();
        $isOpen = $bankAccount->getIsOpen();

        try {
            $sql = "INSERT INTO account (name,type,balance,isopen) VALUES ('$name', '$type','$balance', '$isOpen')";
            $p_sql = Connection::getInstance();
            $p_sql->exec($sql);
            // $p_sql->bindValue(":name", $name);
            // $p_sql->bindValue(":type", $type);
            // $p_sql->bindValue(":balance", $balance);
            // $p_sql->bindValue(":isOpen", $isOpen);

            echo "Cadastrado com successo!";
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e;
        }

        // $url = 'assets/bank-account.json';

        // $array = [];

        // $json = fopen($url, 'r');
        // fclose($json);

        // if ($json) {
        //     $json = file_get_contents($url);
        //     $array[] = json_decode($json);
        //     echo $array . "<br>";
        //     array_push($array, $json);
        //     echo $array . "<br>";
        // }
        // $array = (array) $bankAccount;
        // $array = json_encode($array, JSON_PRETTY_PRINT);
        // // echo $array . "<br>";

        // $fp = fopen($url, 'a+') or die("Nao foi possivel abrir o arquivo!");
        // fwrite($fp, $array);
        // fclose($fp);

        return $bankAccount;
    }

    public function showAccount($accountNumber)
    {
        try {
            $sql = "SELECT * FROM account WHERE accountnumber = '$accountNumber'";
            $aws = $this->pdo->prepare($sql);
            if ($aws->execute()) {
                while ($rs = $aws->fetch(PDO::FETCH_OBJ)) {
                    echo $rs->accountnumber . $rs->name . $rs->type
                        . $rs->balance . $rs->isopen;
                }
            } else {
                echo "Erro: Não foi possível recuperar os dados do banco de dados";
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
