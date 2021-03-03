# Renders diagrams using [kroki](https://kroki.io) service with league/commonmark.

A block renderer for  [league/commonmark](https://github.com/thephpleague/commonmark) to render blocks using [yuzutech/kroki](https://github.com/yuzutech/kroki).

> **Kroki** provides a unified API with support for BlockDiag (BlockDiag, SeqDiag, ActDiag, NwDiag, PacketDiag, RackDiag), BPMN, Bytefield, C4 (with PlantUML), Ditaa, Erd, GraphViz, Mermaid, Nomnoml, PlantUML, SvgBob, UMLet, Vega, Vega-Lite and WaveDrom…​ and more to come!

## Installation

You can install the package via composer:

```bash
composer require ramnzys/commonmark-kroki
```
## Usage

Create a custom CommonMark environment, and register the `KrokiExtension` as described in the [league/commonmark documentation](https://commonmark.thephpleague.com/1.5/customization/extensions/).

````php
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use Ramnzys\CommonmarkKrokiExtension\KrokiExtension;

$environment = Environment::createCommonMarkEnvironment();
$environment->addExtension(new KrokiExtension());

$config = [];

$commonMarkConverter = new CommonMarkConverter($config, $environment);

$markdownContent = <<<EOT
```kroki:graphviz
digraph sample {
    a->b,c->d
   }
```

```plantuml
a->b
```
EOT;

$htmlContent= $commonMarkConverter->convertToHtml($markdownContent);

echo $htmlContent;
````
### Rendered HTML output

````
<img src="https://kroki.io/graphviz/svg/eJxLyUwvSizIUChOzC3ISVWo5lIAgkRduySdZF27FBCvlgsA13gKJA" />
<img src="https://kroki.io/plantuml/svg/eJxL1LVL4gIABCYBOQ" />
````
![](https://kroki.io/graphviz/svg/eJxLyUwvSizIUChOzC3ISVWo5lIAgkRduySdZF27FBCvlgsA13gKJA)
![](https://kroki.io/plantuml/svg/eJxL1LVL4gIABCYBOQ)

### Configuration

The default configuration used by this extension is:
```php
    $config = [
        'kroki' => [
            'server'    => 'https://kroki.io',
            'format'    => 'svg',
            'auto'      => ['plantuml']
        ]
    ];
```

| Config | Description | Default value |
|---|---|---|
|server|Specifies the server endpoint to be used for the diagram rendering. By default the public service is used, but kroki can be [self hosted/deployed](https://docs.kroki.io/kroki/setup/install/). |`https://kroki.io`|
|format|Specifies the render output format. Supported format include svg, png, jpeg, pdf and base64. The output format available depends on the diagram type (https://kroki.io/#support). | `svg` |
|auto| In order to signal the extension to parse the content of the code block as a diagram, you have to specify the *language* of the code block to be `kroki:plantuml` (or any other diagram format suported: plantuml, graphviz, blockdiag, mermaid, etc). This option allow you to specify an array of *diagram types* that my be used directly as the *language*, ommiting the `kroki:` suffix as shown in the usage example. | `['plantuml']`|

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
