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
 * Parse une chaine de bbcode en HTML.
 * @param string $text          La chaine de caractères à parser.
 * @param boolean $lines
 * @return string
 */
function parse($text, $lines=true)
{   
    // Balises de base (gras, italique, souligné, images, etc.)
    $text = preg_replace('`&lt;'.Nw::$config['bbcode']['balise_gras'].'&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_gras'].'&gt;`si', '<strong>$1</strong>', $text);
        $text = preg_replace('`&lt;'.Nw::$config['bbcode']['balise_gras_sm'].'&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_gras_sm'].'&gt;`si', '<strong>$1</strong>', $text);
    $text = preg_replace('`&lt;'.Nw::$config['bbcode']['balise_italique'].'&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_italique'].'&gt;`si', '<em>$1</em>', $text);
        $text = preg_replace('`&lt;'.Nw::$config['bbcode']['balise_italique_sm'].'&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_italique_sm'].'&gt;`si', '<em>$1</em>', $text);
    $text = preg_replace('`&lt;'.Nw::$config['bbcode']['balise_souligne'].'&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_souligne'].'&gt;`si', '<span class="souligne">$1</span>', $text);
        $text = preg_replace('`&lt;'.Nw::$config['bbcode']['balise_souligne_sm'].'&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_souligne_sm'].'&gt;`si', '<span class="souligne">$1</span>', $text);
    $text = preg_replace('`&lt;'.Nw::$config['bbcode']['balise_barre'].'&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_barre'].'&gt;`si', '<span class="barre">$1</span>', $text);
        $text = preg_replace('`&lt;'.Nw::$config['bbcode']['balise_barre_sm'].'&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_barre_sm'].'&gt;`si', '<span class="barre">$1</span>', $text);
    $text = preg_replace_callback('`&lt;'.Nw::$config['bbcode']['balise_image'].'&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_image'].'&gt;`si', 'callback_images', $text);
        $text = preg_replace_callback('`&lt;'.Nw::$config['bbcode']['balise_image_sm'].'&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_image_sm'].'&gt;`si', 'callback_images', $text);
    
    $text = preg_replace('`&lt;'.Nw::$config['bbcode']['balise_position'].' '.Nw::$config['bbcode']['attr_valeur'].'=&quot;('.Nw::$config['bbcode']['balise_position_val'].')&quot;&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_position'].'&gt;`si', '<div class="align_text_$1">$2</div>', $text);
        $text = preg_replace('`&lt;'.Nw::$config['bbcode']['balise_position_sm'].' '.Nw::$config['bbcode']['attr_valeur'].'=&quot;('.Nw::$config['bbcode']['balise_position_val'].')&quot;&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_position_sm'].'&gt;`si', '<div class="align_text_$1">$2</div>', $text);
    $text = preg_replace('`&lt;'.Nw::$config['bbcode']['balise_flottant'].' '.Nw::$config['bbcode']['attr_valeur'].'=&quot;('.Nw::$config['bbcode']['balise_flottant_val'].')&quot;&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_flottant'].'&gt;`si', '<div class="flots flottant_$1">$2</div>', $text);
        $text = preg_replace('`&lt;'.Nw::$config['bbcode']['balise_flottant_sm'].' '.Nw::$config['bbcode']['attr_valeur'].'=&quot;('.Nw::$config['bbcode']['balise_flottant_val'].')&quot;&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_flottant_sm'].'&gt;`si', '<div class="flots flottant_$1">$2</div>', $text);
    
    // Liens
    $text = preg_replace_callback('`&lt;'.Nw::$config['bbcode']['balise_lien'].' '.Nw::$config['bbcode']['attr_url'].'=&quot;(.+?)&quot;&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_lien'].'&gt;`si', 'link_redir', $text);
        $text = preg_replace_callback('`&lt;'.Nw::$config['bbcode']['balise_lien_sm'].' '.Nw::$config['bbcode']['attr_url'].'=&quot;(.+?)&quot;&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_lien_sm'].'&gt;`si', 'link_redir', $text);
    $text = preg_replace('`(?<![>"])(http://[a-zA-Z0-9\.\?=&/,;:%#~_+-]*)`i', '&lt;'.Nw::$config['bbcode']['balise_lien'].'&gt;$1&lt;/'.Nw::$config['bbcode']['balise_lien'].'&gt;', $text);
    $text = preg_replace_callback('`&lt;'.Nw::$config['bbcode']['balise_lien'].'&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_lien'].'&gt;`si', 'shorten_urls', $text);
        $text = preg_replace_callback('`&lt;'.Nw::$config['bbcode']['balise_lien_sm'].'&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_lien_sm'].'&gt;`si', 'shorten_urls', $text);

    $text = preg_replace('`&lt;'.Nw::$config['bbcode']['balise_titre1'].'&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_titre1'].'&gt;`si', '<h3 class="titre1"><span>$1</span></h3>', $text);
    $text = preg_replace('`&lt;'.Nw::$config['bbcode']['balise_titre2'].'&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_titre2'].'&gt;`si', '<h4 class="titre2"><span>$1</span></h4>', $text);
    
    $text = preg_replace('`&lt;'.Nw::$config['bbcode']['balise_citation'].'&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_citation'].'&gt;`si', '<div class="citation_top">'.Nw::$config['bbcode']['title_citation'].' : '.Nw::$config['bbcode']['none_author'].'</div><div class="citation_mid">$1</div>', $text);
    $text = preg_replace('`&lt;'.Nw::$config['bbcode']['balise_citation_sm'].'&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_citation_sm'].'&gt;`si', '<div class="citation_top">'.Nw::$config['bbcode']['title_citation'].' : '.Nw::$config['bbcode']['none_author'].'</div><div class="citation_mid">$1</div>', $text);
    
    // Citations
    while(preg_match('`&lt;'.Nw::$config['bbcode']['balise_citation'].' '.Nw::$config['bbcode']['attr_auteur'].'=&quot;(.+?)&quot;&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_citation'].'&gt;`si', $text))
        $text = preg_replace('`&lt;'.Nw::$config['bbcode']['balise_citation'].' '.Nw::$config['bbcode']['attr_auteur'].'=&quot;(.+?)&quot;&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_citation'].'&gt;`si', '<div class="citation_top">'.Nw::$config['bbcode']['title_citation'].' : $1</div><div class="citation_mid">$2</div>', $text);

    while(preg_match('`&lt;'.Nw::$config['bbcode']['balise_citation_sm'].' '.Nw::$config['bbcode']['attr_auteur'].'=&quot;(.+?)&quot;&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_citation_sm'].'&gt;`si', $text))
        $text = preg_replace('`&lt;'.Nw::$config['bbcode']['balise_citation_sm'].' '.Nw::$config['bbcode']['attr_auteur'].'=&quot;(.+?)&quot;&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_citation_sm'].'&gt;`si', '<div class="citation_top">'.Nw::$config['bbcode']['title_citation'].' : $1</div><div class="citation_mid">$2</div>', $text);
    
    // Listes à puce
    while(preg_match('`&lt;'.Nw::$config['bbcode']['balise_liste'].'&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_liste'].'&gt;`si', $text))
    {
        $text = preg_replace('`&lt;'.Nw::$config['bbcode']['balise_liste'].'&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_liste'].'&gt;`si', '<ul class="style_liste_puce">$1</ul>', $text);
        $text = preg_replace('`&lt;'.Nw::$config['bbcode']['balise_puce'].'&gt;(.+?)&lt;/'.Nw::$config['bbcode']['balise_puce'].'&gt;`si', '<li>$1</li>', $text);
    }
    
    if($lines) {
        $text = nl2br($text);
        $text = str_replace(
            array('</span></h3><br />', '</span></h4><br />', '</li><br />', '</ul><br />'),
            array('</span></h3>', '</span></h4>', '</li>', '</ul>'),
        $text);
    }

    return $text;
}

