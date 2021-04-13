<?php

namespace App\Form;

use App\Entity\Property;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'label' => 'Titre'
            ])
            ->add('description', null, [
                'label' => 'Description'
            ])
            ->add('surface', null, [
                'label' => 'Surface'
            ])
            ->add('rooms', null, [
                'label' => 'Piece(s)'
            ])
            ->add('bedrooms', null, [
                'label' => 'Chambre(s)'
            ])
            ->add('floor', null, [
                'label' => 'Etage'
            ])
            ->add('price', null, [
                'label' => 'Prix'
            ])
            ->add('heat', null, [
                'label' => 'Chauffage'
                //],  ChoiceType::class, [
                //     'choices' => $this->getchoices()
            ])
            ->add('city', null, [
                'label' => 'Ville'
            ])
            ->add('address', null, [
                'label' => 'Adresse'
            ])
            ->add('postal_code', null, [
                'label' => 'Code Postal'
            ])
            ->add('sold', null, [
                'label' => 'Vendu'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
            'translation_domain' => 'forms'
        ]);
    }

    //  ----A voir-----------------

    private function getChoices()
    {
        $choices = Property::HEAT;
        $output = [];
        foreach ($choices as $k => $v) {
            $output[$v] = $k;
        }
        return $output;
    }
    // --------------------------------
}
