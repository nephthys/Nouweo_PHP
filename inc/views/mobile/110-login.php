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
        $this->set_title(Nw::$site_slogan);
        $this->set_tpl('mobile/users/login.html');
        $this->load_lang_file('users');
        
        // Si le membre est déjà connecté
        if (is_logged_in()) {
            redir( Nw::$lang['common']['already_connected'], false, 'mobile-2.html' );
        }
        
        //Si on a soumis le formulaire
        if (!multi_empty(trim($_POST['nw_nickname']), trim($_POST['nw_password'])))
        {
            $array_post = array(
                'pseudo'    => $_POST['nw_nickname'],
                'remember'  => (isset($_POST['nw_remember']))
            );
            
            //On vérifie que la paire pseudo/mot de passe existe
            inc_lib('users/get_info_account');
            if ($dn_info_account = get_info_account($_POST['nw_nickname'], $_POST['nw_password']))
            {
                //Si le compte est actif
                if ($dn_info_account['u_active']==1)
                {
                    $link_redir = 'mobile-2.html';
                    $connex_auto = 1;

                    inc_lib('users/connect_auto_user');
                    connect_auto_user($dn_info_account['u_id'], $_POST['nw_password'], $connex_auto);
                        
                    // On redirige le membre
                    redir(sprintf(Nw::$lang['users']['welcome_user'], $_POST['nw_nickname']), true, $link_redir);
                }
                else
                    display_form($array_post, Nw::$lang['users']['not_active']); return;
            }
            else
                display_form($array_post, Nw::$lang['users']['account_no_exist']); return;
        }
        
        display_form(array('pseudo' => '', 'remember' => true));
        
        Nw::$tpl->set('INC_HEAD', empty($_SERVER['HTTP_AJAX']));
    }
}

/*  *EOF*   */
