<?php

namespace App\Form;

use App\Entity\Mix;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MixType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre'])
            ->add('artist', TextType::class, ['label' => 'Par'])
            ->add('duration', TextType::class, ['label' => 'Durée (ex : 62 min)'])
            ->add('url', TextType::class, ['label' => 'Lien pour écouter', 'required' => false])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Mix::class]);
    }
}
