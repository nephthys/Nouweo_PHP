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

include(PATH_ROOT.'inc/sitemaps.php');

/**
 *  Le membre est-il connecté ?
 *  @author Cam
 *  @return bool
 */
function is_logged_in() 
{
    return ( isset( $_SESSION['logged'] ) && $_SESSION['logged'] == true );
}

/**
 *  Inclusion d'une fonction dans une page
 *  @author Cam
 *  @param mixed $lib       URL de la fonction (ex : news/edit_news)
 */
function inc_lib($lib)
{
    static $includes = array();
    
    if (!in_array($lib, $includes))
    {
        include(PATH_ROOT.'inc/lib/'.$lib.'.php');
        $includes[] = $lib;
        return true;
    }
    else
        return false;
}

/**
 *  Ajout d'un widget dans l'interface du site
 *  @author Cam
 *  @param mixed $wid       Nom exact du widget
 *  @param mixed $wid       Eventuels paramètres
 */
function inc_wid($wid, $params='')
{
    if (is_file(Nw::$assets['dir_widgets'].'w_'.$wid.'.php'))
    {
        include_once(Nw::$assets['dir_widgets'].'w_'.$wid.'.php');
        
        $widget = 'w_'.$wid;
        $widget = new $widget($params);
        
        return $widget->render();
    }
    else
        return false;
}

/**
 *  Fonction de protection pour l'enregistrement de données
 *  @author Cam
 *  @param mixed $var       La variable à protéger
 */
function insertBD($var) 
{
    return Nw::$DB->real_escape_string( htmlspecialchars( $var ) );
}

/**
 *  Pour attraper l'ip du visiteur
 *  @author Cam
 *  @return integer
 */
