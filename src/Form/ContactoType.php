<?php

namespace App\Form;

use App\Entity\Contacto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
// use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ContactoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, [
                'attr' => ['class' => 'form-control col-12'],
            ])
            ->add('apellido', TextType::class, [
                'attr' => ['class' => 'form-control col-12'],
            ])
            ->add('cuil', TextType::class, [
                'label' => 'CUIT/CUIL',
                'attr' => ['class' => 'form-control col-12'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo electrónico',
                'attr' => ['class' => 'form-control col-12'],
            ])
            ->add('telefono', TelType::class, [
                'label' => 'Número de teléfono celular',
                'attr' => ['class' => 'form-control col-12'],
            ])
            ->add('organizacion', TextType::class, [
                'label' => 'Nombre de la ONG',
                'attr' => ['class' => 'form-control col-12'],
            ])
            ->add('cuitOng', TextType::class, [
                'label' => 'CUIT ONG',
                'attr' => ['class' => 'form-control col-12'],
                'required' => false,
            ])
            ->add('observaciones', TextareaType::class, [
                'attr' => ['class' => 'form-control col-12','rows'=>"7"],
            ])
            ->add('enviar', SubmitType::class, array(
                'label' => 'Enviar',
                'attr'=>['class' => 'btn btn-success'],
            ));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contacto::class,
        ]);
    }
}
