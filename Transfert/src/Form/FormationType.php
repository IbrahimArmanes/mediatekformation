<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Niveau;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('publishedAt', null,[
                'label' => "Date de publication",
                'required'=>"true"
            ])
            ->add('title', null, [
                'label' => "Titre de la formation"
            ])
            ->add('description')
            ->add('miniature')
            ->add('picture')
            ->add('videoId', null, [
                'label' => 'ID de la vidéo'
            ])
            ->add('niveau', EntityType::class, [
                'class'=> Niveau::class,
                'choice_label' => 'nom',
                'multiple' => false,
                'required' => true
            ])
            ->add('submit', SubmitType::class, [
                "label" => "Enregistrer"
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
             $infos = $event->getData();
             $picture = $infos->getPicture();
             if (strlen($picture) > 0 && strpos($picture, "//") === false) {
             $infos->setPicture("http://" . $picture);
             }
             $miniature = $infos->getMiniature();
             if (strlen($miniature) > 0 && strpos($miniature, "//") === false) {
             $infos->setMiniature("http://" . $miniature);
             }
             $event->setData($infos);
            })                    
                    
                    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
