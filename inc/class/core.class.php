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

abstract class Core
{
    /***
    *   Le template  à utiliser pour parser la page
    ***/
    private $tpl_file = '';
    
    /***
    *   Les fichiers JS à inclure
    ***/
    private $js = array('news.js');
    
    /***
    *   Les fichiers CSS à inclure
    ***/
    private $css = array();
    
    /***
    *   Metas HTML
    ***/
    private $metas = array('desc' => '', 'tags' => '');
    
    /***
    *   Titre de la page
    ***/
    private $title_page = 'L\'actualité collaborative en temps réel';
    
    /***
    *   Le fil d'ariane de la page
    ***/
    private $fil_ariane = array();
    
    /***
    *   Le module à inclure
    ***/
    public static $module = 'news';
    
    /***
    *   La page à inclure (méthode de la classe $module)
    ***/
    public static $page = 0;
    
    /***
    *   Fichiers CSS / JS à minifier (optimisation)
    ***/
    public static $compress_files = array(
        array('themes/%d/', 'admin', 'css'),
        array('themes/%d/', 'design', 'css'),
        array('themes/%d/', 'code', 'css'),
        array('themes/%d/', 'forms', 'css'),
    );
    
    /***
    *   Design par défaut
    ***/
    public static $design = 1;
    
    
    /***
    *   Ajout de la balise <base> dans head, pour les urls type /profile/cam/
    ***/
    private $head_base = 0;
    
    /***
    *   On s'assure que la foncton main sera bien définie
    ***/
    abstract protected function main();
    
    /***
    *   Le constructeur qui sera utilisé par la classe héritière
    ***/
    final protected function __construct()
    {
        Nw::$frame = &$this;
        
        if (!is_file(PATH_ROOT.Nw::$assets['dir_cache'].Nw::$site_lang.'.last_mod_file.php'))
            $this->gen_last_cache_file();
        
        $this->main();
        $this->compress_cssjs();
        $this->parse_nw();
        
        Nw::$tpl->parse($this->tpl_file);   
    }
    
