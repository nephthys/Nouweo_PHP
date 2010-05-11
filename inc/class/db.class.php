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

/**
 * Wrapper gérant l'accès à la base de données.
 * @author vincent1870
 */
class Db
{
    /**
     * Toutes les requêtes effectuées, avec leur temps d'exécution.
     * @access private
     * @var array
     */
    private $queries;

    /**
     * La connexion avec la base est-elle établie ?
     * @access private
     * @var boolean
     */
    private $connected;

    /**
     * Le temps total d'exécution.
     * @access private
     * @var float
     */
    private $time;

    /**
     * Constructeur de classe établissant la connexion avec la base de données.
     * @access public
     * @param string $host      L'hôte.
     * @param string $user      Le nom d'utilisateur.
     * @param string $pass      Le mot de passe.
     * @param string $base      Le nom de la base.
     * @return void
     */
    public function __construct($host, $user, $pass, $base)
    {
        $time = microtime(true);
        $res = mysql_connect($host, $user, $pass);
        $this->time = microtime(true) - $time;
        if($res != false)
        {
            $time = microtime(true);
            mysql_set_charset('utf8');
            mysql_select_db($base);
            $this->time += microtime(true) - $time;
            $this->connected = true;
        }
        else
        {
            $this->connected = false;
        }
    }

    /**
     * La connexion avec la base de données est-elle établie ?
     * @access public
     * @return boolean
     */
    public function is_connected()
    {
        return $this->connected;
    }

    /**
     * Exécute une requête SQL.
     * @access public
     * @param string $query
     * @return Db_Resource
     */
    public function query($query)
    {
        $time = microtime(true);
        $result = mysql_query($query);
        $time = microtime(true) - $time;
        $this->time += $time;

        $this->queries[] = array('query' => $query, 'time' => $time);
        Nw::$rqts[$query] = $time;

        if($result === false && in_array(Nw::$dn_mbr['u_id'], Nw::$id_devs))
            $this->trigger(__LINE__, __FILE__);
        else
            return new Db_Resource($result);
    }

    /**
     * Sécurise une chaine de caractères pour une requête SQL.
     * @access public
     * @param string $str
     * @return string
     */
    public function real_escape_string($str)
    {
        return mysql_real_escape_string($str);
    }

    /**
     * Renvoie le nombre de requêtes total.
     * @access public
     * @return integer
     */
    public function get_queries_count()
    {
        return count($this->queries);
    }

    /**
     * Coupe l'exécution du script en envoyant les explications sur l'erreur SQL.
     * @access public
     * @return void
     */
    public function trigger()
    {
        $final = '<div style="-moz-border-radius: 7px; -khtml-border-radius: 7px;
        -webkit-border-radius: 7px; border-radius: 7px; margin: 7px 0 4px 0; 
        padding: 8px 15px; background-color: #ffcece; color: #7a0a0a; 
        border: 2px solid #f2b7b7">
            <p>Une erreur SQL a été détectée !</p>
                <ul>
                    <li><strong>Message d\'erreur : </strong> '.mysql_error().'</li>';
        if(@mysql_errno())
            $final .= '<li><strong>Numéro d\'erreur : </strong> '.mysql_errno().'</li>';
        $final .= isset($this->queries[count($this->queries)-1]) ?
            '<li><strong>Requête concernée : </strong> '.$this->queries[count($this->queries)-1]['query'].'</li>' : '';
        $final .= '</ul></div>';
        
        exit($final);
    }

    /**
     * Renvoie l'id de la dernière ligne insérée.
     * @access public
     * @return integer
     */
    public function last_insert_id()
    {
        return mysql_insert_id();
    }

    /**
     * Fonction permettant une rétro-compatibilité avec l'ancienne classe SQL.
     * @access public
     * @param string $var
     * @return integer
     */
    public function __get($var)
    {
        //Émulation de la demande de insert_id (DEPRECATED)
        if($var == 'insert_id')
            return $this->last_insert_id();
    }

    /**
     * Affiche la liste des requêtes avec leur temps d'éxécution.
     * @access public
     * @param boolean $popup        Doit-on ouvrir un popup pour ça ?
     * @return void
     */
    public function debug($popup = false)
    {
        $debug =  '<p>Page générée avec '.count($this->queries).' requêtes SQL .</p>';
        $debug .= '<p>Temps SQL total : '.number_format($this->time * 1000, 3).'s.</p>';
        $debug .= '<h2>Les requêtes sont les suivantes :</h2>
        <ul>';
        foreach($this->queries as $query)
            $debug .= '<li>'.$query['query'].' ('.number_format($query['time'] * 1000, 3).'ms)</li><br />';
        $debug .= '</ul>';

        if($popup)
        {
            file_put_contents(PATH_ROOT.'cache/debug.php', $debug);
            echo '<script language="javascript" type="text/javascript">
                    window.open(\'./cache/debug.php\', \'Informations de déboguage\', \'width=800, height=400,scrollbars=yes\');
                </script>';
        }
        else echo $debug;
    }
}

/**
 * Classe représentant une ressource SQL. Elle contient des raccourcis et des
 * fonctions de compatibilité avec l'ancienne classe fondée sur Mysqli.
 * @author vincent1870
 */
class Db_Resource
{
    /**
     * La ressource SQL.
     * @access private
     * @var resource
     */
    private $res;

    /**
     * Le constructeur de classe.
     * @access public
     * @param resource $res
     * @return vois
     */
    public function __construct($res)
    {
        $this->res = $res;
    }

    /**
     * Renvoie un tableau indexé comportant toutes les lignes de résultat.
     * @return array
     */
    public function fetch_all()
    {
        $result = array();
        while($row = $this->fetch($this->res))
            $result[] = $row;
        return $result;
    }

    /**
     * Renvoie une valeur dans le cas d'une requête ne retournant qu'une seule
     * colonne.
     * @return mixed
     */
    public function fetch_column()
    {
        $row = mysql_fetch_row($this->res);
        return $row[0];
    }

    /**
     * Associe à une resource une ligne de résultats.
     * @return array
     */
    public function fetch()
    {
        return mysql_fetch_assoc($this->res);
    }

    /**
     * Compatibilité avec mysqli.
     * @deprecated
     * @return array
     */
    public function fetch_assoc()
    {
        return mysql_fetch_assoc($this->res);
    }

    /**
     * Compatibilité avec mysqli.
     * @deprecated
     * @return array
     */
    public function fetch_row()
    {
        return mysql_fetch_row($this->res);
    }

    /**
     * Compatibilité avec mysqli.
     * @deprecated
     * @return void
     */
    public function free()
    {
        mysql_free_result($this->res);
    }
}
