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

function post_twitt_news($id_news)
{
    if (Nw::$is_prod && isset(Nw::$twitter['nouweo']) && count(Nw::$twitter['nouweo']) > 0)
    {
        inc_lib('news/get_info_news');
        $donnees_news = get_info_news($id_news);
        
        $real_link_news = Nw::$site_url.$donnees_news['c_rewrite'].'/'.rewrite($donnees_news['n_titre']).'-'.$id_news.'/';
        
        $fields = array('source' => $real_link_news);
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'http://shr.im/api/post/');
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);
        curl_close($curl);
        
        $end_twitt = ' http://shr.im/'.$result.' #'.strtolower(Nw::$site_name).' #'.strtolower($donnees_news['c_rewrite']);
        $longueur_twitt = 145-strlen($end_twitt);
        
        if ($donnees_news['n_titre'] > $longueur_twitt)
            $add_titre = CoupeChar($donnees_news['n_titre'], '...', $longueur_twitt);
        else
            $add_titre = $donnees_news['n_titre'];
            
        $twitt2post = $add_titre.$end_twitt;
        
        // Postage du twitt sur le compte de base
        $to = new TwitterOAuth(Nw::$twitter['nouweo']['consumer_key'], Nw::$twitter['nouweo']['consumer_secret'], Nw::$twitter['nouweo']['token'], Nw::$twitter['nouweo']['token_secret']);
        $to->OAuthRequest('https://twitter.com/statuses/update.xml', array('status' => $twitt2post), 'POST');
        
        return $result;
    }
    else
        return false;
}
