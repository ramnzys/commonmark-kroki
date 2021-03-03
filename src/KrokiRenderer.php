<?php
namespace Ramnzys\CommonmarkKrokiExtension;

use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\Block\Element\FencedCode;
use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Util\ConfigurationAwareInterface;
use League\CommonMark\Util\ConfigurationInterface;

class KrokiRenderer implements BlockRendererInterface, ConfigurationAwareInterface
{
    /** @var ConfigurationInterface */
    private $config;

    private function encodeDiagramString($diagramData)
    {
        return rtrim(strtr(base64_encode(gzcompress($diagramData)), '+/', '-_'), '=');
    }

    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, bool $inTightList = false)
    {
        /** @var FencedCode $block */
        $infoWords = $block->getInfoWords();

        if(empty($infoWords) || empty($infoWords[0])) {
            return null;
        }

        $infoWord = strtolower($infoWords[0]);
        $diagramType = null;

        if(in_array($infoWord,$this->config->get('kroki/auto', ['plantuml']))) {
            $diagramType = $infoWord;
        }

        if(substr($infoWord,0,6) == 'kroki:') {
            $diagramType = substr($infoWord,-1*(strlen($infoWord)-6));
        }

        if(!$diagramType) {
            return null;
        }

        $krokiServer = rtrim($this->config->get('kroki/server', 'https://kroki.io'), '/');
        $outputFormat = $this->config->get('kroki/format', 'svg');

        $encodedData = $this->encodeDiagramString($block->getStringContent());

        return new HtmlElement('img', ['src' => "{$krokiServer}/{$diagramType}/{$outputFormat}/{$encodedData}"], '', true);

    }

    public function setConfiguration(ConfigurationInterface $config)
    {
        $this->config = $config;
    }

}
