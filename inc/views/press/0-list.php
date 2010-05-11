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
        //Si on a posté un article à voir
        if(!empty($_GET['article']) && is_numeric($_GET['article']))
        {
            inc_lib('press/get_info_article');
            $dn_article = get_info_article($_GET['article']);
            if(empty($dn_article))
                redir(Nw::$lang['press']['error_dont_exist'], false, 'press.html');
        
            $this->set_title($dn_article['p_ressource_name']);
        
            // Fil ariane
            $this->set_filAriane(array(
                Nw::$lang['press']['mod_title']                 => array('press.html'),
                $dn_article['p_ressource_name']                 => array('press.html?article='.$dn_article['p_id']),
                Nw::$lang['press']['art_details']               => array(''),
            ));
        
            Nw::$tpl->set(array(
                'DISPLAY_ARTICLE'   => true,
                'ID'                => $dn_article['p_id'],
                'TITRE'             => sprintf(Nw::$lang['press']['apparition_in'], $dn_article['p_ressource_name']),
                'RESSOURCE'         => $dn_article['p_ressource_name'],
                'DATE'              => $dn_article['date'],
                'LIEN'              => $dn_article['p_link'],
                'CONTENU'           => $dn_article['p_description'],
                'PAYS'              => Nw::$lang['common']['countries'][$dn_article['p_lang']],
                'NUMERO'            => $dn_article['p_num'],
            
                'ID_ADMIN'          => $dn_article['u_id'],
                'PSEUDO_ADMIN'      => $dn_article['u_pseudo'],
            ));
        }
        else
        {
            $this->set_title(Nw::$lang['press']['mod_title']);
            
            // Fil ariane
            $this->set_filAriane(array(
                Nw::$lang['press']['mod_title']                 => array('press.html'),
                Nw::$lang['press']['art_list']                  => array(''),
            ));
            
            Nw::$tpl->set('DISPLAY_ARTICLE', false);
        }
        
        $this->set_tpl('press/list.html');
        $this->add_css('code.css');
        
        //Récupération de la liste des articles
        inc_lib('press/get_list_articles');
        $list_articles = get_list_articles();
        
        foreach($list_articles as $art)
        {
            Nw::$tpl->setBlock('art', array(
                'ID'            => $art['p_id'],
                'TITRE'         => $art['p_ressource_name'].' ('.$art['date'].')'
            ));
        }
    }
}

/*  *EOF*   */
