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

Nw::$lang['news'] = array(
    'news'                      => 'News',
    'brief'                     => 'Brève',
    
    /**
    *   Administration
    **/
    'edit_cats_news'            => 'Éditer les catégories de news',
    'nb_news_errors'            => 'Il y a %s erreur(s) signalée(s)',
    'alert'                     => 'Signaler une erreur',
    'alert_redac'               => 'Signaler une erreur',
    'alerts_list'               => 'Liste des alertes',
    'alert_desc'                => 'Si vous avez repéré une erreur sur cette news, nous vous invitons à la signaler ici, elle sera corrigée au plus vite. Merci !',
    'alert_raison'              => 'Détails de votre alerte :',
    'alert_raison_admin'        => 'Détails de l\'alerte',
    'alert_solved_by'           => 'Résolue par %s',
    'alerts_bynews'             => 'Voir les alertes de cette news',
    'alert_solve'               => 'Résoudre l\'alerte',
    'alert_solved'              => 'Résolution',
    'report_error'              => 'Signaler ces erreurs',
    'alert_motif'               => 'Motif :',
    'motif'                     => 'Motif',
    'motifs_list'               => array(
                                        1 => 'Plagiat',
                                        2 => 'Orthographe',
                                        3 => 'Typographie',
                                        4 => 'Hors sujet',
    ),
    'motif_debut'   => 'Création de la news',
    'news_concerned'            => 'News concernée',
    'error_cant_alert'          => 'Vous devez être connecté pour signaler une erreur sur la news.',
    'confirm_alert'             => 'Les administrateurs ont bien été prévenus. Merci !',
    'confirm_solved'            => 'Cette alerte est maintenant résolue.',
    'error_cant_solve_alerts'   => 'Vous n\'avez pas le droit de résoudre des alertes.',
    'error_alert_dont_exist'    => 'Cette alerte n\'existe pas.',
    'solved'                    => 'Résolues',
    'non_solved'                => 'Non résolues',
    'all_alerts'                => 'Toutes',
    'see_alerts'                => 'Voir les alertes : ',
    'seing_alerts_news'         => 'Vous visualisez les alertes de la news suivante : ',
    'see_all_alerts'            => 'Voir toutes les alertes',
    
    /**
    *   Brouillon de news
    **/
    'title_create_brouillon'    => 'Création d\'un brouillon',
    'brouillon_fieldset'        => 'Création d\'un brouillon de news',
    'brouillon_fieldset_inc'    => 'Données relatives à la news',
    'news_section'              => 'News',
    'cant_create_brouillon'     => 'Vous n\'avez pas le droit de créer des news.',
    
    'title_news'                => 'Titre de la news',
    'title_tooltip'             => 'Soyez court et concis',
    'categorie'                 => 'Catégorie',
    'categorie_tooltip'         => 'La plus adaptée à votre sujet',
    'content'                   => 'Contenu',
    'tags'                      => 'Tags relatifs',
    'tags_tooltip'              => 'Séparez les tags par des virgules',
    
    'picture'                   => 'Image associée',
    'picture_tooltip'           => 'Apparaitra sur la page d\'accueil mais pas seulement',
    
    'private_news'              => 'Laisser cette news privée',
    'create_news'               => 'Créer la news',
    'title_content_oblig'       => 'Les champs titre, contenu et source sont obligatoires.',
    'brouillon_cree'            => 'La news a bien été créée.',
    'news_not_exist'            => 'Cette news n\'existe pas.',
    
    'opt_moderation_fieldset'   => 'Options de modération',
    'maj_date_news'             => 'Mettre la date à jour',
    'etat_news'                 => 'Etat de la news',
    
    'etat_news_3'               => 'En ligne',
    'etat_news_2'               => 'En attente',
    'etat_news_1'               => 'En rédaction',
    'etat_news_0'               => 'Hors ligne',
    
    'delete_img_news'           => 'Supprimer',
    'msg_image_delete'          => 'L\'image a bien été supprimée.',
    'agrandir_image'            => 'Agrandir l\'image',
    'apercu_final'              => 'Aperçu final',
    'source'                    => 'Source(s)',
    'source_url'                => 'URL de la source',
    'source_nom'                => 'Nom du média',
    'add_new_source'            => 'Ajouter une nouvelle source',
    
    /**
    *   Edition de news
    **/
    
    'mbr_edit_news'             => '%s est en train d\'éditer',
    'mbr_grilled_edit'          => 'Oops ! <a href="profile/%s/">%s</a> a édité en même temps',
    'compare_bef_aft_grilled'   => 'Comparaison de la nouvelle version avec la vôtre',
    'conseil_grilled'           => 'Nous vous conseillons d\'effectuer les modifications afin de ne pas perdre le travail du dernier contributeur.',
    'delete_comments_news'      => 'Supprimer les commentaires',
    'contrib_mineure'           => 'Contribution mineure',
    'contrib_mineure_tooltip'   => 'Pour ne pas apparaître dans la liste des contributeurs',
    
    /**
    *   News en rédaction
    **/
    'en_redaction_txt'          => 'Envie de participer à la rédaction des news ? Cette partie est faite pour vous : vous pouvez contribuer aux news déjà existantes dont les sujets vous passionnent ou vous <a href="news-50.html">lancer dans la rédaction</a> à partir de zéro.',
    
    'en_redaction_title'        => 'En cours de rédaction',
    'en_redaction_onglet'       => 'Contribuez à la rédaction des news',
    'h3_mes_news'               => 'Mes news',
    'h3_all_news_redac'         => 'Toutes les news',
    'none_tag'                  => 'Aucun tag',
    
    'news_flag_type1'           => 'En favoris',
    'news_flag_type2'           => 'Je contribue',
    'news_flag_type3'           => 'Je rédige',
    'contribute_news'           => 'Contribuer',
    
    'nbr_versions_news'         => 'Voir les %d contributions',
    'none_versions'             => 'Aucune contribution',
    
    'switch_link_redac'         => 'En rédaction',
    'switch_link_attente'       => 'En attente',
    'switch_link_create'        => 'Nouvelle news',
    
    'edit_news_fieldset'        => 'Edition d\'une news',
    'edit_fil_ariane'           => 'Edition de la news',
    
    'edit_lecture'              => 'Lecture',
    'edit_nb_contrib'           => 'Contributions (%d)',
    'edit_form'                 => 'Édition',
    
    'mod_news'                  => 'Modifier la news',
    'delete_news'               => 'Supprimer la news',

    'title_del_news'            => '%s | Suppression de la news',
    'title_edit_news'           => '%s | Edition de la news',
    'msg_news_edit'             => 'La news a bien été modifiée.',
    
    'not_edit_news_perm'        => 'Vous n\'avez pas les permissions nécessaires pour modifier cette news.',
    'not_allowed_delete'        => 'Vous n\'avez pas les permissions nécessaires pour supprimer cette news.',
    
    'field_delete_news'         => 'Suppression de la news',
    'motif_delete_news'         => 'Motif de la suppression (facultatif)',
    'phrase_del_news'           => 'Etes-vous sûr de supprimer la news <a href="%s/%s-%d/">%s</a> ?<br /> Cette action est irréversible, une fois supprimée, la news 
    ne pourra être recupérée.',
    
    'motif_email'               => 'Raison (envoyée par email)',
    'news_deleted'              => 'La news a bien été supprimée !',
    
    'mail_titre_news_del'       => 'Suppression de votre news : %s',
    'mail_news_del'             => 'Bonjour %s, <br /><br />
    
    <a href="%s">%s</a>, un membre de l\'équipe du site %s vient de supprimer votre news &mdash; %s &mdash; pour la raison suivante :<br />
    <blockquote>%s</blockquote><br />
    Nous vous rappellons que toute actualité peut, à tout moment, être supprimée par l\'équipe si elle ne respecte pas <a href="%s">les règles</a>.<br /><br />
    
    Si vous souhaitez en discuter avec ce membre de l\'équipe en question, contactez-le en utilisant la messagerie interne ou utilisez le formulaire de contact, nous tâcherons 
    de répondre à vos questions dans la mesure du possible.',
    
    'propose_news'              => 'Proposer',
    'propose_news_title'        => 'Ma news est prête',
    
    'menu_new_news'             => 'Nouvelles news',
    'menu_last_contrib'         => 'Dernières contributions',
    'version_x'                 => 'Version n°%d',
    
    
    /**
    *   News en attente
    **/
    'dont_propose_news'         => 'Vous n\'avez pas les droits nécessaires pour proposer cette news.',
    'news_already_attente'      => 'Cette news a déjà été proposée !',
    'msg_news_attente'          => 'Votre news est désormais soumise aux votes des utilisateurs.',
    
    'en_attente_intro'          => 'Les news ci-dessous n\'attendent que vos votes pour apparaître sur la page d\'accueil. En effet, au bout d\'un
    certain nombre de votes, les news sont publiées automatiquement.',
    
    'en_attente_title'          => 'News en attente',
    'en_attente_news'           => 'News en attente',
    'en_attente_h2'             => 'Votez pour les news',
    'clic_vote'                 => 'Votez',
    'clic_voted'                => 'Voté!',
    'text_nbr_vote'             => '%d vote',
    'text_nbr_votes'            => '%d votes',
    
    'presque_en_ligne'          => 'Presque promues',
    'top_voters'                => 'Membres les plus actifs',
    'more_stats_mbr'            => 'Plus de statistiques',
    'add_vote_plus'             => 'Ajouter un avis favorable',
    'add_vote_moins'            => 'Ajouter un avis défavorable',
    
    /**
    *   Infos news
    **/
    'nbr_comments_news'         => '%d réaction%s',
    'add_s_comments'            => 's',
    'add_s_versions'            => 's',
    'nbr_contrib'               => '%d contribution%s',
    'nb_version'                => '%d version',
    'nb_versions'               => '%d versions',
    'lib_tags'                  => 'Tags',
    'add_ajax_tag'              => 'Ajouter un tag',
    
    
    /**
    *   Lecture d'une news
    **/
    'lecture_news'              => 'Lecture',
    'view_fil_ariane'           => 'Lecture de la news',
    'not_view_news_perm'        => 'Vous n\'avez pas le droit de visualiser cette news.',
    'share_news'                => 'Partager',
    
    'contributeurs'             => 'Contributeurs',
    'none_contrib'              => 'Aucun contributeur.',
    
    'news_related'              => 'News relatives',
    'none_related'              => 'Aucune news relative.',
    'read_more'                 => 'Lire la suite...',
    'home_news_related'         => 'Sur le même sujet :',
    'more_news_sujet'           => 'À lire aussi sur <a href="search.html?s=%s" style="text-decoration: none;">%s</a> :',
    
    'section_favoris'           => 'Favoris',
    'mettre_favoris'            => 'Ajouter aux favoris',
    'enlever_favoris'           => 'Retirer des favoris',
    'section_relatifs'          => 'Informations relatives',
    
    'infos_author'              => 'L\'auteur',
    'more_infos_author'         => 'En savoir plus',
    
    'biographie'                => 'Biographie',
    'none_bio'                  => 'Aucune biographie.',
    'author_last_news'          => 'Ses dernières news :',
    
    'taille_texte_plus'         => 'Augmenter la taille du texte',
    'taille_texte_moins'        => 'Réduire la taille du texte',
    'print_news'                => 'Imprimer la news',
    'vote_for_news'             => 'Votez',
    'recents_votes'             => 'Votes récents :',
    'none_votes'                => 'aucun vote',
    'deleted_news'              => 'News supprimée',
    
    /**
    *   Versions 
    **/
    'voir_versions'             => 'Toutes les versions',
    'comparaison_2_versions'    => 'Comparaison des versions #%d et #%d',
    'raison_edition'            => 'Motif de l\'édition',
    'no_raison_edition'         => 'Non renseigné',
    'num_version'               => '#',
    'date'                      => 'Date',
    'auteur'                    => 'Auteur',
    'ip'                        => 'Adresse IP',
    'submit_compare_versions'   => 'Voir les différences',
    'gerer_versions'            => 'Gérer',
    'delete_version'            => 'Supprimer cette version',
    'change_version'            => 'Restaurer cette version',
    'version_not_exist'         => 'Cette version n\'existe pas.',
    'error_restore_vrs'         => 'Vous ne pouvez restaurer une version de news.',
    'error_already_restored'    => 'Cette version de news est déjà l\'actuelle !',
    'vrs_restored'              => 'Cette version a bien été restaurée.',
    'error_cant_delete_vrs'     => 'Impossible de supprimer la seule version de cette news.',
    'error_droit_delete_vrs'    => 'Vous ne pouvez supprimer des versions de news !',
    'vrs_deleted'               => 'Cette version a bien été supprimée !',
    'field_delete_vrs'          => 'Suppression de la version de news',
    'phrase_del_vrs'            => 'Etes-vous sûr de supprimer la  <a href="%s/%s-%d/?vrs=%d">version suivante</a> de la news <strong>%s</strong> ?<br /> Cette action est irréversible, une fois supprimée, la version 
    ne pourra être recupérée.',
    'gestion_vrs'               => 'Versions',
    'del_vrs_fil_ariane'        => 'Suppression d\'une version',
    'apercu_vrs'                => 'Aperçu de la version',
    'view_news_vrs_archived'    => 'Visualisation de la version #%d',
    'revenir_list_vrs'          => 'Revenir à la liste des versions',
    'list_vrs_fa'               => 'Liste des versions',
    'nbr_caract'                => '%d caractères',
    
    
    /**
    *   Commentaires
    **/
    
    'cmt_delete_publication'    => 'Les commentaires seront supprimés à la publication de la news &mdash; vous pouvez donc discuter de sa forme ici.',
    
    'read_more_cmts'            => 'Lire',
    'last_cmts'                 => 'Derniers commentaires',
    'titre_comments'            => 'Réactions',
    'lang_avatar'               => 'Avatar de %s',
    'post_comment'              => 'Poster un commentaire',
    'update_comment'            => 'Éditer un commentaire',
    'acn_droit_comment'         => 'Vous n\'avez pas le droit de poster des commentaires.',
    'no_cmts'                   => 'Aucune réaction n\'a encore été postée',
    'no_cmts_comment'           => 'Soyez le premier à commenter cette news',
    'title_cmt_news'            => '%s | Poster un commentaire',
    'nv_cmt_fil_ariane'         => 'Poster un commentaire',
    'msg_new_cmt'               => 'Votre commentaire a bien été posté !',
    'msg_edit_cmt'              => 'Le commentaire a bien été modifié.',
    'comment'                   => 'Commentaire',
    'add_cmt_submit'            => 'Poster le commentaire',
    'mod_cmt_submit'            => 'Éditer le commentaire',
    'mod_cmt'                   => 'Modifier le commentaire',
    'del_cmt'                   => 'Supprimer le commentaire',
    'quote_cmt'                 => 'Citer le commentaire',
    'cmt_no_exist'              => 'Ce commentaire n\'existe pas.',
    'no_drt_edit_cmt'           => 'Vous n\'avez pas le droit d\'éditer ce commentaire.',
    'no_drt_del_cmt'            => 'Vous n\'avez pas le droit de supprimer ce commentaire.',
    'edit_cmt_hidden'           => 'Ne pas afficher le message d\'édition',
    'del_cmt_with_reason'       => 'Post supprimé %s',
    'motif_delete_cmt'          => 'Motif : %s',
    'del_cmt_news'              => 'Suppression d\'un commentaire',
    'title_del_cmt_news'        => '%s | Suppression d\'un commentaire',
    'phrase_del_cmt_modos'      => 'Vous êtes en train de supprimer le <a href="news-10-%d-%d.html#c%d">commentaire suivant</a> posté sur la news <strong>%s</strong>. Choississez le type de suppression :',
    'phrase_del_cmt'            => 'Etes-vous sûr de supprimer le <a href="news-10-%d-%d.html#c%d">commentaire suivant</a> posté sur la news <strong>%s</strong> ?',
    'title_last_cmts'           => '%d derniers commentaires',
    
    'motif_del_cmt_form'        => 'Motif prédéfini',
    'motif_del_news'            => 'Motif de la suppression',
    'choisir_motif_delcmt'      => 'Choisir',
    'cfm_delete_cmt'            => 'Supprimer le commentaire',
    'cmt_deleted'               => 'Le commentaire a bien été supprimé.',
    'cmt_deletedby_himself'     => 'Supprimé par l\'auteur',
    'cmt_not_exist'             => 'Ce commentaire n\'existe pas.', 
    'vote_cmt_ok'               => 'Le vote a bien été ajouté.',
    'vote_cmt_pasok'            => 'Vous avez déjà voté pour ce commentaire.',
    
    'motifs_suppression_cmt'    => array(
                    'SMS'           => 'Langage SMS interdit',
                    'Publicité'     => 'Aucune publicité n\'est tolérée',
                    'Hors sujet'    => 'Hors sujet',
                    'Insultes'      => 'Insultes interdites'),
    'del_completement_cmt'      => 'Ou supprimer définitivement',
    
    
    'motifs_suppression_news'   => array(
                    'SMS'           => 'Langage SMS interdit',
                    'Publicité'     => 'Aucune publicité n\'est tolérée',
                    'Hors sujet'    => 'Hors sujet',
                    'Insultes'      => 'Insultes interdites'),
    
    'news_favorite_ok'          => 'La news a bien été mise aux favoris.',
    'news_defavorite_ok'        => 'La news a bien été enlevée de vos favoris.',
    'vote_news_ok'              => 'Votre vote a été pris en compte.',
    'vote_news_pasok'           => 'Vous avez déjà voté pour cette news.',
    'news_publiee_byvotes'      => 'La news vient d\'être publiée avec %d votes.',
    'news_archivee_byvotes'     => 'La news vient d\'être archivée avec %d votes.',
    'add_tags'                  => 'Ajouter un tag',
    'add_tags_int'              => 'Mot-clé à ajouter',
    'submit_add_tags'           => 'GO',
    'antispam_post_cmt'         => 'Système anti-spam : impossible de poster votre commentaire !',
    
    'log_publication_votes'     => 'News <strong>publiée</strong> avec <em>%d</em> votes',
    'log_votes_archived'        => 'News <strong>archivée</strong> avec <em>%d</em> votes',
    'log_chg_etat'              => '<strong>Etat</strong> changé de <em>%s</em> à <em>%s</em>',
    'log_chg_titre'             => '<strong>Titre</strong> changé de <em>%s</em> à <em>%s</em>',
    'log_etat_3'                => 'Publiée',
    'log_etat_2'                => 'Attente',
    'log_etat_1'                => 'Rédaction',
    'log_etat_0'                => 'Archivée',
    
    'log_news_1'                => 'Création de la news',
    'log_news_3'                => 'Mise à jour de la date',
    'log_news_9'                => 'Suppression totale de la news',
    'log_del_add_raison'        => '<strong>Raison :</strong> %s',
    'log_del_add_email'         => '<strong>Contenu de l\'email :</strong> 
    <div class="citation_top">Auteur : %s</div><div class="citation_mid">%s</div>',
    'log_news_12'               => 'Passage de la news en attente de votes',
    
    'historique_news'           => 'Historique de la news',
    'historiques_news'          => 'Historiques des news',
    'news_histo_view'           => 'Historique des modifications',
    'news_histos_view'          => 'Historiques des modifications',
    'search_titre_index'        => 'Recherche d\'une news dans l\'index',
    'add_titre_log'             => 'Titre',
    'event'                     => 'Evénement',
    'membre'                    => 'Membre',
    
    /**
    *   Suivi des news
    **/
    
    'suivis_news'               => 'Suivre les actualités',
    'opt_tri'                   => 'Options de tri',
    'tri_contrib'               => 'Mes contributions',
    'tri_cat'                   => 'Catégories',
    'tri_sort'                  => 'Ordre',
    'tri_sort_0'                => 'Les plus récentes',
    'tri_sort_1'                => 'Les plus contribuées',
    'tri_sort_2'                => 'Les plus commentées',
    'tri_sort_3'                => 'Les plus votées',
    'tri_etat'                  => 'Etat',
    'tri_etat_0'                => 'Hors ligne',
    'tri_etat_1'                => 'En cours de rédaction',
    'tri_etat_2'                => 'En attente de votes',
    'tri_etat_3'                => 'En ligne',
    'view_all_news'             => 'Toutes les news',
    'view_all_cat'              => 'Toutes les catégories',
    'none_news'                 => 'Aucune news n\'a été trouvée.',
    
    /**
    *   Nuage de tags
    **/
    'titre_pg_nuage_tags'       => 'Tous les tags',
    'cloud_tags'                => 'Nuage de tags',
    'domaines_tags'             => 'Statistiques par catégorie',
    'theme_aborde'              => 'Catégorie',
    'pourcentage_utilisation'   => 'Pourcentage',
    'plus_actif_cat'            => 'Les plus actifs dans cette catégorie',
    'nbr_tag'                   => '%d tag',
    'nbr_tags'                  => '%d tags',
    
    
    /**
    *   Widget
    **/
    
    'widget_netvibes'           => 'Widget Netvibes',
    
    
);
?>
