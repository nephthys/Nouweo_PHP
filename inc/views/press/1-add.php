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
        if(check_auth('manage_articles') || !is_logged_in())
        {
            $this->set_title(Nw::$lang['press']['art_add']);
            $this->set_tpl('press/add.html');
            $this->add_css('forms.css');
            $this->add_js('write.js');
            $this->add_js('forms.js');
    
            // Fil ariane
            $this->set_filAriane(array(
                Nw::$lang['press']['mod_title']                 => array('press.html'),
                Nw::$lang['press']['art_add']                   => array(''),
            ));
            
            //Si on veut ajouter l'article
            if(isset($_POST['submit']))
            {
                inc_lib('press/add_article');
                add_article($_SESSION['ident_session'], $_POST['paper'], 
                $_POST['link'], $_POST['numero'], $_POST['country'], $_POST['contenu'], 
                $_POST['date_pub']);
                redir(Nw::$lang['press']['redir_article_added'], true, 'press.html');
            }
        }
        else
            redir(Nw::$lang['press']['error_cant_manage'], false, 'press.html');
    }
}

/*  *EOF*   */

