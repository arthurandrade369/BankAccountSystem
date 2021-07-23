<?php
require_once("../../config/connection-db.php");
require_once("../Controller/BankAccountController.php");

if (!empty($_POST['accountNumber']) && isset($_REQUEST['send'])) {
    $controller = new BankAccountController();
    $bank = $controller->showAccount($_POST['accountNumber']);
}

?>
<html>

<body>

    <form method="POST">
        <label>Numero da conta:</label>
        <input type="text" name="accountNumber"><br>

        <input type="submit" name="send">
    </form>

    <form action="/public/index.php">
        <input type="submit" value="Voltar">
    </form>


</body>

</html>