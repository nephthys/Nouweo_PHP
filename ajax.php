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

define('PATH_ROOT', './');
define('INC_COMMON', true);
define('DEV_MODE', 0);

//On démarre la tamporisation de sortie
ob_start();

include(PATH_ROOT.'inc/_common.php');
include(PATH_ROOT.'lang/'.Nw::$site_lang.'/common.php');
/**
*   Ajax de base
**/
if(isset($_GET['s']) && $_GET['s'] == 'common')
{
    /**
    *   Apercu final dans les news/commentaires/profils
    **/
    if(isset($_GET['act']) && $_GET['act'] == 'prev')
    {
        if (!is_logged_in())
            exit;
            
        inc_lib('bbcode/parse');
        
        echo parse_widgets(parse(htmlspecialchars(trim($_POST['content']))));
    }
    /**
    *   Auto-complétion dans la recherche
    **/
    elseif($_GET['act'] == 'search')
    {
        if (empty($_POST['tag']) || strlen(trim($_POST['tag'])) < 1)
            exit;

        inc_lib('search/get_tags_search');
        
        $tags_completion = array();
        $etat_news_afficher = (is_logged_in()) ? 0 : 3;
        
        $donnees_tags = get_tags_search($_POST['tag'], 0, $etat_news_afficher);
        
        foreach($donnees_tags AS $donnees)
            $tags_completion[] = '<li><a href="#" onclick="search_after(\''.$donnees['t_tag'].'\');">'.$donnees['t_tag'].'</a></li>';
        
        echo '<ul>'.((count($tags_completion) > 0) ? implode(' ', $tags_completion) : '<li style="text-align: center;"><a href="#" onclick="search_after();">'.Nw::$lang['common']['no_ajax_results'].'</a></li>').'</ul>';
    }
}

