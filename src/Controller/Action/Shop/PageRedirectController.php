<?php

namespace SyliusMolliePlugin\Controller\Action\Shop;

use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sylius\Component\Order\Repository\OrderRepositoryInterface;

class PageRedirectController
{
    private const ORDER_COMPLETED_STATE = 'completed';

    public function __construct(
        private RouterInterface $router,
        private OrderRepositoryInterface $orderRepository
    )
    {
    }

    /**
     * @param Request $request
     * @param SessionInterface $session
     *
     * @return RedirectResponse
     */
    public function thankYouAction(Request $request, SessionInterface $session)
    {
        $orderId = $request->get('orderId');
        $session->set('sylius_order_id', $orderId);
        $thankYouPageUrl = $this->router->generate('sylius_shop_order_thank_you');

        /** @var OrderInterface|null $order */
        $order = $this->orderRepository->findOneBy(['id' => $orderId]);
        $payment = $order->getLastPayment();
        $orderToken = $order->getTokenValue();

        if ($payment && $payment->getState() === self::ORDER_COMPLETED_STATE) {
            return new RedirectResponse($thankYouPageUrl);
        }
        $cartSummaryUrl = str_replace('thank-you', $orderToken, $thankYouPageUrl);
        $cartSummaryUrl = '/' . implode('/', array_slice(explode('/', $cartSummaryUrl), 2));

        return new RedirectResponse($cartSummaryUrl);
    }
}
