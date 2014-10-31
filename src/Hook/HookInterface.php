<?php
namespace Navy\Hook;

interface HookInterface
{
    /**
     * @return string Module Hook Event name.
     */
    public function getEvent();
}
