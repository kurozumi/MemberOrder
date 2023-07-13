<?php

/**
 * This file is part of MemberOrder
 *
 * Copyright(c) Akira Kurozumi <info@a-zumi.net>
 *
 * https://a-zumi.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\MemberOrder;

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Member;
use Eccube\Entity\Order;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Event\TemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class Event implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            EccubeEvents::ADMIN_ORDER_EDIT_INDEX_COMPLETE => 'onAdminOrderEditIndexComplete',
            '@admin/Order/edit.twig' => 'onRenderAdminOrderEdit',
        ];
    }

    public function onAdminOrderEditIndexComplete(EventArgs $event)
    {
        if (!$this->security->getUser() instanceof Member) {
            return;
        }

        /** @var Order $Order */
        $Order = $event->getArgument('TargetOrder');

        /**
         * ############################
         * 端末種別がNULLではなくかつ登録メンバーがNULLの場合、
         * 受注を更新するとカスタマーの注文にもかかわらずメンバーが登録されてしまうため、
         * 端末種別と登録メンバーがNULLの場合、メンバーを登録する
         *
         * 登録メンバーのNULL判定をしない場合、受注を更新するたびに登録メンバーも更新されます。
         * ############################
         */
        if (null === $Order->getMember() && null == $Order->getDeviceType()) {
            $Order->setMember($this->security->getUser());
            $this->entityManager->persist($Order);
            $this->entityManager->flush();
        }
    }

    public function onRenderAdminOrderEdit(TemplateEvent $event)
    {
        $event->addSnippet('@MemberOrder/admin/Order/edit.twig');
    }
}
