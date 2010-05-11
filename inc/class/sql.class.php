<?php
/*
    *   MySQLi Class
    *
    *   Vous êtes libre d'utiliser et de distribuer ce script comme vous l'entendez, en gardant à l'esprit 
    *   que ce script est, à l'origine, fait par des développeurs bénévoles : en conséquence, veillez à 
    *   laisser le Copyright, par respect de ceux qui ont consacré du temps à la création du script. 
    *
    *   @package        MySQLi Class
    *   @author         Kévin Gomez <geek63@gmail.com>
    *   @copyright      ©Kévin Gomez, La Geek Attitude 2007, 2008
    *   @link           http://geek-attitude.fr.nf/ La Geek Attitude
    *   @license        http://www.gnu.org/licenses/gpl.html (COPYING) GNU Public License
    *   @begin          04/07/2007, Kévin Gomez
    *   @last           01/06/2008, Kévin Gomez
*/

final class Sql extends mysqli {
    
    protected $all_query      = array();      // pour visualiser toutes les requêtes
    protected $time           = array();      // temps des requêtes
    protected $count          = 0;            // nombre de requêtes effectuées
    protected $connected      = False;        // nom explicite je trouve ... =p

    
    /**
    *
    *   Constructeur, permet d'établir la connexion
    *
    *   @$hote :: hôte du serveur
    *   @$login :: identifiant de connexion
    *   @$pass :: mot de passe de la connexion
    *   @$base :: base de données à laquelle se connecter
    *
    *   return SQL ressource
    *
    **/

    public function __construct($hote, $login, $pass, $base)
    {
        $retour = @parent::__construct($hote, $login, $pass, $base);
        
        if($retour !== False)
        {
            $this->set_charset('utf8');
            $this->connected = True;
        }
        
        return $retour;
    }
    
    
    /**
    *
    *   Dit si on est connecté, ou pas
    *
    *   return bool
    *
    **/
    
    public function is_connected()
    {
        return $this->connected;
    }
    
    /**
    *
    *   exécuter une requête
    *
    *   @$requete :: code SQL de la requête
    *
    *   return SQL ressource
    *
    **/

    public function query($requete)
    {       
        $time = microtime(true);
        $this->all_query[$this->count] = $requete;

        $retour = parent::query($requete);

        $this->time[$this->count] = round(microtime(true) - $time,8);
        $this->count++;

        return $retour;
    }
    
    
    /**
    *
    *   Accesseur
    *
    *   return int
    *
    **/
    
    public function get_queries_count()
    {
        return $this->count;
    }
    
    /**
    *
    *   renvoie un array a partir d'un identifiant de ressource
    *
    *   @$ressource :: ressource mysqli
    *   @$return :: mettre à true si un seul résultat est attendu (facultatif) (bool)
    *   @$mode :: mode pour "fetcher" les données (string)
    *
    *   return array
    *
    **/
    public function make_array($ressource, $return = false, $mode = 'fetch_assoc') {
        $arr = array();
        $i = 0;
        
        if(!in_array($mode, array('fetch_array', 'fetch_assoc', 'fetch_row'))) $this->trigger(__LINE__, __FILE__, 'La m&eacute;thode "'.$mode.'" n\'est pas disponible via cette fonction.');
        while($data = $ressource->{$mode}())
        {
            $arr[$i] = $data;
            $i++;
        }
        
        return ($return) ? (($i == 1) ? $arr[0] : $arr) : $arr;
    }
        
    /**
    *
    *   affichage d'un message d'erreur, et arrêt du script
    *
    *   @$line :: ligne de l'erreur (int)
    *   @$file :: fichier concerné (string)
    *   @$msg :: message d'erreur (si vide, on tente de récupérer celui de MySQLi) (string)
    *
    *   return void
    *
    **/
    
    public function trigger($line, $file, $msg = '')
    {
        $final = '<h1>Erreur :</h1>
                <p>Une erreur de type <b>SQL</b> a &eacute;t&eacute; d&eacute;t&eacute;ct&eacute;e.<br />
                <b>Fichier :</b> '.$file.'<br />
                <b>Ligne :</b> '.$line.'<br />';

        $final .= (!empty($msg)) ? '<b>Message d\'erreur : </b> '.$msg.'<br />' : '<b>Message d\'erreur : </b> '.$this->errno.'<br />';
        
        if(@$this->error)
            $final .= '<b>Num&eacute;ro d\'erreur : </b> '.$this->error.'<br />';
        print_r($this->all_query);
        $final .= (isset($this->all_query[$this->count-1])) ? '<b>Requ&ecirc;te concern&eacute;e : </b> '.$this->all_query[$this->count-1].'</p>' : '</p>';

        exit($final);
    }
    
    /**
    *
    *   Affiche les informations de debugage
    *
    *   @$popup :: veut-on les infos de debug sous la forme d'une popup ? (bool)
    *
    **/
    
    public function debug($popup=false) {
        
        $debug =  '<p>Page g&eacute;n&eacute;r&eacute;e avec '.$this->count.' requ&ecirc;tes SQL .</p>
        <h2>Les requ&ecirc;tes sont les suivantes :</h2><p><code><ul>';
        foreach($this->all_query as $query => $value)
        {
            $debug .= '<li>'.$value.' ('.$this->time[$query].' secondes)</li><br />';
        } 
        $debug .= '</ul></code></p>';

        $debug .= '<p>Temps SQL total : '.array_sum($this->time).' secondes</p>';
        
        if($popup)
        {
            file_put_contents('debug.php', $debug);//on écrit dans le fichier

            echo '<script language="javascript" type="text/javascript">
            <!--
                window.open(\'debug.php\', \'Informations de debugage\', \'width=600, height=800,scrollbars=yes\');
            -->
            </script>';
        }
        else echo $debug;
    }
}

?>
