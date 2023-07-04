<?php declare(strict_types=1);

namespace DeveloperHub\CancelOrder\Api;

use DeveloperHub\CancelOrder\Api\Data\CancelOrderInterface;
interface CancelOrderRepositoryInterface
{
    /**
     * @param CancelOrderInterface $cancelOrder
     * @return mixed
     */
    public function save(CancelOrderInterface $cancelOrder);
}
