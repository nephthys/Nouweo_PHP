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

function generate_news_sitemap()
{
    inc_lib('news/get_list_news_light');
    $list_dn_news = get_list_news_light('n_etat = 3', 'n_date ASC', '', 0);
        
    foreach($list_dn_news AS $donnees_news)
    {
        Nw::$tpl->setBlock('news', array(
            'ID'            => $donnees_news['n_id'],
            'CAT_ID'        => $donnees_news['c_id'],
            'CAT_TITRE'     => $donnees_news['c_nom'],
            'CAT_REWRITE'   => $donnees_news['c_rewrite'],
            'REWRITE'       => rewrite($donnees_news['n_titre']),
            'DATE'          => $donnees_news['date_sitemap'],
        ) );
    }
        
    Nw::$tpl->set(array(
        '_SITE_URL_'        => Nw::$site_url,
    ));
        
    $content_tpl = Nw::$tpl->pparse('sitemap/news.html');
        
    $xml_file = gzopen(PATH_ROOT.'inc/res/sitemaps/'.Nw::$site_lang.'.news.xml.gz', 'w');
    gzwrite($xml_file, $content_tpl);
    gzclose($xml_file);
}

function generate_members_sitemap()
{
    inc_lib('users/get_list_mbr');
    $list_membres = get_list_mbr('u_active = 1', 'u_date_register ASC', '', 0);
        
    foreach($list_membres AS $donnees)
    {
        Nw::$tpl->setBlock('users', array(
            'ID'            => $donnees['u_id'],
            'PSEUDO'        => $donnees['u_pseudo'],
            'ALIAS'         => $donnees['u_alias'],
            'AVATAR'        => $donnees['u_avatar'],
            'DATE'          => $donnees['date_sitemap'],
            'DATE_REGISTER' => $donnees['date_sitemap_register'],
        ) );
    }
        
    Nw::$tpl->set(array(
        '_SITE_URL_'        => Nw::$site_url,
    ));
        
    $content_tpl = Nw::$tpl->pparse('sitemap/users.html');
        
    $xml_file = gzopen(PATH_ROOT.'inc/res/sitemaps/'.Nw::$site_lang.'.members.xml.gz', 'w');
    gzwrite($xml_file, $content_tpl);
    gzclose($xml_file);
}

function generate_categories_sitemap()
{
    inc_lib('news/get_list_cat');
    $list_cat = get_list_cat();
    
    foreach($list_cat AS $donnees)
    {
        Nw::$tpl->setBlock('cat', array(
            'ALIAS'         => $donnees['c_rewrite'],
            'DATE'          => $donnees['date_sitemap'],
        ) );
    }
    
    Nw::$tpl->set(array(
        '_SITE_URL_'        => Nw::$site_url,
        'DATE_NOW'          => date('c', time()),
    ));
        
    $content_tpl = Nw::$tpl->pparse('sitemap/cat.html');
        
    $xml_file = gzopen(PATH_ROOT.'inc/res/sitemaps/'.Nw::$site_lang.'.cat.xml.gz', 'w');
    gzwrite($xml_file, $content_tpl);
    gzclose($xml_file);
}
