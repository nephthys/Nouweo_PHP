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


class Page extends Core
{
    protected function main()
    {
        $this->set_title(Nw::$lang['admin']['titre_droit']);
        $this->set_tpl('admin/gestion_droit.html');
        $this->set_filAriane(Nw::$lang['admin']['fa_droit']);

        inc_lib('admin/get_xml_droits');
        $all_droit = get_xml_droits();
        
        foreach($all_droit as $section => $list_droit)
        {
            Nw::$tpl->setBlock("section", array(
                'NOM'   => $section
            ));
            foreach($list_droit as $nom_droit => $droit)
            {
                Nw::$tpl->setBlock("section.droit", array(
                    'TYPE'  => $droit[0],
                    'NOM'   => $nom_droit,
                    'FULLNAME'  => $droit[1]
                ));
            }
        }
    }
}

/*  *EOF*   */