function get_ip() 
{
    if( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    elseif( isset($_SERVER['HTTP_CLIENT_IP'] ) )
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    else
        $ip = $_SERVER['REMOTE_ADDR'];
        
    return ip2long($ip);
}

/**
 *  Affiche une page d'erreur (erreur 404, maintenance, etc.)
 *  @author Cam
 *  @param string $title        Titre de l'erreur
  * @param string $content      Message d'erreur
 *  @return void
 */
function error($title, $content)
{
    Nw::$tpl->set(array(
            'SITENAME'  => Nw::$site_name,
            'ERROR'     => $title,
            'CONTENT'   => $content
        ));
    
    Nw::$tpl->parse('_error.html');
    exit();
}

/**
*   Permet de créer très facilement un fil ariane
**/
function fil_ariane( $location ) 
{
    if( !is_array( $location ) )
        return FALSE;
    
    $fil = '';
    
    // On exploite le array pour exploiter les informations du fil ariane
    foreach( $location AS $titre => $vals )
    {
        // On vérifie que le lien soit bien rempli (ce qui n'est pas forcément obligatoire)
        $titre_lien = ( !empty( $vals[1] ) ) ? $vals[1] : $titre; // Pour le "title" du lien, soit il est défini, soit non
        $fil .= ( !empty( $vals[0] ) ) ? '<a href="' . $vals[0] . '" title="' . $titre_lien . '">'.$titre.'</a> > ' : $titre . ' > ';
    }
    
    // On retourne le fil ariane s'il n'est pas vide ou niveau on retourne rien
    $first_li = '<a href="./">'.Nw::$site_name.'</a> > ';
    return substr( $fil, 0, -3 );
}

/***
*   Permet de vérifier si plusieurs variables sont vides
***/
function multi_empty()
{
    // -- Pas de parametre.. C'est donc vrai :o
    if (func_num_args() == 0){
        return true;
    }
    
    $args = func_get_args();
    
    // -- On teste chacun des arguments. Si y'en a un vide, on retourne true..
    foreach ($args as $arg){
        if (empty($arg)){
            return true;
        }
    }
    
    return false;
}

/***
*   Fonction qui affiche un formulaire
***/
function display_form($champs, $error='')
{
    $champs['msg_error'] = (empty($error)) ? '' : '<div class="redir_box message_erreur">'.$error.'</div><br />';    
    Nw::$tpl->set('FORM', $champs);
}

/**
 *  Envoi d'un mail au format HTML
 *  @author Cam
 *  @param string $dest     L'adresse mail du destinataire
 *  @param string $titre        Le titre du mail
 *  @param string $cont     Le corps du mail
 *  @return bool
 */
function envoi_mail( $dest, $titre, $cont ) 
{
    Nw::$tpl->set(array(
        'SITE_NAME'     => Nw::$site_name,
        'SITE_URL'      => Nw::$site_url,
        'LANG'          => Nw::$lang,
        'TITRE'         => $titre,
        'CONTENT'       => $cont,
    ));
    
    $content_html_mail = Nw::$tpl->pparse('mail/base.html');

    $mail = new PHPMailer(); // defaults to using php "mail()"
    
    $mail->AddReplyTo(Nw::$site_email_nor, Nw::$site_name);
    $mail->SetFrom(Nw::$site_email_nor, Nw::$site_name);
    $mail->AddAddress($dest);

    $mail->CharSet = 'UTF-8';
    $mail->Subject = $titre;
    $mail->MsgHTML($content_html_mail);

    
    if (!$mail->Send())
        return false;
    else 
        return true;
}

/**
 *  Permet de faire des liens type url rewriting
 *  @author Talus
 *  @param string $texte        Le texte à rewriter
 *  @param integer $petit       Doit-on convertir en minuscules ? (oui par défaut)
**/
function rewrite($str, $petit = true)
{
    $separator = '-';
    $str = utf8_decode($str);
    
    if ($petit)
        $str = strtolower($str);
    
    $str = strtr($str, utf8_decode('ßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŕ'), 'baaaaaaaceeeeiiiidnoooooouuuyybyr'); 
    
    $str = preg_replace('`\b('.implode('|', Nw::$lang['common']['blacklist_words_url']).')[sx]?\b`si', '', $str);
    $str = preg_replace('`[^a-z0-9'.$separator.']+`', $separator, $str);
    $str = preg_replace('`'.$separator.'{2,}`', $separator, $str);

    $str = trim($str, $separator);
    
    return utf8_encode($str);
}

/**
 *  Fonction de redirection
 *  @author Cam
 *  @param string $texte        Le message de redirection
 *  @param integer $type        Le type : 1 = confirmation, 0 = erreur
 *  @param string $url          URL de redirection ('' par défaut pour indiquer aucune redirection)
 */
function redir( $texte, $type, $url = '' ) 
{
    $class_redir = ($type) ? 'message_info' : 'message_erreur';
    $url_redir = (!empty($url)) ? $url : './';
    $_SESSION['nw_redir'] = array('t'=>$class_redir, 'c'=>$texte, 'u'=>$url_redir);
    header('Location: '.$url_redir);
    exit();
}

/**
 *  Fonction pour gérer le décalage horaire (dans les rqt)
 *  @author Cam
 *  @param string $champs
 *  @param string $as
 *  @param integer $format
 *  @return string
 */
function decalageh( $champs, $as, $format = DATETIME ) 
{
    if (is_logged_in() && !empty(Nw::$dn_mbr['u_decalage']))
    {
        $curdate_sql    = 'DATE_ADD(NOW(), INTERVAL \''.Nw::$dn_mbr['u_decalage'].'\' HOUR_SECOND)';
        $champs         = 'DATE_ADD('.$champs.', INTERVAL \''.Nw::$dn_mbr['u_decalage'].'\' HOUR_SECOND)';
        $curjour_sql    = 'DATE('.$curdate_sql.')';
    }
    else
    {
        $curdate_sql    = 'NOW()';
        $curjour_sql    = 'CURDATE()';
    }

    $add_sql = 'TIMEDIFF('.$curdate_sql.', '.$champs.') AS heures_' . $as . ', ';
    $add_sql.= 'DATEDIFF(DATE('.$champs.'),'.$curjour_sql.') AS jours_'.$as.', ';
    $format_datetime = $format == DATETIME ? '%d/%m/%Y &agrave; %H:%i' : '%d/%m/%Y';
    return $add_sql . 'DATE_FORMAT(' .  $champs . ', \'' . $format_datetime . '\') AS ' . $as;
}

/**
 *  Gère le format des dates (il y a 10 minutes, aujourd'hui à 10h54, hier à 09h01, etc.)
 *  @author Cam
 *  @param string $date     Date "brute" (directement sortie des requêtes)
 *  @param string $diff_heure   Nbr d'heures/minutes/secondes de différence entre la date et maintenant
 *  @paramn string $diff_jour   Nbr de jours de différence entre la date et maintenant
 *  @return string
 */
function date_sql( $date, $diff_heure, $diff_jour, $small_dates = false ) 
{
    $date_actuelle = explode( '&agrave;', $date );
    $explode_date = explode( ':', $diff_heure );
    $is_futur = (strlen($explode_date[0])>2) ? true : false;

    if ($small_dates)
    {
        if ($explode_date[0] == 0 && isset($explode_date[1]) AND $explode_date[1] == 0)
            return '<strong>'.$date_actuelle[1].'</strong>';
        elseif ($diff_jour == 0)
            return $date_actuelle[1];
        else
            return sprintf(Nw::$lang['common']['date_defaut_small'], substr($date_actuelle[0], 0, 5), $date_actuelle[1]);
    }
    else
    {
        // Aujourd'hui
        if( $explode_date[0] == 0 )
        {
            if(isset($explode_date[1]) AND $explode_date[1] == 0)   
            {
                if($is_futur) 
                    return Nw::$lang['common']['date_dans_moins_1min'];
                else 
                    return Nw::$lang['common']['date_moins_1min'];
            }
            elseif(isset($explode_date[1]) AND $explode_date[1] < 60)
            {
                if( $explode_date[1] > 1 )
                {
                    $date_futur = Nw::$lang['common']['date_dans_x_mins'];
                    $date_passe = Nw::$lang['common']['date_ilya_x_mins'];
                }
                else
                {
                    $date_futur = Nw::$lang['common']['date_dans_x_min'];
                    $date_passe = Nw::$lang['common']['date_ilya_x_min'];
                }
                
                if( $is_futur ) 
                    return sprintf( $date_futur, ltrim($explode_date[1], '0') );
                else 
                    return sprintf( $date_passe, ltrim($explode_date[1], '0') );
            }
        }
        // Aujourd'hui
        elseif( $diff_jour==0 )
            return sprintf( Nw::$lang['common']['date_today'], substr($date_actuelle[1], 0, 6) );
        // Hier
        elseif( $diff_jour==-1 )
            return sprintf( Nw::$lang['common']['date_yesteday'], substr($date_actuelle[1], 0, 6) );
        // Demain
        elseif( $diff_jour==1 )
            return sprintf( Nw::$lang['common']['date_demain'], substr($date_actuelle[1], 0, 6) );
        // Date par défaut
        else
            return sprintf( Nw::$lang['common']['date_defaut'], $date_actuelle[0], $date_actuelle[1] );
    }
}


/**
 *    Génère la pagination
 *    @author Talus
 *    @param integer $cur       Page courante.
 *    @param integer $total     Nombre total de pages.
 *    @param string $url        Url préformattée (un un %s) pour la la page.
 *    @param integer $flag      Quelle pagination utiliser ?
 *    @return void
*/
function list_pg($total, $cur, $url, $sep = '-', $flag = PAGINATION_ALL){
    
    // -- Les Pages en elle même.
    $pages = array();
    
    $pages[] = '<span class="blc_prev_pages">';
     
    // -- Veut-on afficher les pages precedentes / la premiere page ?
    if( $flag & PAGINATION_FIRST_LAST && $total > 1 && $cur != 1 ){
        $pages[] = '<a href="' . sprintf($url, '') . '" class="first_last_pg" title="'.Nw::$lang['common']['go_first_page'].'">&lt;&lt;</a>';
    }
    
    if( ($flag & PAGINATION_PREV_NEXT) && $cur > 1){
        $add_tiretp = ( ($cur-1) == 1 ) ? sprintf($url, '') : sprintf($url, $sep.'p'.($cur-1));
        $pages[] = $cur > 1 ? ('<a href="' . $add_tiretp . '" class="pg_suivante_forum">'.Nw::$lang['common']['go_prev_page'].'</a>') : '<span class="pg_suivante_forum">'.Nw::$lang['common']['go_prev_page'].'</span>';
    }
    
    $pages[] = '</span>';
    
    // -- Si $cur != 0 (selection de pages), alors on affiche toutes les pages aux alentours de celle ci.
    if( $cur ){
        // -- On s'occuppe du commencement de la pagination, et de la fin de celle ci.
        $begin = ( $cur > ( AROUND_PAGE + 1 ) ) ? ($cur - AROUND_PAGE) : 1;
        $end = ( $cur > ( $total - ( AROUND_PAGE ) ) ) ? $total : ( $cur + AROUND_PAGE );
        
        for( $i = $begin; $i <= $end; $i++ ){
            $no_i = ($i==1) ? '' : $sep.'p'.$i;
            $pages[] = (($i == $cur ) ? ('<span class="page_actu">' . $i . '</span>') : ('<a href="' . sprintf($url, $no_i) . '" class="pagination">' . $i . '</a>'));
        }
    }
    else{
        // -- On affiche toutes les pages "que" si y'a un total inférieur à 2 * AROUND_PAGE pages.
        if( $total <= ( 2 * AROUND_PAGE ) ){
            for( $i = 1; $i <= $total; $i++ ){
                $pages[] = '<a href="' . sprintf($url, $sep.'p'.$i) . '" class="pagination">' . $i . '</a>';
            }
        }
        else{
            for( $i = 1; $i <= AROUND_PAGE; $i++ ){
                $pages[] = '<a href="' . sprintf($url, $sep.'p'.$i) . '" class="pagination">' . $i . '</a>';
            }
            
            $pages[] = '<span class="pagination">...</span>';
            
            for( $i = (AROUND_PAGE - 1); $i >= 0; $i-- ){
                $pages[] = '<a href="' . sprintf($url, $sep.'p'.($total - $i)) . '" class="pagination">' . ($total - $i) . '</a>';
            }
        }
    }
    
    $pages[] = '<span class="blc_next_pages">';
    
    // -- Veut-on afficher les pages suivantes / la dernière page ?
    if( ($flag & PAGINATION_PREV_NEXT) && $cur && $cur < $total){
        $pages[] = '<a href="' . sprintf($url, $sep.'p'.($cur + 1)) . '" class="pg_suivante_forum">'.Nw::$lang['common']['go_next_page'].'</a>';
    }
    
    if( $flag & PAGINATION_FIRST_LAST && $total > 1 && $cur != $total ){
        $pages[] = '<a href="' . sprintf($url, $sep.'p'.$total) . '" class="first_last_pg" title="'.sprintf(Nw::$lang['common']['go_last_page'], $total).'">&gt;&gt;</a>';
    }
    
    $pages[] = '</span>';

    $affichage_pages = ( count( $pages ) > 0 ) ? implode(' ', $pages) : '<span class="page_actu">1</span>';
    
    if (isset($_GET['p']) && $_GET['p'] == 'mobile')
        return $affichage_pages;
    else
        return '<p class="list_all_pages">'.sprintf(Nw::$lang['common']['go_all_pages'], $affichage_pages).'</p>';
}


/**
 *  Couper une chaine
 *  @author Cam
 *  @param string $chaine           La chaine à couper
 *  @param string $sep              La marque de coupure ('...' par défaut)
 *  @param integer $longueur_maxi   La longueur maximale
 *  @return string
 */
function CoupeChar($chaine, $sep='...', $longueur_maxi=300) 
{
    $tronque_maxi=0;
    
    if(strlen($chaine)>$longueur_maxi) 
    {
        $c = substr(ltrim($chaine), 0, $longueur_maxi);
        preg_match('`.+(?=[,;\.])`s', $c, $out1);  // la chaîne raccourcie jusqu'à la dernière ponctuation
        preg_match('`.+(?=[ ])`s', $c, $out2);    // la chaîne raccourcie jusqu'au dernier espace
        
        // Si la ponctuation trouvée est trop loin du dernier mot on n'en tient pas compte
        if ( ( strlen($out2[0]) - strlen($out1[0]) ) < $tronque_maxi)
          $c = $out1[0];
        else
          $c = $out2[0];
        
        // Supprime les mots courts en fin de phrase
        if (preg_match('`.+(?=('.Nw::$lang['common']['mask_coupe_char'].')$)`s', $c, $out3)) 
            $c = $out3[0];
            
        return trim($c).$sep;
    } 
    else
        return $chaine;
}

/**
 *  Vérifie si le membre possède un droit donné.
 *  @param string $right        Le nom du droit.
 *  @return boolean|integer
 */
function check_auth($right)
{
    return (isset(Nw::$droits[$right])) ? Nw::$droits[$right] : false;
}


function minify_css($file)
{
    $input = file_get_contents($file);
        
    $stylesheet = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $input);
    $stylesheet = str_replace(array("\r\n", "\r", "\n", "\t", '/\s\s+/', '  ', '   '), '', $stylesheet);
    $stylesheet = str_replace(array(' {', '{ '), '{', $stylesheet);
    $stylesheet = str_replace(array(' }', '} '), '}', $stylesheet);
        
    return $stylesheet;
}

