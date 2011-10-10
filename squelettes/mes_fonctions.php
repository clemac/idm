<?php

global $tables_principales;
$tables_principales['spip_auteurs']['field']['billettiste'] = "enum('oui','non') NOT NULL DEFAULT 'non'";

function initiale ($mot) {
  return strtoupper($mot[0]);
}

?>
