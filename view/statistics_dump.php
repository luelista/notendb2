<?php
echo '"Vorname";"Name";"Klasse"'."\r\n";
foreach ($list as $l) {
  echo '"'.$l["vorname"].'";"'.$l["name"].'";"'.$l["klasse"].'"'."\r\n";
}?>