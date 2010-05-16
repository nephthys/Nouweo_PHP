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

function edit_news($id, $author = false)
{
    inc_lib('bbcode/parse');
    inc_lib('bbcode/clearer');
    $add_champs_sql = array();
    $content_news = $_POST['contenu'];
    
    $requete_news = Nw::$DB->query('SELECT n_etat, n_titre FROM '.Nw::$prefix_table.'news WHERE n_id = '.intval($id)) OR Nw::$DB->trigger(__LINE__, __FILE__);
    $donnees_news = $requete_news->fetch_assoc();

    /**
    *   Le membre peut-il changer l'état de la news et mettre à jour sa date ?
    **/
    if (Nw::$droits['mod_news_status']) 
    {
        if(isset($_POST['maj_dat']))
        {
            $add_champs_sql[] = 'n_date = NOW()';
            Nw::$DB->query('INSERT INTO '.Nw::$prefix_table.'news_logs (l_id_news, l_id_membre, l_action, l_date, l_ip) VALUES('.intval($id).', '.intval(Nw::$dn_mbr['u_id']).', 3, NOW(), \''.get_ip().'\')' ) OR Nw::$DB->trigger(__LINE__, __FILE__);
        }
        
        // Si on change l'état
        if ($_POST['etat'] != $donnees_news['n_etat'])
        {
            $texte_log = sprintf(Nw::$lang['news']['log_chg_etat'], Nw::$lang['news']['log_etat_'.$donnees_news['n_etat']], Nw::$lang['news']['log_etat_'.$_POST['etat']]);
            Nw::$DB->query('INSERT INTO '.Nw::$prefix_table.'news_logs (l_id_news, l_id_membre, l_action, l_texte, l_date, l_ip) VALUES('.intval($id).', '.intval(Nw::$dn_mbr['u_id']).', 1'.intval($_POST['etat']).', \''.$texte_log.'\', NOW(), \''.get_ip().'\')' ) OR Nw::$DB->trigger(__LINE__, __FILE__);
            
            $add_champs_sql[] = 'n_etat = '.intval($_POST['etat']);
        }
        
        if (isset($_POST['maj_dat']) && $_POST['etat'] != $donnees_news['n_etat'] && $_POST['etat'] == 3)
        {
            inc_lib('admin/post_twitt_news');
            $return_alias = post_twitt_news($id);
            
            if (!empty($return_alias) && strlen(trim($return_alias)) > 0)
                $add_champs_sql[] = 'n_miniurl = \''.insertBD($return_alias).'\'';
        }
        
        // Suppression des commentaires
        if (isset($_POST['delete_comments']))
        {
            inc_lib('news/delete_all_cmt');
            delete_all_cmt($id);
        }
    }

    /**
    *   Si c'est l'auteur, le membre peut modifier le titre, la catégorie et
    *   les tags
    **/
    if ($author)
    {
        $news_private = ( isset($_POST['private_news']) ) ? 1 : 0;
        $is_breve = (isset($_POST['is_breve'])) ? $_POST['is_breve'] : 0;
        
        // Si on change le titre
        if ($_POST['titre_news'] != $donnees_news['n_titre'])
        {
            $texte_log = Nw::$DB->real_escape_string(sprintf(Nw::$lang['news']['log_chg_titre'], $donnees_news['n_titre'], $_POST['titre_news']));
            Nw::$DB->query('INSERT INTO '.Nw::$prefix_table.'news_logs (l_id_news, l_id_membre, l_action, l_texte, l_date, l_ip) VALUES('.intval($id).', '.intval(Nw::$dn_mbr['u_id']).', 4, \''.$texte_log.'\', NOW(), \''.get_ip().'\')' ) OR Nw::$DB->trigger(__LINE__, __FILE__);
            
            $add_champs_sql[] = 'n_titre = \''.insertBD(trim($_POST['titre_news'])).'\'';
        }
        
        $add_champs_sql[] = 'n_id_cat = '.intval($_POST['cat']);
        $add_champs_sql[] = 'n_private = '.$news_private;
        $add_champs_sql[] = 'n_breve = '.$is_breve;
        
        /**
        *   Sources
        **/
        $nbr_sources = 0;
        Nw::$DB->query('DELETE FROM '.Nw::$prefix_table.'news_src WHERE src_id_news = '.intval($id)) OR Nw::$DB->trigger(__LINE__, __FILE__);
                
        if (count($_POST['sources']) > 0)
        {
            foreach($_POST['sources'] AS $id_src => $value)
            {
                if (!multi_empty(trim($_POST['sources_nom'][$id_src]), trim($_POST['sources'][$id_src])))
                {
                    ++$nbr_sources;
                    Nw::$DB->query('INSERT INTO '.Nw::$prefix_table.'news_src (src_id_news, src_media, src_url, src_order) VALUES('.intval($id).', \''.insertBD(trim($_POST['sources_nom'][$id_src])).'\', \''.insertBD(trim($_POST['sources'][$id_src])).'\', '.$nbr_sources.')' ) OR Nw::$DB->trigger(__LINE__, __FILE__);
                }
            }
        }
        
        $add_champs_sql[] = 'n_nb_src = '.$nbr_sources;
    
        // Tags
        if (!empty($_POST['tags']) && strlen(trim( $_POST['tags'])) > 0)
        {
            Nw::$DB->query('DELETE FROM '.Nw::$prefix_table.'tags
                WHERE t_id_news = '.intval($id)) OR Nw::$DB->trigger(__LINE__, __FILE__);
            $tags_news = explode(',', $_POST['tags']);
            $num_tag = 0;

            inc_lib('news/add_tag_news');
            foreach ($tags_news AS $tag) {
                if (!empty($tag) && strlen(trim($tag)) > 0) {
                    ++$num_tag;
                    add_tag_news($id, $tag, $num_tag);
                }
            }
        }

        /**
        *   Associer une image à la news (si celle -ci est remplie)
        **/
        if (!empty($_FILES['file']['name']))
        {
            inc_lib('news/add_img_news');
            $id_last_image = add_img_news($id);

            if ($id_last_image)
                $add_champs_sql[] = 'n_id_image = '.intval($id_last_image);
        }
    }

    $count_flag = Nw::$DB->query('SELECT f_type 
        FROM '.Nw::$prefix_table.'news_flags
        WHERE f_id_news = '.intval($id).' AND f_id_membre = '.intval(Nw::$dn_mbr['u_id'])) OR Nw::$DB->trigger(__LINE__, __FILE__);
    $donnees_count = $count_flag->fetch_assoc();

    // Si le membre n'a pas déjà contribé à la news, on lui met le flag
    if($donnees_count['f_type'] != 3 && $donnees_count['f_type'] != 2)
    {
        Nw::$DB->query('INSERT INTO '.Nw::$prefix_table.'news_flags (f_id_news, f_id_membre, f_type)
            VALUES('.intval($id).', '.intval(Nw::$dn_mbr['u_id']).', 2)' ) OR Nw::$DB->trigger(__LINE__, __FILE__);
    }

    $contenu_version = Nw::$DB->real_escape_string(parse(htmlspecialchars(trim($content_news))));

    /**
    *   On recherche la dernière version de la news
    **/
    $donnees_version = Nw::$DB->query('SELECT v_texte, v_nb_mots, v_number 
        FROM '.Nw::$prefix_table.'news_versions
        WHERE v_id_news = '.intval($id).'
        ORDER BY v_date DESC
        LIMIT 1') OR Nw::$DB->trigger(__LINE__, __FILE__);
    $last_version = $donnees_version->fetch_assoc();

    // Si le texte de l'ancienne version n'est pas le même que celui proposé
    if($last_version['v_texte'] != parse(htmlspecialchars(trim($content_news))))
    {
        $raison_edition = Nw::$DB->real_escape_string(htmlspecialchars($_POST['raison']));
        $version_mineure = (isset($_POST['mini_contrib'])) ? 1 : 0;
        $nb_mots = strlen(htmlspecialchars(trim($content_news)));
        $diff_mots = ($nb_mots-$last_version['v_nb_mots']);

        // On créé une entrée dans la table des versions
        Nw::$DB->query('INSERT INTO '.Nw::$prefix_table.'news_versions (v_id_news,
            v_id_membre, v_texte, v_date, v_ip, v_raison, v_nb_mots, v_diff_mots, v_number, v_mineure)
            VALUES('.intval($id).', '.intval(Nw::$dn_mbr['u_id']).', \''.$contenu_version.'\',
            NOW(), \''.get_ip().'\', \''.$raison_edition.'\', \''.$nb_mots.'\', \''.$diff_mots.'\', '.($last_version['v_number']+1).', '.$version_mineure.')') OR Nw::$DB->trigger(__LINE__, __FILE__);

        $id_version_news = Nw::$DB->insert_id;

        Nw::$DB->query('UPDATE '.Nw::$prefix_table.'members_stats 
            SET s_nb_contrib = s_nb_contrib + 1
            WHERE s_id_membre = '.intval(Nw::$dn_mbr['u_id'])) OR Nw::$DB->trigger(__LINE__, __FILE__);

        $contenu_extrait = Nw::$DB->real_escape_string(CoupeChar(clearer(parse(htmlspecialchars(trim($content_news)))), '...', Nw::$pref['long_intro_news']));

        //die('<br />'.$contenu_extrait);

        $add_champs_sql[] = 'n_resume = \''.$contenu_extrait.'\'';
        $add_champs_sql[] = 'n_last_version = '.intval($id_version_news);
        $add_champs_sql[] = 'n_last_mod = NOW()';
        $add_champs_sql[] = 'n_nb_versions = n_nb_versions + 1';
    }
    
    if(count($add_champs_sql) > 0) 
    {
        // On met à jour l'entrée de la news avec l'id de la version
        Nw::$DB->query('UPDATE '.Nw::$prefix_table.'news SET '.implode(', ', $add_champs_sql).' WHERE n_id = '.intval($id)) OR Nw::$DB->trigger(__LINE__, __FILE__);
        
        if ($donnees_news['n_etat'] == 3 || $_POST['etat'] == 3)
        {
            generate_news_sitemap();
            generate_categories_sitemap();
        }
    }
}