    /***
    *   La fonction qui gère les headers/footers
    ***/
    final private function parse_nw()
    {
        $list_css = '';
        $list_js = '';
        $txt_connected_user = '';
        
        // Si une page nécessite de feuilles de style (CSS) particulières
        if (count($this->css) > 0)
        {
            foreach( $this->css AS $line_css )
            {
                $nom_css = substr($line_css, 0, -4);
                $type_css = ($nom_css == 'code') ? 'screen,print' : 'screen';
                
                $list_css .= sprintf( '<link rel="stylesheet" media="%s" type="text/css" href="themes/%d/%s.css?%d" />', $type_css, self::$design, $nom_css, Nw::$last_mod_file[$line_css])."\r\t\t";
            }
        }
        
        // Si une page nécessite des scripts JS particulier
        if (count($this->js) > 0)
        {
            foreach($this->js AS $line_js)
                $list_js .= '<script type="text/javascript" src="inc/js/'.$line_js.'"></script>'."\r\t\t";
        }
        
        if (count($this->fil_ariane) == 0)
        {
            $this->fil_ariane=array(
                Nw::$lang['common']['fa_inconnu']   =>  array('')
            );
        }
        
        foreach(Nw::$cache_categories AS $idcs => $donnees_categorie)
        {
            Nw::$tpl->setBlock('_categories_', array(
                'ID'        => $idcs,
                'TITRE'     => $donnees_categorie[0],
                'REWRITE'   => $donnees_categorie[1],
                'IMG'       => $donnees_categorie[2]
            ));
        }
        
        foreach(array_slice(Nw::$hot_search, 0, 4) AS $keyword_hot)
        {
            Nw::$tpl->setBlock('_hot_search_', array(
                'KEY'       => $keyword_hot,
                'REWRITE'   => urlencode($keyword_hot),
            ));
        }
        
        $msg_type = '';
        $msg_content = '';
        $msg_url = '';
        
        if (isset($_SESSION['nw_redir']) && count($_SESSION['nw_redir']) > 0)
        {
            $msg_type       = $_SESSION['nw_redir']['t'];
            $msg_content    = $_SESSION['nw_redir']['c'];
            $msg_url        = $_SESSION['nw_redir']['u'];
            $_SESSION['nw_redir'] = array();
        }
        
        if (is_logged_in())
        {
            $txt_connected_user = sprintf( Nw::$lang['common']['txt_connected_user'], Nw::$dn_mbr['u_alias'], Nw::$dn_mbr['u_pseudo'] );
        }
        
        Nw::$rpx_login['url'] = urlencode(Nw::$site_url.'users-40.html');
        
        Nw::$tpl->set(array(
            '_SITE_NAME_'           => Nw::$site_name,
            '_SITE_SLOGAN_'         => Nw::$site_slogan,
            '_SITE_URL_'            => Nw::$site_url,
            '_SITE_LANG_'           => Nw::$site_lang,
            '_TITLE_PAGE_'          => $this->title_page,
            '_ASSETS_'              => Nw::$assets,
            '_DEV_MODE_'            => Nw::$dev_mode,
            '_ID_DEVS_'             => Nw::$id_devs,
            '_RQTS_'                => Nw::$rqts,
            '_SOCIAL_'              => Nw::$social,
            '_IS_MOBILE_'           => (strpos($_SERVER['SERVER_NAME'], 'm.nouweo.com') !== false) ? 1 : 0,
            
            '_ADD_CONTENT_'         => Nw::$add_content,
            '_CSS_'                 => $list_css,
            '_JS_'                  => $list_js,
            '_METAS_'               => $this->metas,
            '_DESIGN_'              => self::$design,
            '_FA_'                  => fil_ariane($this->fil_ariane),
            '_BASE_'                => $this->head_base,
            '_LAST_MOD_FILE_'       => Nw::$last_mod_file,
            '_RPX_'                 => Nw::$rpx_login,
            '_NB_MBR_'              => sprintf(Nw::$lang['common']['nbr_inscrits'], number_format(Nw::$total_membres, 0, ' ', ' ')),
            '_FORM_SIZE_'           => Nw::$forms_size,
            
            'IS_LOGGED_IN'          => is_logged_in(),
            'LANG'                  => Nw::$lang,
            'USER'                  => Nw::$dn_mbr,
            'ACT'                   => self::$page,
            'PREF'                  => Nw::$pref,
            
            'MSG_TYPE'              => $msg_type,
            'MSG_CONTENT'           => $msg_content,
            'MSG_URL'               => $msg_url,
                
            '_DEV_'                 => array('q' => Nw::$DB->get_queries_count()),
            
            '_USER_TXT_WE'          => $txt_connected_user,
            '_PAGE_ACTUELLE_'       => substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'],self::$module))
        ));
    }
    
    final protected function add_css($css)
    {
        if (is_array($css))
            $this->css = array_merge($this->css, $css);
        else
            $this->css[] = $css;
        return true;
    }
    
    final protected function add_form($form)
    {
        if (is_array($form))
            Nw::$forms_size = array_merge(Nw::$forms_size, $form);
        else
            Nw::$forms_size[] = $form;
        return true;
    }
    
    final protected function metas($metas)
    {
        if(is_array($metas))
            $this->metas = $metas;

        return true;
    }

    final protected function add_js($js)
    {
        if(is_array($js))
            $this->js = array_merge($this->js, $js);
        else
            $this->js[] = $js;
    }
    
    final protected function add_wid_in_content($page_include)
    {
        if (is_file(Nw::$assets['dir_cache'].'widgets/content/'.Nw::$site_lang.'.'.$page_include.'.php'))
            include(Nw::$assets['dir_cache'].'widgets/content/'.Nw::$site_lang.'.'.$page_include.'.php');
        else
            return false;
    }
    
    final protected function load_lang_file($section, $lang = null)
    {
        if(is_null($lang))
            $lang = Nw::$site_lang;
        
        include(PATH_ROOT.'lang/'.$lang.'/'.$section.'.php');
    }
    
    final protected function base_enabled($value)
    {
        if ($value)
            $this->head_base = 1;
    }
    
    final protected function set_title($titre)
    {
        $this->title_page = $titre;
    }
    
    final protected function set_tpl($tpl)
    {
        $this->tpl_file = $tpl;
    }
    
    final protected function set_filAriane($fil)
    {
        if(is_array($fil)) {
            $this->fil_ariane = array_merge($this->fil_ariane, $fil);
        } else {
            $this->fil_ariane = array(
                $fil    => array('')
            );
        }
    }
    
