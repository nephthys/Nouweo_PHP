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

function add_abonnement($email, $id_membre)
{
    $token_url = md5(uniqid().time().rand(0, 20));
    Nw::$DB->query('INSERT INTO '.Nw::$prefix_table.'abonnes (a_id_membre, a_email, a_date, a_ip, a_token) VALUES('.intval($id_membre).', \''.insertBD(trim($email)).'\', NOW(), \''.get_ip().'\', \''.$token_url.'\')') OR Nw::$DB->trigger(__LINE__, __FILE__);
}
