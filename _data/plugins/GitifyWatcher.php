id: 2
name: GitifyWatcher
plugincode: "/**\n * @var modX $modx\n * @var array $scriptProperties\n * @var GitifyWatch $gitifywatch\n */\n\nuse mhwd\\GitifyWatch;\n\n$path = $modx->getOption('gitifywatch.core_path', null, MODX_CORE_PATH  . 'components/gitifywatch/', true);\nrequire_once($path . 'model/gitifywatch/gitifywatch.class.php');\n$gitifywatch = $modx->getService('gitifywatch', 'mhwd\\GitifyWatch', $path . 'model/gitifywatch/');\n\nif (!$gitifywatch) {\n    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not load gitifywatch service from ' . $path);\n    return;\n}\n\n$path = $modx->getOption('scheduler.core_path', null, $modx->getOption('core_path') . 'components/scheduler/');\n$scheduler = $modx->getService('scheduler', 'Scheduler', $path . 'model/scheduler/');\nif (!$scheduler) {\n    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not load Scheduler service from ' . $path);\n    return;\n}\n\n$environment = $gitifywatch->getEnvironment();\n$trigger = false;\n$username = ($modx->user) ? $modx->user->get('username') : 'Anonymous';\n\n\nswitch ($modx->event->name) {\n    case 'OnDocFormSave':\n        /**\n         * @var int $mode\n         * @var modResource $resource\n         */\n        $trigger = array(\n            'username' => $username,\n            'mode' => ($mode === modSystemEvent::MODE_NEW) ? 'created' : 'edited',\n            'target' => $resource->get('pagetitle'),\n            'partition' => $environment['partitions']['modResource'],\n        );\n        break;\n\n    case 'OnTempFormSave':\n        /**\n         * @var int $mode\n         * @var modTemplate $template\n         */\n        $trigger = array(\n            'username' => $username,\n            'mode' => ($mode === modSystemEvent::MODE_NEW) ? 'created' : 'edited',\n            'target' => $template->get('templatename'),\n            'partition' => $environment['partitions']['modTemplate'],\n        );\n        break;\n\n    case 'OnTempFormDelete':\n        /**\n         * @var modTemplate $template\n         */\n        $trigger = array(\n            'username' => $username,\n            'mode' => 'deleted',\n            'target' => $template->get('templatename'),\n            'partition' => $environment['partitions']['modTemplate'],\n        );\n        break;\n\n    case 'OnTVFormSave':\n        /**\n         * @var int $mode\n         * @var modTemplateVar $tv\n         */\n        $trigger = array(\n            'username' => $username,\n            'mode' => ($mode === modSystemEvent::MODE_NEW) ? 'created' : 'edited',\n            'target' => $tv->get('name'),\n            'partition' => $environment['partitions']['modTemplateVar'],\n        );\n        break;\n    case 'OnTVFormDelete':\n        /**\n         * @var modTemplateVar $tv\n         */\n        $trigger = array(\n            'username' => $username,\n            'mode' => 'deleted',\n            'target' => $tv->get('name'),\n            'partition' => $environment['partitions']['modTemplateVar'],\n        );\n        break;\n\n    case 'OnChunkFormSave':\n        /**\n         * @var int $mode\n         * @var modChunk $chunk\n         */\n        $trigger = array(\n            'username' => $username,\n            'mode' => ($mode === modSystemEvent::MODE_NEW) ? 'created' : 'edited',\n            'target' => $chunk->get('name'),\n            'partition' => $environment['partitions']['modChunk'],\n        );\n        break;\n    case 'OnChunkFormDelete':\n        /**\n         * @var modChunk $chunk\n         */\n        $trigger = array(\n            'username' => $username,\n            'mode' => 'deleted',\n            'target' => $chunk->get('name'),\n            'partition' => $environment['partitions']['modChunk'],\n        );\n        break;\n    \n    case 'OnChunkFormSave':\n        /**\n         * @var int $mode\n         * @var modChunk $chunk\n         */\n        $trigger = array(\n            'username' => $username,\n            'mode' => ($mode === modSystemEvent::MODE_NEW) ? 'created' : 'edited',\n            'target' => $chunk->get('name'),\n            'partition' => $environment['partitions']['modChunk'],\n        );\n        break;\n    case 'OnChunkFormDelete':\n        /**\n         * @var modChunk $chunk\n         */\n        $trigger = array(\n            'username' => $username,\n            'mode' => 'deleted',\n            'target' => $chunk->get('name'),\n            'partition' => $environment['partitions']['modChunk'],\n        );\n        break;\n}\n\nif ($trigger) {\n    /** @var sTask $task */\n    $task = $scheduler->getTask('gitifywatch', 'extract');\n    if ($task instanceof sTask) {\n        // Try to find one already scheduled\n        $run = $modx->getObject('sTaskRun', array(\n            'task' => $task->get('id'),\n            'status' => sTaskRun::STATUS_SCHEDULED,\n        ));\n\n        if ($run instanceof sTaskRun) {\n            $data = $run->get('data');\n            $data['triggers'][] = $trigger;\n            $run->set('data', $data);\n            $run->save();\n        } else {\n            $task->schedule(time() - 60, array(\n                'triggers' => array($trigger),\n            ));\n        }\n    }\n    else {\n        $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find sTask gitifywatch:extract');\n    }\n}"
properties: 'a:0:{}'
static: 1
static_file: /Volumes/MiniDrive/projects/GitifyWatch/core/components/gitifywatch/elements/plugins/gitifywatcher.plugin.php
content: "/**\n * @var modX $modx\n * @var array $scriptProperties\n * @var GitifyWatch $gitifywatch\n */\n\nuse mhwd\\GitifyWatch;\n\n$path = $modx->getOption('gitifywatch.core_path', null, MODX_CORE_PATH  . 'components/gitifywatch/', true);\nrequire_once($path . 'model/gitifywatch/gitifywatch.class.php');\n$gitifywatch = $modx->getService('gitifywatch', 'mhwd\\GitifyWatch', $path . 'model/gitifywatch/');\n\nif (!$gitifywatch) {\n    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not load gitifywatch service from ' . $path);\n    return;\n}\n\n$path = $modx->getOption('scheduler.core_path', null, $modx->getOption('core_path') . 'components/scheduler/');\n$scheduler = $modx->getService('scheduler', 'Scheduler', $path . 'model/scheduler/');\nif (!$scheduler) {\n    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not load Scheduler service from ' . $path);\n    return;\n}\n\n$environment = $gitifywatch->getEnvironment();\n$trigger = false;\n$username = ($modx->user) ? $modx->user->get('username') : 'Anonymous';\n\n\nswitch ($modx->event->name) {\n    case 'OnDocFormSave':\n        /**\n         * @var int $mode\n         * @var modResource $resource\n         */\n        $trigger = array(\n            'username' => $username,\n            'mode' => ($mode === modSystemEvent::MODE_NEW) ? 'created' : 'edited',\n            'target' => $resource->get('pagetitle'),\n            'partition' => $environment['partitions']['modResource'],\n        );\n        break;\n\n    case 'OnTempFormSave':\n        /**\n         * @var int $mode\n         * @var modTemplate $template\n         */\n        $trigger = array(\n            'username' => $username,\n            'mode' => ($mode === modSystemEvent::MODE_NEW) ? 'created' : 'edited',\n            'target' => $template->get('templatename'),\n            'partition' => $environment['partitions']['modTemplate'],\n        );\n        break;\n\n    case 'OnTempFormDelete':\n        /**\n         * @var modTemplate $template\n         */\n        $trigger = array(\n            'username' => $username,\n            'mode' => 'deleted',\n            'target' => $template->get('templatename'),\n            'partition' => $environment['partitions']['modTemplate'],\n        );\n        break;\n\n    case 'OnTVFormSave':\n        /**\n         * @var int $mode\n         * @var modTemplateVar $tv\n         */\n        $trigger = array(\n            'username' => $username,\n            'mode' => ($mode === modSystemEvent::MODE_NEW) ? 'created' : 'edited',\n            'target' => $tv->get('name'),\n            'partition' => $environment['partitions']['modTemplateVar'],\n        );\n        break;\n    case 'OnTVFormDelete':\n        /**\n         * @var modTemplateVar $tv\n         */\n        $trigger = array(\n            'username' => $username,\n            'mode' => 'deleted',\n            'target' => $tv->get('name'),\n            'partition' => $environment['partitions']['modTemplateVar'],\n        );\n        break;\n\n    case 'OnChunkFormSave':\n        /**\n         * @var int $mode\n         * @var modChunk $chunk\n         */\n        $trigger = array(\n            'username' => $username,\n            'mode' => ($mode === modSystemEvent::MODE_NEW) ? 'created' : 'edited',\n            'target' => $chunk->get('name'),\n            'partition' => $environment['partitions']['modChunk'],\n        );\n        break;\n    case 'OnChunkFormDelete':\n        /**\n         * @var modChunk $chunk\n         */\n        $trigger = array(\n            'username' => $username,\n            'mode' => 'deleted',\n            'target' => $chunk->get('name'),\n            'partition' => $environment['partitions']['modChunk'],\n        );\n        break;\n    \n    case 'OnChunkFormSave':\n        /**\n         * @var int $mode\n         * @var modChunk $chunk\n         */\n        $trigger = array(\n            'username' => $username,\n            'mode' => ($mode === modSystemEvent::MODE_NEW) ? 'created' : 'edited',\n            'target' => $chunk->get('name'),\n            'partition' => $environment['partitions']['modChunk'],\n        );\n        break;\n    case 'OnChunkFormDelete':\n        /**\n         * @var modChunk $chunk\n         */\n        $trigger = array(\n            'username' => $username,\n            'mode' => 'deleted',\n            'target' => $chunk->get('name'),\n            'partition' => $environment['partitions']['modChunk'],\n        );\n        break;\n}\n\nif ($trigger) {\n    /** @var sTask $task */\n    $task = $scheduler->getTask('gitifywatch', 'extract');\n    if ($task instanceof sTask) {\n        // Try to find one already scheduled\n        $run = $modx->getObject('sTaskRun', array(\n            'task' => $task->get('id'),\n            'status' => sTaskRun::STATUS_SCHEDULED,\n        ));\n\n        if ($run instanceof sTaskRun) {\n            $data = $run->get('data');\n            $data['triggers'][] = $trigger;\n            $run->set('data', $data);\n            $run->save();\n        } else {\n            $task->schedule(time() - 60, array(\n                'triggers' => array($trigger),\n            ));\n        }\n    }\n    else {\n        $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find sTask gitifywatch:extract');\n    }\n}"

