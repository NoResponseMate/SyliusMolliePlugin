<?php


declare(strict_types=1);

namespace SyliusMolliePlugin\Checker\ApplePay;

use SyliusMolliePlugin\Entity\MollieGatewayConfigInterface;
use Mollie\Api\Types\PaymentMethod;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

final class ApplePayEnabledChecker implements ApplePayEnabledCheckerInterface
{
    /** @var RepositoryInterface */
    private $mollieGatewayConfigurationRepository;

    public function __construct(RepositoryInterface $mollieGatewayConfigurationRepository)
    {
        $this->mollieGatewayConfigurationRepository = $mollieGatewayConfigurationRepository;
    }

    public function isEnabled(): bool
    {
        $applePayConfig = $this->mollieGatewayConfigurationRepository->findOneBy([
            'methodId' => PaymentMethod::APPLEPAY,
        ]);

        if ($applePayConfig instanceof MollieGatewayConfigInterface) {
            Assert::notNull($applePayConfig->isApplePayDirectButton());

            return $applePayConfig->isApplePayDirectButton() && $applePayConfig->isEnabled();
        }

        return false;
    }
}
