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
        $this->load_lang_file('users');
        $this->load_lang_file('news');
        
        // Si le paramètre ID manque
        if (empty($_GET['id']))
            header('Location: ./');

        inc_lib('users/mbr_exists');
        if (mbr_exists($_GET['id']) == false)
            redir(Nw::$lang['users']['mbr_dont_exist'], false, 'users.html');

        inc_lib('users/get_info_mbr');
        $donnees_profile = get_info_mbr($_GET['id']);
        
        $this->add_wid_in_content('view_profile.'.$donnees_profile['u_id']);
        $this->set_tpl('profile/full_bio.html');
        $this->set_title(sprintf(Nw::$lang['profile']['profile_title'], $donnees_profile['u_pseudo']));
        $this->add_css('code.css');
        $this->add_js('profil.js');
        $this->set_filAriane(array(
            Nw::$lang['users']['members_section']           => array('users.html'),
            $donnees_profile['u_pseudo']                    => array('./profile/'.$donnees_profile['u_alias'].'/'),
            Nw::$lang['profile']['title_full_bio']          => array(),
        ));
        $this->base_enabled(true);
        
        inc_lib('profile/assign_required_vars_profile');
        assign_required_vars_profile($donnees_profile);
    }
}

/*  *EOF*   */
