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
        
        $this->set_title(Nw::$lang['users']['item_avatar']);
        $this->set_tpl('membres/options_avatar.html');
        $this->add_css('forms.css');
        
        $this->set_filAriane(array(
            Nw::$lang['users']['mes_options_title']     => array('users-60.html'),
            Nw::$lang['users']['item_avatar']           => array('')
        ));
        
        if (isset($_GET['delete']) && !empty(Nw::$dn_mbr['u_avatar']))
        {
            inc_lib('users/delete_avatar');
            delete_avatar(Nw::$dn_mbr['u_id']);
            
            redir(Nw::$lang['users']['redir_d_avatar'], true, 'users-62.html');
        }
    
        if (isset($_POST['submit']) && (!empty($_FILES['file']['name']) || !empty($_POST['url'])))
        {
            inc_lib('users/edit_avatar_mbr');
            edit_avatar_mbr();
            
            redir(Nw::$lang['users']['redir_t_avatar'], true, 'users-62.html');
        }
        
        Nw::$tpl->set(array(    
            'AVATAR'            => Nw::$dn_mbr['u_avatar'],
        ));
        
        // On affiche le template
        display_form(array(
            'avatar'            => Nw::$dn_mbr['u_avatar'],
        ));
    }
}

/*  *EOF*   */
