<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Department;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    // Format E-mail valide
                    new Email(),
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Bénéficiaire' => 'ROLE_BENEFICIARY',
                    'Bénévole' => 'ROLE_VOLUNTEER',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                // Tableau attendu côté PHP
                'multiple' => true,
                // Checkboxes
                'expanded' => true,
            ])

            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                // Le user mappé sur le form
                $user = $event->getData();
                // L'objet form à récupérer pour travailler avec
                // (car il est inconnu dans cette fonction anonyme)
                $form = $event->getForm();

                // L'entité $user existe-t-il en BDD ?
                // Si $user a un identifiant, c'est qu'il existe en base
                $id = $user->getId();

                if ($id !== null) {
                    // Si non => add
                    $form->add('password', PasswordType::class, [
                        'label' => 'Mot de passe',
                        // Si donnée vide (null), remplacer par chaine vide
                        // @see https://symfony.com/doc/current/reference/forms/types/password.html#empty-data
                        'empty_data' => '',
                        'attr' => [
                            'placeholder' => 'Laissez vide si inchangé',
                        ],
                        // @see https://symfony.com/doc/current/reference/forms/types/email.html#mapped
                        // Ce champ ne sera présent que dans la requête et dans le form
                        // mais PAS dans l'entité !
                        'mapped' => false,

                    ]);
                } else {
                    // Si oui => edit
                    $form->add('password', PasswordType::class, [
                        'label' => 'Mot de passe',
                        // Si donnée vide (null), remplacer par chaine vide
                        // @see https://symfony.com/doc/current/reference/forms/types/password.html#empty-data
                        'empty_data' => '',
                    ]);
                }
            })
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
            ])

            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
            ])

            // ->add('picture', FileType::class, [
            //     'label' => 'Image',
            //     'mapped' => false,
            //     'required' => false,
            //     'constraints' => [
            //         new File([
            //             'maxSize' => '4096k',
            //             'mimeTypes' => [
            //                 'image/png',
            //                 'image/jpeg',
            //             ],
            //             'mimeTypesMessage' => 'Le fichier n\'est pas au bon format (formats acceptés: .png, .jpg, .jpeg)',
            //         ]),
            //     ]
            // ])

            ->add('phoneNumber', TextType::class, [
                'label' => 'Numéro de téléphone',
            ])
            ->add('bio', TextareaType::class, [
                'label' => 'Votre biographie',
            ])
            ->add('department', EntityType::class, [
                'class' => Department::class,
                'choice_label' => 'name',
                'label' => 'Département',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
