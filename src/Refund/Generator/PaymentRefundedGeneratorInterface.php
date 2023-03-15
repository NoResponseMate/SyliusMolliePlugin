<?php


declare(strict_types=1);

namespace SyliusMolliePlugin\Refund\Generator;

use SyliusMolliePlugin\DTO\PartialRefundItems;
use Sylius\Component\Core\Model\OrderInterface;

interface PaymentRefundedGeneratorInterface
{
    public function generate(OrderInterface $order): PartialRefundItems;
}
