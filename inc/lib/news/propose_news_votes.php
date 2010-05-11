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

function propose_news_votes($id)
{
    Nw::$DB->query('UPDATE '.Nw::$prefix_table.'news
        SET n_date = NOW(), n_last_mod = NULL, n_private = 0, n_etat = 2
        WHERE n_id = '.intval($id)) OR Nw::$DB->trigger(__LINE__, __FILE__);
        
    Nw::$DB->query('INSERT INTO '.Nw::$prefix_table.'news_logs (l_id_news, l_id_membre, l_action, l_date, l_ip) VALUES('.intval($id).', '.intval(Nw::$dn_mbr['u_id']).', 12, NOW(), \''.get_ip().'\')' ) OR Nw::$DB->trigger(__LINE__, __FILE__);
}
