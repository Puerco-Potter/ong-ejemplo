<?php
namespace App\Form;

use App\Entity\Organizacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrganizacionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder


            ->add('formaJuridica', TextType::class, [
                'label' => 'Forma Juridica',
                'attr' => [
                    'class' => 'col-12',
                ],
            ])

            ->add('matricula', TextType::class, [
                'label' => 'Matricula',
                'attr' => [
                    'class' => 'col-12',
                ],
            ])

            ->add('nroCuit', TextType::class, [
                'label' => 'Número de Cuit',
                'attr' => [
                    'class' => 'col-12',
                ],
            ])

            ->add('areaTematica', TextType::class, [
                'label' => 'Area Temática',
                'attr' => [
                    'class' => 'col-12',
                ],
            ])

            ->add('direccion', TextType::class, [
                'label' => 'Dirección',
                'attr' => [
                    'class' => 'col-12',
                ],
            ])

            ->add('localidad', TextType::class, [
                'label' => 'Localidad',
                'attr' => [
                    'class' => 'col-12',
                ],
            ])

            ->add('codigoInstitucional', TextType::class, [
                'label' => 'Código Institucional',
                'attr' => [
                    'class' => 'col-12',
                ],
            ])

            ->add('telefonoFijo', TextType::class, [
                'label' => 'Teléfono Fijo',
                'attr' => [
                    'class' => 'col-12',
                ],
            ])

            ->add('telefonoCelular', TextType::class, [
                'label' => 'Teléfono Celular',
                'attr' => [
                    'class' => 'col-12',
                ],
            ])

            ->add('contacto', TextType::class, [
                'label' => 'Contacto',
                'attr' => [
                    'class' => 'col-12',
                ],
            ])

            ->add('observaciones', TextAreaType::class, [
                'label' => 'Observaciones',
                'attr' => [
                    'class' => 'col-12',
                ],
            ])
            ->add('file', VichFileType::class, [
                'label'=> 'Archivo (jpeg/png)',
                'required' => false,
                'allow_delete' => true, 
                'download_label' => 'Descargar',
                'attr' => [
                    'class' => 'col-md-4',
                ],
            ])

            ->add('enviar', SubmitType::class, array('label' => 'Enviar'))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Organizacion'
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
