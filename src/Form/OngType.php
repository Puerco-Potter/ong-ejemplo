<?php

namespace App\Form;

use App\Entity\Ong;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Tematica;
use App\Entity\SubTematica;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class OngType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formasJuridicas = [
            'ASOCIACIÓN CIVIL' => 0,
            'COMUNIDAD INDIGENA' => 1,
            'COOPERATIVA' => 2,
            'FUNDACIÓN' => 3,
            'GRUPO COMUNITARIO' => 4,
            'GRUPO DE PERSONAS' => 5,
            'MUTUAL' => 6,
            'NO ESPECIFICA' => 7,
            'SOCIEDAD DE FOMENTO' => 8,
        ];
        $localidades = [
            'Resistencia' => 1,
            'Avia Terai'=> 3,
            'Barranqueras'=> 4,
            'Barrio los Pescadores'=> 5,
            'Basail'=> 6,
            'Campo largo'=> 7,
            'Capitán Solari'=> 8,
            'Charadai'=> 9,
            'Charata'=> 10,
            'Chorotis'=> 11,
            'Ciervo Petiso'=> 12,
            'Colonia Aborigen'=> 13,
            'Colonia Baranda'=> 14,
            'Colonia Benitez'=> 15,
            'Colonia Elisa'=> 16,
            'Colonia Popular'=> 17,
            'Colonias Unidas'=> 18,
            'Concepción del Bermejo'=> 19,
            'Coronel Du Graty'=> 20,
            'Corzuela'=> 21,
            'Cote Lai'=> 22,
            'El Espinillo'=> 23,
            'El Sauzal'=> 24,
            'El Sauzalito'=> 25,
            'Enrique Urien'=> 26,
            'Estación General Obligado'=> 27,
            'Fontana'=>28,
            'Fortín Las Chuñas'=> 29,
            'Fortín Lavalle'=> 30,
            'Fuerte ESperanza'=> 31,
            'Gancedo'=> 32,
            'General Capdevilla'=> 33,
            'General José de San Martín'=> 34,
            'General Pinedo'=> 35,
            'General Vedia'=> 36,
            'Haumonia'=> 37,
            'Hermoso Campo'=> 38,
            'Horquilla'=> 39,
            'Ingeniero Barnet'=> 40,
            'Isla del Cerrito'=> 41,
            'Itín'=> 42,
            'Juan José Castelli'=> 43,
            'La Clotilde'=> 44,
            'La Eduvigis'=> 45,
            'La Escondida'=> 46,
            'La Leonesa'=> 47,
            'La Sábana'=> 48,
            'La Tigra'=> 49,
            'La Verde'=> 50,
            'Laguna Blanca'=> 51,
            'Laguna Limpia'=> 52,
            'Lapachito'=> 53,
            'Las Breñas'=> 54,
            'Las Garcitas'=> 55,
            'Las Palmas'=> 56,
            'Los Frentones'=> 57,
            'Machagai'=> 58,
            'Makallé'=> 59,
            'Margarita Belén'=> 60,
            'Mesón de Fierro'=> 61,
            'Miraflores'=> 62,
            'Napalpí'=> 63,
            'Napenay'=> 64,
            'Nueva Pompeya'=> 65,
            'Pampa Almirón'=> 66,
            'Pampa del Indio'=> 67,
            'Pampa del Infierno'=> 68,
            'Pampa Landriel'=> 69,
            'Presidencia de la Plaza'=> 70,
            'Presidencia Roque Sáenz Peña'=> 71,
            'Puerto Bermejo'=> 72,
            'Puerto Eva Perón'=> 73,
            'Puerto Lavalle'=> 74,
            'Puerto Tirol'=> 75,
            'Puerto Vilelas'=> 76,
            'Quitilipi'=> 77,
            'Río Muerto'=> 78, 
            'Samuhú'=> 79,
            'San Bernardo'=> 80,
            'Santa Sylvina'=> 81,
            'Selvas del Río de Oro'=> 82,
            'Taco Pozo'=> 83,
            'Tres Isletas'=> 84,
            'Venados Grandes'=> 85,
            'Villa Angela'=> 86,
            'Villa Berthet'=> 87,
            'Villa El Palmar'=> 88,
            'Villa Río Bermejito'=> 89,
            'Wichi'=> 90,
            'Zaparinqui'=> 91
        ];

        $builder
            ->add('nombreOrganizacion', null, [
                'label' => 'Nombre de la Organización',
                'attr' => ['col' => 'col-6'],
            ])
            ->add('formaJuridica', ChoiceType::class, [
                'label' => 'Forma Jurídica',
                'attr' => ['col' => 'col-6'],
                'choices' => $formasJuridicas,
            ])
            ->add('matricula', null, [
                'label' => 'Matrícula (Registro de organizaciones)',
                'attr' => ['col' => 'col-6'],
            ])
            ->add('cuit', TextType::class, [
                'required' => false,
                'label' => 'Nro CUIT',
                'attr' => ['col' => 'col-6'],
            ])
            ->add('tematicas', EntityType::class, array(
                'class' => Tematica::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->where('a.publicado = :publicado')
                        ->setParameter('publicado', true)
                        ->orderBy('a.nombre', 'ASC');
                },
                'label' => 'Área Temática Principal',
                'attr' => ['col' => 'col-12', 'class' => 'select2'],
                'required' => false,
                'multiple' => true,
            ))
            ->add('subTematicas', EntityType::class, array(
                'class' => SubTematica::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->where('a.publicado = :publicado')
                        ->setParameter('publicado', true)
                        ->orderBy('a.nombre', 'ASC');
                },
                'choice_label' => 'nombreMasTematica',
                'label' => 'Área Temática Secundaria',
                'attr' => ['col' => 'col-12', 'class' => 'select2'],
                'required' => false,
                'multiple' => true,
            ))
            ->add('direccion', null, [
                'label' => 'Dirección (Calle, Nro, Dto, Barrio, Piso)',
                'attr' => ['col' => 'col-12'],
            ])
            ->add('localidad', ChoiceType::class, [
                'attr' => ['col' => 'col-6'],
                'choices' => $localidades,
            ])
            ->add('codigoPostal', null, [
                'label' => 'Código Postal',
                'attr' => ['col' => 'col-6'],
            ])
            ->add('telefono', null, [
                'label' => 'Tel. Fijo (Unicamente números)',
                'attr' => ['col' => 'col-6'],
            ])
            ->add('celular', null, [
                'label' => 'Celular (Unicamente números)',
                'attr' => ['col' => 'col-6'],
            ])
            ->add('apellido', null, [
                'label' => 'Contacto Apellido',
                'attr' => ['col' => 'col-4'],
            ])
            ->add('nombre', null, [
                'label' => 'Contacto Nombre',
                'attr' => ['col' => 'col-4'],
            ])
            ->add('observaciones', null, [
                'label' => 'Observaciones',
                'attr' => ['col' => 'col-12'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ong::class,
        ]);
    }
}
