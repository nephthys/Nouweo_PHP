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
        $this->set_tpl('mobile/news/tags_cloud.html');
        
        $this->load_lang_file('mobile');
        
        /**
        *   Nuage de tags
        **/
        inc_lib('news/nuage_tags');
        $tags_a_afficher = 30;
        $nuage_tags = nuage_tags($tags_a_afficher);
        
        foreach($nuage_tags AS $donnees_tags)
        {
            Nw::$tpl->setBlock('nuage', array(
                'INT'           => $donnees_tags['t_tag'],
                'REWRITE'       => urlencode($donnees_tags['t_tag']),
                'SIZE'          => $donnees_tags['size'],
                'COLOR'         => $donnees_tags['c_couleur'],
            ));
        }
        
        Nw::$tpl->set('INC_HEAD', empty($_SERVER['HTTP_AJAX']));
    }
}

/*  *EOF*   */
