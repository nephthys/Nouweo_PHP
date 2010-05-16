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

function get_info_news($id_news, $id_version = 0)
{
    $id_version_sql = ($id_version != 0) ? intval($id_version) : 'n_last_version';
    $add_champs_sql = '';
    $add_jointure_sql = '';

    // Si l'utilisateur est connecté
    if (is_logged_in())
    {
        $add_champs_sql .= ', f_id_membre, f_type, vp.v_id_membre';
        $add_jointure_sql .= ' LEFT JOIN '.Nw::$prefix_table.'news_flags ON (n_id = f_id_news AND f_type = 1 AND f_id_membre = '.intval(Nw::$dn_mbr['u_id']).')';
        $add_jointure_sql .= ' LEFT JOIN '.Nw::$prefix_table.'news_vote vp ON (n_id = vp.v_id_news AND vp.v_id_membre = '.intval(Nw::$dn_mbr['u_id']).' AND vp.v_etat = n_etat)';
    }

    // Rqt SQL
    $rqt_dn_news = Nw::$DB->query('SELECT c_id, c_rewrite, c_nom, v_texte, n_nb_votes, n_nb_votes_neg, n_resume, n_nb_src,
        n_last_version, n_nb_versions, n_id, n_id_auteur, n_id_cat, n_titre, n_breve, n_etat,
        n_vues, n_private, n_nbr_coms, i_id, i_nom, '.decalageh('n_date', 'date_news').',
        u_id, u_pseudo, u_alias, u_avatar, u_bio'.$add_champs_sql.'
        FROM '.Nw::$prefix_table.'news
            LEFT JOIN '.Nw::$prefix_table.'members ON n_id_auteur = u_id
            LEFT JOIN '.Nw::$prefix_table.'categories ON c_id = n_id_cat
            LEFT JOIN '.Nw::$prefix_table.'news_versions ON (v_id_news = n_id AND v_id = '.$id_version_sql.')
            LEFT JOIN '.Nw::$prefix_table.'news_images ON i_id = n_id_image'.$add_jointure_sql.'
        WHERE n_id = '.intval($id_news)) OR Nw::$DB->trigger(__LINE__, __FILE__);

    return $rqt_dn_news->fetch_assoc();
}
