<?php
namespace Ramnzys\CommonmarkKrokiExtension;

use League\CommonMark\Block\Element\FencedCode;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\ConfigurableEnvironmentInterface;

final class KrokiExtension implements ExtensionInterface
{
    private $priority;

    public function __construct($priority = 100)
    {
        $this->priority = $priority;
    }

    public function register(ConfigurableEnvironmentInterface $environment)
    {
        $environment->addBlockRenderer(FencedCode::class, new KrokiRenderer(), $this->priority);
    }
}
