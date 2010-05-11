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
        $this->set_tpl('mobile/news/homepage.html');
        $this->load_lang_file('users');
        $this->load_lang_file('news');
        $this->load_lang_file('search');
        
        // Recherche
        if (!empty($_POST['search']) && strlen(trim($_POST['search'])) > 1)
            header('Location: mobile-1.html?s='.urlencode($_POST['search']));
        
        // 3 dernières news
        inc_lib('news/get_list_news');
        $list_dn_news = get_list_news('n_etat = 3', 'n_date DESC', 1, 3);
        
        foreach($list_dn_news AS $donnees_news)
        {
            Nw::$tpl->setBlock('news', array(
                'ID'            => $donnees_news['n_id'],

                'IMAGE_ID'      => $donnees_news['i_id'],
                'IMAGE_NOM'     => $donnees_news['i_nom'],
                
                'TITRE'         => $donnees_news['n_titre'],
                'RESUME'        => $donnees_news['n_resume'],
                'REWRITE'       => rewrite($donnees_news['n_titre']),
                
                'DATE'          => date_sql($donnees_news['date_news'], $donnees_news['heures_date_news'], $donnees_news['jours_date_news']),
            ) );
        }
        
        Nw::$tpl->set(array(
            'INC_HEAD'      => (isset($_GET['head'])) ? false : true,
        ));
    }
}

/*  *EOF*   */
