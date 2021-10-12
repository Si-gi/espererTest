<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class PeopleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('firstName', TextType::class)
            ->add('familySituation', ChoiceType::class, [
                'choices' => [
                    'Homme seul' => 'Homme seul',
                    'Femme seul' => "Femme seul",
                    'Couple sans enfant' => "Couple sans enfant",
                    'Couple avec enfant' => "Couple avec enfant",
                ],
            ])
            ->add('dateOfBirth', BirthdayType::class)
            ->add('save', SubmitType::class)
        ;
    }
}

?>