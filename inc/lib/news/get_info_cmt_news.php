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

function get_info_cmt_news($id_comment)
{
    $query = Nw::$DB->query( 'SELECT u_id, u_pseudo, c_id_news, c_id_membre, c_texte, 
        c_date, c_ip, c_plussoie
        FROM '.Nw::$prefix_table.'news_commentaires
        LEFT JOIN '.Nw::$prefix_table.'members ON c_id_membre = u_id
        WHERE c_id = '.intval($id_comment)) OR Nw::$DB->trigger(__LINE__, __FILE__);
    $dn = $query->fetch_assoc();

    return $dn;
}
