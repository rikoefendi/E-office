<?php

use Carbon\Carbon;

function carbon($date = '')
{
    if(!$date) return Carbon::now();

    return (new Carbon($date))->setTimeZone('Asia/Jakarta');
}


// dd(carbon('Thu, 30 May 2019 14:56:58 +0000 (UTC)')->diffForHumans());
function payload($data)
{
    return $data->getPayload()->getHeaders();
}
