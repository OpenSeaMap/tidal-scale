<!--
erstellt von Tim Reinartz im Rahmen der Bachelor-Thesis
letzte Änderung 23.04.11 16:21 Uhr
-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html>
    <head>
        <title>Info Datei</title>
    </head>
    <body>
<?php
echo 'PHP Version:';
echo phpversion();
echo '<br><br>';
echo 'Folgendes muss vorhanden sein:';
echo '<br>SimpleXML<br>';
print_r(get_extension_funcs("SimpleXML"));
echo '<br>folgende Ausgabe muss erscheinen: <br>Array ( [0] => simplexml_load_file [1] => simplexml_load_string [2] => simplexml_import_dom ) ';
echo '<br>SOAP<br>';
print_r(get_extension_funcs("soap"));
echo '<br><br>';
echo 'Eins der beiden muss vorhanden sein';
echo '<br><br>';
echo 'allow_url_fopen = ' . ini_get('allow_url_fopen') . "\n";
echo '<br>';
echo 'allow_url_include = ' . ini_get('allow_url_include') . "\n";
echo '<br><br>';
echo 'oder';
echo '<br><br>';
echo '<br>Socket Support<br>';
print_r(get_extension_funcs("sockets"));
?>
<br><br>
    </body>
</html>