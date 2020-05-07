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

use Countable;
use Cycle\ORM\Select;
use Doctrine\Common\Collections\ArrayCollection;
use InvalidArgumentException;
use IteratorAggregate;
use Spiral\Database\Query\SelectQuery;
use Spiral\Pagination\PaginableInterface;
use Traversable;
use Yiisoft\Data\Reader\CountableDataInterface;
use Yiisoft\Data\Reader\DataReaderInterface;
use Yiisoft\Data\Reader\OffsetableDataInterface;
use Yiisoft\Data\Reader\Sort;
use Yiisoft\Data\Reader\SortableDataInterface;
use Yiisoft\Yii\Cycle\DataReader\Cache\CachedCollection;
use Yiisoft\Yii\Cycle\DataReader\Cache\CachedCount;

final class SelectDataReader implements
    DataReaderInterface,
    OffsetableDataInterface,
    CountableDataInterface,
    SortableDataInterface,
    IteratorAggregate
{
    /** @var Select|SelectQuery */
    private $query;
    private ?int $limit = null;
    private ?int $offset = null;
    private ?Sort $sorting = null;
    private ?Search $search = null;
    private CachedCount $countCache;
    private CachedCollection $itemsCache;

    /**
     * @param Select|SelectQuery $query
     */
    public function __construct($query)
    {
        if (!$query instanceof Countable) {
            throw new InvalidArgumentException(sprintf('Query should implement %s interface', Countable::class));
        }
        if (!$query instanceof PaginableInterface) {
            throw new InvalidArgumentException(
                sprintf('Query should implement %s interface', PaginableInterface::class)
            );
        }
        $this->query = clone $query;
        $this->countCache = new CachedCount($this->query);
        $this->itemsCache = new CachedCollection();
    }

    public function getSort(): ?Sort
    {
        return $this->sorting;
    }

    public function withLimit(int $limit): self
    {
        $clone = clone $this;
        $clone->setLimit($limit);

        return $clone;
    }

    public function withOffset(int $offset): self
    {
        $clone = clone $this;
        $clone->setOffset($offset);

        return $clone;
    }

    public function withSort(?Sort $sorting): self
    {
        $clone = clone $this;
        $clone->setSort($sorting);

        return $clone;
    }

    public function withSearch(?Search $search): self
    {
        $clone = clone $this;
        $clone->setSearch($search);

        return $clone;
    }

    public function count(): int
    {
        return $this->countCache->getCount();
    }

    public function read(): iterable
    {
        if ($this->itemsCache->getCollection() !== null) {
            $collection = $this->itemsCache->getCollection();
        } else {
            $query = $this->buildQuery();
            $this->itemsCache->setCollection($query->fetchAll());

            $collection = $this->itemsCache->getCollection();
        }

        return $collection ?? new ArrayCollection();
    }

    /**
     * Get Iterator without caching
     * @throws \Exception
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        if ($this->itemsCache->getCollection() !== null) {
            yield from $this->itemsCache->getCollection();
        } else {
            yield from $this->buildQuery()->getIterator();
        }
    }

    private function setSearch(?Search $search): void
    {
        if ($this->search !== $search) {
            $this->search = $search;
            $this->itemsCache = new CachedCollection();
        }
    }

    private function setSort(?Sort $sorting): void
    {
        if ($this->sorting !== $sorting) {
            $this->sorting = $sorting;
            $this->itemsCache = new CachedCollection();
        }
    }

    private function setLimit(?int $limit): void
    {
        if ($this->limit !== $limit) {
            $this->limit = $limit;
            $this->itemsCache = new CachedCollection();
        }
    }

    private function setOffset(?int $offset): void
    {
        if ($this->offset !== $offset) {
            $this->offset = $offset;
            $this->itemsCache = new CachedCollection();
        }
    }

    /**
     * @return Select|SelectQuery
     */
    private function buildQuery()
    {
        $newQuery = clone $this->query;
        if ($this->search !== null && $newQuery instanceof Select) {
            $newQuery = $this->search->buildQuery($newQuery);
        }
        if ($this->offset !== null) {
            $newQuery->offset($this->offset);
        }
        if ($this->sorting !== null) {
            $newQuery->orderBy($this->sorting->getOrder());
        }
        if ($this->limit !== null) {
            $newQuery->limit($this->limit);
        }

        return $newQuery;
    }
}
