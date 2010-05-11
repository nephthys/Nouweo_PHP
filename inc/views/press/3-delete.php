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
        //Si on a bien envoyé un article à supprimer
        if(!empty($_GET['id']) && is_numeric($_GET['id']))
        {
            inc_lib('press/get_info_article');
            $dn_article = get_info_article($_GET['id']);
            if(empty($dn_article))
                redir(Nw::$lang['press']['error_dont_exist'], false, 'press.html');
            
            //Si on a soumis le formulaire
            if(isset($_POST['submit']))
            {
                inc_lib('press/delete_article');
                delete_article($dn_article['p_id']);
                redir(Nw::$lang['press']['redir_article_deleted'], true, 'press.html');
            }
            elseif(isset($_POST['cancel']))
            {
                header('Location: press.html?article='.$dn_article['p_id']);
            }
        
            $this->set_title($dn_article['p_ressource_name']);
            $this->set_tpl('press/delete.html');
            $this->add_css('forms.css');
        
            // Fil ariane
            $this->set_filAriane(array(
                Nw::$lang['press']['mod_title']     => array('press.html'),
                $dn_article['p_ressource_name']     => array('press.html?article='.$dn_article['p_id']),
                Nw::$lang['press']['art_delete']    => array(''),
            ));
        
            Nw::$tpl->set(array(
                'ID'                => $dn_article['p_id'],
                'RESSOURCE'         => $dn_article['p_ressource_name'],
                'TEXT_CONFIRM'      => sprintf(Nw::$lang['press']['confirm_delete'], 
                                        $dn_article['p_id'], $dn_article['p_ressource_name']),
            ));
        }
        else
        {
            redir(Nw::$lang['press']['error_dont_exist'], false, 'press.html');
        }
    }
}

/*  *EOF*   */
