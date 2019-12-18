<?php
    

    function TeeHaku($haku)
    {
        $host = "127.0.0.1";  // Tietokannan tiedot
        $user = "root";
        $pass ="";
        $db = "Tyoajanseuranta";

        $conn = new mysqli($host, $user, $pass, $db);

        if ($conn -> connect_errno)
        {
            echo "Virhe yhdistäessä kantaan!";
            exit();
        }

        $q = "SELECT * FROM tyoajanseuranta";

        if ($result = $conn->query($haku))
        {
            $conn->close();
            return $result;
        }
        $conn->close();
        return null;
    }

    function haeTyoaika()
    {
        $q = "SELECT * FROM tyoajanseuranta";

        $result = TeeHaku($q);

        if ($result)
        {
            while($rivi = $result->fetch_assoc())
            {
                echo "<option id='". $rivi["AVAIN"] ."' value='" . $rivi["AVAIN"] ."'>".$rivi["SELITE"]."</option>\n";
            }
        }
        else
        {
            echo "Ei tuloksia!";
        }
    }

    function poistaTyoaika($id)
    {
        $q = "DELETE FROM tyoajanseuranta WHERE AVAIN = '$id'";

        $result = TeeHaku($q);

        if (!$result)
        {
            echo "Poisto ei onnistunut!";
        }
    }

    function poistaTyontekija($id)
    {
        $q = "DELETE FROM tyontekija WHERE Tyontekija_ID = '$id'";

        $result = TeeHaku($q);

        if (!$result)
        {
            echo "Poisto ei onnistunut!";
        }
    }

    function poistaProjekti($id)
    {
        $q = "DELETE FROM projekti WHERE Projektin_ID = '$id'";

        $result = TeeHaku($q);

        if (!$result)
        {
            echo "Poisto ei onnistunut!";
            echo "\n" . $q;
        }
    }

    function haeTyontekijat()
    {
        $q = "SELECT * FROM `tyontekija`";
        
        $result = TeeHaku($q);

        if ($result)
        {
            while($rivi = $result->fetch_assoc())
            {
                echo "<option value=". $rivi["Tyontekija_ID"] .">" . $rivi["Etunimi"] . " " . $rivi["Sukunimi"];
                echo "</option>";
            }
        }
        else
        {
            echo "Ei tuloksia!";
        }
    }

    function lisaaUusiProjekti($nimi)
    {
        $q = "INSERT INTO `projekti` (Projektin_Nimi) VALUES ('$nimi')";
        echo $q;
        $result = TeeHaku($q);
        if ($result)
        {
            echo "Onnistui";
        }
        else
        {
            echo "Ei onnistunut";
        }
    }

    function lisaaUusiTyontekija($etunimi, $sukunimi, $sahkoposti)
    {
        $q = "INSERT INTO `tyontekija` (Etunimi, Sukunimi, Sahkoposti) VALUES ('$etunimi', '$sukunimi', '$sahkoposti')";
        echo $q;
        $result = TeeHaku($q);
        if ($result)
        {
            echo "Onnistui";
        }
        else
        {
            echo "Ei onnistunut";
        }
    }

    function haeProjektit()
    {
        $q = "SELECT * FROM `projekti`";
        
        $result = TeeHaku($q);

        if ($result)
        {
            while($rivi = $result->fetch_assoc())
            {
                echo "<option value=". $rivi["Projektin_ID"] .">" . $rivi["Projektin_Nimi"];
                echo "</option>";
            }
        }
        else
        {
            echo "Ei tuloksia!";
        }
    }

    function haeTyoaikaJaTulosta($tyontekija_id, $projekti_id)
    {
        $q = "SELECT * FROM `tyoajanseuranta` WHERE 1=1";
        if (isset($tyontekija_id) && $tyontekija_id != "Ei valintaa")
        {
            $q = $q . " AND Tyontekija_ID = $tyontekija_id";
        }
        if (isset($projekti_id) && $projekti_id != "Ei valintaa")
        {
            $q = $q . " AND Projekti_ID = $projekti_id";
        }
        
        $result = TeeHaku($q);

        if ($result)
        {
            while($rivi = $result->fetch_assoc())
            {
                echo "<tr>
                <td>". $rivi["Tyoaika_ID"] . "</td>
                <td>" . $rivi["Projekti_ID"] . "</td>
                <td>" . $rivi["Tyontekija_ID"] . "</td>
                <td>" . $rivi["Aloitusaika"] . "</td>
                <td>" . $rivi["Lopetusaika"] . "</td>
                </tr>";
            }
        }
        else
        {
            echo "Ei tuloksia!";
        }
    }

    if (isset($_GET['poista']) && isset($_GET['tyyppi']))
    {
        if ($_GET['tyyppi'] == "projekti")
        {
            poistaProjekti($_GET['poista']);
        }
        else if ($_GET['tyyppi'] == "tyontekija")
        {
            poistaTyontekija($_GET['poista']);
        }
        
        exit();
    }

    

    if (isset($_GET['lisaaUusiProjekti']) && isset($_GET['projektinNimi']))
    {
        lisaaUusiProjekti($_GET['projektinNimi']);
        exit();
    }

    if (isset($_GET['lisaaUusiTyontekija']))
    {
        lisaaUusiTyontekija($_GET['etunimi'], $_GET['sukunimi'], $_GET['sahkoposti']);
    }
        

    if (isset($_GET['lisaaUusiAika']))
    {
        $projekti_id = $_GET['projekti'];
        $tyontekija_id = $_GET['tyontekija'];
        $aloitusaika = $_GET['Aloitusaika'];
        $lopetusaika = $_GET['Lopetusaika'];

        // $aikaleima = date('Y-m-d H:i:s');
        
        $a = date("Y-m-d H:i:s", strtotime($aloitusaika));
        $l = date("Y-m-d H:i:s", strtotime($lopetusaika));

        $q = "INSERT INTO tyoajanseuranta (Projekti_ID, Tyontekija_ID, Aloitusaika, Lopetusaika) VALUES ('$projekti_id', '$tyontekija_id', '$a', '$l')";

        echo $q;
        $result = TeeHaku($q);

        if (!$result)
        {
            echo "Ei onnistunut!";
        }
        else
            echo "Lisätty";

        exit();

    }
