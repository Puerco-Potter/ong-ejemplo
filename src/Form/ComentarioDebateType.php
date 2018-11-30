<?php
namespace App\Form;

use App\Entity\ComentarioDebate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ComentarioDebateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comentario', TextareaType::class, [
                'label' => 'Comentario',
                'attr' => [
                    'class' => 'form-control col-12 ',
                    'placeholder' => 'Escribe tu mensaje',
                    'rows' => '5'
                    
                ],
            ])
            ->add('enviar', SubmitType::class, array(
                'label' => 'Enviar',
                'attr' => [
                    'class' => "btn btn-danger btn-flat",
                    'style' => "margin-top:10px;"
                ]
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ComentarioDebate::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_comentarioDebate';
    }
}
