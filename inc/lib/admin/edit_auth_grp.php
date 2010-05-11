<?php
/*
 *  Copyright (C) 2009 Nouweo
 *  
 *  Nouweo is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *  
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *  
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/***
*   Modifie la valeur d'un droit pour un groupe.
*   @author vincent1870.
*   @param integer $id_group        ID du groupe.
*   @param string $nom_droit        Nom du droit.
*   @param integer $value           Valeur du droit.
*   @return void
***/
function edit_auth_grp($id_group, $nom_droit, $value)
{
    $query = Nw::$DB->query( 'SELECT COUNT(*) as count
        FROM '.Nw::$prefix_table.'droits
        WHERE droit_groupe = '.intval($id_group).' AND droit_nom = \''.Nw::$DB->real_escape_string($nom_droit).'\'')
        OR Nw::$DB->trigger(__LINE__, __FILE__);
    $dn = $query->fetch_assoc();

    if ($dn['count'] != 0)
    {
        Nw::$DB->query("UPDATE ".Nw::$prefix_table."droits
            SET droit_valeur = ".intval($value)."
            WHERE droit_groupe = ".intval($id_group)." AND droit_nom = '".Nw::$DB->real_escape_string($nom_droit)."'")
            OR Nw::$DB->trigger(__LINE__, __FILE__);
    }
    else
    {
        Nw::$DB->query("INSERT INTO ".Nw::$prefix_table."droits (droit_nom,
            droit_groupe, droit_valeur)
            VALUES('".Nw::$DB->real_escape_string($nom_droit)."', ".intval($id_group).", ".intval($value).")")
            OR Nw::$DB->trigger(__LINE__, __FILE__);
    }
}