?>
<meta charset="utf-8">
<html>
<head>
<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.1/moment.min.js"></script>        
<script src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.4.0/lang/en-gb.js"></script>                
<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.0.0/js/bootstrap-datetimepicker.min.js"></script>

<script>

$(function()
{
    $('#lisaatyoaika').click(function (e) { 
        e.preventDefault();
        $('#tyoaika_ikkuna').modal();
    });

    $('#tallennaTyoaikaNappi').click(function(e)
    {
        e.preventDefault();
        
        var d = $('#lisaaaikaFormi').serialize();
        console.log(d);
        $.ajax({
            url: "Tyoajanseuranta.php",
            data: d,
            success: function(result)
            {
                // location.reload();
                // console.log(result);
            }
        });

        $('#tyoaika_ikkuna').modal('hide');
    });

    
 

});


$(function()
{
    $('#lisaaprojekti').click(function (e) { 
        e.preventDefault();
        $('#projekti_ikkuna').modal();
    });



    $('#tallennaProjektiNappi').click(function(e)
    {
        e.preventDefault();
        
        var d = $('#lisaaprojektiFormi').serialize();

        console.log(d);
        $.ajax({
            url: "Tyoajanseuranta.php",
            data: d,
            success: function(result)
            {
                // location.reload();
                console.log(result);
            }
        });

        $('#projekti_ikkuna').modal('hide');
    });

    $('#poistaProjektiNappi').click(function(e)
    {
        var id = $('#poista_projekti').val();
        if (id != 'Ei valintaa')
        {
            poista(id, "projekti");
        }
    });
    
 

});
$(function()
{
    $('#lisaatekija').click(function (e) { 
        e.preventDefault();
        $('#tyontekija_ikkuna').modal();
    });

    $('#tallennaTyontekijaNappi').click(function(e)
    {
        e.preventDefault();
        
        var d = $('#lisaatyontekijaFormi').serialize();

        $.ajax({
            url: "Tyoajanseuranta.php",
            data: d,
            success: function(result)
            {
                // location.reload();
                console.log(result);
            }
        });

        $('#tyontekija_ikkuna').modal('hide');
    });

    $('#poistaTyontekijaNappi').click(function(e)
    {
        var id = $('#poista_tyontekija').val();
        if (id != 'Ei valintaa')
        {
            poista(id, "tyontekija");
        }
    });


});
function poista(id, tyyppi)
{
    console.log(id);
    console.log(tyyppi);

    $.ajax({
            url: "Tyoajanseuranta.php",
            data: {
                poista : id,
                tyyppi : tyyppi
            },
            success: function(result)
            {
                // location.reload();
                console.log(result);
            }
        });
}




    
</script>
</head>


