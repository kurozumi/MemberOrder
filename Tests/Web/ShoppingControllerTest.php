<?php

namespace Plugin\MemberOrder\Tests\Web;

use Eccube\Entity\Order;

class ShoppingControllerTest extends \Eccube\Tests\Web\ShoppingControllerTest
{
    public function testCompleteWithLogin()
    {
        $Customer = $this->createCustomer();

        $this->scenarioCartIn($Customer);

        // 手続き画面
        $crawler = $this->scenarioConfirm($Customer);
        $this->expected = 'ご注文手続き';
        $this->actual = $crawler->filter('.ec-pageHeader h1')->text();
        $this->verify();

        // 確認画面
        $crawler = $this->scenarioComplete($Customer, $this->generateUrl('shopping_confirm'));
        $this->expected = 'ご注文内容のご確認';
        $this->actual = $crawler->filter('.ec-pageHeader h1')->text();
        $this->verify();

        // 完了画面
        $crawler = $this->scenarioCheckout($Customer);
        $this->assertTrue($this->client->getResponse()->isRedirect($this->generateUrl('shopping_complete')));

        // 生成された受注のチェック
        $Order = $this->entityManager->getRepository(Order::class)->findOneBy(
            [
                'Customer' => $Customer,
            ]
        );

        self::assertNull($Order->getMember());
    }
}