/**
*   Ajax des news
**/
elseif($_GET['s'] == 'news')
{
    include(PATH_ROOT.'lang/'.Nw::$site_lang.'/news.php');
    
    /**
    *   Mettre/enlever une news en favoris
    **/
    if(isset($_GET['act']) && $_GET['act'] == 'fav')
    {
        if (!is_logged_in())
            exit;
            
        $id_news = explode('fav_', $_POST['id']);   

        inc_lib('news/manage_fav');
        $response = manage_fav($id_news[1]);
        
        $add_text = '';
        
        if($response == 1)
        {
            if (isset($_GET['with_txt']))
                $add_text = ' '.Nw::$lang['news']['enlever_favoris'];
                
            echo '<a href="news-25-'.$id_news[1].'.html" id="fav_'.$id_news[1].'" class="link_authors"><img src="themes/1/images/fav.png" alt="" />'.$add_text.'</a>';
        }
        elseif($response == 2)
        {
            if (isset($_GET['with_txt']))
                $add_text = ' '.Nw::$lang['news']['mettre_favoris'];
                
            echo '<a href="news-25-'.$id_news[1].'.html" id="fav_'.$id_news[1].'" class="link_authors"><img src="themes/1/images/fav_off.png" alt="" />'.$add_text.'</a>';
        }
    }
    /**
    *   Voter pour une news
    **/
    elseif($_GET['act'] == 'vote')
    {
        if (!is_logged_in())
            exit;
            
        $id_news = explode('vote_', $_POST['id']);  
        
        inc_lib('news/add_vote_news');
        $response = add_vote_news($id_news[1]);
        
        if ($response[0])
            echo '<span class="voted_news" id="voted_'.$id_news[1].'"><img src="themes/1/images/plussun.png" alt="" /> +'.$response[1].'</span>';
        else
            echo '<img src="themes/1/images/plussun.png" alt="" /> +'.$response[1];
    }
    elseif($_GET['act'] == 'vote_attente')
    {
        if (!is_logged_in())
            exit;
        
        if ($_POST['type'] == 'moins')
        {
            $prefix = 'vote_moins_';
            $return_img = 'vote_moins';
            $type = false;
        }
        else
        {
            $prefix = 'vote_plus_';
            $return_img = 'vote_plus';
            $type = true;
        }
        
        $id_news = explode($prefix, $_POST['id']);  
        
        inc_lib('news/add_vote_news');
        $response = add_vote_news($id_news[1], $type);
        
        if ($response[0])
            echo '<img src="themes/1/images/icones/'.$return_img.'.png" alt="" /><span class="already_voted">'.$response[1].'</a>';
        else
            echo '<img src="themes/1/images/icones/'.$return_img.'.png" alt="" /> '.$response[1];
    }
    /**
    *   Voter pour un commentaire
    **/
    elseif($_GET['act'] == 'vote_cmt')
    {
        if (!is_logged_in())
            exit;
            
        $id_cmt = explode('vote_cmt_', $_POST['id']);   
        
        inc_lib('news/add_vote_cmt');
        $response = add_vote_cmt($id_cmt[1]);
        
        $query = Nw::$DB->query( 'SELECT c_plussoie FROM '.Nw::$prefix_table.'news_commentaires WHERE c_id = '.intval($id_cmt[1])) OR Nw::$DB->trigger(__LINE__, __FILE__);
        $dn = $query->fetch_assoc();
        
        echo $dn['c_plussoie'];
    }
    
    /**
    *   Ajouter un tag à une news
    **/
    elseif($_GET['act'] == 'tags')
    {
        $id_news = explode('addtag_', $_POST['id']);
        
        if (!is_numeric($id_news[1]) || empty($_POST['tag']) || !is_logged_in())
            exit;
        
        
        // Ce tag n'existe pas sur la news
        inc_lib('news/tag_news_exists');
        if (tag_news_exists($id_news[1], $_POST['tag']) == false)
        {
            inc_lib('news/add_tag_news');
            add_tag_news($id_news[1], $_POST['tag']);
            echo '<a href="search.html?s='.urlencode($_POST['tag']).'" class="ntag">'.$_POST['tag'].'</a> ';
        }
        else
            echo ' ';
    }
    /**
    *   Proposition de tags à l'édition/création de news
    **/
    elseif($_GET['act'] == 'auto_tags_edit')
    {
        if (!is_logged_in())
            exit;
            
        $tags_completion = array();
        $list_tags = explode(',', $_POST['tag']);
        
        // S'il y a déjà plusieurs tags d'écrits
        if(is_array($list_tags) && count($list_tags) > 1)
        {   
            $tag_bdd = $list_tags[count($list_tags)-1];
            $tags_after = implode(',', array_slice($list_tags, 0, count($list_tags)-1)) . ', ';
        }
        else
        {
            $tag_bdd = $_POST['tag'];
            $tags_after = $_POST['tag'] . ', ';
        }
        
        if (empty($tag_bdd) || strlen(trim($tag_bdd)) < 1)
            exit('EMPTY');

        inc_lib('search/get_tags_search');
        $donnees_tags = get_tags_search($tag_bdd, 1);
        
        foreach($donnees_tags AS $donnees) {
            $tags_completion[] = '<a href="#" onclick="tags_completion_after(\''.$tags_after.$donnees['t_tag'].', \');">'.$donnees['t_tag'].'</a>';
        }
        
        if(count($tags_completion) > 0)
            echo implode(', ', $tags_completion);
        else
            exit('<a href="#" onclick="tags_completion_after();">'.Nw::$lang['common']['no_ajax_results'].'</a>');
    }
    /**
    *   Système anti-grillé : permet de signaler qu'un membre est en train d'éditer une news
    **/
    elseif($_GET['act'] == 'add_edit_trace')
    {
        if (!is_logged_in())
            exit;
        
        $id_news = $_POST['id_news'];
        
        inc_lib('news/add_edit_trace');
        add_edit_trace(Nw::$dn_mbr['u_id'], $id_news);
    }
}
/**
*   Ajax des widgets
**/
elseif($_GET['s'] == 'widgets')
{
    include(PATH_ROOT.'lang/'.Nw::$site_lang.'/common.php');
    include(PATH_ROOT.'lang/'.Nw::$site_lang.'/widgets.php');
    
    /**
    *   Mettre/enlever une news en favoris
    **/
    if(isset($_GET['act']) && $_GET['act'] == 'post_live')
    {
        if (!is_logged_in() || empty($_POST['id']) || empty($_POST['msg']))
            exit;
            
        inc_lib('widgets/count_list_live_part');
        $count_acces_post = count_list_live_part($_POST['id'], Nw::$dn_mbr['u_id']);

        if ($count_acces_post)
        {
            inc_lib('widgets/add_msg_list_live');
            $id_post = add_msg_list_live($_POST['id'], $_POST['msg']);
        }
    }
    elseif($_GET['act'] == 'get_list_msgs')
    {
        if (empty($_POST['id_live']) || !isset($_POST['limit']) || !isset($_POST['type']))
            exit;
            
        Nw::$tpl->set(array(
            '_ASSETS_'                      => Nw::$assets, 
            'TYPE_WID'                      => $_POST['type'],
        ));
                
        $id_live = (int) $_POST['id_live'];
        $limit = (int) $_POST['limit'];
                
        inc_lib('widgets/get_list_live_messages');
        $all_posts = get_list_live_messages($id_live, $limit);
                
        foreach($all_posts AS $donnees)
        {
            Nw::$tpl->setBlock('posts', array(
            'ID'            => $donnees['post_id'],
            'AUTEUR_ID'     => $donnees['u_id'],
            'AUTEUR_PSEUDO' => $donnees['u_pseudo'],
            'AUTEUR_ALIAS'  => $donnees['u_alias'],
            'AUTEUR_AVATAR' => $donnees['u_avatar'],
            'DATE'          => date_sql($donnees['date'], $donnees['heures_date'], $donnees['jours_date'], true),
            'CONTENU'       => $donnees['post_contenu'],
            ));
        }
        
        echo Nw::$tpl->pparse('widgets/w_live_msgs.html');
    }
}

//On met fin à la tamporisation de sortie
ob_end_flush();
?>