<body>
    <form action="Tyoajanseuranta.php" method="post">
    <table>
    <tr><td>Työntekijä</td>
    <td>
    <select name="tyontekija">
    <option id="">Ei valintaa</option>
    <?php
        haeTyontekijat();
        // haeTyoaikaJaTulosta(1);
    ?>
    </select>
    </td></tr>
    <tr><td>Projekti</td>
    <td>
    <select name="projekti">
    <option id="">Ei valintaa</option>
    <?php
        haeProjektit();
        // haeTyontekijat();
    ?>
    </select>
    </td></tr>
    <tr><td style="padding:1em"><input type="submit" value="Hae"></td><td style="padding:1em"><button id="lisaatyoaika">Lisää työaika</button></td>
    <td style="padding:1em"><button id="lisaaprojekti">Lisää projekti</button></td><td style="padding:1em"><button id="lisaatekija">Lisää työntekijä</button></td></tr>
    </table>
    </form>
    <style>
    .tyoaikataulukko *
    {
        padding:0.3em;
    }
    </style>
    <table class="tyoaikataulukko">
    <form method="post" action="Tyoajanseuranta.php">
    <tr><th style="padding:0.5em">Työaika</th><th style="padding:0.5em"> Projekti </th><th style="padding:0.5em"> Tyontekija </th><th style="padding:0.5em"> Aloitusaika </th><th style="padding:0.5em"> Lopetusaika </th></tr>

    <?php
        // haeTyoaikaJaTulosta($_POST['projekti_id'], $_POST['tyontekija_id'], $_POST['aloitusaika'], $_POST['lopetusaika']);
        if (isset($_POST['tyontekija']) || isset($_POST['projekti']))
        {
            haeTyoaikaJaTulosta($_POST['tyontekija'], $_POST['projekti']);
        }
            
    ?>
    </form>
    </table>

    <!-- Projektin lisäys -->
    <div id="projekti_ikkuna" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Lisää Projekti</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="Tyoajanseuranta.php" method="post" name="lisaaprojektiFormi" id="lisaaprojektiFormi">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="Projekti_id">Projekti</label>
                        <select name="poista_projekti" id="poista_projekti">
                        <option id="">Ei valintaa</option>
                        <?php
                            haeProjektit();
                        ?>
                        </select>
                        <button type="button" id="poistaProjektiNappi" class="btn btn-default">Poista</button>
                    </div>
                    <div class="form-group">
                        <label for="Projektin_nimi">Projektin nimi</label>
                        <input type="hidden" name="lisaaUusiProjekti" value="1">
                        <input type='text' name="projektinNimi" class="form-control" />
                    </div>
                </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
                    <button type="submit" id="tallennaProjektiNappi" class="btn btn-primary">Tallenna</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Työntekijän lisäys -->

    <div id="tyontekija_ikkuna" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Lisää Projekti</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="Tyoajanseuranta.php" method="post" name="lisaatyontekijaFormi" id="lisaatyontekijaFormi">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="Tyontekija_id">Työntekijä</label>
                        <select name="poista_tyontekija" id="poista_tyontekija">
                            <option id="">Ei valintaa</option>
                            <?php
                                haeTyontekijat();
                                // haeTyoaikaJaTulosta(1);
                            ?>
                        </select>
                        <button type="button" id="poistaTyontekijaNappi" class="btn btn-default">Poista</button>
                    </div>
                    <div class="form-group">
                        <label for="Tyontekijan_nimi">Etunimi</label>
                        <input type="hidden" name="lisaaUusiTyontekija" value="1">
                        <input type='text' name="etunimi" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="Tyontekijan_nimi">Sukunimi</label>
                        <input type='text' name="sukunimi" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="Tyontekijan_nimi">Sähköposti</label>
                        <input type='text' name="sahkoposti" class="form-control" />
                    </div>
                </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
                    <button type="submit" id="tallennaTyontekijaNappi" class="btn btn-primary">Tallenna</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Työajan lisäys -->

    <div id="tyoaika_ikkuna" class="modal fade" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">Lisää työaika</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <form action="Tyoajanseuranta.php" method="post" name="lisaaaikaFormi" id="lisaaaikaFormi">
    <div class="modal-body">
            <input type="hidden" name="lisaaUusiAika" value="1">
            <div class="form-group">
                <label for="Projekti_id">Projekti</label>
                <select name="projekti">
                <option id="">Ei valintaa</option>
                <?php
                    haeProjektit();
                ?>
                </select>
                
            </div>
            <div class="form-group">
                <label for="Tyontekija_id">Tyontekija</label>
                <select name="tyontekija">
                <option id="">Ei valintaa</option>
                <?php
                    haeTyontekijat();
                ?>
                </select>
            </div>
            <label for="Aloitusaika">Aloitusaika</label>
            <div class="container">
        <div class="row">
            <div class='col-sm-6'>
                <div class="form-group">
                    <div class='input-group date' id='datetimepicker1'>
                        <input type='text' name="Aloitusaika" class="form-control" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                $(function () {
                    $('#datetimepicker2').datetimepicker({
                        locale: 'fi'
                    });
                });
            </script>
        </div>
    </div>
    <label for="Lopetusaika">Lopetusaika</label>
                <div class="container">
        <div class="row">
            <div class='col-sm-6'>
                <div class="form-group">
                    <div class='input-group date' id='datetimepicker2'>
                        <input type='text' name="Lopetusaika" class="form-control" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                $(function () {
                    $('#datetimepicker2').datetimepicker({
                        locale: 'fi'
                    });
                });
            </script>
        </div>
    </div>
    <script type="text/javascript">
    $(function () {
        $('#datetimepicker1').datetimepicker();
        $('#datetimepicker2').datetimepicker({
            useCurrent: false //Important! See issue #1075
        });
    });
        </script>
   
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
        <button type="submit" id="tallennaTyoaikaNappi" class="btn btn-primary">Tallenna</button>
    </div>
    </form>
    </div>

    </div>
    </div>
    

    </body>
</html>