    /***
    *   La fonction qui démarre tout le système
    ***/
    final public static function start()
    {
        self::$module = (isset($_GET['p'])) ? $_GET['p'] : 'news';
        self::$page = (isset($_GET['act'])) ? intval($_GET['act']) : 0;
        
        if (self::$module == 'mobile' && strpos($_SERVER['SERVER_NAME'], 'm.nouweo.com') === false)
        {
            header('Status: 301 Moved Permanently', false, 301);
            header('Location: http://m.nouweo.com');
            exit();
        }
        
        // Version mobile
        if (strpos($_SERVER['SERVER_NAME'], 'm.nouweo.com') !== false)
        {
            self::$module = 'mobile';
            self::$page = (isset(self::$page)) ? self::$page : 0;
        }
        
        // On inclut les fichiers langues
        include(PATH_ROOT.'lang/'.Nw::$site_lang.'/common.php');
        
        // On n'inclut le fichier lang de la section que s'il existe
        if (is_file(PATH_ROOT.'lang/'.Nw::$site_lang.'/'.self::$module.'.php')) {
            include(PATH_ROOT.'lang/'.Nw::$site_lang.'/'.self::$module.'.php');
        }
        
        // On vérifie que le module existe
        if(is_file(PATH_ROOT.'inc/views/'.self::$module.'/_module.php'))
            include(PATH_ROOT.'inc/views/'.self::$module.'/_module.php');
        else
            error(Nw::$lang['common']['page_introuvable'], Nw::$lang['common']['page_no_exist']);
            
        if(!isset(Module::$vars_pg[self::$page]))
            error(Nw::$lang['common']['page_introuvable'], Nw::$lang['common']['page_no_exist']);
        
        // On vérifie que la frame existe
        if(is_file(PATH_ROOT.'inc/views/'.self::$module.'/'.Module::$vars_pg[self::$page].'.php'))
            include(PATH_ROOT.'inc/views/'.self::$module.'/'.Module::$vars_pg[self::$page].'.php');
        else
            error(Nw::$lang['common']['page_introuvable'], Nw::$lang['common']['page_no_exist']);
        
        return new Page();
    }
    
    /***
    *   Lance (ou non) une compression d'un fichier CSS/JS
    *   @author Cam
    *   @param array $files     array(dir, file, type)
    *   @return void
    ***/
    final public static function compress_cssjs()
    {
        if (count(self::$compress_files) == 0)
            return false;
        
        $lancement_compression = false;
        
        foreach(self::$compress_files AS $dn_compress)
        {
            $dir = sprintf($dn_compress[0], self::$design);
            $file = $dn_compress[1];
            $type = $dn_compress[2];
            
            /**
            *   Si le fichier a bien été modifié, on lance la compression
            **/
            if (!is_file($dir.$file.'_min.'.$type))
            {
                if ($type == 'css')
                    $content_file = minify_css($dir.$file.'.'.$type);
                
                $content_file = str_replace("\n", '', $content_file);
                
                file_put_contents($dir.$file.'_min.'.$type, $content_file);
                $lancement_compression = true;
            }
        }
        
        // Si un fichier a été minifié, on met à jour le fichier cache des dernières modifications
        if ($lancement_compression)
            self::gen_last_cache_file();
    }
    
    /***
    *   Regénère un fichier cache avec la dernière date de modification des fichiers CSS/JS que l'on minifie (optimisation)
    *   @author Cam
    *   @return void
    ***/
    final public static function gen_last_cache_file()
    {
        if (count(self::$compress_files) == 0)
            return false;
        
        foreach(self::$compress_files AS $dn_compress)
            $add_date[] = '\''.$dn_compress[1].'.'.$dn_compress[2].'\' => '.filemtime(sprintf($dn_compress[0],self::$design).$dn_compress[1].'.'.$dn_compress[2]);
        
        $content_file = '<?php '."\r".'Nw::$last_mod_file = array('."\r".implode(', '."\r", $add_date).' '."\r".'); '."\r".'?>';
        file_put_contents(PATH_ROOT.Nw::$assets['dir_cache'].Nw::$site_lang.'.last_mod_file.php', $content_file);
    }
}

/*  *EOF*   */
