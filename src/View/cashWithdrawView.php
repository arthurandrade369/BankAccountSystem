<?php
require_once("../../config/connection-db.php");
require_once("../Controller/BankAccountController.php");

if (!empty($_POST['accountNumber']) && !empty($_POST['value']) && isset($_REQUEST['send'])) {
    $controller = new BankAccountController();
    $controller->cashWithdraw($_POST['accountNumber'],$_POST['value']);
}

?>
<html>

<body>

    <form method="POST">
        <label>Numero da conta:</label>
        <input type="text" name="accountNumber"><br>
        <label>Valor do saque:</label>
        <input type="text" name="value"><br>

        <input type="submit" name="send">
    </form>
    
    <form action="/public/index.php">
        <input type="submit" value="Voltar">
    </form>


</body>

</html>