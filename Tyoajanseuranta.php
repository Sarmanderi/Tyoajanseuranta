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

    function poistaTyoaika($id)
    {
        $q = "DELETE FROM tyoajanseuranta WHERE Tyoaika_ID = '$id'";

        $result = TeeHaku($q);

        if (!$result)
        {
            echo "Poisto ei onnistunut!";
            echo "\n" . $q;
        }

    }

    function muokkaaTyontekijaa($id, $etunimi, $sukunimi, $sahkoposti)
    {
        $q = "UPDATE tyontekija SET Etunimi = '$etunimi', Sukunimi = '$sukunimi', Sahkoposti = '$sahkoposti' WHERE Tyontekija_ID = $id";

        $result = TeeHaku($q);

        if ($result)
        {

        }
        else
        {
            echo "Ei onnistu.";
        }
    }

    function haeTyontekija($id)
    {
        $q = "SELECT * FROM `tyontekija` WHERE Tyontekija_ID = $id";

        $result = TeeHaku($q);

        if ($result)
        {
            $d = array();
            while($rivi = $result->fetch_assoc())
            {
                array_push($d, $rivi);
            }
            echo json_encode($d);
        }
        else
        {
            echo "Ei tuloksia!";
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

    function muokkaaProjektia($id, $nimi)
    {
        $q = "UPDATE projekti SET Projektin_Nimi = '$nimi' WHERE Projektin_ID = $id";

        $result = TeeHaku($q);

        if ($result)
        {

        }
        else
        {
            echo "Ei onnistu.";
        }
    }

    function haeProjekti($id)
    {
        $q = "SELECT * FROM `projekti` WHERE Projektin_ID = $id";
        
        $result = TeeHaku($q);

        if ($result)
        {
            $d = array();
            while($rivi = $result->fetch_assoc())
            {
                array_push($d, $rivi);
            }
            echo json_encode($d);
        }
        else
        {
            echo "Ei tuloksia!";
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

    function haeTyoaika($id)
    {
        $q = "SELECT * FROM `tyoajanseuranta` WHERE Tyoaika_ID = $id";

        $result = TeeHaku($q);

        if ($result)
        {
            $d = array();
            while($rivi = $result->fetch_assoc())
            {
                array_push($d, $rivi);
            }
            echo json_encode($d);
        }
        else
        {
            echo "Ei tuloksia!";
        }
    }

    function haeTyoajatJaTulosta($tyontekija_id, $projekti_id)
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
            $d = array();
            while($rivi = $result->fetch_assoc())
            {
                array_push($d, $rivi);
            }
            echo json_encode($d);
        }
        else
        {
            echo "Ei tuloksia!";
        }
    }

    if (isset($_GET['haetyoaika']))
    {
        haeTyoaika($_GET['haetyoaika']);
        exit();
    }

    if (isset($_GET['tyontekija']) && isset($_GET['projekti']) && !isset($_GET['lisaaUusiAika']))
    {
        haeTyoajatJaTulosta($_GET['tyontekija'], $_GET['projekti']);
        exit();
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
        else if ($_GET['tyyppi'] == "tyoaika")
        {
            poistaTyoaika($_GET['poista']);
        }
        
        exit();
    }

    

    if (isset($_GET['lisaaUusiProjekti']) && isset($_GET['projektinNimi']))
    {
        if (isset($_GET['muokkaaprojektia']))
        {
            muokkaaProjektia($_GET['muokkaaprojektia'], $_GET['projektinNimi']);
        }
        else
        {
            lisaaUusiProjekti($_GET['projektinNimi']);    
        }
        exit();
    }

    if (isset($_GET['lisaaUusiTyontekija']))
    {
        if (isset($_GET['muokkaatyontekijaa']))
        {
            muokkaaTyontekijaa($_GET['muokkaatyontekijaa'], $_GET['etunimi'], $_GET['sukunimi'], $_GET['sahkoposti']);
        }
        else
        {
            lisaaUusiTyontekija($_GET['etunimi'], $_GET['sukunimi'], $_GET['sahkoposti']);
        }
        exit();
        
    }

    if (isset($_GET['haetyontekija']))
    {
        haeTyontekija($_GET['haetyontekija']);
        exit();
    }

    if (isset($_GET['haeprojekti']))
    {
        haeProjekti($_GET['haeprojekti']);
        exit();
    }
        

    if (isset($_GET['lisaaUusiAika']))
    {
        if ($_GET['lisaaUusiAika'] == "uusi")
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

        else if ($_GET['lisaaUusiAika'] == "muokkaa" && $_GET['tyoaikaid'])
        {
            $tyoaika_id = $_GET['tyoaikaid'];
            $projekti_id = $_GET['projekti'];
            $tyontekija_id = $_GET['tyontekija'];
            $aloitusaika = $_GET['Aloitusaika'];
            $lopetusaika = $_GET['Lopetusaika'];
    
            // $aikaleima = date('Y-m-d H:i:s');
            
            $a = date("Y-m-d H:i:s", strtotime($aloitusaika));
            $l = date("Y-m-d H:i:s", strtotime($lopetusaika));
    
            $q = "UPDATE tyoajanseuranta SET Projekti_ID = $projekti_id, Tyontekija_ID = $tyontekija_id, Aloitusaika = '$a', Lopetusaika = '$l' WHERE Tyoaika_ID = $tyoaika_id";
    
            echo $q;
            $result = TeeHaku($q);
    
            if (!$result)
            {
                echo "Ei onnistunut!";
            }
            else
                echo "Päivitetty";
    
            exit();
        }
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

function haeTyoajat()
    {
        var d = $('#tyoaikahakuform').serialize();
        // console.log(d);

        $.ajax({
            url: "Tyoajanseuranta.php",
            data: d,
            success: function(result)
            {
                $('.tyoaikataulukko').empty();
                // location.reload();
                var tyoajat = JSON.parse(result);
                // console.log(tyoajat);
                var headerit = '<tr><th style="padding:0.5em">Työaika</th><th style="padding:0.5em"> Projekti </th><th style="padding:0.5em"> Tyontekija </th><th style="padding:0.5em"> Aloitusaika </th><th style="padding:0.5em"> Lopetusaika </th></tr>';
                $('.tyoaikataulukko').append(headerit);

                for (var i = 0; i < tyoajat.length; i++)
                {
                    var rivi = $("<tr></tr>");
                    var tyoaikaID = $("<td></td>").text(tyoajat[i].Tyoaika_ID);
                    var projektiID = $("<td></td>").text(tyoajat[i].Projekti_ID);
                    var tyontekijaID = $("<td></td>").text(tyoajat[i].Tyontekija_ID);
                    var aloitusaika = $("<td></td>").text(tyoajat[i].Aloitusaika);
                    var lopetusaika = $("<td></td>").text(tyoajat[i].Lopetusaika);
                    var poistaNappi = "<button onclick='poista(" + tyoajat[i].Tyoaika_ID + ", \"tyoaika\")'>Poista</button>";
                    var muokkaaNappi = "<button onclick='muokkaaTyoaika(" + tyoajat[i].Tyoaika_ID + ")'>Muokkaa</button>";
                    
                    $('.tyoaikataulukko').append(rivi);
                    rivi.append(tyoaikaID);
                    rivi.append(projektiID);
                    rivi.append(tyontekijaID);
                    rivi.append(aloitusaika);
                    rivi.append(lopetusaika);
                    var poistaSarake = $('<td></td>').append(poistaNappi);
                    var muokkaaSarake = $('<td></td>').append(muokkaaNappi);
                    rivi.append(poistaSarake);
                    rivi.append(muokkaaSarake);
                }
            }
        });
    }

    function muokkaaTyoaika(id)
    {
        $.ajax({
            url: "Tyoajanseuranta.php",
            data: {
                haetyoaika : id
            },
            success: function(result)
            {
                // location.reload();
                console.log(result);
                var d = JSON.parse(result);
                console.log(d[0].Aloitusaika);
                console.log(d[0].Lopetusaika);
                $('#aloitusaika').val(d[0].Aloitusaika);
                $('#lopetusaika').val(d[0].Lopetusaika);
                $('#tyontekijavalinta option[value=' + d[0].Tyontekija_ID + ']').attr('selected', 'selected');
                $('#projektivalinta option[value=' + d[0].Projekti_ID + ']').attr('selected', 'selected');
                $('#lisaaUusiAika').val("muokkaa");
                $('#tyoaikaid').val(id);
                $('#tyoaika_ikkuna').modal();
                haeTyoajat();
            }
        });

        // $('#tyoaika_ikkuna').modal();
    }

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
                    haeTyoajat();
                }
            });
    }


