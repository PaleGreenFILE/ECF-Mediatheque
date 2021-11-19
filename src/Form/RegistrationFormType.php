<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom', TextType::class, [
                'attr' => [
                    'autofocus' => true
                ]
            ])
            ->add(
                'date_naissance', BirthdayType::class, [
                'format' => 'dd-MMMM-yyyy',
                'label' => 'Votre date de naissance'
                ]
            )
            ->add('adresse')
            ->add(
                'email', EmailType::class, [
                'label' => 'Votre Email',
                'attr' => [
                    'placeholder' => 'email@email.fr'
                ]
                ]
            )

            ->add(
                'agreeTerms', CheckboxType::class, [
                'label' => 'Accepter nos conditions d\'utilisations.',
                'mapped' => false,
                'constraints' => [
                    new IsTrue(
                        [
                        'message' => 'Vous devez accepter nos conditions.',
                        ]
                    ),
                ],
                ]
            )

            ->add(
                'plainPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class ,
                'first_options' => [
                    'label' => 'Mot de passe :'
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe :'
                 ],
                 'invalid_message' => "Les mots de passe doivent être identique",
                 'constraints' => [
                    new NotBlank(),
                    new Length(
                        [
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères.',
                        'max' => 50,
                        'maxMessage' => 'Votre mot de passe ne peut pas comporter plus de {{ limit }} caractères.'
                        ]
                    )
                 ]
                ]
            )

            // ->add('plainPassword', PasswordType::class, [
            //     // instead of being set onto the object directly,
            //     // this is read and encoded in the controller
            //     'label' => 'Votre mot de passe',
            //     'mapped' => false,
            //     'attr' => ['autocomplete' => 'new-password'],
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Veuillez entrer un mot de passe',
            //         ]),
            //         new Length([
            //             'min' => 6,
            //             'minMessage' => 'Votre mot de passe doit comporter au moins {{ limite }} caractères.',
            //             // max length allowed by Symfony for security reasons
            //             'max' => 4096,
            //         ]),
            //     ],
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
            'data_class' => User::class,
            ]
        );
    }
}
