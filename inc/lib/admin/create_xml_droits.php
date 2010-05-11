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
*   Fonction qui créé le fichier xml des droits selon l'array qu'elle reçoit
*   @author Vanger
*   @param array        $array_section      L'array des droits
*   ***                 Format de l'array : $array('section'    => array('droits' => 'type'));
*   @return void
***/
function create_xml_droits($array_section)
{
    $dom_document = new DomDocument("1.0", "UTF-8");
    $dom_document->formatOutput = true;
    $root = $dom_document->createElement("droits");
    $root = $dom_document->appendChild($root);

    foreach($array_section as $nom_section => $droits)
    {
        $section = $dom_document->createElement("section");
        $section->setAttribute("name", $nom_section);
        $section = $root->appendChild($section);

        foreach($droits as $nom_droit => $type_droit)
        {
            $droit = $dom_document->createElement("droit");
            $droit = $section->appendChild($droit);

            $nom = $dom_document->createElement("name");
            $nom = $droit->appendChild($nom);
            $textNom = $dom_document->createTextNode($nom_droit);
            $textNom = $nom->appendChild($textNom);

            $type = $dom_document->createElement("type");
            $type = $droit->appendChild($type);
            $textType = $dom_document->createTextNode($type_droit);
            $textType = $type->appendChild($textType);
        }
    }

    $dom_document->save(PATH_ROOT.'inc/droits.xml');
}
