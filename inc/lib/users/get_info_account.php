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

function get_info_account($pseudo, $password)
{
    $bf_token = 'jJ_=éZAç1l';
    $ft_token = 'ù%*àè1ç0°dezf';

    //On s'assure que le compte existe, et on récupère les infos
    $query = Nw::$DB->query('SELECT u_id, u_active
    FROM '.Nw::$prefix_table.'members
    WHERE u_pseudo=\''.insertBD(trim($pseudo)).'\'
        AND u_password=\''.insertBD(sha1($bf_token.$password.$ft_token)).'\'') OR Nw::$DB->trigger(__LINE__, __FILE__);

    return $query->fetch_assoc();
}
