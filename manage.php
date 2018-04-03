<?php
session_start();

if($_SESSION["loggedin"] != true) {
    header('Location: login.php');
} else {
    include_once("config.php");
    $lookupURL = $db->prepare("select id, shorturl, redirect, date_created from redirects where owner = ? and enabled = '1' order by id desc");
    $lookupURL->execute(array($_SESSION["username"]));
    $ownedURLs = $lookupURL->fetchall(PDO::FETCH_ASSOC);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        URL - Manage
    </title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css" type="text/css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</head>

<script type="text/javascript">
    $(document).ready(function()
    {
        $(".delete").click(function()
        {
            var del_id = $(this).attr('id');
            $.ajax({
                type:'POST',
                url:'delete.php',
                data:'id='+del_id,
                success: function(data)
                {
                    $("#" + del_id).show().fadeOut(600);
                }
            });
        });

        $(".stats").click(function()
        {
            var stat_id = $(this).attr('id');
            $.ajax({
                type:'POST',
                url:'stat.php',
                data:'id='+stat_id,
                success: function(server_response) {
                    $("#statresult" + stat_id).html(server_response);
                    $("#statresult" + stat_id).show().fadeIn("slow");
                }
            });
        });

        var x_timer;
        $("#shorturl").keyup(function (e){
            clearTimeout(x_timer);
            var shorturl = $(this).val();
            x_timer = setTimeout(function(){
                check_url(shorturl);
            }, 1000);
        });

        function check_url(shorturl){
            $("#url_check").html('<img src="ajax-loader.gif" />');
            $.post('url_check.php', {'shorturl':shorturl}, function(data) {
                $("#url_check").html(data);
            });
        }

    });
</script>

<body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                <a class="navbar-brand" href="#">DA URL Redirect</a>
            </div>
                    <div id="navbar" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="/login.php?logout=true">Logout</a></li>
                        </ul>
                    </div>
        </div>
    </nav>

    <div class="container main">
    <div class="form">
        <h4>Add New</h4>
        <form action="add.php" method="post">
            <span>http://url.da.org/</span><input type="text" placeholder="short url" id="shorturl" name="shorturl" style="width: 200px;"/>
            <span id="url_check"></span>
            <br />
            <input type="text" placeholder="redirect url" id="redirect_url" name="redirect_url"/>
            <br />
            <button>Add</button>
        </form>
    </div>

        <br />
        <br />
        <h4>Current URLs</h4>
            <?php
            foreach($ownedURLs as $row => $val) {
                print("<div id=" . $val["id"] . ">");
                print("<strong><a href=\"http://" . $host . "/" . $val["shorturl"] . "\" target='_blank'>" . $host . "/" . $val["shorturl"] . "</a></strong></br>");
                print("<input type=\"text\" value=\"" . $val["redirect"] . "\"/>");
                print("<button class=\"delete\" id=\"" . $val["id"] . "\">Delete</button><br />");
                print("<button class=\"stats\" id=\"" . $val["id"] . "\">Stats</button><br />");
                print("<div class=\"statresult\" id=\"statresult" . $val["id"] . "\"></div>");
                print("<hr />");
                print("</div>");
            }
            ?>
    </div>

</body>
</html>
