<?php

namespace RJozwiak\Libroteca\Lumen\Events;

use Illuminate\Queue\SerializesModels;

abstract class Event
{
    use SerializesModels;
}
