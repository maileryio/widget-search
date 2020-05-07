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

use Doctrine\Common\Collections\ArrayCollection;

class SearchByList extends ArrayCollection
{
    /**
     * @return array
     */
    public function getValueOptions(): array
    {
        return array_map(
            function (SearchBy $searchBy) {
                return $searchBy->getValue();
            },
            $this->toArray()
        );
    }

    /**
     * @param string|null $value
     * @return SearchBy|null
     */
    public function findByValue(?string $value): ?SearchBy
    {
        foreach ($this->getIterator() as $searchBy) {
            if ($searchBy->match($value)) {
                return $searchBy;
            }
        }

        return null;
    }
}
