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

function connect_auto_user($id_membre, $pass, $connexion_auto = false, $hash_pass=True)
{
    //Si on a coché "Se souvenir de moi", on crée les cookies
    if ($connexion_auto)
    {
        $bf_token = 'jJ_=éZAç1l';
        $ft_token = 'ù%*àè1ç0°dezf';
        
        $pass = ((bool) $hash_pass) ? sha1($bf_token.$pass.$ft_token) : $pass;

        $time_expire = time()+10*365*24*3600;
        setcookie('nw_ident', $id_membre, $time_expire);
        setcookie('nw_pass', insertBD($pass), $time_expire);
    }
    $_SESSION['ident_session'] = $id_membre;
    $_SESSION['nw_invit'] = true;
    $_SESSION['logged'] = true;
}
