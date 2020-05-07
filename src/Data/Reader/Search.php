<?php

declare(strict_types=1);

/**
 * Search Widget for Mailery Platform
 * @link      https://github.com/maileryio/widget-search
 * @package   Mailery\Widget\Search
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

namespace Mailery\Widget\Search\Data\Reader;

use Cycle\ORM\Select;
use Mailery\Widget\Search\Model\SearchBy;

class Search
{
    /**
     * @var SearchBy|null
     */
    private ?SearchBy $searchBy = null;

    /**
     * @var string|null
     */
    private ?string $searchPhrase = null;

    /**
     * @param SearchBy|null $searchBy
     * @return self
     */
    public function withSearchBy(?SearchBy $searchBy): self
    {
        $new = clone $this;

        $new->searchBy = $searchBy;

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
     * @param Select $query
     * @return Select
     */
    public function buildQuery(Select $query): Select
    {
        if (empty($this->searchBy)) {
            return $query;
        }

        return $this->searchBy
            ->withSearchPhrase($this->searchPhrase)
            ->buildQuery($query);
    }
}
