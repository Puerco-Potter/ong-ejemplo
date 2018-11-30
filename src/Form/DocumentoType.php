<?php
namespace App\Form;

use App\Entity\Documento;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DocumentoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('epigrafe', TextType::class, [
                'label' => 'Título',
                'attr' => [
                    'class' => 'col-md-3',
                    'required' => 'required'
                ],
            ])

            ->add('imageFile', VichFileType::class, array(
                'label'         => 'Archivo',
                'required'      => false,
                'allow_delete'  => true, // not mandatory, default is true
                'download_link' => true, // not mandatory, default is true
                'attr' => [
                    'class' => 'col-md-12',
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
            'data_class' => Documento::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_documento';
    }
}
