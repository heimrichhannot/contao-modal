<?php

$arrLang = &$GLOBALS['TL_LANG']['tl_modal_archive'];

/**
 * Fields
 */
$arrLang['title']       = array('Titel', 'Geben Sie hier bitte den Titel ein.');
$arrLang['customModal'] = array('Eigenen Modal-Typ verwenden', 'Überschreiben Sie den Standard-Modal-Typ (Standard: ' . \HeimrichHannot\Modal\ModalController::getDefaultModalType(true) . ')');
$arrLang['modal']       = array('Modal-Typ', 'Geben Sie den Standardtyp für die Darstellung von Modalfenster hier an.');
$arrLang['tstamp']      = array('Änderungsdatum', '');


/**
 * Legends
 */
$arrLang['general_legend'] = 'Allgemeine Einstellungen';
$arrLang['expert_legend'] = 'Experten-Einstellungen';


/**
 * Buttons
 */
$arrLang['new']        = array('Neues Modal-Archiv', 'Modal-Archiv erstellen');
$arrLang['edit']       = array('Modal-Archiv bearbeiten', 'Modal-Archiv ID %s bearbeiten');
$arrLang['editheader'] = array('Modal-Archiv-Einstellungen bearbeiten', 'Modal-Archiv-Einstellungen ID %s bearbeiten');
$arrLang['copy']       = array('Modal-Archiv duplizieren', 'Modal-Archiv ID %s duplizieren');
$arrLang['delete']     = array('Modal-Archiv löschen', 'Modal-Archiv ID %s löschen');
$arrLang['show']       = array('Modal-Archiv Details', 'Modal-Archiv-Details ID %s anzeigen');
