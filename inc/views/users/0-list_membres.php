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
        $this->set_title(Nw::$lang['users']['list_members']);
        $this->set_tpl('membres/list_membres.html');
        
        // Fil ariane
        $this->set_filAriane(array(
            Nw::$lang['users']['members_section']           => array('users.html'),
            Nw::$lang['users']['list_members']              => array('')
        ));
        
        $order_by = 'u_pseudo';
        $asc_desc = 'ASC';
        $list_criteres = array();
        
        $corres_id_champs = array(
            0       => 'u_date_register',
            1       => 'u_last_visit',
            2       => 'u_pseudo',
            3       => 's_nb_news',
        );
        
        // Recherche dans les membres
        if (isset($_POST['searchm']))
            header('Location: users.html?pseudo='.htmlspecialchars($_POST['pseudo']).'&group='.intval($_POST['groupe']).'&local='.htmlspecialchars($_POST['local']).'&order='.intval($_POST['order']).'&ad='.htmlspecialchars($_POST['asc_desc']));
        
        if (!empty($_GET['pseudo']))
            $list_criteres[] = 'u_pseudo LIKE "%'.insertBD(trim($_GET['pseudo'])).'%"';
            
        if (!empty($_GET['group']))
            $list_criteres[] = 'u_group = '.intval($_GET['group']); 
            
        if (!empty($_GET['local']))
            $list_criteres[] = 'u_localisation LIKE "%'.insertBD(trim($_GET['local'])).'%"';
            
        if (isset($_GET['order']) && isset($corres_id_champs[$_GET['order']]))
            $order_by = $corres_id_champs[$_GET['order']];
            
        if (isset($_GET['ad']) && ($_GET['ad'] == 'asc' || $_GET['ad'] == 'desc'))
            $asc_desc = strtoupper($_GET['ad']);
            
        $sql_implode_arg = (count($list_criteres) > 0) ? ' AND '.implode(' AND ', $list_criteres) : '';
        inc_lib('bbcode/clearer');
        
        // On compte le nbr total de membres
        inc_lib('users/count_all_mbr');
        $nombre_membres = count_all_mbr('u_active = 1'.$sql_implode_arg);
        
        // Pagination
        $page = ( isset( $_GET['page'] ) ) ? intval( $_GET['page'] ) : 1;
        $nombreDePages = ceil( $nombre_membres / Nw::$pref['nb_news_homepage'] );
        
        // On vérifie bien que la page existe
        if ($nombreDePages > 0 && $page > $nombreDePages)
            redir(Nw::$lang['common']['pg_not_exist'], false, 'users.html');
            
        // On recherche toutes les news en rédaction
        inc_lib('users/get_list_mbr');
        $list_membres = get_list_mbr('u_active = 1'.$sql_implode_arg, $order_by.' '.$asc_desc, $page, Nw::$pref['nb_news_homepage']);
        
        foreach($list_membres AS $donnees)
        {
            Nw::$tpl->setBlock('users', array(
                'ID'            => $donnees['u_id'],
                'PSEUDO'        => $donnees['u_pseudo'],
                'ALIAS'         => $donnees['u_alias'],
                'AVATAR'        => $donnees['u_avatar'],
                
                'DATE_REGISTER' => date_sql($donnees['date_register'], $donnees['heures_date_register'], $donnees['jours_date_register']),
                'DATE_LVISIT'   => date_sql($donnees['last_visit'], $donnees['heures_last_visit'], $donnees['jours_last_visit']),
                'BIO'           => (!empty($donnees['u_bio'])) ? CoupeChar(clearer($donnees['u_bio']), '...', 200) : '',
                'LOCAL'         => $donnees['u_localisation'],
                
                'GROUPE_TITRE'  => $donnees['g_titre'],
                'GROUPE_ICONE'  => $donnees['g_icone'],
                
                'TXT_NEWS'      => sprintf(Nw::$lang['users']['nombre_actu'].(($donnees['s_nb_news'] > 1) ? 's' : ''), $donnees['s_nb_news']),
                'TXT_CONTRIB'   => sprintf(Nw::$lang['users']['nombre_contrib'].(($donnees['s_nb_contrib'] > 1) ? 's' : ''), $donnees['s_nb_contrib']),
                'TXT_COMS'      => sprintf(Nw::$lang['users']['nombre_com'].(($donnees['s_nb_coms'] > 1) ? 's' : ''), $donnees['s_nb_coms']),
                'NBR_NEWS'      => $donnees['s_nb_news'],
                'NBR_CONTRIB'   => $donnees['s_nb_contrib'],
                'NBR_COMS'      => $donnees['s_nb_coms'],
            ));
        }
        
        /**
        *   Liste des groupes
        **/
        inc_lib('admin/get_list_grp');
        $groupes = get_list_grp();
        
        foreach($groupes AS $donnees)
        {
            Nw::$tpl->setBlock('groups', array(
                'ID'            => $donnees['g_id'],
                'NOM'           => $donnees['g_nom'],
            ));
        }
        
        
        /**
        *   Derniers inscrits
        **/
        inc_lib('users/get_last_registered');
        $last_register = get_last_registered(5);
        
        foreach($last_register AS $donnees)
        {
            Nw::$tpl->setBlock('lr', array(
                'ID'            => $donnees['u_id'],
                'PSEUDO'        => $donnees['u_pseudo'],
                'AVATAR'        => $donnees['u_avatar'],
                'ALIAS'         => $donnees['u_alias'],
                'DATE_REGISTER' => date_sql($donnees['date_register'], $donnees['heures_date_register'], $donnees['jours_date_register']),
            ));
        }
        
        $par_pseudo = (isset($_GET['pseudo'])) ? htmlspecialchars($_GET['pseudo']) : '';
        $par_group = (isset($_GET['group'])) ? intval($_GET['group']) : '';
        $par_local = (isset($_GET['local'])) ? htmlspecialchars($_GET['local']) : '';
        $par_order = (isset($_GET['order'])) ? intval($_GET['order']) : '';
        $par_asc = (isset($_GET['ad'])) ? htmlspecialchars($_GET['ad']) : '';
        
        Nw::$tpl->set(array(
            'LIST_PG'       => list_pg($nombreDePages, $page, 'users%s.html?pseudo='.$par_pseudo.'&group='.$par_group.'&local='.$par_local.'&order='.$par_order.'&ad='.$par_asc),
            
            'PSEUDO'        => $par_pseudo,
            'GROUP'         => $par_group,
            'LOCAL'         => $par_local,
            'ORDER'         => $par_order,
            'ASC'           => $par_asc,
        ));
    }   
}

/*  *EOF*   */
