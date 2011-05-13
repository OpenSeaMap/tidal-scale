#!/bin/bash
file="./pegelstaende_neu.xml"
if [ -e $file ]; then
	echo "File exists"
	rm $file
	wget "http://www.pegelonline.wsv.de/svgz/pegelstaende_neu.xml"
else 
	echo "File does not exists"
	wget "http://www.pegelonline.wsv.de/svgz/pegelstaende_neu.xml"
fi 
./pegel_aktualisieren.php
echo "erfolgreich ausgefuehrt"