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
        
        // Si le paramètre ID manque
        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: news-70.html');
        }

        inc_lib('news/news_exists');
        $count_news_existe = news_exists($_GET['id']);
        
        if ($count_news_existe == 0) {
            redir(Nw::$lang['news']['news_not_exist'], false, 'news-70.html');
        }

        inc_lib('news/get_info_news');
        $donnees_news = get_info_news($_GET['id']);
        
        // Ce membre a le droit d'éditer la news ?
        inc_lib('news/can_edit_news');
        if (!can_edit_news($donnees_news['n_id_auteur'], $donnees_news['n_etat'])) {
            redir(Nw::$lang['news']['not_edit_news_perm'], false, 'news-70.html');
        }
        
        // Est-ce que le membre peut éditer le titre, la catégorie et les tags de la news ?
        inc_lib('news/can_edit_news_related');
        $edit_related = can_edit_news_related($donnees_news['n_id_auteur'], $donnees_news['n_etat']);
        $edition_grilled = false;
        
        $this->set_title(sprintf(Nw::$lang['news']['title_edit_news'], $donnees_news['n_titre']));
        $this->set_tpl('news/edit_news.html');
        $this->add_css('forms.css');
        $this->add_css('code.css');
        $this->add_js('write.js');
        $this->add_form('contenu');
        
        // Pour rediriger le visiteur d'où il est venu
        if (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], Nw::$site_url) !== false && strpos($_SERVER['HTTP_REFERER'], Nw::$site_url.'news-60-'.$_GET['id'].'.html') === false) {
            $_SESSION['nw_referer_edit'] = $_SERVER['HTTP_REFERER'];
        }
        
        $link_redir = (!empty($_SESSION['nw_referer_edit'])) ? $_SESSION['nw_referer_edit'] : 'news-60-'.intval($_GET['id']).'.html';   
        
        // Fil ariane
        $this->set_filAriane(array(
            Nw::$lang['news']['news_section']               => array('news-70.html'),
            $donnees_news['c_nom']                          => array($donnees_news['c_rewrite'].'/'),
            $donnees_news['n_titre']                        => array($donnees_news['c_rewrite'].'/'.rewrite($donnees_news['n_titre']).'-'.$_GET['id'].'/'),
            Nw::$lang['news']['edit_fil_ariane']            => array(''),
        ));
        
        $array_status = array(
            3   => Nw::$lang['news']['etat_news_3'],
            2   => Nw::$lang['news']['etat_news_2'],
            1   => Nw::$lang['news']['etat_news_1'],
            0   => Nw::$lang['news']['etat_news_0']
        );
        
        $list_src = array();
        $list_src_url = array();
        $position = 0;
        
        if ($donnees_news['n_nb_src'] > 0)
        {
            inc_lib('news/get_list_src');
            $donnees_src = get_list_src($_GET['id']);
            
            foreach($donnees_src AS $donnees)
            {
                ++$position;
                $list_src[$position]        = $donnees['src_media'];
                $list_src_url[$position]    = $donnees['src_url'];
                
                Nw::$tpl->setBlock('src', array(
                    'ID'            => $position,
                ));
            }
        }
        
        Nw::$tpl->set(array(
            'ID'                => $_GET['id'],
            'TITRE'             => $donnees_news['n_titre'],
            'REWRITE'           => rewrite($donnees_news['n_titre']),
            'CAT_REWRITE'       => $donnees_news['c_rewrite'],
            'ID_CAT'            => $donnees_news['n_id_cat'],
            
            'IMAGE_ID'          => $donnees_news['i_id'],
            'IMAGE_NOM'         => $donnees_news['i_nom'],
            
            'LINK_NB_CONTRIB'   => sprintf(Nw::$lang['news']['edit_nb_contrib'], $donnees_news['n_nb_versions']),
            'LAST_VERSION'      => $donnees_news['n_last_version'],
            'BAL_CHAMP'         => 'contenu',
            
            'ETAT_ACTUEL'       => $donnees_news['n_etat'],
            'ETATS_NEWS'        => $array_status,
                
            'EDIT_RELATED'      => $edit_related,
            'MOD_STATUS'        => Nw::$droits['mod_news_status'],
            
            'GRILLED'           => false,
            'MAX_SRC'           => ($position == 0) ? $position+1 : $position,
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
                'sources'           => (isset($_POST['sources'])) ? $_POST['sources'] : '',
                'sources_nom'       => (isset($_POST['sources_nom'])) ? $_POST['sources_nom'] : '',
            );
            
            $var_titre = trim($_POST['titre_news']);
            $var_content = trim($_POST['contenu']);
            
            // Les champs titre & contenu & source ne sont pas vides
            if ( ( $edit_related && !multi_empty($var_titre, $var_content) ) || ( !$edit_related && !empty($var_content) ) )
            {
                // On édite la news
                inc_lib('news/count_anti_grille');
                inc_lib('news/edit_news');
                
                $anti_grille = count_anti_grille($_GET['id'], $_POST['last_version']);
                
                if ($anti_grille['count'])
                {
                    inc_lib('bbcode/parse');
                    inc_lib('bbcode/unparse');
                    inc_lib('news/get_info_vrs');
                    
                    $output_compare = '';
                    $dn_vrs_grilled = get_info_vrs($donnees_news['n_last_version']);
                    
                    $news_vrs1 = $dn_vrs_grilled['v_texte'];
                    $news_vrs2 = parse($_POST['contenu']);
                    
                    function clean_cache_file($content)
                    {
                        $content = explode("\r", trim($content));
                        $array_return = array();
                        
                        foreach($content AS $texte_trim)
                            if(strlen(trim($texte_trim)) > 0)
                                $array_return[] = trim($texte_trim);
                                
                        return $array_return;
                    }

                    include_once('Text/Diff.php');
                    include_once('Text/Diff/Renderer/unified.php');
                    
                    $lines1 = clean_cache_file(unparse($news_vrs1, 0));
                    $lines2 = clean_cache_file(unparse($news_vrs2, 0));
                    
                    $diff = new Text_Diff($lines1, $lines2);

                    $renderer = new Text_Diff_Renderer_unified();
                    $array_compare = explode("\n", $renderer->render($diff));

                    foreach($array_compare AS $donnees)
                    {
                        $first_cararacter = '';
                        $style_line = '';
                                    
                        if(isset($donnees[0]) && in_array($donnees[0], array('-', '+')))
                        {
                            if($donnees[0] == '-')
                                $style_line = ' style="background-color: #ffcccc;"';
                            elseif($donnees[0] == '+')
                                $style_line = ' style="background-color: #ccffcc;"';
                                            
                            $first_cararacter = $donnees[0];
                            $ligne_changee = substr($donnees, 1);
                        }
                        else
                        {
                            $ligne_changee = $donnees;
                        }
                                    
                        if(!in_array(substr($donnees, 0, 2), array('@@')) && strlen(trim($ligne_changee)) > 0)
                        {
                            $output_compare .= '<tr>
                                <td class="line_statut">'.$first_cararacter.'</td>
                                <td'.$style_line.'>'.trim($ligne_changee).'</td>
                            </tr>';
                        }
                    }
            
                    display_form($array_post);
                    
                    Nw::$tpl->set(array(
                        'GRILLED'           => true,
                        'COMPARAISON'       => $output_compare,
                        'TEXTE_GRILLED'     => sprintf(Nw::$lang['news']['mbr_grilled_edit'], $dn_vrs_grilled['u_alias'], $dn_vrs_grilled['u_pseudo']),
                    ));
                    
                }
                else
                {
                    edit_news($_GET['id'], $edit_related);
                    redir(Nw::$lang['news']['msg_news_edit'], true, $link_redir);
                }
                
            }
            else
                display_form($array_post, Nw::$lang['news']['title_content_oblig']); return;
        }
        
        // Si l'auteur veut supprimer la news
        if(isset($_GET['imgdel']) && is_numeric($_GET['imgdel']) && $edit_related)
        {
            inc_lib('news/delete_img_news');
            delete_img_news($_GET['imgdel'], $_GET['id']);
            redir(Nw::$lang['news']['msg_image_delete'], true, 'news-60-'.$_GET['id'].'.html');
        }
        
        // Catégories de news
        foreach(Nw::$cache_categories AS $idcs => $donnees_categorie)
        {
            Nw::$tpl->setBlock('cats_news', array(
                'ID'        => $idcs,
                'TITRE'     => $donnees_categorie[0],
            ));
        }

        inc_lib('news/get_list_tags_news');
        $list_tags = get_list_tags_news(0, $_GET['id']);
        $list_tags_html = '';
        
        foreach($list_tags AS $dn_tags)
            $list_tags_html .= $dn_tags['t_tag'].', ';
        
        
        // On affiche le template
        inc_lib('bbcode/unparse');
        display_form(array( 
                'is_breve'          => $donnees_news['n_breve'],
                'titre_news'        => $donnees_news['n_titre'],
                'cat'               => 0,
                'contenu'           => unparse($donnees_news['v_texte']),
                'tags'              => substr($list_tags_html, 0, -2),
                'private_news'      => $donnees_news['n_private'],
                'sources'           => $list_src_url,
                'sources_nom'       => $list_src,
            )
        );
    }
}

/*  *EOF*   */
