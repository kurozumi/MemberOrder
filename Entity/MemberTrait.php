<?php

namespace Plugin\MemberOrder\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Member;
use Eccube\Entity\Order;

trait MemberTrait
{
    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Eccube\Entity\Order", mappedBy="Member")
     */
    private $Orders;

    /**
     * @param Order $Orders
     * @return Member
     */
    public function addOrder(Order $Order): self
    {
        if (null === $this->Orders) {
            $this->Orders = new ArrayCollection();
        }

        if (false === $this->Orders->contains($Order)) {
            $this->Orders->add($Order);
        }

        return $this;
    }

    /**
     * @param Order $Order
     * @return Member
     */
    public function removeOrder(Order $Order): self
    {
        if (null === $this->Orders) {
            $this->Orders = new ArrayCollection();
        }

        if ($this->Orders->contains($Order)) {
            $this->Orders->removeElement($Order);
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getOrders(): Collection
    {
        if (null === $this->Orders) {
            $this->Orders = new ArrayCollection();
        }

        return $this->Orders;
    }
}
