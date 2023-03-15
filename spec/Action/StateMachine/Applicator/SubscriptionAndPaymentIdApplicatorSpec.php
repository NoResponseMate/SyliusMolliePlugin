<?php


declare(strict_types=1);

namespace spec\SyliusMolliePlugin\Action\StateMachine\Applicator;

use SyliusMolliePlugin\Action\StateMachine\Applicator\SubscriptionAndPaymentIdApplicator;
use SyliusMolliePlugin\Action\StateMachine\Applicator\SubscriptionAndPaymentIdApplicatorInterface;
use SyliusMolliePlugin\Action\StateMachine\Transition\PaymentStateMachineTransition;
use SyliusMolliePlugin\Action\StateMachine\Transition\PaymentStateMachineTransitionInterface;
use SyliusMolliePlugin\Action\StateMachine\Transition\ProcessingStateMachineTransitionInterface;
use SyliusMolliePlugin\Action\StateMachine\Transition\StateMachineTransitionInterface;
use SyliusMolliePlugin\Client\MollieApiClient;
use SyliusMolliePlugin\Entity\MollieSubscriptionConfigurationInterface;
use SyliusMolliePlugin\Entity\MollieSubscriptionInterface;
use SyliusMolliePlugin\Transitions\MollieSubscriptionPaymentProcessingTransitions;
use SyliusMolliePlugin\Transitions\MollieSubscriptionProcessingTransitions;
use SyliusMolliePlugin\Transitions\MollieSubscriptionTransitions;
use Mollie\Api\Endpoints\PaymentEndpoint;
use Mollie\Api\Resources\Payment;
use Mollie\Api\Types\PaymentStatus;
use PhpSpec\ObjectBehavior;

