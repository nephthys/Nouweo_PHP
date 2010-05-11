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
        $pages = array('faq', 'legal', 'rules', 'terms', 'tour', 'about');
        
        if(!empty($_GET['topic']) && in_array($_GET['topic'], $pages))
        {
            $this->set_title(Nw::$lang['help'][$_GET['topic'].'_title']);
            $this->add_css('code.css');
            $this->add_js('admin.js');
            
            if ($_GET['topic'] == 'about')
            {
                inc_lib('users/get_list_mbr');
                $list_membres = get_list_mbr('u_group IN (1, 8, 13)', 'u_group ASC, u_date_register ASC');
                
                foreach($list_membres AS $donnees)
                {
                    Nw::$tpl->setBlock('users', array(
                        'ID'            => $donnees['u_id'],
                        'PSEUDO'        => $donnees['u_pseudo'],
                        'ALIAS'         => $donnees['u_alias'],
                        'AVATAR'        => $donnees['u_avatar'],
                        'BIO'           => $donnees['u_bio'],
                    ));
                }
            }
            
            // Fil ariane
            $this->set_filAriane(array(
                Nw::$lang['help'][$_GET['topic'].'_title']      => array(''),
            ));
            $this->set_tpl('static/'.Nw::$site_lang.'/'.$_GET['topic'].'.html');
        }
        else
            redir(Nw::$lang['help']['error_dont_exist'], false, Nw::$site_url);
    }
}

/*  *EOF*   */
