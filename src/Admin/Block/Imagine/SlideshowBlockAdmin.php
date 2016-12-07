<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Admin\Block\Imagine;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Admin\Block\AbstractBlockAdmin;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SlideshowBlock;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\CoreBundle\Form\Type\CollectionType;
use Sonata\DoctrinePHPCRAdminBundle\Form\Type\TreeModelType;

/**
 * @author Horner
 */
class SlideshowBlockAdmin extends AbstractBlockAdmin
{
    /**
     * Service name of the sonata_type_collection service to embed.
     *
     * @var string
     */
    protected $embeddedAdminCode;

    /**
     * Configure the service name (admin_code) of the admin service for the embedded slides.
     *
     * @param string $adminCode
     */
    public function setEmbeddedSlidesAdmin($adminCode)
    {
        $this->embeddedAdminCode = $adminCode;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);
        $listMapper
            ->addIdentifier('id', 'text')
            ->add('title', 'text')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->with('form.group_general')
                ->add('title', TextType::class, array('required' => false))
            ->end()
            ->with('Items')
                ->add('children', CollectionType::class,
                    array(),
                    array(
                        'edit' => 'inline',
                        'inline' => 'table',
                        'admin_code' => $this->embeddedAdminCode,
                        'sortable' => 'position',
                    ))
            ->end()
        ;

        if (null === $this->getParentFieldDescription()) {
            $formMapper
                ->with('form.group_general')
                    ->add('parentDocument', TreeModelType::class, array('root_node' => $this->getRootPath(), 'choice_list' => array(), 'select_root_node' => true))
                    ->add('name', TextType::class)
                ->end()
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($slideshow)
    {
        foreach ($slideshow->getChildren() as $child) {
            $child->setName($this->generateName());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($slideshow)
    {
        foreach ($slideshow->getChildren() as $child) {
            if (!$this->modelManager->getNormalizedIdentifier($child)) {
                $child->setName($this->generateName());
            }
        }
    }

    /**
     * Generate a most likely unique name.
     *
     * TODO: have blocks use the autoname annotation - https://github.com/symfony-cmf/BlockBundle/issues/149
     *
     * @return string
     */
    private function generateName()
    {
        return 'child_'.time().'_'.rand();
    }

    public function toString($object)
    {
        return $object instanceof SlideshowBlock && $object->getTitle()
            ? $object->getTitle()
            : parent::toString($object)
        ;
    }
}
