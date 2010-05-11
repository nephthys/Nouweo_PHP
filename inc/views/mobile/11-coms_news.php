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
        // Si le paramètre ID manque
        if (empty($_GET['id']) || !is_numeric($_GET['id']))
            header('Location: news-70.html');

        inc_lib('news/news_exists');
        if (news_exists($_GET['id']) == false)
            redir(Nw::$lang['news']['news_not_exist'], false, 'news-70.html');

        inc_lib('news/get_info_news');
        $donnees_news = get_info_news($_GET['id'], $id_version_load);
        
        $this->set_tpl('mobile/news/view_coms.html');
        $this->load_lang_file('news');
        
        /**
        *   Liste des commentaires
        **/
        
        if ($donnees_news['n_nbr_coms'] > 0)
        {
            // Pagination
            $page = ( isset( $_GET['page'] ) ) ? intval($_GET['page']) : 1;
            $nombreDePages = ceil($donnees_news['n_nbr_coms'] / Nw::$pref['nb_cmts_page']);
            
            // On vérifie bien que la page existe
            if ($nombreDePages > 0 && $page > $nombreDePages)
                redir(Nw::$lang['common']['pg_not_exist'], false, $donnees_news['c_rewrite'].'/'.rewrite($donnees_news['n_titre']).'-'.$_GET['id'].'/');
            
            // L'utilisateur demande un commentaire particulier, on le redirige sur la bonne page
            if (!empty($_GET['id2']) && is_numeric($_GET['id2']))
            {
                inc_lib('news/count_cmt_before_idc');
                $nbr_cmts_before = count_cmt_before_idc($_GET['id'], $_GET['id2']);
                $page = ceil($nbr_cmts_before / Nw::$pref['nb_cmts_page']);
            }

            inc_lib('news/get_list_cmt_news');
            $list_cmts = get_list_cmt_news($_GET['id'], 'c_date ASC', $page, Nw::$pref['nb_cmts_page']);
            $com_cours = 0;
            
            // Affichage de tous les commentaires de la page
            foreach($list_cmts AS $donnees_cmts)
            {
                ++$com_cours;
                
                $date_cmt = date_sql($donnees_cmts['date'], $donnees_cmts['heures_date'], $donnees_cmts['jours_date']);
                
                Nw::$tpl->setBlock('cmt', array(
                    'ID'            => $donnees_cmts['c_id'],
                    'NUM'           => (($page-1)*Nw::$pref['nb_cmts_page'])+$com_cours,
                    
                    'DATE'          => $date_cmt,
                    
                    'AVATAR'        => $donnees_cmts['u_avatar'],
                    'LANG_AVATAR'   => sprintf(Nw::$lang['news']['lang_avatar'], $donnees_cmts['u_pseudo']),
                    
                    'AUTEUR'        => $donnees_cmts['u_pseudo'],
                    'AUTEUR_ID'     => $donnees_cmts['u_id'],
                    'AUTEUR_ALIAS'  => $donnees_cmts['u_alias'],
                    
                    'TEXTE'         => $donnees_cmts['c_texte'],
                ));
            }
        }

        Nw::$tpl->set(array(
            'ID'                => $_GET['id'],
            'ETAT'              => $donnees_news['n_etat'],
            
            'TITRE'             => $donnees_news['n_titre'],
            'TITRE_REWRITE'     => rewrite($donnees_news['n_titre']),
            'LIST_PG'           => ($donnees_news['n_nbr_coms'] > 0) ? list_pg($nombreDePages, $page, 'news-10-'.$_GET['id'].'%s.html#c') : '',
        ));
        
        Nw::$tpl->set('INC_HEAD', empty($_SERVER['HTTP_AJAX']));
    }
}

/*  *EOF*   */
