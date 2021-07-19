<?php
require_once("BankAccount.php");

class BankAccountController
{
    public function openAccount($name, $type)
    {
        if (empty($name) || empty($type)) return;
        $bankAccount = new BankAccount();
        $bankAccount->setName($name);
        $bankAccount->setType($type);
        $bankAccount->setAccountNumber(date("Y") . intval(mt_rand(0001, 9999)));

        $bankAccount->setName($_POST["name"]);
        if ($bankAccount->getType() === "CC") {
            $bankAccount->setBalance(50.00);
        } else if ($bankAccount->getType() === "CP") {
            $bankAccount->setBalance(150.00);
        }

        $bankAccount->setIsOpen(true);

        $url = 'assets/bank-account.json';
        //$json = file_get_contents($url);
        //$array = json_decode($json);
        $array = json_encode($bankAccount, JSON_PRETTY_PRINT);
        print_r($bankAccount);
        print_r($array . "<br>");

        $fp = fopen($url, 'a+');
        fwrite($fp, $array);
        fclose($fp);

        return $bankAccount;
    }

    public function cashDeposit($bankAccount, $value)
    {
        return $bankAccount->setBalance($bankAccount->getBalance() + $value);
    }

    public function casWithdraw($bankAccount, $value)
    {
        if ($value > $bankAccount->getBalance) {
            echo "Saldo insuficiente!";
            return;
        }
        return $bankAccount->setBalance($bankAccount->getBalance() - $value);
    }

    public function montlyPayment()
    {
    }

    public function checkAccount($bankAccount)
    {
    }

    public function closeAccount()
    {
    }
}
