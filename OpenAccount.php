<?php

require_once("./BankAccountController.php");

if (!empty($_POST['name']) && !empty($_POST['type']) && isset($_REQUEST['send'])) {
    $controller = new BankAccountController();
    $bank = $controller->openAccount($_POST["name"], $_POST['type']);
}
if (!empty($_POST['deposit']) && isset($_REQUEST['send'])) {
    $controller->cashDeposit($bank, $_POST["deposit"]);
}
?>
<html>
<title>Criação de conta</title>

<body>
    <form method="POST">
        <label>Nome do Proprietário:</label>
        <input type="text" name="name"><br>

        <label for="type"> Tipo da conta:</label>
        <select name="type">
            <option></option>
            <option value="CC">Conta Corrente</option>
            <option value="CP">Conta Poupança</option>
        </select><br>

        <input type="submit" name="send">
    </form>
    <form method="POST">
        <label>Deposito:</label>
        <input type="text" name="deposit"><br>
        <input type="submit" name="send">
    </form>

    <?php
    if (isset($bank)) {
        echo "Nome: " . $bank->getName() . "</br>";
        echo "Numero da conta: " . $bank->getAccountNumber() . "</br>";
        if ($bank->getType() === "CC") {
            echo "Tipo: Conta Corrente<br>";
        } else if ($bank->getType() === "CP") {
            echo "Tipo: Conta Poupança<br>";
        }
        //echo "Tipo: " . $bank->getType() . "</br>";
        echo "Saldo: " . $bank->getBalance() . "</br>";
        if ($bank->getIsOpen()) {
            echo "Status: Ativo";
        } else {
            echo "Status: Desativado";
        }
    }
    ?>
</body>

</html>