function parse_widgets($text)
{
    return preg_replace_callback('`&lt;widget id=&quot;([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)&quot;((?: args=&quot;[^"]*?&quot;)*) /&gt;`', 'callback_widgets', $text);
}

function callback_widgets($match)
{
    if (!empty($match[1]))
    {
        if (!in_array($match[1], get_included_files()))
            if (file_exists(Nw::$assets['dir_widgets'].'w_'.$match[1].'.php'))
                include_once Nw::$assets['dir_widgets'].'w_'.$match[1].'.php';
            else
                return '';
        
        $widget = 'w_'.$match[1];
        $widget = new $widget((!empty($match[2])) ? substr($match[2], 12, -6) : '');
        
        return $widget->render();
        //return '<!-- Begin Widget:'.$match[1].((!empty($match[2])) ? ':'.$match[2] : '').' -->'.$widget->render().'<!-- End Widget -->';
    }
    return '';
}

/**
*   Est-ce vraiment une image ?
**/
function callback_images($m)
{
    // On vérifie l'extension
    $extension_valide = array('gif', 'png', 'jpg', 'jpeg');
    $extension = pathinfo($m[1], PATHINFO_EXTENSION);

    if( !in_array($extension, $extension_valide))
        return $m[1];

    // Si l'extension est valide, on vérifie l'image
    $info_fichier = @getimagesize($m[1]);

    if (strpos($m[1], '<script') !== true && $info_fichier!=false)
        return '<img src="'.$m[1].'" alt="'.Nw::$config['bbcode']['alt_image'].'" />';
    else
        return $m[1];
}

/**
*   Pour raccourcir les URLs trop longues
**/
function shorten_urls($match)
{
    $url = $match[1];

    if (strlen($match[1]) > URLS_MAX)
        $url = substr($match[1], 0, URLS_NB_FIX) . '[...]' . substr($match[1], URLS_NB_FIX * (-1));

    $class_css = (strpos($match[1], Nw::$site_url) !== false) ? 'normal' : 'extern';

    return '<a href="'.$match[1].'" class="'.$class_css.'">'.$url.'</a>';
}

/**
*   Permet de savoir si c'est un lien externe ou non
*   Surtout utile pour le référencement
**/
function link_redir($match)
{
	$class_css = (strpos($match[1], Nw::$site_url) !== false) ? 'normal' : 'extern';

    return '<a href="'.$match[1].'" class="'.$class_css.'">'.$match[2].'</a>';
}
