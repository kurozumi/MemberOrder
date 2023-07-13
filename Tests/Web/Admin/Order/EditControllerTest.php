<?php

namespace Plugin\MemberOrder\Tests\Web\Admin\Order;

use Eccube\Entity\Member;
use Eccube\Entity\Order;

class EditControllerTest extends \Eccube\Tests\Web\Admin\Order\EditControllerTest
{
    public function testRoutingAdminOrderNewPost()
    {
        $Member = $this->entityManager->getRepository(Member::class)->find(1);
        $this->loginTo($Member);

        $formData = $this->createFormData($this->Customer, $this->Product);
        unset($formData['OrderStatus']);
        $crawler = $this->client->request(
            'POST',
            $this->generateUrl('admin_order_new'),
            [
                'order' => $formData,
                'mode' => 'register',
            ]
        );

        $url = $crawler->filter('a')->text();
        $this->assertTrue($this->client->getResponse()->isRedirect($url));

        /** @var Order[] $Orders */
        $Orders = $this->orderRepository->findBy([], ['create_date' => 'DESC']);
        self::assertEquals($Member, $Orders[0]->getMember());
    }
}
