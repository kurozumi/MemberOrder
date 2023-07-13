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

namespace Plugin\MemberOrder\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation\EntityExtension;
use Eccube\Entity\Member;

/**
 * @EntityExtension("Eccube\Entity\Order")
 */
trait OrderTrait
{
    /**
     * @var Member
     *
     * @ORM\ManyToOne(targetEntity="Eccube\Entity\Member", inversedBy="Orders")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id", nullable=true)
     */
    private $Member;

    /**
     * @return Member|null
     */
    public function getMember(): ?Member
    {
        return $this->Member;
    }

    /**
     * @param Member|null $Member
     * @return OrderTrait
     */
    public function setMember(?Member $Member): self
    {
        $this->Member = $Member;

        return $this;
    }
}
