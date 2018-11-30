<?php
namespace App\Form;

use App\Entity\Resource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ResourceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('epigrafe', TextType::class, [
                'label' => 'Epígrafe',
                'attr' => [
                    'class' => 'col-md-3',
                ],
            ])
            ->add('imageFile', VichImageType::class, array(
                'label'         => 'Imágen (jpeg/png)',
                'required'      => false,
                'allow_delete'  => true, // not mandatory, default is true
                'download_link' => true, // not mandatory, default is true
                'attr' => [
                    'class' => 'col-md-4',
                ],
            ))
           
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Resource::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_resource';
    }
}
