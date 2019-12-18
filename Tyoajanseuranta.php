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

    if (isset($_GET['poista']))
    {
        poistaTyoaika($_GET['poista']);
    }

    if (isset($_GET['lisaaUusi']))
    {
        $projekti_id = $_GET['projekti'];
        $tyontekija_id = $_GET['tyontekija'];
        $aloitusaika = $_GET['Aloitusaika'];
        $lopetusaika = $_GET['Lopetusaika'];

        $aikaleima = date('Y-m-d H:i:s');

        $q = "INSERT INTO tyoajanseuranta (Projekti_ID, Tyontekija_ID, Aloitusaika, Lopetusaika) VALUES ('$projekti_id', '$tyontekija_id', '$aikaleima', '$aikaleima')";

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
    $('#lisaaNappi').click(function (e) { 
        e.preventDefault();
        $('#ikkuna').modal();
    });

    $('#tallennaNappi').click(function(e)
    {
        e.preventDefault();
        
        var d = $('#lisaaFormi').serialize();

        $.ajax({
            url: "Tyoajanseuranta.php",
            data: d,
            success: function(result)
            {
                // location.reload();
                console.log(result);
            }
        });

        $('#ikkuna').modal('hide');
    });

    
 

});

function poista(id)
{
    $.ajax({
            url: "Tyoajanseuranta.php",
            data: {
                poista : id
            },
            success: function(result)
            {
                location.reload();
                // console.log(result);
            }
        });
}




    
</script>
</head>


<body>
    <form action="Tyoajanseuranta.php" method="post">
    <table>
    <tr><td>Nimi</td><td><input type="text" name="nimi"></td></tr>
    <tr><td>Osoite</td><td><input type="text" name="osoite"></td></tr>
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
    <tr><td><input type="submit" value="Hae"></td><td><button id="lisaaNappi">Lisää asiakas</button></td></tr>
    </table>
    </form>

    <table>
    <form method="post" action="Tyoajanseuranta.php">
    <tr><th>Työaika_ID</th><th>Projekti_id</th><th>Tyontekija_id</th><th>Aloitusaika</th><th>Lopetusaika</th></tr>

    <?php
        // haeTyoaikaJaTulosta($_POST['projekti_id'], $_POST['tyontekija_id'], $_POST['aloitusaika'], $_POST['lopetusaika']);
        if (isset($_POST['tyontekija']) || isset($_POST['projekti']))
        {
            haeTyoaikaJaTulosta($_POST['tyontekija'], $_POST['projekti']);
        }
            
    ?>
    </form>
    </table>

    
    <div id="ikkuna" class="modal fade" role="dialog">
    <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">Lisää työaika</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <form action="Tyoajanseuranta.php" method="post" name="lisaaFormi" id="lisaaFormi">
    <div class="modal-body">
            <input type="hidden" name="lisaaUusi" value="1">
            <div class="form-group">
                <label for="Projekti_id">Projekti_id</label>
                <select name="projekti">
                <option id="">Ei valintaa</option>
                <?php
                    haeProjektit();
                    // haeTyontekijat();
                ?>
                </select>
                
            </div>
            <div class="form-group">
                <label for="Tyontekija_id">Tyontekija_id</label>
                <select name="tyontekija">
                <option id="">Ei valintaa</option>
                <?php
                    haeTyontekijat();
                    // haeTyoaikaJaTulosta(1);
                ?>
                </select>
            </div>
            <div class="form-group">
                        <div class="input-append date form_datetime">
                <input size="16" type="text" value="" readonly data-date-format="DD-MM-YYYY HH:mm:ss">
                <span class="add-on"><i class="icon-th"></i></span>
            </div>
            
            <script type="text/javascript">
                $(".form_datetime").datetimepicker({
                    autoclose: true,
                    todayBtn: true,
                    pickerPosition: "bottom-left"
                });
            </script>  
                                    
            </div>
            <div class="form-group">
                <label for="Lopetusaika">Lopetusaika</label>
                <input type="text" class="form-control" name="Lopetusaika" placeholder="Syötä Lopetusaika">
                <?php
                    // haeTyoaikaJaTulosta();
                ?>
                </select>
            </div>
        
        
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
        <button type="submit" id="tallennaNappi" class="btn btn-primary">Tallenna</button>
    </div>
    </form>
    </div>

    </div>
    </div>
    

    </body>
</html>



