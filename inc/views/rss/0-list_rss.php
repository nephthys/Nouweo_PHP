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
        $this->set_title(Nw::$lang['rss']['rss_title']);
        $this->add_css('code.css');
        $this->set_tpl('rss/list_rss.html');
        $this->set_filAriane(Nw::$lang['rss']['rss_title']);
        $this->load_lang_file('news');
        
        inc_lib('news/give_cat_images');
        $list_images = give_cat_images();
        
        $color = 0;
        
        foreach(Nw::$cache_categories AS $idcs => $donnees_categorie)
        {
            Nw::$tpl->setBlock('cat', array(
                'ID'        => $idcs,
                'TITRE'     => $donnees_categorie[0],
                'COLOR'     => ($color%2),
                'IMG'       => (isset($list_images[$idcs])) ? $list_images[$idcs] : array(),
            ));
            
            ++$color;
        }
    }
}

/*  *EOF*   */
