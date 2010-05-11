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

/**
 *  Rafraichit le cache des droits.
 *  @author vincent1870
 *  @return void
 */
function refresh_cache_droits($id_grp = null)
{
    inc_lib('admin/new_grp_auth_cache');

    if(!is_null($id_grp))
        $list_grp = array('g_id' => $id_grp);
    else
    {
        inc_lib('admin/get_list_grp');
        $list_grp = get_list_grp();
    }
    
    foreach($list_grp as $grp)
    {
        //Suppression du vieux cache s'il existe.
        if(is_file(PATH_ROOT.Nw::$assets['dir_cache'].Nw::$site_lang.'._groupauth_'.$grp['g_id'].'.php')) {
            @unlink(PATH_ROOT.Nw::$assets['dir_cache'].Nw::$site_lang.'._groupauth_'.$grp['g_id'].'.php');
        }

        $start_cache_file = '<?php'."\r".' $group_auth[\'g'.$grp['g_id'].'\'] = array( '."\r";

        //Récupération des droits de la bdd
        $dn = array();
        $query = Nw::$DB->query( 'SELECT droit_valeur, droit_nom
        FROM '.Nw::$prefix_table.'droits
        WHERE droit_groupe = '.intval($grp['g_id'])) OR Nw::$DB->trigger(__LINE__, __FILE__);
        while($dn = $query->fetch_assoc())
            $droits[] = $dn;

        foreach($droits as $droit)
        {
            if (in_array($droit['droit_valeur'], array(1, 0)))
                $value_droit = $droit['droit_valeur'];
            else
                $value_droit = '\''.intval($droit['droit_valeur']).'\'';

            $start_cache_file .= "\t".'\''.$droit['droit_nom'].'\' => '.$value_droit.', '."\r";
        }

        $start_cache_file .= "\r".');'."\r".'?>';
        
        new_grp_auth_cache($grp['g_id'], $start_cache_file);
    }
}
