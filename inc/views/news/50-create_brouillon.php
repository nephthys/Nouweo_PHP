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
        // Seuls les membres peuvent créer des brouillons
        if (!is_logged_in()) {
            redir(Nw::$lang['common']['need_login'], false, 'users-10.html');
        }
        
        if (!Nw::$droits['can_create_brouillon']) {
            redir(Nw::$lang['news']['cant_create_brouillon'], false, 'news-70.html');
        }
        
        $this->set_title(Nw::$lang['news']['title_create_brouillon']);
        $this->set_tpl('news/create_brouillon.html');
        $this->add_css('forms.css');
        $this->add_css('code.css');
        $this->add_js(array('write.js', 'jquery.blockUI.js'));
        $this->add_form('contenu');
        
        // Fil ariane
        $this->set_filAriane(array(
            Nw::$lang['news']['news_section']               => array('news-70.html'),
            Nw::$lang['news']['title_create_brouillon']     => array('')
        ));
        
        Nw::$tpl->set(array(
            'BAL_CHAMP'         => 'contenu',
        ));
        
        // Formulaire soumis
        if (isset($_POST['submit']))
        {
            $array_post = array(
                'is_breve'          => (isset($_POST['is_breve'])) ? $_POST['is_breve'] : '',
                'titre_news'        => $_POST['titre_news'],
                'cat'               => (isset($_POST['cat'])) ? $_POST['cat'] : 0,
                'contenu'           => $_POST['contenu'],
                'tags'              => (isset($_POST['tags'])) ? $_POST['tags'] : '',
                'private_news'      => (isset($_POST['private_news'])),
                'source'            => (isset($_POST['source'])) ? $_POST['source'] : '',
                'source_nom'        => (isset($_POST['source_nom'])) ? $_POST['source_nom'] : '',
            );
            
            // Les champs titre & contenu ne sont pas vides
            if (!multi_empty(trim($_POST['titre_news']), trim($_POST['contenu'])))
            {
                // On créé la news
                inc_lib('news/add_news_brouillon');
                add_news_brouillon();
                
                redir(Nw::$lang['news']['brouillon_cree'], true, 'news-70.html');
            }
            else
                display_form($array_post, Nw::$lang['news']['title_content_oblig']); return;
        }
        
        
        // Catégories de news
        foreach(Nw::$cache_categories AS $idcs => $donnees_categorie)
        {
            Nw::$tpl->setBlock('cats_news', array(
                'ID'        => $idcs,
                'TITRE'     => $donnees_categorie[0],
            ));
        }
        
        // On affiche le template
        display_form(array( 
                'is_breve'          => '',
                'titre_news'        => '',
                'cat'               => 0,
                'contenu'           => '',
                'tags'              => '',
                'private_news'      => 0,
                'source'            => '',
                'source_nom'        => '',
            )
        );
    }
}

/*  *EOF*   */