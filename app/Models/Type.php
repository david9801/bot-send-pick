<?php

namespace App\Models;

class Type
{
    const NORMAL = 'normal';
    const LIVE = 'live';
    const COMBO = 'combo';
    const ASIAN = 'asian';
    const ASIAN_LIVE = 'asian_live';

    private $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function isLive()
    {
        return $this->type === self::LIVE;
    }

    public function isCombo()
    {
        return $this->type === self::COMBO;
    }

    public function isAsian()
    {
        return $this->type === self::ASIAN;
    }
}
