<?php
include('../auth.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="../../favicon.ico">

        <title>Boniersystem - Administration</title>

        <!-- Bootstrap core CSS -->
        <link href="bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" rel="stylesheet">
        <!--<link href="../../dist/css/bootstrap.min.css" rel="stylesheet">-->

        <!-- Custom styles for this template -->
        <link href="starter-template.css" rel="stylesheet">
    </head>

    <body>

        <nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!--<a class="navbar-brand" href="#">Navbar</a>-->

            <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav mr-auto">
                    <!--<li class="nav-item active">
                        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                    </li>-->
                    <!--
                    <li class="nav-item active">
                        <a class="nav-link" href="#" onclick="loadGetraenkekarte();">Getränke<span class="sr-only">(current)</span></a>
                    </li>                    -->
                    <li class="nav-item active">
                        <a class="nav-link" href="#" onclick="loadSpeisekarte();">Speisekarte <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="#" onclick="loadTische();">Tische<span class="sr-only">(current)</span></a>
                    </li>                    
                    <!--
                    <li class="nav-item">
                      <a class="nav-link" href="#">Link</a>
                    </li>-->
                    <!--<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Menü</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown01">
                            <a class="dropdown-item" href="#mainContent" id="menueTische" onclick="loadTische();">Tische</a>
                            <a class="dropdown-item" href="#">Speisekarte</a>
                        </div>
                    </li>-->
                </ul>

            </div>
        </nav>

        <div class="container">

            <div class="starter-template">
            </div>
        </div><!-- /.container -->

        <div id="mainContent" class="container-fluid">

        </div>

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="../include/jquery-3.2.1.js"></script>
        <!--<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
        <script src="bootstrap-4.0.0-alpha.6-dist/js/bootstrap.js"></script>
        <script type="text/javascript">
                            jQuery.ready(function () {

                                /*                $('#befundeInner').load('befunde.php', function () {
                                 $('#befunde').trigger('create');
                                 });*/
                                // $("#menueTische").click(function () {
                                //    $("#mainContent").load("list_tische_admin.php");
                                //});

                                //mainContent
                            });

                            function loadTische() {
                                $('#mainContent').load('list_tische_admin.php', function () {
                                    $('#mainContent').trigger('create');

                                });
                                //$("#mainContent").
//                 $("#mainContent").load("list_tische_admin.php");
                            }
                            function loadSpeisekarte() {
                                $('#mainContent').load('list_speisekarte_admin.php', function () {
                                    $('#mainContent').trigger('create');

                                });
                            }
                            /*
                             function loadGetraenkekarte() {
                             $('#mainContent').load('list_getraenkekarte_admin.php', function () {
                             $('#mainContent').trigger('create');
                             
                             });
                             }
                             */
                            //$("#mainContent").
//                 $("#mainContent").load("list_tische_admin.php");


                            function updateTischname(tischname, tischnummer) {

                                tischname = prompt("Neuer Tischname:", tischname);
                                console.log("Update Tischname");
                                if (tischname !== null) {
                                    $.ajax({
                                        url: 'update_tischname.php?tischname=' + tischname + '&tischnummer=' + tischnummer,
                                        type: "get",
                                        complete: function (data, responseText) {
                                            loadTische();
                                        },
                                        success: function (data) {

                                        },
                                        dataType: "json"
                                    });
                                }
                            }
                            function updateXY(x, y, tischnummer) {

                                x = prompt("x neu: (Spalte)", x);
                                y = prompt("y neu: (Zeile)", y);
                                console.log("Update XY");
                                if (x > 0 && y > 0) {
                                    $.ajax({
                                        url: 'update_xy.php?x=' + x + '&y=' + y + '&tischnummer=' + tischnummer,
                                        type: "get",
                                        complete: function (data, responseText) {
                                            loadTische();
                                        },
                                        success: function (data) {

                                        },
                                        dataType: "json"
                                    });
                                }
                            }
                            function editPositionsname(rowid, Positionsname) {

                                Positionsname = prompt("Positionsname neu:", Positionsname);

                                console.log("Update Positionsname");
                                if (Positionsname !== null) {
                                    $.ajax({
                                        url: 'update_positionsname.php?rowid=' + rowid + '&Positionsname=' + Positionsname,
                                        type: "get",
                                        complete: function (data, responseText) {
                                            loadSpeisekarte()();
                                        },
                                        success: function (data) {

                                        },
                                        dataType: "json"
                                    });
                                }
                            }
                            function editKurzbezeichnung(rowid, Kurzbezeichnung) {

                                Kurzbezeichnung = prompt("Kurzbezeichnung neu:", Kurzbezeichnung);

                                console.log("Update Kurzbezeichnung");
                                if (Kurzbezeichnung !== null) {
                                    $.ajax({
                                        url: 'update_kurzbezeichnung.php?rowid=' + rowid + '&Kurzbezeichnung=' + Kurzbezeichnung,
                                        type: "get",
                                        complete: function (data, responseText) {
                                            loadSpeisekarte()();
                                        },
                                        success: function (data) {

                                        },
                                        dataType: "json"
                                    });
                                }
                            }
                            function deleteTable(tischnummer) {

                                Confirm = confirm("Wirklich löschen?");

                                if (Confirm === true) {
                                    console.log("del Table");
                                    //if (Betrag >=0) {
                                    $.ajax({
                                        url: 'delete_table.php?tischnummer=' + tischnummer,
                                        type: "get",
                                        complete: function (data, responseText) {
                                            loadTische();
                                        },
                                        success: function (data) {

                                        },
                                        dataType: "json"
                                    });
                                    //}
                                }
                            }
                            function deleteMeal(rowid) {

                                //Betrag = prompt("Betrag neu:", Betrag);

                                console.log("del meal");
                                //if (Betrag >=0) {
                                $.ajax({
                                    url: 'delete_meal.php?rowid=' + rowid,
                                    type: "get",
                                    complete: function (data, responseText) {
                                        loadSpeisekarte()();
                                    },
                                    success: function (data) {

                                    },
                                    dataType: "json"
                                });
                                //}
                            }

                            function editBetrag(rowid, Betrag) {

                                Betrag = prompt("Betrag neu:", Betrag);

                                if (Betrag == null || Betrag == "") {
                                    console.log("editBetrag cancled");
                                } else {

                                    console.log("Update Betrag");
                                    if (Betrag >= 0) {
                                        $.ajax({
                                            url: 'update_betrag.php?rowid=' + rowid + '&Betrag=' + Betrag,
                                            type: "get",
                                            complete: function (data, responseText) {
                                                loadSpeisekarte()();
                                            },
                                            success: function (data) {

                                            },
                                            dataType: "json"
                                        });
                                    }
                                }
                            }

                            function editReihenfolge(rowid, reihenfolge) {

                                reihenfolge = prompt("neu:", reihenfolge);

                                if (reihenfolge == null || reihenfolge == "") {
                                    console.log("editReihenfolge cancled");
                                } else {

                                    console.log("Update Reihenfolge");
                                    if (reihenfolge >= 0) {
                                        $.ajax({
                                            url: 'update_reihenfolge.php?rowid=' + rowid + '&reihenfolge=' + reihenfolge,
                                            type: "get",
                                            complete: function (data, responseText) {
                                                loadSpeisekarte()();
                                            },
                                            success: function (data) {

                                            },
                                            dataType: "json"
                                        });
                                    }
                                }
                            }
                            function addPosition(Positionsname, type, Betrag) {


                                console.log("add Position");
                                if (Betrag >= 0) {
                                    $.ajax({
                                        url: 'insert_meal.php?Positionsname=' + Positionsname + '&Betrag=' + Betrag + '&type=' + type,
                                        type: "get",
                                        complete: function (data, responseText) {
                                            loadSpeisekarte()();
                                        },
                                        success: function (data) {

                                        },
                                        dataType: "json"
                                    });
                                }
                            }


                            function addTable() {

                                tischname = $('#tischname').val();
                                x = $('#x').val();
                                y = $('#y').val();
                                dataString = 'neuerTischName=' + tischname + '&neueTischX=' + x + '&neueTischY=' + y;
                                console.log("neuer tisch: " + tischname);
                                if (tischname != "") {
                                    $.ajax({
                                        url: '../neuerTisch_save.php',
                                        type: "POST",
                                        data: dataString,
                                        complete: function (data, responseText) {
                                            loadTische();
                                        },
                                        success: function (data) {

                                        },
                                        dataType: "json"
                                    });
                                }
                            }

                            function addMeal() {

                                positionsname = $('#positionsname').val();
                                type = $('#type').val();
                                betrag = $('#betrag').val();
                                kapazitaet = $('#kapazitaet').val();

                                dataString = 'Positionsname=' + positionsname + '&Betrag=' + betrag + '&type=' + type + '&Kapazitaet=' + kapazitaet;
                                console.log(dataString);
                                if (positionsname != "") {
                                    $.ajax({
                                        url: 'new_meal.php',
                                        type: "GET",
                                        data: dataString,
                                        complete: function (data, responseText) {
                                            loadSpeisekarte();
                                        },
                                        success: function (data) {

                                        },
                                        dataType: "json"
                                    });
                                }
                            }
                            function farbeSpeichern(tischnummer) {

                                color = $('#html5colorpicker' + tischnummer).val();

                                dataString = 'color=' + color + '&tischnummer=' + tischnummer;
                                console.log("neuer tisch: " + tischname);

                                $.ajax({
                                    url: 'update_color.php',
                                    type: "POST",
                                    data: dataString,
                                    complete: function (data, responseText) {
                                        loadTische();
                                    },
                                    success: function (data) {

                                    },
                                    dataType: "json"
                                });

                            }


                            function farbeSpeiseSpeichern(rowid) {

                                color = $('#html5colorpickerM' + rowid).val();

                                dataString = 'color=' + color + '&rowid=' + rowid;


                                $.ajax({
                                    url: 'update_color_meal.php',
                                    type: "POST",
                                    data: dataString,
                                    complete: function (data, responseText) {
                                        loadSpeisekarte()();
                                    },
                                    success: function (data) {

                                    },
                                    dataType: "json"
                                });

                            }


        </script>

    </body>
</html>
