<?php

namespace AdminBundle\Form\Factory;

/**
 * Interface FactoryInterface
 * @package AdminBundle\Form\Factory
 */
interface FactoryInterface
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm();
}
