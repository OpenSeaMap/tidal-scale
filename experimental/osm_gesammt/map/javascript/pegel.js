/*
erstellt von Tim Reinartz im Rahmen der Bachelor-Thesis
letzte Änderung 10.05.11 15:14 Uhr
Aufgabe der Datei:
Stellt die Marker auf OSM dar.
 */

function get_poi_url (bounds) {
//bounds = this.adjustBounds(bounds);
var res = this.map.getResolution();
var z = this.map.getZoom();
var path = "?z=" + z
+ "&l=" + getLeft(bounds)
+ "&t=" + getTop(bounds)
+ "&r=" + getRight(bounds)
+ "&b=" + getBottom(bounds)
+ "&f=" + features;
//bitte anpassen
var url = "http://localhost/xampp/test/fertig/web/pegelanzeigen.php";
//bitte anpassen
return url + path;
}