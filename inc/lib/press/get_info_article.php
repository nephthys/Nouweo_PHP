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

/**
 *  Retourne les infos sur un article.
 *  @param integer $id      L'id de l'article.
 *  @return array
 */
function get_info_article($id)
{
    $rqt_list_articles = Nw::$DB->query('SELECT p_id, p_ressource_name, p_link,
        p_lang, '.decalageh('p_date', 'date', DATE).', p_description, p_num, p_description,
        u_id, u_pseudo
        FROM '.Nw::$prefix_table.'press
        LEFT JOIN '.Nw::$prefix_table.'members ON p_id_admin = u_id
        WHERE p_id = '.intval($id)) OR Nw::$DB->trigger(__LINE__, __FILE__);

    return $rqt_list_articles->fetch_assoc();
}
