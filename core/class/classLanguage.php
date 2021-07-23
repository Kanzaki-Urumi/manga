<?php

use Gettext\Translator;

class Language{

    public const DEFAULT = 'fr_FR';
    public const ALL_LANGUE =  ['fr_FR', 'en_US'];
    public const FOLDER_CONTENT = ['modal', 'src', 'inc']; // Dossiers contenant des pages php avec du gettext. (Hors header, footer, index)

    public function __construct(public string $LANG = SELF::DEFAULT)
    {
        // Constructeur, quelques paramètres arriverons icip plus tard (comme les n plural...)
    }

    /**
     * Permet de générer une nouvelle langue
     * @param string $lang est la langue, exemple fr_FR
     */
    public function addLanguage(string $lang): bool
    {
        if(file_exists(__ROOT__.'core/i18n/'.$lang.'/LC_MESSAGES/traduction.php'))
            return false;

        if(!mkdir(__ROOT__.'core/i18n/'.$lang.'',0777))
            return false;

        if(!mkdir(__ROOT__.'core/i18n/'.$lang.'/LC_MESSAGES',0777))
            return false;

        $translations = new Gettext\Translations();
        $translations->mergeWith($this->mergeAllFile());
        $translations->toPoFile(__ROOT__.'core/i18n/'.$lang.'/LC_MESSAGES/traduction.po');

        return true;
    }

    /**
     * Récupère toutes les nouvelles traduction à ajouter dans le Po file
     * @param string $lang est la langue, exemple fr_FR, elle doit déjà exister !
     * @return void
     */    
    public function updatePoFileFromPhpScript(string $lang):void
    {
        $translations = Gettext\Translations::fromPoFile(__ROOT__.'core/i18n/'.$lang.'/LC_MESSAGES/traduction.po');
        $translationsBis = $this->mergeAllFile();
        $translations->mergeWith($translationsBis);
        $translations->toPoFile(__ROOT__.'core/i18n/'.$lang.'/LC_MESSAGES/traduction.po');

    }

     /**
     * Parcour tous les fichiers du site avec des gettext
     * @return object retourne une instance de Translations
     */   
    public function mergeAllFile():object
    {
        $translations = Gettext\Translations::fromPhpCodeFile(__ROOT__.'index.php');
        foreach(SELF::FOLDER_CONTENT as $values){
            foreach(array_diff(scandir(__ROOT__.$values), array('.', '..')) as $elem){
                $translationsBis = Gettext\Translations::fromPhpCodeFile(__ROOT__.$values.'/'.$elem);
                $translations->mergeWith($translationsBis);
            }
        }
        $translationsBis = Gettext\Translations::fromPhpCodeFile(__ROOT__.'core/header.php');
        $translations->mergeWith($translationsBis);
        $translationsBis = Gettext\Translations::fromPhpCodeFile(__ROOT__.'core/footer.php');
        $translations->mergeWith($translationsBis);
        return $translations;
    }

    /**
     * Met à jour le fichier de traduction PhP avec le fichier PO à jour
     * @param string $lang la langue dont le Po file doit être mis à jour sur le fichier array php
     * @return void
     */
    public function updatePhpFileFromPo(string $lang):void
    {
        $translations = Gettext\Translations::fromPoFile(__ROOT__.'core/i18n/'.$lang.'/LC_MESSAGES/traduction.po');
        Gettext\Generators\PhpArray::toFile($translations, __ROOT__.'core/i18n/'.$lang.'/LC_MESSAGES/trad.php'); 
    }

    /**
     * Récupère les traductions de la langue courrante
     * @return object un objet de traduction pour appliquer sur notre gettext
     */
    public function getTad():object
    {
        $langFind = (!in_array($this->LANG, SELF::ALL_LANGUE))? SELF::DEFAULT : $this->LANG;
        
        $translator_gettext = new Translator();
        $translator_gettext->loadTranslations(__ROOT__.'core/i18n/'.$langFind.'/LC_MESSAGES/trad.php');
        $translator_gettext->register();
        return $translator_gettext;
    }
}