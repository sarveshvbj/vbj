<?php
/**
 * @deprecated 101.0.0
 */
declare(strict_types=1);

namespace FishPig\WordPress\Helper;

class Core extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @return bool
     */
    public function hasHelper(): bool
    {
        return false;
    }

    /**
     * @return self
     */
    public function getHelper(): self
    {
        return $this;
    }

    /**
     * @param  \Closure $callback
     * @return mixed
     */
    public function simulatedCallback(\Closure $callback, array $params = [])
    {
        return null;
    }

    /**
     * @return string|false
     */
    public function getHtml()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return false;
    }
}
