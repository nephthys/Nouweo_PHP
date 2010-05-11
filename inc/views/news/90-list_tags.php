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
        $this->set_title(Nw::$lang['news']['titre_pg_nuage_tags']);
        $this->set_tpl('news/tags.html');
        
        // Fil ariane
        $this->set_filAriane(array(
            Nw::$lang['news']['titre_pg_nuage_tags']    => array(''),
        ));
        
        /**
        *   Nuage de tags
        **/
        inc_lib('news/nuage_tags');
        $list_count_by_cat = array();
        $int_cats = array();
        $nuage_tags = nuage_tags();
        $nbr_tags = 0;
        
        foreach($nuage_tags AS $donnees_tags)
        {
            if( !isset($list_count_by_cat[$donnees_tags['n_id_cat']]))
                $list_count_by_cat[$donnees_tags['n_id_cat']] = 0;
                
            $list_count_by_cat[$donnees_tags['n_id_cat']] = $list_count_by_cat[$donnees_tags['n_id_cat']] + 1;
            $int_cats[$donnees_tags['n_id_cat']] = array($donnees_tags['c_nom'], $donnees_tags['c_couleur']);
            ++$nbr_tags;
            
            Nw::$tpl->setBlock('nuage', array(
                'INT'           => $donnees_tags['t_tag'],
                'REWRITE'       => urlencode($donnees_tags['t_tag']),
                'SIZE'          => $donnees_tags['size'],
                'COLOR'         => $donnees_tags['c_couleur'],
            ));
        }
        
        foreach($list_count_by_cat AS $idc => $dn_stats)
        {
            $int_nbr_tags = ($dn_stats > 1) ? Nw::$lang['news']['nbr_tags'] : Nw::$lang['news']['nbr_tag'];
            
            Nw::$tpl->setBlock('stats', array(
                'CAT_ID'        => $idc,
                'CAT_NOM'       => $int_cats[$idc][0],
                'CAT_COLOR'     => $int_cats[$idc][1],
                'CAT_REWRITE'   => rewrite($int_cats[$idc][0]),
                'NBR_TAGS'      => sprintf($int_nbr_tags, $dn_stats),
                'PX'            => round(($dn_stats/$nbr_tags) * 100),
            ));
        }

        inc_lib('news/get_list_actifs_bycategorie');
        Nw::$tpl->set(array(
            'ID'                => '',
            'TOP_ACTIF'         => get_list_actifs_bycategorie(),
        ));
    }
}

/*  *EOF*   */
