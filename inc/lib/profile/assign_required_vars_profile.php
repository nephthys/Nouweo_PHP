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

function assign_required_vars_profile($donnees_profile)
{
    Nw::$tpl->set(array(
        'ID'                => $donnees_profile['u_id'],
        'PSEUDO'            => $donnees_profile['u_pseudo'],
        'AVATAR'            => $donnees_profile['u_avatar'],
        'ALIAS'             => $donnees_profile['u_alias'],
        'IDENT_UNIQ'        => $donnees_profile['u_ident_unique'],

        'LOCAL'             => $donnees_profile['u_localisation'],

        'BIO_COURT'         => CoupeChar($donnees_profile['u_bio'], '... <a href="profile-140-'.$donnees_profile['u_id'].'.html">'.Nw::$lang['users']['read_more_bio'].'</a>', 300),
        'BIO'               => $donnees_profile['u_bio'],

        'DATE_REGISTER'     => date_sql($donnees_profile['date_register'], $donnees_profile['heures_date_register'], $donnees_profile['jours_date_register']),
        'LAST_VISIT'        => date_sql($donnees_profile['last_visit'], $donnees_profile['heures_last_visit'], $donnees_profile['jours_last_visit']),

        'GROUPE_TITRE'      => $donnees_profile['g_titre'],
        'GROUPE_ICONE'      => $donnees_profile['g_icone'],
    ));
}