function create_mini($img_source, $new_name, $extension, $n_width) 
{
    // Quelle est l'extension du fichier ?
    if ($extension == 'gif')
        $src = imagecreatefromgif($img_source);
    elseif($extension == 'jpg' || $extension == 'jpeg')
        $src = imagecreatefromjpeg($img_source);
    else
        $src = imagecreatefrompng($img_source);
    
    $src_info = getimagesize($img_source);
    
    // Si l'image est plus petite que la taille de la minute, on ne redimensionne pas
    if ($n_width >= $src_info[0])
    {
    	copy($img_source, $new_name);
    	return;
    }
    
    // Nouvelle hauteur
    $n_height = round($n_width * $src_info[1] / $src_info[0]);

    // Création de la nouvelle image redimenssionée
    $new_image = imagecreatetruecolor($n_width, $n_height);
    
    if ($extension == 'gif' || $extension == 'png') 
    {
        $trnprt_indx = imagecolortransparent($src);
 
        if ($trnprt_indx >= 0) 
        {
            $trnprt_color    = imagecolorsforindex($src, $trnprt_indx);
            $trnprt_indx     = imagecolorallocate($new_image, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
     
            imagefill($new_image, 0, 0, $trnprt_indx);
            imagecolortransparent($new_image, $trnprt_indx);
        } 
        elseif ($extension == 'png') 
        {
            imagealphablending($new_image, false);

            $color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
            imagefill($new_image, 0, 0, $color);
            imagesavealpha($new_image, true);
        }
    }
    
    // Copie de l'ancienne image dans la nouvelle, avec le redimensionnement
    imagecopyresampled($new_image, $src, 0, 0, 0, 0, $n_width, $n_height, $src_info[0], $src_info[1]);

    imagepng( $new_image, $new_name );
}

function recadrer_image($file, $file2, $hauteur_max, $largeur_max) {

    $ext_upload = strtolower(substr(strrchr($file, '.'), 1));
    $details = getimagesize($file);

    $hauteur = $details[1];
    $largeur = $details[0];
    
    $image_p = imagecreatetruecolor($largeur_max, $hauteur_max);
    
    if( $ext_upload == 'gif' )
        $image = imagecreatefromgif($file);
    elseif( $ext_upload == 'jpg' || $ext_upload == 'jpeg' )
        $image = imagecreatefromjpeg($file);
    else
        $image = imagecreatefrompng($file);
    
    if ($ext_upload == 'gif' || $ext_upload == 'png') 
    {
        $trnprt_indx = imagecolortransparent($image_p);
 
        // If we have a specific transparent color
        if ($trnprt_indx >= 0) 
        {
            $trnprt_color    = imagecolorsforindex($image_p, $trnprt_indx);
            $trnprt_indx     = imagecolorallocate($image, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
     
            imagefill($image_p, 0, 0, $trnprt_indx);
            imagecolortransparent($image_p, $trnprt_indx);
        } 
        elseif ($ext_upload == 'png') 
        {
            imagealphablending($image_p, false);

            $color = imagecolorallocatealpha($image_p, 0, 0, 0, 127);
            imagefill($image_p, 0, 0, $color);
            imagesavealpha($image_p, true);
        }
    }
    
    imagecopy($image_p, $image, 0, 0, ($largeur-$largeur_max)/2, ($hauteur-$hauteur_max)/2, $largeur_max, $hauteur_max);
    
    imagepng($image_p, $file2); 
}

function add_symb_photo($fond, $symbole)
{
    $image       = imagecreatefrompng($symbole); 
    $destination = imagecreatefrompng($fond); //Image qui sera l'image de destination 

    $blanc    = imagecolorallocate($image, 255, 255, 255); //On a donc une image de 150*150 sur fond blanc  
    $noir     = imagecolorallocate($image, 0, 0, 0); 

    $largeur_src = imagesx($image); //Renvoie la largeur de l'image source 
    $hauteur_src = imagesy($image); //Renvoie la hauteur de l'image source 
         
    $largeur_des = imagesx($destination); //Renvoie la largeur de l'image source 
    $hauteur_des = imagesy($destination); //Renvoie la hauteur de l'image source 
         
    $destination_x = ($largeur_des / 2) - ($largeur_src / 2);
    $destination_y = ($hauteur_des / 2) - ($hauteur_src / 2);

    imagecopyresampled($destination, $image, $destination_x, $destination_y, 0, 0, $largeur_src, $hauteur_src, $largeur_src, $hauteur_src); 
	imagealphablending($destination, false);
    imagesavealpha($destination, true);
    
    @unlink($fond);
    
    imagepng($destination, $fond);  
}
