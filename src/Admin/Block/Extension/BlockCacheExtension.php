<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Admin\Block\Extension;

use Sonata\AdminBundle\Admin\AdminExtension;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Provide cache form fields for block models.
 *
 * @author Sven Cludius <sven.cludius@valiton.com>
 */
class BlockCacheExtension extends AdminExtension
{
    /**
     * Configure form fields.
     *
     * @param FormMapper $formMapper
     */
    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('form.group_metadata', array('translation_domain' => 'CmfBlockBundle'))
                ->add('ttl', TextType::class)
            ->end()
        ;
    }
}
