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

function add_news_brouillon($etat=1)
{
    inc_lib('bbcode/clearer');
    inc_lib('bbcode/parse');
    $news_private = (isset($_POST['private_news'])) ? 1 : 0;
    $categorie_news = (isset($_POST['cat'])) ? $_POST['cat'] : 0;
    $mod_news_sql = '';

    $contenu_version = Nw::$DB->real_escape_string(parse(htmlspecialchars(trim($_POST['contenu']))));
    $contenu_extrait = Nw::$DB->real_escape_string(CoupeChar(clearer(parse(htmlspecialchars(trim($_POST['contenu'])))), '...', Nw::$pref['long_intro_news']));

    /**
    *   Enregistrement de la news
    **/

    Nw::$DB->query('INSERT INTO '.Nw::$prefix_table.'news (n_id_auteur, n_id_cat, n_titre, n_date, n_last_mod, n_etat, n_private, n_nb_versions, n_resume) VALUES('.intval(Nw::$dn_mbr['u_id']).',
    '.intval($categorie_news).', \''.insertBD(trim($_POST['titre_news'])).'\', NOW(), NOW(), '.$etat.', '.$news_private.', 1, \''.$contenu_extrait.'\')') OR Nw::$DB->trigger(__LINE__, __FILE__);

    $id_last_news = Nw::$DB->insert_id;

    Nw::$DB->query('INSERT INTO '.Nw::$prefix_table.'news_flags (f_id_news, f_id_membre, f_type) VALUES('.intval($id_last_news).', '.intval(Nw::$dn_mbr['u_id']).', 3)' ) OR Nw::$DB->trigger(__LINE__, __FILE__);
    Nw::$DB->query('INSERT INTO '.Nw::$prefix_table.'news_logs (l_id_news, l_id_membre, l_titre, l_action, l_date, l_ip) VALUES('.intval($id_last_news).', '.intval(Nw::$dn_mbr['u_id']).', \''.insertBD(trim($_POST['titre_news'])).'\', 1, NOW(), \''.get_ip().'\')' ) OR Nw::$DB->trigger(__LINE__, __FILE__);
    
    /**
    *   Sources
    **/
    if (count($_POST['sources']) > 0)
    {
        $nbr_sources = 0;
        
        foreach($_POST['sources'] AS $id => $value)
        {
            if (!multi_empty(trim($_POST['sources_nom'][$id]), trim($_POST['sources'][$id])))
            {
                ++$nbr_sources;
                Nw::$DB->query('INSERT INTO '.Nw::$prefix_table.'news_src (src_id_news, src_media, src_url, src_order) VALUES('.intval($id_last_news).', \''.insertBD(trim($_POST['sources_nom'][$id])).'\', \''.insertBD(trim($_POST['sources'][$id])).'\', '.$nbr_sources.')' ) OR Nw::$DB->trigger(__LINE__, __FILE__);
            }
        }
        
        $mod_news_sql .= 'n_nb_src = '.$nbr_sources.', ';
    }
    
    /**
    *   On créé une entée dans la table des versions
    **/
    $nb_mots = strlen(htmlspecialchars(trim($_POST['contenu'])));

    Nw::$DB->query('INSERT INTO '.Nw::$prefix_table.'news_versions (v_id_news, v_id_membre, v_texte, v_date, v_ip, v_nb_mots, v_number, v_raison) VALUES('.intval($id_last_news).',
        '.intval(Nw::$dn_mbr['u_id']).', \''.$contenu_version.'\', NOW(), \''.get_ip().'\', \''.$nb_mots.'\', 1, \''.Nw::$lang['news']['motif_debut'].'\')') OR Nw::$DB->trigger(__LINE__, __FILE__);

    $id_version_news = Nw::$DB->insert_id;

    /**
    *   Associer une image à la news (si celle -ci est remplie)
    **/
    if (!empty($_FILES['file']['name']))
    {
        inc_lib('news/add_img_news');
        $id_last_image = add_img_news($id_last_news);

        if ($id_last_image) {
            $mod_news_sql .= 'n_id_image = '.intval($id_last_image).', ';
        }
    }

    Nw::$DB->query('UPDATE '.Nw::$prefix_table.'news 
        SET '.$mod_news_sql.'n_last_version = '.intval($id_version_news).'
        WHERE n_id = '.intval($id_last_news)) OR Nw::$DB->trigger(__LINE__, __FILE__);
    Nw::$DB->query('UPDATE '.Nw::$prefix_table.'members_stats 
        SET s_nb_news = s_nb_news + 1
        WHERE s_id_membre = '.intval(Nw::$dn_mbr['u_id'])) OR Nw::$DB->trigger(__LINE__, __FILE__);

    /**
    *   Tags
    **/
    if (!empty($_POST['tags']) && strlen(trim($_POST['tags'])) > 0)
    {
        $tags_news = explode(',', $_POST['tags']);
        $position_tag = 0;

        inc_lib('news/add_tag_news');
        foreach ($tags_news AS $tag) {
            if (!empty($tag) && strlen(trim($tag)) > 0) {
                ++$position_tag;
                add_tag_news($id_last_news, $tag, $position_tag);
            }
        }
    }
}
