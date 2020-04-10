<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class ActionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('action', ChoiceType::class, [
            'choices'  => [
                'Buy' => 'buy',
                'Sale' => 'sale',
            ],
        ])
        ->add('quantity', IntegerType::class)
        ->add('price', IntegerType::class, ['label' => 'Unit Price'])
        ->add('submit', SubmitType::class, ['label' =>'Submit'])
        ;
    }
}