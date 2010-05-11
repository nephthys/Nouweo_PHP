//------------------------------------------------
// 24 juin : votes négatifs
//------------------------------------------------

ALTER TABLE `nw_news_vote` ADD `v_type` BOOL NOT NULL DEFAULT '1';
ALTER TABLE `nw_news` ADD `n_nb_votes_neg` SMALLINT( 6 ) NOT NULL AFTER `n_nb_votes`;


//------------------------------------------------
// 29 juin : logs des news
//------------------------------------------------

CREATE TABLE IF NOT EXISTS `nw_news_logs` (
  `l_id_news` mediumint(9) NOT NULL,
  `l_id_membre` mediumint(9) NOT NULL,
  `l_titre` varchar(200) NOT NULL,
  `l_action` smallint(6) NOT NULL,
  `l_texte` text NOT NULL,
  `l_date` datetime NOT NULL,
  `l_ip` int(10) unsigned NOT NULL,
  KEY `l_id_news` (`l_id_news`,`l_id_membre`)
);

//-----------------------------------------------
// 30 juin : motif dune alerte
//----------------------------------------------
ALTER TABLE `nw_news_alerts` ADD `a_motif` TINYINT( 1 ) NOT NULL AFTER 
`a_texte` ;

//-----------------------------------------------
// 2 juillet : multi sources
//----------------------------------------------

ALTER TABLE `nw_news` ADD `n_nb_src` SMALLINT NOT NULL;
ALTER TABLE `nw_news`
  DROP `n_src_url`,
  DROP `n_src_nom`;
  
CREATE TABLE IF NOT EXISTS `nw_news_src` (
  `src_id_news` mediumint(9) NOT NULL,
  `src_media` varchar(50) NOT NULL,
  `src_url` varchar(255) NOT NULL,
  `src_order` smallint(6) NOT NULL,
  KEY `src_id_news` (`src_id_news`)
);


//-----------------------------------------------
// 24 juillet : modif des permaliens
//----------------------------------------------

ALTER TABLE `nw_categories` ADD `c_rewrite` VARCHAR( 30 ) NOT NULL AFTER `c_nom`;
ALTER TABLE `nw_news` ADD `n_miniurl` VARCHAR( 6 ) NOT NULL;

CREATE TABLE IF NOT EXISTS `nw_extern_tracker` (
  `t_id_membre` mediumint(9) NOT NULL,
  `t_type` varchar(4) NOT NULL,
  `t_date` datetime NOT NULL,
  `t_ip` int(11) unsigned NOT NULL,
  `t_nb_clics` smallint(6) NOT NULL,
  `r_referer` varchar(200) NOT NULL,
  UNIQUE KEY `t_type` (`t_type`,`t_ip`),
  KEY `t_id_membre` (`t_id_membre`)
);

ALTER TABLE `nw_extern_tracker` ADD `t_id` MEDIUMINT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;

CREATE TABLE IF NOT EXISTS `nw_abonnes` (
  `a_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `a_id_membre` mediumint(9) NOT NULL,
  `a_email` varchar(40) NOT NULL,
  `a_date` datetime NOT NULL,
  `a_ip` int(11) unsigned NOT NULL,
  `a_token` varchar(32) NOT NULL,
  PRIMARY KEY (`a_id`),
  KEY `a_id_membre` (`a_id_membre`)
);