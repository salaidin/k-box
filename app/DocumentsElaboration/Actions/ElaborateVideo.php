<?php

namespace KBox\DocumentsElaboration\Actions;

use KBox\Jobs\ConvertVideo;
use KBox\Contracts\Action;

class ElaborateVideo extends Action
{
    protected $canFail = true;

    public function run($descriptor)
    {
        \Log::info('Elaborate video action called');

        dispatch(new ConvertVideo($descriptor));
        
        return $descriptor;
    }
}
