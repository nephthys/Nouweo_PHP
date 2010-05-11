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
        if(is_logged_in() && check_auth('manage_groups'))
        {
            $this->set_title(Nw::$lang['admin']['titre_grp']);
            $this->set_tpl('admin/gestion_grp.html');
            $this->set_filAriane(array(
                    Nw::$lang['admin']['fa_admin']                  => array('admin.html'),
                    Nw::$lang['admin']['fa_grp']                    => array(''),
            ));

            inc_lib('admin/get_list_grp');
            $list_grp = get_list_grp();
            
            foreach($list_grp as $grp)
            {
                Nw::$tpl->setBlock('grp', array(
                    'ID'        => $grp['g_id'],
                    'NOM'       => $grp['g_nom'],
                    'TITRE'     => $grp['g_titre'],
                    'ICONE'     => $grp['g_icone'],
                    'COULEUR'   => $grp['g_couleur'],
                ));
            }
        }
        else
            redir(Nw::$lang['admin']['error_cant_see_admin'], false, './');
    }
}

/*  *EOF*   */
