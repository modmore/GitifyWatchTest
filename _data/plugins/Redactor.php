id: 1
name: Redactor
description: 'Redactor WYSIWYG editor plugin for MODX Revolution'
properties: null

-----

/**
 * Redactor WYSIWYG Editor Plugin
 *
 * Events: OnManagerPageBeforeRender, OnRichTextEditorRegister, OnRichTextBrowserInit, OnDocFormPrerender
 *
 * @author JP DeVries <mail@devries.jp>
 *
 * @package redactor
 */

$corePath = $modx->getOption('redactor.core_path', null, $modx->getOption('core_path').'components/redactor/');

switch ($modx->event->name) {
    case 'OnTVInputRenderList':
        $modx->event->output($corePath.'elements/tvs/input/');
        break;

    case 'OnTVInputPropertiesList':
        $modx->event->output($corePath.'elements/tvs/inputoptions/'); 
        break;

    case 'OnTVOutputRenderPropertiesList':
        $modx->event->output($corePath.'elements/tvs/properties/');
        break;

    case 'OnManagerPageBeforeRender':
        break;

    case 'OnRichTextEditorRegister':
        $modx->event->output('Redactor');
        break;

    case 'OnRichTextEditorInit':
        /**
         * @var string $editor
         * @var array $elements
         *
         * Only load up the editor if the editor is Redactor, and use_editor is enabled.
         */
        $rte = isset($editor) ? $editor : $modx->getOption('which_editor', null, '');
        if ($rte !== 'Redactor' || !$modx->getOption('use_editor', null, true)) {
            return;
        }

        /**
         * Attempt to load the Redactor service class. Log error and halt processing if it fails.
         */
        $redactor = $modx->getService('redactor', 'Redactor', $corePath . 'model/redactor/');
        if (!($redactor instanceof Redactor)) {
            $modx->log(modX::LOG_LEVEL_ERROR, '[Redactor] Error loading Redactor service class.');
            return;
        }

        if ($modx->controller && !($modx->controller instanceof modManagerControllerDeprecated)) {
            $modx->controller->addLexiconTopic('redactor:default');
            $modx->controller->addCSS($redactor->config['assetsUrl'].'redactor-1.5.3.min.css');
            if($redactor->degradeUI) $modx->controller->addCSS($redactor->config['assetsUrl'].'buttons-legacy.min.css');
            if($redactor->rebeccaDay) $modx->controller->addCSS($redactor->config['assetsUrl'].'rebecca.min.css');
        }
        else {
            $modx->lexicon->load('redactor:default');
            $modx->regClientCSS($redactor->config['assetsUrl'].'redactor-1.5.3.min.css');
            if($redactor->degradeUI) $modx->regClientCSS($redactor->config['assetsUrl'].'buttons-legacy.min.css');
            if($redactor->rebeccaDay) $modx->regClientCSS($redactor->config['assetsUrl'].'rebecca.min.css');
        }

        if (isset($resource) && $resource instanceof modResource) {
            $redactor->setResource($resource);
        }
        elseif ($modx->resource) {
            $redactor->setResource($modx->resource);
        }
        elseif ($modx->controller && $modx->controller->resource && $modx->controller->resource instanceof modResource) {
            $redactor->setResource($modx->controller->resource);
        }

        $html = $redactor->getHtml();
        $modx->event->output($html);
        break;
}

return;