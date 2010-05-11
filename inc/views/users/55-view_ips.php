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
        if(!is_logged_in() || !check_auth('can_see_ip'))
            redir(Nw::$lang['users']['error_cant_see_ip'], false, './');

        $this->load_lang_file('admin');
        $this->set_title(Nw::$lang['users']['check_ip']);
        $this->set_tpl('membres/check_ip.html');
        $this->add_css('forms.css');

        if(!empty($_GET['ip']))
        {
            inc_lib('users/check_ip');
            $list_mbr = check_ip($_GET['ip']);
            Nw::$tpl->set('SEARCH', $_GET['ip']);

            foreach($list_mbr as $mbr)
            {
                Nw::$tpl->setBlock('mbr', array(
                    'ID'        => $mbr['u_id'],
                    'PSEUDO'    => $mbr['u_pseudo'],
                    'IDENTIFIER'=> $mbr['u_identifier'],
                    'LAST_IP'   => long2ip($mbr['u_ip']),
                    'GROUP'     => $mbr['g_nom'],
                    'ID_ADMIN'  => $mbr['a_admin'],
                    'DATE_REGISTER' => $mbr['date_register'],
                ));
            }
        }
        else
            Nw::$tpl->set('SEARCH', '');
        

        // Fil ariane
        $this->set_filAriane(array(
            Nw::$lang['admin']['fa_admin']          => array('admin.html'),
            Nw::$lang['users']['check_ip']      => array(''),
        ));
    }
}

/*  *EOF*   */
