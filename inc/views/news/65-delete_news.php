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
        
        // Si le paramètre ID manque
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: ./');
        }

        inc_lib('news/news_exists');
        $count_news_existe = news_exists($_GET['id']);
        
        if ($count_news_existe == false) {
            redir(Nw::$lang['news']['news_not_exist'], false, 'news-70.html');
        }
        
        // Récupération des données de la news
        inc_lib('news/get_info_news');
        $donnees_news = get_info_news($_GET['id']);
        $droit_delete_news = (($donnees_news['n_id_auteur'] == Nw::$dn_mbr['u_id'] && Nw::$droits['can_delete_mynews']) || Nw::$droits['can_delete_news']) ? true : false;
        
        
        if (!$droit_delete_news) {
            redir(Nw::$lang['news']['not_allowed_delete'], false, 'news-70.html');
        }
        
        // Pour rediriger le visiteur d'où il est venu
        if (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], Nw::$site_url) !== false && strpos($_SERVER['HTTP_REFERER'], Nw::$site_url.'news-65-'.$_GET['id'].'.html') === false) {
            $_SESSION['nw_referer_edit'] = $_SERVER['HTTP_REFERER'];
        }
                
        $link_redir = (!empty($_SESSION['nw_referer_edit'])) ? $_SESSION['nw_referer_edit'] : 'news-70.html';
        
        $this->set_title(sprintf(Nw::$lang['news']['title_del_news'], $donnees_news['n_titre']));
        $this->set_tpl('news/delete.html');
        
        $this->add_css('forms.css');
        $this->add_js('ajax.js');
        $this->add_js('admin.js');
        $this->add_form('contenu');
        
        
        // Fil ariane
        $this->set_filAriane(array(
            Nw::$lang['news']['news_section']               => array('news-70.html'),
            $donnees_news['c_nom']                          => array($donnees_news['c_rewrite'].'/'),
            $donnees_news['n_titre']                        => array($donnees_news['c_rewrite'].'/'.rewrite($donnees_news['n_titre']).'-'.$_GET['id'].'/'),
            Nw::$lang['news']['field_delete_news']          => array(''),
        ));
        
        // Formulaire soumis
        if (isset($_POST['submit']))
        {
            inc_lib('news/delete_news');
            delete_news($_GET['id']);
            redir(Nw::$lang['news']['news_deleted'], true, rtrim(Nw::$site_url, '/').'/news-70.html');
        }
        
        if (isset($_POST['no']))    
            header('Location: '.$link_redir);
        
        Nw::$tpl->set(array(
            'ID'                => $_GET['id'],
            'TITRE'             => $donnees_news['n_titre'],
            'PHRASE_DEL'        => sprintf(Nw::$lang['news']['phrase_del_news'], $donnees_news['c_rewrite'], rewrite($donnees_news['n_titre']), $_GET['id'], $donnees_news['n_titre']),
        ));
        
        display_form(array( 
            'contenu'           => '',
        ));
    }
}

/*  *EOF*   */
