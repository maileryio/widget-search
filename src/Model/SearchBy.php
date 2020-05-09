<?php

declare(strict_types=1);

/**
 * Search Widget for Mailery Platform
 * @link      https://github.com/maileryio/widget-search
 * @package   Mailery\Widget\Search
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

namespace Mailery\Widget\Search\Model;

use Cycle\ORM\Select;

abstract class SearchBy
{
    /**
     * @var string|null
     */
    private ?string $label = null;

    /**
     * @var string|null
     */
    private ?string $value = null;

    /**
     * @var string|null
     */
    private ?string $searchPhrase = null;

    /**
     * @param string|null $label
     * @return self
     */
    public function withLabel(?string $label): self
    {
        $new = clone $this;

        $new->label = $label;

        return $new;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function withValue(?string $value): self
    {
        $new = clone $this;

        $new->value = $value;

        return $new;
    }

    /**
     * @param string|null $searchPhrase
     * @return self
     */
    public function withSearchPhrase(?string $searchPhrase): self
    {
        $new = clone $this;

        $new->searchPhrase = $searchPhrase;

        return $new;
    }

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     * @return bool
     */
    public function match(?string $value): bool
    {
        return $value === $this->getValue();
    }

    /**
     * @param Select $query
     * @return Select
     */
    public function buildQuery(Select $query): Select
    {
        if (empty($this->searchPhrase)) {
            return $query;
        }

        return $this->buildQueryInternal($query, $this->searchPhrase);
    }

    /**
     * @param Select $query
     * @param string $searchPhrase
     * @return Select
     */
    abstract protected function buildQueryInternal(Select $query, string $searchPhrase): Select;
}
