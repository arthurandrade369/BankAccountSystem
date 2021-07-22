<?php
    require_once("../../config/connection-db.php");
    require_once("../Controller/BankAccountController.php");
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

    <?php
    if (!empty(trim($_POST['accountNumber'])) && isset($_REQUEST['send'])) {
        $controller = new BankAccountController($pdo);
        $bank = $controller->showAccount($_POST['accountNumber']);
    }
    ?>

</body>

</html>