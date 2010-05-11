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

/**
 * Renvoie le bbcode correpondant à une chaine de caractères HTML.
 * @param string $text          La chaine de caractères à transformer.
 * @return string
 */
function unparse($text)
{
    // Balises de base (gras, italique, souligné, images, etc.)
    $text = preg_replace('`<strong>(.+?)</strong>`si', '&lt;'.Nw::$config['bbcode']['balise_gras'].'&gt;$1&lt;/'.Nw::$config['bbcode']['balise_gras'].'&gt;', $text);
    $text = preg_replace('`<em>(.+?)</em>`si', '&lt;'.Nw::$config['bbcode']['balise_italique'].'&gt;$1&lt;/'.Nw::$config['bbcode']['balise_italique'].'&gt;', $text);
    $text = preg_replace('`<span class="souligne">(.+?)</span>`si', '&lt;'.Nw::$config['bbcode']['balise_souligne'].'&gt;$1&lt;/'.Nw::$config['bbcode']['balise_souligne'].'&gt;', $text);
    $text = preg_replace('`<span class="barre">(.+?)</span>`si', '&lt;'.Nw::$config['bbcode']['balise_barre'].'&gt;$1&lt;/'.Nw::$config['bbcode']['balise_barre'].'&gt;', $text);
    $text = preg_replace('`<img src="(.+?)" alt="(.+?)" />`si', '&lt;'.Nw::$config['bbcode']['balise_image'].'&gt;$1&lt;/'.Nw::$config['bbcode']['balise_image'].'&gt;', $text);

    $text = preg_replace('`<div class="align_text_(.+?)">(.+?)</div>`si', '&lt;'.Nw::$config['bbcode']['balise_position'].' '.Nw::$config['bbcode']['attr_valeur'].'=&quot;$1&quot;&gt;$2&lt;/'.Nw::$config['bbcode']['balise_position'].'&gt;', $text);
    $text = preg_replace('`<div class="flots flottant_(.+?)">(.+?)</div>`si', '&lt;'.Nw::$config['bbcode']['balise_flottant'].' '.Nw::$config['bbcode']['attr_valeur'].'=&quot;$1&quot;&gt;$2&lt;/'.Nw::$config['bbcode']['balise_flottant'].'&gt;', $text);
    
    
    $text = preg_replace('`<h3 class="titre1"><span>(.+?)</span></h3>`si', '&lt;'.Nw::$config['bbcode']['balise_titre1'].'&gt;$1&lt;/'.Nw::$config['bbcode']['balise_titre1'].'&gt;', $text);
    $text = preg_replace('`<h4 class="titre2"><span>(.+?)</span></h4>`si', '&lt;'.Nw::$config['bbcode']['balise_titre2'].'&gt;$1&lt;/'.Nw::$config['bbcode']['balise_titre2'].'&gt;', $text);

    // Liens
    $text = preg_replace_callback('`<a href="(.+?)" class="(normal|extern)">(.+?)</a>`si', 'link_redir_inverse', $text);

    // Citations
    while (preg_match('`<div class="citation_top">'.Nw::$config['bbcode']['title_citation'].' : (.+?)</div><div class="citation_mid">(.+?)</div>`si', $text))
    {
        $text = preg_replace('`<div class="citation_top">'.Nw::$config['bbcode']['title_citation'].' : (.+?)</div><div class="citation_mid">(.+?)</div>`si', '&lt;'.Nw::$config['bbcode']['balise_citation'].' '.Nw::$config['bbcode']['attr_auteur'].'=&quot;$1&quot;&gt;$2&lt;/'.Nw::$config['bbcode']['balise_citation'].'&gt;', $text);
    }

    // Listes à puce
    while(preg_match('`<ul class="style_liste_puce">(.+?)</ul>`si', $text))
    {
        $text = preg_replace('`<ul class="style_liste_puce">(.+?)</ul>`is', '&lt;'.Nw::$config['bbcode']['balise_liste'].'&gt;$1&lt;/'.Nw::$config['bbcode']['balise_liste'].'&gt;', $text);
        $text = preg_replace('`<li>(.+?)</li>`si', '&lt;'.Nw::$config['bbcode']['balise_puce'].'&gt;$1&lt;/'.Nw::$config['bbcode']['balise_puce'].'&gt;', $text);
    }
    $text = str_replace('<br />', '', $text);

    return $text;
}

/*
function unparse_widgets($text)
{
    return preg_replace_callback('`<!-- Begin Widget:(.+?)((?:\:[^"]*?)*) -->(.+?)<!-- End Widget -->`si', 'callback_widgets', $text);
}

function callback_widgets($match)
{
    $return = '';
    
    if(!empty($match[1]))
    {
        $return .= '<widget id="'.$match[1].'"'.(!empty($match[2]) ? substr($match[2], 1) : '').' />';
    }
    
    return $return;
}
*/

function link_redir_inverse($match)
{
    $new_link = $match[1];
    return '&lt;'.Nw::$config['bbcode']['balise_lien'].' '.Nw::$config['bbcode']['attr_url'].'="'.$new_link.'"&gt;'.$match[3].'&lt;/'.Nw::$config['bbcode']['balise_lien'].'&gt;';
}
