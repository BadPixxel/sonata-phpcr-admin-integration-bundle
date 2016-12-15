<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Admin\Menu\Extension;

use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\CollectionType;

/**
 * Admin extension to add menu items tab to content.
 *
 * @author David Buchmann <mail@davidbu.ch>
 */
class MenuNodeReferrersExtension extends AbstractAdminExtension
{
    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('form.group_menus', array(
                'translation_domain' => 'CmfMenuBundle',
            ))
            ->add(
                'menuNodes',
                CollectionType::class,
                array(),
                array(
                    'edit' => 'inline',
                    'inline' => 'table',
                ))
            ->end()
        ;
    }
}