$(function()
{
    $('#haetyoajat').click(function (e)
    {
        e.preventDefault();
        haeTyoajat();
        
    })

    $('#lisaatyoaika').click(function (e) { 
        e.preventDefault();
        $('#aloitusaika').val("");
        $('#lopetusaika').val("");
        $('#lisaaUusiAika').val("uusi");
        $('#tyoaikaid').val('');
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
                console.log(result);
                haeTyoajat();
            }
        });

        $('#tyoaika_ikkuna').modal('hide');
        
    });

    $('#lisaaprojekti').click(function (e) { 
        e.preventDefault();
        $('#muokkaaprojektia').remove();
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
                location.reload();
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
    

    $('#lisaatekija').click(function (e) { 
        e.preventDefault();
        $('#muokkaatyontekijaa').remove();
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
                location.reload();
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

    $('#poista_tyontekija').change(function()
    {
        var valinta = $('#poista_tyontekija').val();

        if (valinta != 'Ei valintaa')
        {
            $.ajax({
            url: "Tyoajanseuranta.php",
            data: {
                haetyontekija : $('#poista_tyontekija').val()
            },
            success: function(result)
            {
                // location.reload();
                var r = JSON.parse(result);
                $('#muokkaatyontekijaa').remove();

                var inp = "<input type='hidden' id='muokkaatyontekijaa' name='muokkaatyontekijaa' value=" + r[0].Tyontekija_ID + ">";
                $('#lisaatyontekijaFormi').append(inp);
                
                $('#etunimi').val(r[0].Etunimi);
                $('#sukunimi').val(r[0].Sukunimi);
                $('#sahkoposti').val(r[0].Sahkoposti);
                console.log(result);
            }
        });
        }
        
    });


    $('#poista_projekti').change(function()
    {
        var valinta = $('#poista_projekti').val();

        if (valinta != 'Ei valintaa')
        {
            $.ajax({
            url: "Tyoajanseuranta.php",
            data: {
                haeprojekti : $('#poista_projekti').val()
            },
            success: function(result)
            {
                // location.reload();
                var r = JSON.parse(result);
                $('#muokkaaprojektia').remove();

                var inp = "<input type='hidden' id='muokkaaprojektia' name='muokkaaprojektia' value=" + r[0].Projektin_ID + ">";
                $('#lisaaprojektiFormi').append(inp);
                
                $('#projektinNimi').val(r[0].Projektin_Nimi);
                $('#sukunimi').val(r[0].Sukunimi);
                $('#sahkoposti').val(r[0].Sahkoposti);
                console.log(result);
            }
        });
        }
        
    });

    
});

    
</script>
</head>


<body>
    <form action="Tyoajanseuranta.php" method="get" id="tyoaikahakuform">
    <table>
    <tr><td>Työntekijä</td>
    <td>
    <select name="tyontekija">
    <option id="">Ei valintaa</option>
    <?php
        haeTyontekijat();
        // haeTyoajatJaTulosta(1);
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
    <tr><td style="padding:1em"><button id="haetyoajat">Hae</button></td><td style="padding:1em"><button id="lisaatyoaika">Lisää työaika</button></td>
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
                        <input type='text' id="projektinNimi" name="projektinNimi" class="form-control" />
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
                                // haeTyoajatJaTulosta(1);
                            ?>
                        </select>
                        <button type="button" id="poistaTyontekijaNappi" class="btn btn-default">Poista</button>
                    </div>
                    <div class="form-group">
                        <label for="Tyontekijan_nimi">Etunimi</label>
                        <input type="hidden" name="lisaaUusiTyontekija" value="1">
                        <input type='text' id="etunimi" name="etunimi" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="Tyontekijan_nimi">Sukunimi</label>
                        <input type='text' id="sukunimi" name="sukunimi" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="Tyontekijan_nimi">Sähköposti</label>
                        <input type='text' id="sahkoposti" name="sahkoposti" class="form-control" />
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
            <input type="hidden" id="lisaaUusiAika" name="lisaaUusiAika" value="uusi">
            <input type="hidden" id="tyoaikaid" name="tyoaikaid" value="">
            <div class="form-group">
                <label for="Projekti_id">Projekti</label>
                <select id="projektivalinta" name="projekti">
                <option id="">Ei valintaa</option>
                <?php
                    haeProjektit();
                ?>
                </select>
                
            </div>
            <div class="form-group">
                <label for="Tyontekija_id">Tyontekija</label>
                <select id="tyontekijavalinta" name="tyontekija">
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
                        <input type='text' name="Aloitusaika" id="aloitusaika" class="form-control" />
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
                        <input type='text' name="Lopetusaika" id="lopetusaika" class="form-control" />
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



