<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Info</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <style>
        head,
        body {
            background-position: center;
            background-attachment: fixed;
        }
        .dano{
            background-color: red;
        }
        .kubo{
            background-color: yellow;
        }
        .mato{
            background-color: blue;
        }
        .miro{
            background-color: green;
        }
        .aron{
            background-color: indigo;
        }
        .img {
            object-fit: cover;
            width: 180px;
            height: 217px;
        }
        .container {
            text-align: center;
            color: black;
            background-color: rgba(163, 163, 163, 0.8);
            width: 80%;
            margin: auto;
            margin-top: 5%;
            padding-bottom: 2%;
            padding-top: 2%;
        }

        .nadpis {
            text-align: center;
            font-size: 36px;
        }

        .tabulka{
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 0.9em;
            min-width: 100%;
            border-radius: 5px 5px 0 0;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.15);
        }
        .tabulka thead tr {
            background-color: slategray;
        }
        .tabulka th{
            padding: 12px 15px;
        }

        .tabulka tr{
            border-bottom: 1px solid white;
        }
        .tabulka tbody tr:nth-of-type(even){
            background-color: silver;
        }
        .tabulka tbody tr:last-of-type{
            border-bottom: 2px solid slategray;
        }
    </style>
</head>
<body>
<h2 class='row justify-content-center'>Technick?? dokument??cia projektu</h2>
<br>

<table class="table table-striped">
    <tbody>
    <tr>
        <th>dev_server</th>
        <td>https://wt79.fei.stuba.sk/skuska/</td>

    </tr>
    <tr>
        <th>phpMyAdmin</th>
        <td>https://wt79.fei.stuba.sk/phpmyadmin/</td>

    </tr>
    <tr>
        <th>username</th>
        <td>user</td>
    </tr>
    <tr>
        <th>password</th>
        <td>SkuskazWebov</td>
    </tr>
    <tr>
        <th>db_name</th>
        <td>skuska</td>
    </tr>
    </tbody>
</table>
<div class="nadpis">N???? realiza??n?? t??m: </div>
<div class="container">
    <div class="row">
        <div class="col">
            <img src="dano.jpg " class="img"></a>
            <p>Daniel <br> No????k</p>
        </div>
        <div class="col">
            <img src="kubo.jpg " class="img"></a>
            <p>Jakub <br> Petr??k</p>
        </div>
        <div class="col">
            <img src="martin.jpg " class="img"></a>
            <p>Martin <br> ??of????k</p>
        </div>
        <div class="col">
            <img src="miro.jpg " class="img"></a>
            <p>Miroslav <br> Kopeck??</p>
        </div>
        <div class="col">
            <img src="images/aron.jpg " class="img"></a>
            <p>B??lint ??ron  <br> Zaj??c</p>
        </div>
    </div>

    Zoznam pr??c:
    <div class="div2">
        <table class="tabulka">
            <thead>
            <tr>
                <th> </th>
                <th>Daniel</th>
                <th>Jakub</th>
                <th>Martin</th>
                <th>Miroslav</th>
                <th>??ron</th>
            </tr>
            </thead>
            <tbody>

            <tr>
                <th>Prihlasovanie sa do aplik??cie</th>
                <th></th>
                <th></th>
                <th></th>
                <th class="miro"></th>
                <th></th>
            </tr>
            <tr>
                <th>Realiz??cia ot??zok sviacer??mi odpove??ami</th>
                <th></th>
                <th></th>
                <th></th>
                <th  class="miro">
                <th></th>
                </th>
            </tr>
            <tr>
                <th>Realiz??cia ot??zok skr??tkymi odpove??ami </th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="aron"></th>
            </tr>
            <tr>
                <th>Realiz??cia p??rovac??ch ot??zok</th>
                <th class="dano"></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th>Realiz??cia ot??zok skreslen??m</th>
                <th></th>
                <th class="kubo"></th>
                <th></th>
                <th></th>
                <th class="aron"></th>
            </tr>
            <tr>
                <th>Realiz??cia ot??zok smatematick??m v??razom</th>
                <th></th>
                <th></th>
                <th class="mato"></th>
                <th></th>
                <th></th>
            </tr>

            <tr>
                <th>Ukon??enie testu</th>
                <th class="dano"></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th>Mo??nos?? zadefinovania viacer??ch testov, ich aktiv??cia a deaktiv??cia</th>
                <th class="dano"></th>
                <th></th>
                <th></th>
                <th class="miro"></th>
                <th class="aron"></th>
            </tr>
            <tr>
                <th>Info pre u??ite??a ozbiehan?? testov</th>
                <th></th>
                <th class="kubo"></th>
                <th></th>
                <th  class="miro"></th>
                <th></th>
            </tr>
            <tr>
                <th>Export do pdf</th>
                <th></th>
                <th class="kubo"></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th>Export do csv</th>
                <th class="dano"></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th>Docker bal????ek</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="aron"></th>

            </tr>
            <tr>
                <th>Finaliz??cia aplik??cie</th>
                <th class="dano"></th>
                <th class="kubo"></th>
                <th class="mato"></th>
                <th class="miro"></th>
                <th class="aron"></th>

            </tr>
            </tbody>
        </table>
    </div>
</div>
<h3 class='row justify-content-center'>Pou??it?? kni??nice:</h3>
<ul class="list-group">
    <li class="list-group-item">style https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css</li>
    <li class="list-group-item">Sortable | jQuery UI</li>
    <li class="list-group-item">CANVAS JavaScript Drawing App from BananaCoding</li>
    <li class="list-group-item">PDF conventor - https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js</li>
    <li class="list-group-item">Mathquill</li>
</ul>
<br>
</body>
</html>