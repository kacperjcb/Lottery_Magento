<?php

namespace dsw\Lottery\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

final class LotteryConfig
{
    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function isEnabled(): bool
    {
        return (bool) $this->scopeConfig->getValue('lottery/general/enabled');
    }

    public function getMinimumAmount(): float
    {
        return (float) $this->scopeConfig->getValue('lottery/general/minimum_amount');
    }

    public function getWinningChance(): int
    {
        return (int) $this->scopeConfig->getValue('lottery/general/winning_chance');
    }
}
