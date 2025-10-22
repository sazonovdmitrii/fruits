<?php

namespace App\Form;

use App\Entity\OrderItem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderItemFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product', EntityType::class, [
                'class' => 'App\Entity\Product',
                'choice_label' => 'name',
                'label' => 'Товар',
                'placeholder' => 'Выберите товар',
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Количество',
                'attr' => ['min' => 1]
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Цена',
                'currency' => 'RUB',
                'scale' => 2,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderItem::class,
        ]);
    }
}




