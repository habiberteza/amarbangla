<?php

namespace App\Libraries;

class CI4Pager
{
    protected int $total;
    protected int $perPage;
    protected int $currentPage;
    protected string $group;
    protected int $surroundCount = 2;

    public function __construct(int $total = 0, int $perPage = 20, int $currentPage = 1, string $group = 'default')
    {
        $this->total = max(0, $total);
        $this->perPage = max(1, $perPage);
        $this->currentPage = max(1, $currentPage);
        $this->group = $group;
    }

    public function setSurroundCount(int $count)
    {
        $this->surroundCount = max(0, $count);
        return $this;
    }

    public function getPageCount(): int
    {
        return (int) ceil($this->total / $this->perPage);
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function hasPrevious(): bool
    {
        return $this->currentPage > 1;
    }

    public function hasNext(): bool
    {
        return $this->currentPage < $this->getPageCount();
    }

    public function getFirst(): string
    {
        return $this->getPageURI(1);
    }

    public function getLast(): string
    {
        return $this->getPageURI(max(1, $this->getPageCount()));
    }

    public function getPrevious(): ?string
    {
        return $this->hasPrevious() ? $this->getPageURI($this->currentPage - 1) : null;
    }

    public function getNext(): ?string
    {
        return $this->hasNext() ? $this->getPageURI($this->currentPage + 1) : null;
    }

    public function getPageURI(?int $page = null): string
    {
        $page = $page ?? $this->currentPage;
        $key = ($this->group === 'default') ? 'page' : 'page_' . $this->group;

        $params = request()->query();
        if ($page > 1) {
            $params[$key] = $page;
        } else {
            unset($params[$key]);
        }

        $query = http_build_query($params);
        $url = request()->url();
        if (empty($url) || $url === 'http://:' || !filter_var($url, FILTER_VALIDATE_URL)) {
            $path = ltrim(request()->path(), '/');
            $url = function_exists('base_url') ? base_url($path) : url($path);
        }
        return $query ? "{$url}?{$query}" : $url;
    }

    public function getPageDetails(): array
    {
        $pageCount = $this->getPageCount();
        $start = max(1, $this->currentPage - $this->surroundCount);
        $end = min($pageCount, $this->currentPage + $this->surroundCount);

        $links = [];
        for ($i = $start; $i <= $end; $i++) {
            $links[] = [
                'uri'    => $this->getPageURI($i),
                'title'  => (string) $i,
                'active' => ($i === $this->currentPage),
            ];
        }

        return $links;
    }

    public function links(...$args)
    {
        $template = $args[1] ?? 'default_full';
        return $this->render($template);
    }

    public function render(string $template = 'default_full'): string
    {
        $pageCount = $this->getPageCount();
        if ($pageCount <= 1) {
            return '';
        }

        $html = '<nav aria-label="Page navigation">';
        $html .= '<ul class="pagination pagination-sm m-0 justify-content-center">';

        if ($this->hasPrevious()) {
            $html .= '<li class="page-item"><a href="' . htmlspecialchars($this->getFirst()) . '" class="page-link" aria-label="First">&laquo;</a></li>';
            $html .= '<li class="page-item"><a href="' . htmlspecialchars($this->getPrevious()) . '" class="page-link" aria-label="Previous">&lsaquo;</a></li>';
        }

        foreach ($this->getPageDetails() as $link) {
            $active = $link['active'] ? ' active' : '';
            $html .= '<li class="page-item' . $active . '"><a href="' . htmlspecialchars($link['uri']) . '" class="page-link">' . htmlspecialchars($link['title']) . '</a></li>';
        }

        if ($this->hasNext()) {
            $html .= '<li class="page-item"><a href="' . htmlspecialchars($this->getNext()) . '" class="page-link" aria-label="Next">&rsaquo;</a></li>';
            $html .= '<li class="page-item"><a href="' . htmlspecialchars($this->getLast()) . '" class="page-link" aria-label="Last">&raquo;</a></li>';
        }

        $html .= '</ul></nav>';
        return $html;
    }

    public function simpleLinks(?string $group = 'default', ?string $template = 'default_simple'): string
    {
        return $this->render($template ?: 'default_simple');
    }

    public function makeLinks(int $page, ?int $perPage, int $total, string $template = 'default_full', int $segment = 0, ?string $group = 'default'): string
    {
        $this->currentPage = max(1, $page);
        $this->perPage = max(1, $perPage ?: 20);
        $this->total = max(0, $total);
        if ($group) {
            $this->group = $group;
        }
        return $this->render($template);
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
