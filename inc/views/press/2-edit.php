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
        //Si on a bien envoyé un article à éditer
        if(!empty($_GET['id']) && is_numeric($_GET['id']))
        {
            inc_lib('press/get_info_article');
            $dn_article = get_info_article($_GET['id']);
            if(empty($dn_article))
                redir(Nw::$lang['press']['error_dont_exist'], false, 'press.html');
            
            //Si on a soumis le formulaire
            if(isset($_POST['submit']))
            {
                inc_lib('press/edit_article');
                edit_article($dn_article['p_id'], $_POST['paper'], 
                    $_POST['link'], $_POST['numero'], $_POST['country'], $_POST['contenu'], 
                    $_POST['date_pub']);
                    redir(Nw::$lang['press']['redir_article_edited'], true, 'press.html?article='.$dn_article['p_id']);
            }
        
            $this->set_title($dn_article['p_ressource_name']);
            $this->set_tpl('press/edit.html');
            $this->add_css('code.css');
            $this->add_css('forms.css');
        
            // Fil ariane
            $this->set_filAriane(array(
                Nw::$lang['press']['mod_title']     => array('press.html'),
                $dn_article['p_ressource_name']     => array('press.html?article='.$dn_article['p_id']),
                Nw::$lang['press']['art_edit']      => array(''),
            ));
            
            inc_lib('bbcode/unparse');
        
            Nw::$tpl->set(array(
                'ID'                => $dn_article['p_id'],
                'RESSOURCE'         => $dn_article['p_ressource_name'],
                'DATE'              => $dn_article['date'],
                'LIEN'              => $dn_article['p_link'],
                'CONTENU'           => unparse($dn_article['p_description']),
                'PAYS'              => $dn_article['p_lang'],
                'NUMERO'            => $dn_article['p_num'],
            ));
        }
        else
        {
            redir(Nw::$lang['press']['error_dont_exist'], false, 'press.html');
        }
    }
}

/*  *EOF*   */
