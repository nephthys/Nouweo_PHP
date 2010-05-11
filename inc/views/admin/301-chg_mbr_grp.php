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
        if(is_logged_in() && check_auth('change_mbr_grp'))
        {
            $this->set_title(Nw::$lang['admin']['titre_grp']);
            $this->set_tpl('admin/chg_mbr_grp.html');
            $this->add_css('forms.css');
            $this->set_filAriane(array(
                    Nw::$lang['admin']['fa_admin']                  => array('admin.html'),
                    Nw::$lang['admin']['fa_chg_mbr_grp']                    => array(''),
            ));

            //Si on a envoyé un utilisateur
            if(!empty($_POST['pseudo']))
            {
                inc_lib('users/get_info_mbr');
                $dn_mbr = get_info_mbr($_POST['pseudo'], 'pseudo');
                if($dn_mbr == false)
                    redir(Nw::$lang['admin']['error_mbr_dont_exist'], false, 'admin-301.html');

                Nw::$tpl->set('USER_SELECTED', true);
                Nw::$tpl->set('ID_MBR', $dn_mbr['u_id']);
                Nw::$tpl->set('PSEUDO_MBR', htmlspecialchars($_POST['pseudo']));
                Nw::$tpl->set('GRP_MBR', $dn_mbr['u_group']);

                inc_lib('admin/get_list_grp');
                $list_grp = get_list_grp();

                foreach($list_grp as $grp)
                {
                    Nw::$tpl->setBlock('grp', array(
                        'ID'        => $grp['g_id'],
                        'NOM'       => $grp['g_nom'],
                        'COULEUR'   => $grp['g_couleur'],
                    ));
                }
            }
            else
                Nw::$tpl->set('USER_SELECTED', false);

            //Si on a demandé le changement de groupe
            if(!empty($_POST['groupe']) && is_numeric($_POST['groupe']) &&
                !empty($_POST['id_mbr']) && is_numeric($_POST['id_mbr']))
            {
                inc_lib('admin/chg_mbr_grp');
                chg_mbr_grp($_POST['id_mbr'], $_POST['groupe']);
                redir($lang['admin']['confirm_chg_grp'], true, 'admin.html');
            }
        }
        else
            redir(Nw::$lang['admin']['error_cant_see_admin'], false, './');
    }
}

/*  *EOF*   */