-----

/**
 * @var modX $modx
 * @var array $scriptProperties
 * @var GitifyWatch $gitifywatch
 */

use mhwd\GitifyWatch;

$path = $modx->getOption('gitifywatch.core_path', null, MODX_CORE_PATH  . 'components/gitifywatch/', true);
require_once($path . 'model/gitifywatch/gitifywatch.class.php');
$gitifywatch = $modx->getService('gitifywatch', 'mhwd\GitifyWatch', $path . 'model/gitifywatch/');

if (!$gitifywatch) {
    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not load gitifywatch service from ' . $path);
    return;
}

$path = $modx->getOption('scheduler.core_path', null, $modx->getOption('core_path') . 'components/scheduler/');
$scheduler = $modx->getService('scheduler', 'Scheduler', $path . 'model/scheduler/');
if (!$scheduler) {
    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not load Scheduler service from ' . $path);
    return;
}

$environment = $gitifywatch->getEnvironment();
$trigger = false;
$username = ($modx->user) ? $modx->user->get('username') : 'Anonymous';


switch ($modx->event->name) {
    case 'OnDocFormSave':
        /**
         * @var int $mode
         * @var modResource $resource
         */
        $trigger = array(
            'username' => $username,
            'mode' => ($mode === modSystemEvent::MODE_NEW) ? 'created' : 'edited',
            'target' => $resource->get('pagetitle'),
            'partition' => $environment['partitions']['modResource'],
        );
        break;

    case 'OnTempFormSave':
        /**
         * @var int $mode
         * @var modTemplate $template
         */
        $trigger = array(
            'username' => $username,
            'mode' => ($mode === modSystemEvent::MODE_NEW) ? 'created' : 'edited',
            'target' => $template->get('templatename'),
            'partition' => $environment['partitions']['modTemplate'],
        );
        break;

    case 'OnTempFormDelete':
        /**
         * @var modTemplate $template
         */
        $trigger = array(
            'username' => $username,
            'mode' => 'deleted',
            'target' => $template->get('templatename'),
            'partition' => $environment['partitions']['modTemplate'],
        );
        break;

    case 'OnTVFormSave':
        /**
         * @var int $mode
         * @var modTemplateVar $tv
         */
        $trigger = array(
            'username' => $username,
            'mode' => ($mode === modSystemEvent::MODE_NEW) ? 'created' : 'edited',
            'target' => $tv->get('name'),
            'partition' => $environment['partitions']['modTemplateVar'],
        );
        break;
    case 'OnTVFormDelete':
        /**
         * @var modTemplateVar $tv
         */
        $trigger = array(
            'username' => $username,
            'mode' => 'deleted',
            'target' => $tv->get('name'),
            'partition' => $environment['partitions']['modTemplateVar'],
        );
        break;

    case 'OnChunkFormSave':
        /**
         * @var int $mode
         * @var modChunk $chunk
         */
        $trigger = array(
            'username' => $username,
            'mode' => ($mode === modSystemEvent::MODE_NEW) ? 'created' : 'edited',
            'target' => $chunk->get('name'),
            'partition' => $environment['partitions']['modChunk'],
        );
        break;
    case 'OnChunkFormDelete':
        /**
         * @var modChunk $chunk
         */
        $trigger = array(
            'username' => $username,
            'mode' => 'deleted',
            'target' => $chunk->get('name'),
            'partition' => $environment['partitions']['modChunk'],
        );
        break;
    
    case 'OnSnipFormSave':
        /**
         * @var int $mode
         * @var modSnippet $snippet
         */
        $trigger = array(
            'username' => $username,
            'mode' => ($mode === modSystemEvent::MODE_NEW) ? 'created' : 'edited',
            'target' => $snippet->get('name'),
            'partition' => $environment['partitions']['modSnippet'],
        );
        break;
    case 'OnSnipFormDelete':
        /**
         * @var modSnippet $snippet
         */
        $trigger = array(
            'username' => $username,
            'mode' => 'deleted',
            'target' => $snippet->get('name'),
            'partition' => $environment['partitions']['modSnippet'],
        );
        break;
    case 'OnPluginFormSave':
        /**
         * @var int $mode
         * @var modPlugin $plugin
         */
        $trigger = array(
            'username' => $username,
            'mode' => ($mode === modSystemEvent::MODE_NEW) ? 'created' : 'edited',
            'target' => $plugin->get('name'),
            'partition' => $environment['partitions']['modPlugin'],
        );
        break;
    case 'OnPluginFormDelete':
        /**
         * @var modPlugin $plugin
         */
        $trigger = array(
            'username' => $username,
            'mode' => 'deleted',
            'target' => $plugin->get('name'),
            'partition' => $environment['partitions']['modPlugin'],
        );
        break;
}

if ($trigger) {
    /** @var sTask $task */
    $task = $scheduler->getTask('gitifywatch', 'extract');
    if ($task instanceof sTask) {
        // Try to find one already scheduled
        $run = $modx->getObject('sTaskRun', array(
            'task' => $task->get('id'),
            'status' => sTaskRun::STATUS_SCHEDULED,
        ));

        if ($run instanceof sTaskRun) {
            $data = $run->get('data');
            $data['triggers'][] = $trigger;
            $run->set('data', $data);
            $run->save();
        } else {
            $task->schedule(time() - 60, array(
                'triggers' => array($trigger),
            ));
        }
    }
    else {
        $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find sTask gitifywatch:extract');
    }
}