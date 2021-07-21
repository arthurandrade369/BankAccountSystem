<?php
require_once("../Entity/BankAccount.php");
require_once("./config/connection-db.php");;

class BankAccountController
{
    public function openAccount($name, $type)
    {
        if (empty($name) || empty($type)) return;
        $bankAccount = new BankAccount();
        $bankAccount->setName($name);
        $bankAccount->setType($type);
        $bankAccount->setAccountNumber(date("Y") . intval(mt_rand(001, 999)));

        $bankAccount->setName($_POST["name"]);
        if ($bankAccount->getType() === "CC") {
            $bankAccount->setBalance(50.00);
        } else if ($bankAccount->getType() === "CP") {
            $bankAccount->setBalance(150.00);
        }

        $bankAccount->setIsOpen(true);

        

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
        if($bankAccount->getType()=="CC"){
            $bankAccount->setBalance();
        }
    }

    public function closeAccount()
    {
    }
}
