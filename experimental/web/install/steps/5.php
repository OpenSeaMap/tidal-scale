<div>
<p><b>GK Koordinaten Bessel transformieren</b></p>
<p>
Es werden die notwendigen Transformationen fuer den Bessel-Ellipsoid durchgefuehrt:<br><br>
<?php
Daten::set_coord_bessel();
 ?>
 <?=$db->getQueryCount()?> Datenbankabfragen in <?=substr($db->getQueryTimeSum(),0,6)?> Sekunden. 
</p>
<p>Klicken Sie auf &quot;Weiter&quot; um fortzufahren</p>
</div>
<div><input type="submit" value="Weiter" /></div>