final class SubscriptionAndPaymentIdApplicatorSpec extends ObjectBehavior
{
    function let(
        MollieApiClient $mollieApiClient,
        StateMachineTransitionInterface $stateMachineTransition,
        PaymentStateMachineTransitionInterface $paymentStateMachineTransition,
        ProcessingStateMachineTransitionInterface $processingStateMachineTransition
    ): void {
        $this->beConstructedWith(
            $mollieApiClient,
            $stateMachineTransition,
            $paymentStateMachineTransition,
            $processingStateMachineTransition
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(SubscriptionAndPaymentIdApplicator::class);
    }

    function it_should_implement_interface(): void
    {
        $this->shouldImplement(SubscriptionAndPaymentIdApplicatorInterface::class);
    }

    function it_applies_transition_when_status_is_open(
        MollieSubscriptionInterface $subscription,
        MollieSubscriptionConfigurationInterface $configuration,
        MollieApiClient $mollieApiClient,
        PaymentEndpoint $paymentEndpoint,
        Payment $payment,
        PaymentStateMachineTransitionInterface $paymentStateMachineTransition,
        StateMachineTransitionInterface $stateMachineTransition
    ): void {
        $subscription->getSubscriptionConfiguration()->willReturn($configuration);
        $mollieApiClient->payments = $paymentEndpoint;
        $paymentEndpoint->get('id_1')->willReturn($payment);
        $configuration->getMandateId()->willReturn(null);
        $configuration->getCustomerId()->willReturn(null);
        $payment->mandateId = 'mandate_id';
        $payment->customerId = 'customer_id';

        $configuration->setMandateId('mandate_id')->shouldBeCalled();
        $configuration->setCustomerId('customer_id')->shouldBeCalled();

        $payment->status = PaymentStatus::STATUS_OPEN;

        $paymentStateMachineTransition->apply(
            $subscription,
            MollieSubscriptionPaymentProcessingTransitions::TRANSITION_BEGIN
        )->shouldBeCalled();
        $stateMachineTransition->apply(
            $subscription,
            MollieSubscriptionTransitions::TRANSITION_PROCESS
        )->shouldBeCalled();

        $this->execute($subscription, 'id_1');
    }

    function it_applies_transition_when_status_is_pending(
        MollieSubscriptionInterface $subscription,
        MollieSubscriptionConfigurationInterface $configuration,
        MollieApiClient $mollieApiClient,
        PaymentEndpoint $paymentEndpoint,
        Payment $payment,
        PaymentStateMachineTransitionInterface $paymentStateMachineTransition,
        StateMachineTransitionInterface $stateMachineTransition
    ): void {
        $subscription->getSubscriptionConfiguration()->willReturn($configuration);
        $mollieApiClient->payments = $paymentEndpoint;
        $paymentEndpoint->get('id_1')->willReturn($payment);
        $configuration->getMandateId()->willReturn(null);
        $configuration->getCustomerId()->willReturn(null);
        $payment->mandateId = 'mandate_id';
        $payment->customerId = 'customer_id';

        $configuration->setMandateId('mandate_id')->shouldBeCalled();
        $configuration->setCustomerId('customer_id')->shouldBeCalled();

        $payment->status = PaymentStatus::STATUS_PENDING;

        $paymentStateMachineTransition->apply(
            $subscription,
            MollieSubscriptionPaymentProcessingTransitions::TRANSITION_BEGIN
        )->shouldBeCalled();
        $stateMachineTransition->apply(
            $subscription,
            MollieSubscriptionTransitions::TRANSITION_PROCESS
        )->shouldBeCalled();

        $this->execute($subscription, 'id_1');
    }

    function it_applies_transition_when_status_is_authorized(
        MollieSubscriptionInterface $subscription,
        MollieSubscriptionConfigurationInterface $configuration,
        MollieApiClient $mollieApiClient,
        PaymentEndpoint $paymentEndpoint,
        Payment $payment,
        PaymentStateMachineTransitionInterface $paymentStateMachineTransition,
        StateMachineTransitionInterface $stateMachineTransition
    ): void {
        $subscription->getSubscriptionConfiguration()->willReturn($configuration);
        $mollieApiClient->payments = $paymentEndpoint;
        $paymentEndpoint->get('id_1')->willReturn($payment);
        $configuration->getMandateId()->willReturn(null);
        $configuration->getCustomerId()->willReturn(null);
        $payment->mandateId = 'mandate_id';
        $payment->customerId = 'customer_id';

        $configuration->setMandateId('mandate_id')->shouldBeCalled();
        $configuration->setCustomerId('customer_id')->shouldBeCalled();

        $payment->status = PaymentStatus::STATUS_AUTHORIZED;

        $paymentStateMachineTransition->apply(
            $subscription,
            MollieSubscriptionPaymentProcessingTransitions::TRANSITION_BEGIN
        )->shouldBeCalled();
        $stateMachineTransition->apply(
            $subscription,
            MollieSubscriptionTransitions::TRANSITION_PROCESS
        )->shouldBeCalled();

        $this->execute($subscription, 'id_1');
    }

    function it_applies_transition_when_status_is_paid(
        MollieSubscriptionInterface $subscription,
        MollieSubscriptionConfigurationInterface $configuration,
        MollieApiClient $mollieApiClient,
        PaymentEndpoint $paymentEndpoint,
        Payment $payment,
        PaymentStateMachineTransitionInterface $paymentStateMachineTransition,
        StateMachineTransitionInterface $stateMachineTransition,
        ProcessingStateMachineTransitionInterface $processingStateMachineTransition
    ): void {
        $subscription->getSubscriptionConfiguration()->willReturn($configuration);
        $mollieApiClient->payments = $paymentEndpoint;
        $paymentEndpoint->get('id_1')->willReturn($payment);
        $configuration->getMandateId()->willReturn(null);
        $configuration->getCustomerId()->willReturn(null);
        $payment->mandateId = 'mandate_id';
        $payment->customerId = 'customer_id';

        $configuration->setMandateId('mandate_id')->shouldBeCalled();
        $configuration->setCustomerId('customer_id')->shouldBeCalled();

        $payment->status = PaymentStatus::STATUS_PAID;

        $subscription->resetFailedPaymentCount()->shouldBeCalled();

        $processingStateMachineTransition->apply(
            $subscription,
            MollieSubscriptionProcessingTransitions::TRANSITION_SCHEDULE
        )->shouldBeCalled();
        $paymentStateMachineTransition->apply(
            $subscription,
            MollieSubscriptionPaymentProcessingTransitions::TRANSITION_SUCCESS
        )->shouldBeCalled();
        $stateMachineTransition->apply(
            $subscription,
            MollieSubscriptionTransitions::TRANSITION_ACTIVATE
        )->shouldBeCalled();

        $this->execute($subscription, 'id_1');
    }

    function it_applies_transition_when_status_is_failure(
        MollieSubscriptionInterface $subscription,
        MollieSubscriptionConfigurationInterface $configuration,
        MollieApiClient $mollieApiClient,
        PaymentEndpoint $paymentEndpoint,
        Payment $payment,
        PaymentStateMachineTransitionInterface $paymentStateMachineTransition
    ): void {
        $subscription->getSubscriptionConfiguration()->willReturn($configuration);
        $mollieApiClient->payments = $paymentEndpoint;
        $paymentEndpoint->get('id_1')->willReturn($payment);
        $configuration->getMandateId()->willReturn(null);
        $configuration->getCustomerId()->willReturn(null);
        $payment->mandateId = 'mandate_id';
        $payment->customerId = 'customer_id';

        $configuration->setMandateId('mandate_id')->shouldBeCalled();
        $configuration->setCustomerId('customer_id')->shouldBeCalled();

        $payment->status = 'definitely not payment status';

        $subscription->incrementFailedPaymentCounter()->shouldBeCalled();

        $paymentStateMachineTransition->apply(
            $subscription,
            MollieSubscriptionPaymentProcessingTransitions::TRANSITION_FAILURE
        )->shouldBeCalled();

        $this->execute($subscription, 'id_1');
    }
}
