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

class Page extends Core
{
    protected function main()
    {
        $this->set_title('hey');
        $this->set_tpl('invit/programme.html');
        
        Nw::$tpl->set('RPX_URL_INVIT', urlencode(Nw::$site_url.'users-40.html?invit'));
        
        if (isset($_POST['submit_invit']) && !empty($_POST['code']))
        {
            $query = Nw::$DB->query( 'SELECT COUNT(*) as count, i_id, i_nb_max_auth, i_nb_auth FROM invits WHERE i_code = \''.insertBD(trim($_POST['code'])).'\' GROUP BY i_id' ) OR Nw::$DB->trigger(__LINE__, __FILE__);
            $dn = $query->fetch_assoc();
            
            if ($dn['count'] > 0)
            {
                if ($dn['i_nb_auth'] < $dn['i_nb_max_auth'])
                {
                    Nw::$DB->query( 'UPDATE invits SET i_nb_auth = i_nb_auth + 1 WHERE i_id = '.intval($dn['i_id']));
                    $_SESSION['nw_invit'] = true;
                    
                    redir('Bienvenue sur la version bêta privée de Nouweo.', true, './');
                }
                else
                    redir('Ce code d\'invitation a expiré.', false, './');
            }
            else
                redir('Ce code d\'invitation n\'existe pas.', false, './');
        }
        
        if (isset($_POST['submit_request']) && !empty($_POST['pseudo']) && !empty($_POST['email']))
        {
            // L'email est bien sous la bonne forme (name@domain.tld)
            if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            {   
                $query = Nw::$DB->query( 'SELECT COUNT(*) as count FROM invits_request WHERE r_email = \''.insertBD(trim($_POST['email'])).'\' GROUP BY r_email' ) OR Nw::$DB->trigger(__LINE__, __FILE__);
                $dn = $query->fetch_assoc();
                
                if ($dn['count'] == 0)
                {
                    Nw::$DB->query( 'INSERT INTO invits_request (r_pseudo, r_email, r_date, r_ip) VALUES(\''.insertBD(trim($_POST['pseudo'])).'\', \''.insertBD(trim($_POST['email'])).'\', NOW(), \''.get_ip().'\')');
                    redir('Vous avez bien été noté sur la liste d\'attente.', true, './');
                }
                else
                    redir('Cette adresse email est déjà utilisée.', false, './');
            }
            else
                redir('Cette adresse email n\'est pas valide.', false, './');
        }
        
        if (isset($_POST['submit_login']) && !empty($_POST['pseudo']) && !empty($_POST['mdp']))
        {
            inc_lib('users/get_info_account');
            if ($dn_info_account = get_info_account($_POST['pseudo'], $_POST['mdp']))
            {
                if ($dn_info_account['u_active']==1)
                {
                    inc_lib('users/connect_auto_user');
                    connect_auto_user($dn_info_account['u_id'], $_POST['mdp'], true);
                    $_SESSION['nw_invit'] = true;
                    
                    redir('Bienvenue sur la version bêta privée de Nouweo.', true, './');
                }
                else
                    redir('Votre compte n\'est pas activé, il ne peut être utilisé.', false, './');
            }
            else
                redir('Aucun compte ne correspond à ce pseudo  et mot de passe.', false, './');
        }
    }
}

/*  *EOF*   */
