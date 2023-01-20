<?php
if ($_POST) {
    if ($_POST['jmeno'] == '' || $_POST['prijmeni'] == '' || $_POST['vek'] == '' || $_POST['telefon'] == '') {
        $zprava = '<div class="alert alert-danger text-center">Zkontrolujte prosím ÚPLNOST zadávaných údajů</div>';
    } elseif (
        !preg_match('/^[\p{L}-]+$/u', $_POST['jmeno']) ||
        !preg_match('/^[\p{L}-]+$/u', $_POST['prijmeni']) ||
        !filter_var($_POST['vek'], FILTER_VALIDATE_INT) || $_POST['vek'] < 0 ||
        !preg_match('/^(\d{3}\s){2}\d{3}|\d{3}\s+$/', $_POST['telefon'])
    ) {
        $zprava = '<div class="alert alert-danger text-center">Zkontrolujte prosím SPRÁVNOST zadávaných údajů</div>';
    } else {
        require_once('Db.php');
        Db::connect('127.0.0.1', 'pojisteni_min', 'root', '');

        Db::query('
            INSERT INTO pojistenci (jmeno, prijmeni, vek, telefon)
            VALUES (?, ?, ?, ?)
            ', $_POST['jmeno'], $_POST['prijmeni'], $_POST['vek'], $_POST['telefon']);
        header('Location:?');
    }
} else {
    $zprava = '';
}
?>

<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <meta name="author" content="Pavel Caska" />
    <meta name="keywords" content="ITnetwork, projekt, minimum" />
    <meta name="description" content="Projekt v minimálním rozsahu pro rekvalifikační kurz PHP developer" />
    <title>Pojištěnci</title>
    <!-- test Gitu -->
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
            <!-- Brand/logo -->
            <a class="navbar-brand" href="#">PojištěníApp</a>
            <!-- Links -->
            <ul class="navbar-nav ml-5">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Pojištěnci</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">O aplikaci</a>
                </li>
            </ul>
        </nav>
    </header>
    <div class="container">

        <h2 class="py-lg-5">Pojištěnci</h2>

        <?php
        require_once('Db.php');
        Db::connect('127.0.0.1', 'pojisteni_min', 'root', '');
        $pojistenci = Db::queryAll('
        SELECT *
        FROM pojistenci
        ');
        echo ('<table class="table table-bordered table-hover table-responsive-sm">');
        echo ('<thead>
        <th>Jméno a příjmení</th>
        <th>Telefon</th>
        <th>Věk</th>
        </thead>
        <tbody>');
        foreach ($pojistenci as $pojistenec) {
            echo ('<tr><td>' . htmlspecialchars($pojistenec['jmeno']) . ' ' . htmlspecialchars($pojistenec['prijmeni']));
            echo ('</td><td>' . htmlspecialchars($pojistenec['telefon']));
            echo ('</td><td>' . htmlspecialchars($pojistenec['vek']));
            echo ('</td></tr>');
        }
        echo ('</tbody></table>');
        ?>

        <h2 class="py-lg-5">Nový pojištěnec</h2>
        <?php
        echo $zprava;

        if ($_POST) {
            $jmeno = (isset($_POST['jmeno'])) ? $_POST['jmeno'] : '';
            $prijmeni = (isset($_POST['prijmeni'])) ? $_POST['prijmeni'] : '';
            $vek = (isset($_POST['vek'])) ? $_POST['vek'] : '';
            $telefon = (isset($_POST['telefon'])) ? $_POST['telefon'] : '';
        } else {
            $jmeno = '';
            $prijmeni = '';
            $vek = '';
            $telefon = '';
        }
        ?>


        <div class="row">
            <div class="col-md-2">
                <img src="./person-icon.png" class="img-fluid img-thumbnail" alt="Symbol osoby">
            </div>
            <form class="col" method="post">
                <div class="d-sm-flex">
                    <div class="form-group col-md-6">
                        <label for="jmeno">Jméno</label>
                        <input type="text" class="form-control" id="jmeno" name="jmeno" placeholder="Jan" value="<?= htmlspecialchars($jmeno) ?>" />
                        <!-- Placeholder jsem použil proto, aby stránka odpovídala zadání. Jinak dává větší smysl použít buď placeholder, nebo uživatelské vstupy, nikoli však obojí najednou... -->
                    </div>
                    <div class="form-group col-md-6">
                        <label for="prijmeni">Příjmení</label>
                        <input type="text" class="form-control" name="prijmeni" id="prijmeni" placeholder="Novák" value="<?= htmlspecialchars($prijmeni) ?>" />
                    </div>
                </div>

                <div class="d-sm-flex">
                    <div class="form-group col-md-6">
                        <label for="vek">Věk</label>
                        <input type="number" class="form-control" id="vek" name="vek" placeholder="31" value="<?= htmlspecialchars($vek) ?>" />
                    </div>
                    <div class="form-group col-md-6">
                        <label for="telefon">Telefon</label>
                        <input type="text" class="form-control" id="telefon" name="telefon" placeholder="731 584 972" value="<?= htmlspecialchars($telefon) ?>" />
                    </div>
                </div>
                <div class="row justify-content-center">
                    <input type="submit" class="btn btn-primary px-5" value="Uložit">
                </div>
            </form>
        </div>
    </div>
</body>

</html>