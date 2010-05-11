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
        $this->set_title(Nw::$lang['news']['en_attente_title']);
        $this->set_tpl('news/list_versions.html');

        inc_lib('news/news_exists');
        $count_news_existe = news_exists($_GET['id']);
        
        if ($count_news_existe == false) {
            redir(Nw::$lang['news']['news_not_exist'], false, 'news-70.html');
        }

        inc_lib('news/get_info_news');
        $donnees_news = get_info_news($_GET['id']);
        
        // Fil ariane
        $this->set_filAriane(array(
            Nw::$lang['news']['news_section']       => array('news-70.html'),
            $donnees_news['c_nom']                  => array($donnees_news['c_rewrite'].'/'),
            $donnees_news['n_titre']                => array($donnees_news['c_rewrite'].'/'.rewrite($donnees_news['n_titre']).'-'.$_GET['id'].'/'),
            Nw::$lang['news']['gestion_vrs']        => array('news-16-'.$_GET['id'].'.html'),
            Nw::$lang['news']['list_vrs_fa']        => array(''),
        ));
        
        // Ce membre a le droit d'éditer la news ?
        if ($donnees_news['n_etat'] != 3 && !is_logged_in()) {
            redir(Nw::$lang['news']['not_view_news_perm'], false, './');
        }
        
        // Redirection pour comparer 2 versions
        if(isset($_POST['compare_diff']) && isset($_POST['rev_old']) && isset($_POST['rev_new'])) {
            if($_POST['rev_new'] != $_POST['rev_old']) {
                header('Location: news-16-'.$_GET['id'].'.html?vrs1='.intval($_POST['rev_old']).'&vrs2='.intval($_POST['rev_new']));
            }
        }
        
        /**
        *   Comparaison de 2 versions d'une news
        *   Requiert SHELL /!\ 
        **/
        
        $output_compare = '';
        $compare_versions = false;
        $news_vrs1 = 0;
        $news_vrs2 = 0;
        
        if ($donnees_news['n_nb_versions'] > 1)
        {
            // Parser BBcode
            inc_lib('bbcode/unparse');
            
            $news_vrs1 = 0;
            $news_vrs2 = 0;
            $id_unique = uniqid();
            
            // On compare 2 versions
            if(isset($_GET['vrs1']) && is_numeric($_GET['vrs1']) && isset($_GET['vrs2']) && is_numeric($_GET['vrs2']))
            {
                $news_vrs1 = $_GET['vrs1'];
                $news_vrs2 = $_GET['vrs2'];
                
                $compare_versions = true;
            }

            inc_lib('news/get_compare_text_vrs');
            $textes_compare = get_compare_text_vrs($_GET['id'], $news_vrs1, $news_vrs2);
            
            $news_vrs1 = (isset($textes_compare[1][0])) ? $textes_compare[1][0] : '';
            $news_vrs2 = (isset($textes_compare[0][0])) ? $textes_compare[0][0] : '';
            
            function clean_cache_file($content)
            {
                $content = explode("\r", trim($content));
                $array_return = array();
                
                foreach($content AS $texte_trim)
                    if(strlen(trim($texte_trim)) > 0)
                        $array_return[] = trim($texte_trim);
                        
                return $array_return;
            }
            
            /**
            *   Utilisation de la classe Text_diff (http://pear.php.net/package/Text_Diff/download/1.1.0)
            **/
            
            include_once('Text/Diff.php');
            include_once('Text/Diff/Renderer/unified.php');
            
            $lines1 = clean_cache_file(unparse($textes_compare[1][1], 0));
            $lines2 = clean_cache_file(unparse($textes_compare[0][1], 0));
            
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
        }
        
        
        /**
        *   Affichage de la liste des versions
        **/
        inc_lib('news/get_list_vrs');
        $numeros_version = array();
        $donnees_version = get_list_vrs($_GET['id']);
        
        foreach($donnees_version AS $donnees)
        {
            Nw::$tpl->setBlock('versions', array(
                'ID'            => $donnees['v_id'],
                'NUM'           => $donnees['v_number'],
                'AUTEUR'        => $donnees['u_pseudo'],
                'AUTEUR_ID'     => $donnees['u_id'],
                'AUTEUR_ALIAS'  => $donnees['u_alias'],
                
                'MINEURE'       => $donnees['v_mineure'],
                'DATE'          => date_sql($donnees['date'], $donnees['heures_date'], $donnees['jours_date']),
                'COMMENT'       => $donnees['v_raison'],
                'IP'            => long2ip($donnees['v_ip']),
            ) );
            
            $numeros_version[$donnees['v_id']] = $donnees['v_number'];
        }
        
        $numbers_vrs = array_flip($numeros_version);
        $texte_compare = '';
        
        if ($donnees_news['n_nb_versions'] > 1)
        {
            $texte_compare = sprintf(Nw::$lang['news']['comparaison_2_versions'], $numeros_version[$news_vrs1], $numeros_version[$news_vrs2]);
        }
        
        $droit_edit_news = false;
        
        if (is_logged_in())
		{
            inc_lib('news/can_edit_news');
            $droit_edit_news = can_edit_news($donnees_news['n_id_auteur'], $donnees_news['n_etat']);
        }
        
        
        Nw::$tpl->set(array(
            'ID'                => $_GET['id'],
            'TITRE'             => $donnees_news['n_titre'],
            'REWRITE'           => rewrite($donnees_news['n_titre']),
            'CAT_REWRITE'       => $donnees_news['c_rewrite'],
            'COMPARAISON'       => $output_compare,
            'LAST_VERSION'      => $donnees_news['n_last_version'],
            'NB_VERSIONS'       => $donnees_news['n_nb_versions'],
            'NUMBERS_VRS'       => $numbers_vrs,
            'TXT_COMPARE'       => $texte_compare,
            'DRT_VIEW_IP'       => (is_logged_in() && Nw::$droits['can_see_ip']),
            
            'NUM_OLD'           => $news_vrs1,
            'NUM_NEW'           => $news_vrs2,
            
            'NEWS_AUTEUR'       => $donnees_news['n_id_auteur'],
            'CHG_MY_VERSIONS'   => (is_logged_in() && Nw::$droits['can_change_version_my_news']),
            'CHG_ALL_VERSIONS'  => (is_logged_in() && Nw::$droits['can_change_version_all_news']),
            'DLT_VERSIONS'      => (is_logged_in() && Nw::$droits['can_delete_version']),
            
            'LINK_NB_CONTRIB'   => sprintf(Nw::$lang['news']['edit_nb_contrib'], $donnees_news['n_nb_versions']),
            'DRT_EDIT'          => $droit_edit_news,
        ));
        
    }
}

/*  *EOF*   */
