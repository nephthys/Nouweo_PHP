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
    /**
     *  Activation du compte.
     *  @author Cam
     *  @return tpl
     */
    protected function main()
    {
        if (empty($_GET['mid']) || empty($_GET['ca']))
            header('Location: ./');
        
        if (is_logged_in())
            redir( Nw::$lang['common']['already_connected'], false, './' );
    
        $return_valid = false;

        inc_lib('users/mbr_act_exists');
        if (mbr_act_exists($_GET['mid'], $_GET['ca']))
        {
            inc_lib('users/get_info_mbr');
            $donnees_compte = get_info_mbr($_GET['mid']);
            
            if ($donnees_compte['u_active'] == 0)
            {
                inc_lib('users/valid_account');
                inc_lib('admin/gen_cachefile_nb_members');
                valid_account($_GET['mid']);
                gen_cachefile_nb_members();
                generate_members_sitemap();
                
                redir(Nw::$lang['users']['compte_valide'], true, './');
                $return_valid = true;
            }
        }
        
        if (!$return_valid)
            redir(Nw::$lang['users']['compte_valid_error'], false, './');
    }
}

/*  *EOF*   */
