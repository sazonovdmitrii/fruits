<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

class OrderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('customerName', TextType::class, [
                'label' => 'Имя',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Пожалуйста, введите ваше имя']),
                    new Length(['min' => 2, 'max' => 100])
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Введите ваше имя'
                ]
            ])
            ->add('customerEmail', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Пожалуйста, введите email']),
                    new Email(['message' => 'Пожалуйста, введите корректный email'])
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'example@email.com'
                ]
            ])
            ->add('customerPhone', TelType::class, [
                'label' => 'Телефон',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Пожалуйста, введите номер телефона']),
                    new Length(['min' => 10, 'max' => 20])
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '+7 (999) 123-45-67'
                ]
            ])
            ->add('shippingAddress', TextareaType::class, [
                'label' => 'Адрес доставки',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Пожалуйста, введите адрес доставки']),
                    new Length(['min' => 10, 'max' => 500])
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Введите полный адрес доставки',
                    'rows' => 3
                ]
            ])
            ->add('notes', TextareaType::class, [
                'label' => 'Комментарий к заказу',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Дополнительная информация о заказе (необязательно)',
                    'rows' => 3
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}

