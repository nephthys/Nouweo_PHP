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

abstract class Session 
{
    /**
     *  Un utilisateur tente de connecter avec les cookies
     *  @author Cam
     *  @param $id      ID du membre
     *  @param $pass        Mot de passe (crypté)
     *  @return integer
     */
    public static function count_exit_cookies($id, $pass)
    {
        $query = Nw::$DB->query('SELECT COUNT(*) as count FROM '.Nw::$prefix_table.'members WHERE u_id='.intval($id).' AND u_password=\''.insertBD($pass).'\'');
        $data = $query->fetch_assoc();
        $query->free();
        
        return $data['count'];
    }
    
    
    /**
     *  Trouve toutes les infos sur un utilisateur
     *  @author Cam
      * @param $idm     ID du membre
     *  @return array
     */
    public static function recup_donnees_membre($idm)
    {
        $rq_info_mbr = Nw::$DB->query('SELECT u_id, u_password, u_alias, u_pseudo, u_identifier,
            u_avatar, u_email, u_decalage, u_bio, DATE_FORMAT(u_date_naissance, "%d/%m/%Y") AS date_naissance, u_localisation, u_group, b_id
            FROM '.Nw::$prefix_table.'members
                LEFT JOIN '.Nw::$prefix_table.'ban ON (b_id_membre=u_id AND b_is_end=0)
            WHERE u_id='.intval($idm)) OR Nw::$DB->trigger(__LINE__, __FILE__);
            
        return $rq_info_mbr->fetch_assoc();
    }
    
    
    /**
     *  Met à jour les infos du membre connecté
     *  @author Cam
      * @param $idm     ID du membre
     *  @return void
     */
    public static function maj_donnees_membre($idm)
    {
        Nw::$DB->query('UPDATE '.Nw::$prefix_table.'members 
            SET u_last_visit = NOW(), u_ip = '.get_ip().'
            WHERE u_id = '.intval($idm));

        if(!isset($_SESSION['last_ip']) || $_SESSION['last_ip'] != get_ip())
        {
            $_SESSION['last_ip'] = get_ip();
            Nw::$DB->query("INSERT INTO ".Nw::$prefix_table."members_ip(ip_ip,
                ip_id_mbr, ip_date_begin, ip_date_last)
                VALUES(".get_ip().", ".intval($idm).", NOW(), NOW())
                ON DUPLICATE KEY UPDATE ip_date_last = NOW()")
            OR Nw::$DB->trigger(__LINE__, __FILE__);
        }
    }
}
?>
