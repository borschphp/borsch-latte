<?php

namespace Borsch\Latte;

use Borsch\Template\AbstractTemplateRenderer;
use Borsch\Latte\Exception\NamespaceException;
use Latte\Engine;
use Latte\Loaders\FileLoader;

class LatteRenderer extends AbstractTemplateRenderer
{

    protected Engine $latte;

    public function __construct(string $template_dir, string $temporary_dir, bool $auto_refresh = true)
    {
        $this->latte = new Engine();
        $this->latte->setLoader(new FileLoader($template_dir));
        $this->latte->setTempDirectory($temporary_dir);
        $this->latte->setAutoRefresh($auto_refresh);
    }

    public function render(string $name, array $params = []): string
    {
        $namespace_template = explode('::', $name);
        $count = count($namespace_template);

        if ($count == 0 || $count > 2) {
            throw NamespaceException::invalidNamespaceTemplate($name);
        }

        $template = $namespace_template[0];
        if ($count == 2) {
            $template = sprintf('%s/%s', $template, $namespace_template[1]);
        }

        $template = trim($template);
        if (!str_ends_with($template, '.tpl')) {
            $template .= '.tpl';
        }

        $params = array_merge($this->parameters, $params);

        // Clone Latte so $params will not override the one already in place.
        $latte = clone $this->latte;

        return $latte->renderToString($template, $params);
    }
}