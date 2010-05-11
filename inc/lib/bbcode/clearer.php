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

include_once(dirname(__FILE__).'/bbcode_config_'.Nw::$site_lang.'.php');

function clearer($text, $br=0)
{
    $text = preg_replace('`<div class="align_text_(.+?)">(.+?)</div>`si', ' $2 ', $text);
    $text = preg_replace('`<div class="flots flottant_(.+?)">(.+?)</div>`si', ' $2 ', $text);
    
    // Balises de base (gras, italique, souligné, images, etc.)
    $text = preg_replace('`<strong>(.+?)</strong>`si', '$1', $text);
    $text = preg_replace('`<em>(.+?)</em>`si', '$1', $text);
    $text = preg_replace('`<span class="souligne">(.+?)</span>`si', '$1', $text);
    $text = preg_replace('`<span class="barre">(.+?)</span>`si', '$1', $text);
    $text = preg_replace('`<img src="(.+?)" alt="(.+?)" />`si', '', $text);

    $text = preg_replace('`<h3 class="titre1"><span>(.+?)</span></h3>`si', ' ', $text);
    $text = preg_replace('`<h4 class="titre2"><span>(.+?)</span></h4>`si', ' ', $text);

    // Liens
    $text = preg_replace('`<a href="(.+?)" class="(normal|extern)">(.+?)</a>`si', '$3', $text);

    // Citations
    while (preg_match('`<div class="citation_top">'.Nw::$config['bbcode']['title_citation'].' : (.+?)</div><div class="citation_mid">(.+?)</div>`si', $text))
    {
        $text = preg_replace('`<div class="citation_top">'.Nw::$config['bbcode']['title_citation'].' : (.+?)</div><div class="citation_mid">(.+?)</div>`si', ' $2 ', $text);
    }

    // Listes à puce
    while (preg_match('`<ul class="style_liste_puce">(.+?)</ul>`si', $text))
    {
        $text = preg_replace('`<ul class="style_liste_puce">(.+?)</ul>`si', ' $1 ', $text);
        $text = preg_replace('`<li>(.+?)</li>`si', ' $1 ', $text);
    }
    
    // Widgets
    $text = preg_replace('`&lt;widget id=&quot;([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)&quot;((?: args=&quot;[^"]*?&quot;)*) /&gt;`', ' ', $text);
    
    if($br == 0)
        $text = str_replace(array('<br />', "\n", "\r"), '', $text);

    return $text;
}
