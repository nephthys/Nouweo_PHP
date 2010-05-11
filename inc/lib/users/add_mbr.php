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

function add_mbr($pseudo, $password, $email, $identifier = '', $valide = 0)
{
    $bf_token = 'jJ_=éZAç1l';
    $ft_token = 'ù%*àè1ç0°dezf';
    $key_alea_code_activate = md5( uniqid( mt_rand() ) );

    // Enregistrement de l'utilisateur dans la base de données
    Nw::$DB->query( 'INSERT INTO '.Nw::$prefix_table.'members (u_pseudo, u_alias, u_identifier, u_password, u_email, u_group, u_date_register, u_active, u_code_act, u_ip)
    VALUES(\''.insertBD(trim($pseudo)).'\', \''.rewrite(trim($pseudo)).'\', \''.insertBD(trim($identifier)).'\', \''.insertBD(sha1($bf_token.trim($password).$ft_token)).'\', \''.insertBD(trim($email)).'\',
    4, NOW(), '.intval($valide).', \''.insertBD($key_alea_code_activate).'\', \''.get_ip().'\')' ) OR Nw::$DB->trigger(__LINE__, __FILE__);

    $id_new_membre = Nw::$DB->insert_id;
    $identifiant_unique = md5($id_new_membre.uniqid(rand(), true));
    $lien_activation = Nw::$site_url.'users-32.html?mid='.$id_new_membre.'&ca='.$key_alea_code_activate;

    Nw::$DB->query( 'UPDATE '.Nw::$prefix_table.'members SET u_ident_unique = \''.Nw::$DB->real_escape_string($identifiant_unique).'\' WHERE u_id = '.intval($id_new_membre)) OR Nw::$DB->trigger(__LINE__, __FILE__);
    Nw::$DB->query( 'INSERT INTO '.Nw::$prefix_table.'members_stats (s_id_membre) VALUES('.intval($id_new_membre).')') OR Nw::$DB->trigger(__LINE__, __FILE__);

    inc_lib('users/mail2gd');
    mail2gd( $identifiant_unique, trim($email) );

    inc_lib('newsletter/add_abonnement');
    add_abonnement(trim($email), $id_new_membre);
    
    // Envoie d'email de validation
    if ($valide == 0)
    {
        $txt_mail = sprintf(Nw::$lang['users']['mail_confirm_insc'], $pseudo, Nw::$site_url, Nw::$site_name, $lien_activation, $lien_activation, $lien_activation);
        @envoi_mail(trim($email), sprintf(Nw::$lang['users']['confirm_inscription'], Nw::$site_name), $txt_mail);
    }
    else
    {
        // Le compte est confirmé, on met à jour le nbr de membres
        inc_lib('admin/gen_cachefile_nb_members');
        gen_cachefile_nb_members();
        generate_members_sitemap();
    }

    return $id_new_membre;
}
