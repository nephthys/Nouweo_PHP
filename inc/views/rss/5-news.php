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
        header('Content-Type: text/xml'); 
        
        $this->set_title(Nw::$lang['rss']['rss_title']);
        $this->set_tpl('rss/news.html');
        $this->set_filAriane(Nw::$lang['rss']['rss_title']);
        
        $params = array();
        $params[] = 'n_etat = 3';
        
        if (!empty($_GET['id']) && is_numeric($_GET['id']))
            $params[] = 'n_id_cat = '.intval($_GET['id']);

        inc_lib('rss/list_news_rss');
        $list_dn_news = list_news_rss(20, $params);
        
        foreach($list_dn_news AS $donnees_news)
        {
            Nw::$tpl->setBlock('news', array(
                'ID'            => $donnees_news['n_id'],
                
                'CAT_ID'        => $donnees_news['c_id'],
                'CAT_TITRE'     => $donnees_news['c_nom'],
                'CAT_REWRITE'   => $donnees_news['c_rewrite'],
                
                'IMAGE_ID'      => $donnees_news['i_id'],
                'IMAGE_NOM'     => $donnees_news['i_nom'],
                'IMAGE_TAILLE'  => (file_exists('upload/th2/'.$donnees_news['i_nom'].'_'.$donnees_news['n_id'].'.jpg')) ? filesize('upload/th2/'.$donnees_news['i_nom'].'_'.$donnees_news['n_id'].'.jpg') : 0,
                
                'TITRE'         => $donnees_news['n_titre'],
                'RESUME'        => $donnees_news['v_texte'],
                'REWRITE'       => rewrite($donnees_news['n_titre']),
                
                'AUTEUR'        => $donnees_news['u_pseudo'],
                'AUTEUR_ID'     => $donnees_news['u_id'],
                'AUTEUR_ALIAS'  => $donnees_news['u_alias'],
                'AUTEUR_AVATAR' => $donnees_news['u_avatar'],
                
                'DATE'          => $donnees_news['date'],
                
            ) );
        }
        
        Nw::$tpl->set(array(
            'EMAIL'     => Nw::$site_email,
            
        ));
    }
}

/*  *EOF*   */
