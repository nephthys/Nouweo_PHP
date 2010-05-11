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
        if (!is_logged_in()) {
            redir(Nw::$lang['common']['need_login'], false, 'users-10.html');
        }
        
        $this->set_title(Nw::$lang['users']['item_mdp']);
        $this->set_tpl('membres/options_pass.html');
        $this->add_css('forms.css');
        
        $this->set_filAriane(array(
            Nw::$lang['users']['mes_options_title']     => array('users-60.html'),
            Nw::$lang['users']['item_mdp']              => array('')
        ));
        
        if (isset($_POST['submit']) && !multi_empty(trim($_POST['old']), trim($_POST['nw_pass1']), trim($_POST['nw_pass2'])))
        {
            $bf_token = 'jJ_=éZAç1l';
            $ft_token = 'ù%*àè1ç0°dezf';
            $pass_membre = insertBD(sha1($bf_token.trim($_POST['old']).$ft_token));
        
            if ($_POST['nw_pass1'] == $_POST['nw_pass2'])
            {
                if (Nw::$dn_mbr['u_password'] == $pass_membre)
                {   
                    inc_lib('users/chg_password');
                    chg_password($_POST['nw_pass1'], Nw::$dn_mbr['u_id']);
                    
                    if (!empty($_COOKIE['nw_pass']))
                    {
                        $time_expire = time()+10*365*24*3600;
                        setcookie('nw_ident', Nw::$dn_mbr['u_id'], $time_expire);
                        setcookie('nw_pass', $pass_membre, $time_expire);
                    }
                    
                    redir(Nw::$lang['users']['mdp_change'], true, 'users-60.html');
                }
                else
                    redir(Nw::$lang['users']['not_root_password'], false, 'users-63.html');
            }
            else
                redir(Nw::$lang['users']['sames_password'], false, 'users-63.html');
        }
    }
}

/*  *EOF*   */
