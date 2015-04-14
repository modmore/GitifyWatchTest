id: 2
name: GitifyWatcher
properties: null
static: 1
static_file: /Volumes/MiniDrive/projects/GitifyWatch/core/components/gitifywatch/elements/plugins/gitifywatcher.plugin.php

-----

/**
 * @var modX $modx
 * @var array $scriptProperties
 * @var GitifyWatch $GitifyWatch
 */

use mhwd\GitifyWatch;

$path = $modx->getOption('GitifyWatch.core_path', null, MODX_CORE_PATH  . 'components/GitifyWatch/', true);
require_once($path . 'model/GitifyWatch/GitifyWatch.class.php');
$GitifyWatch = $modx->getService('GitifyWatch', 'mhwd\GitifyWatch', $path . 'model/GitifyWatch/');

if (!$GitifyWatch) {
    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not load GitifyWatch service from ' . $path);
    return;
}

$path = $modx->getOption('scheduler.core_path', null, $modx->getOption('core_path') . 'components/scheduler/');
$scheduler = $modx->getService('scheduler', 'Scheduler', $path . 'model/scheduler/');
if (!$scheduler) {
    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not load Scheduler service from ' . $path);
    return;
}

$environment = $GitifyWatch->getEnvironment();

switch ($modx->event->name) {
    case 'OnBeforeDocFormSave':
        $_SESSION['dirty'] = $resource->_dirty;
        break;


    case 'OnDocFormSave':
        /**
         * @var int $mode
         * @var modResource $resource
         */
        $trigger = array(
            'username' => ($modx->user) ? $modx->user->get('username') : 'Anonymous',
            'mode' => ($mode === modSystemEvent::MODE_NEW) ? 'created' : 'edited',
            'target' => $resource->get('pagetitle'),
            'partition' => $environment['partitions']['modResource'],
        );

        /** @var sTask $task */
        $task = $scheduler->getTask('GitifyWatch', 'extract');
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
            }
            else {
                $task->schedule(time() - 60, array(
                    'triggers' => array($trigger),
                ));
            }
        }
        else {
            $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find sTask GitifyWatch:extract');
        }

        return;
        break;
}