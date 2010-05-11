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
*   Fonction qui permet de récupèrer les droits depuis le fichier xml
*   @author Vanger
*   @return array
***/
function get_xml_droits()
{
    if(!is_file(PATH_ROOT.'inc/droits.xml'))
        return array();

    $dom = new DomDocument("1.0", "UTF-8");
    $dom->load(PATH_ROOT.'inc/droits.xml');
    $elements = $dom->getElementsByTagName('droits');
    $arbre = $elements->item(0);

    $array_droit = array();

    $sections = $arbre->childNodes;
    foreach($sections as $section)
    {
        if($section->nodeName == "section")
        {
            $nom_section = $section->attributes->getNamedItem("name")->nodeValue;
            $array_droit[$nom_section] = array();
            $droits = $section->childNodes;
            foreach($droits as $droit)
            {
                if($droit->nodeName == "droit")
                {
                    $infos_droit = $droit->childNodes;
                    $nom_droit = $droit->getElementsByTagName("name")->item(0)->nodeValue;
                    $type_droit = $droit->getElementsByTagName("type")->item(0)->nodeValue;
                    $array_droit[$nom_section][$nom_droit] = array($type_droit);
                }
            }
        }
    }
    return $array_droit;
}
