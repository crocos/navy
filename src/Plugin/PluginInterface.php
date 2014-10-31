<?php
namespace Navy\Plugin;

interface PluginInterface
{
    /**
     * @return string module name.
     */
    public function getName();

    /**
     * @return string config path.
     */
    public function getConfig();

    /**
     * @return array
     */
    public function getHooks();
}
