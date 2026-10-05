<?php

namespace App\Libraries;

use Throwable;

class CI4ViewRenderer
{
    protected string $path;
    protected array $data = [];
    protected ?string $layout = null;
    protected array $sections = [];
    protected array $sectionStack = [];

    public function __construct(string $path, array $data = [])
    {
        $this->path = $path;
        $this->data = $data;
    }

    public function extend(string $layout): string
    {
        $this->layout = $layout;
        return '';
    }

    public function section(string $name): string
    {
        $this->sectionStack[] = $name;
        ob_start();
        return '';
    }

    public function endSection(): string
    {
        if (!empty($this->sectionStack)) {
            $name = array_pop($this->sectionStack);
            $this->sections[$name] = ob_get_clean();
        }
        return '';
    }

    public function renderSection(string $name): string
    {
        return $this->sections[$name] ?? '';
    }

    public function include(string $view, array $data = []): string
    {
        return (string) view($view, array_merge($this->data, $data))->render();
    }

    public function setData(array $data = [], ?string $context = null): self
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    public function setVar(string $name, $value = null, ?string $context = null): self
    {
        $this->data[$name] = $value;
        return $this;
    }

    public function __set(string $name, $value): void
    {
        $this->data[$name] = $value;
    }

    public function __get(string $name)
    {
        return $this->data[$name] ?? null;
    }

    public function __isset(string $name): bool
    {
        return isset($this->data[$name]);
    }

    public function __toString(): string
    {
        return '';
    }

    public function render(): string
    {
        $obLevel = ob_get_level();
        ob_start();
        $data = $this->data;
        extract($this->data, EXTR_SKIP);

        try {
            include $this->path;
        } catch (Throwable $e) {
            while (ob_get_level() > $obLevel) {
                ob_end_clean();
            }
            throw $e;
        }

        $output = ob_get_clean();

        // If the view extended a layout (e.g. Shop_admin/layout, Agent/layout)
        if ($this->layout) {
            $layoutView = view($this->layout, $this->data);
            $layoutPath = $layoutView->getPath();

            $obLevel = ob_get_level();
            ob_start();
            $data = $this->data;
            extract($this->data, EXTR_SKIP);

            try {
                include $layoutPath;
            } catch (Throwable $e) {
                while (ob_get_level() > $obLevel) {
                    ob_end_clean();
                }
                throw $e;
            }

            return ob_get_clean();
        }

        return $output;
    }
}
