<?php

namespace App\Form;

use App\Entity\Card;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('attack')
            ->add('lifePoint')
            ->add('cost')
            ->add('category', EntityType::class, [
            	'class' => Category::class,
				'choice_label' => 'name',
			])
			->add('imgFileName', FileType::class, [
				'label' => 'imgFileName',
				// unmapped means that this field is not associated to any entity property
				'mapped' => false,
				// make it optional so you don't have to re-upload the PDF file
				// every time you edit the Product details
				'required' => false,
				// unmapped fields can't define their validation using annotations
				// in the associated entity, so you can use the PHP constraint classes
				'constraints' => [
					new File([
						'maxSize' => '2000k',
						'mimeTypes' => [
							'image/png',
							'image/jpg',
							'image/jpeg',
						],
						'mimeTypesMessage' => 'Please upload a valid IMG ',
					])
				],
			])
        	->add('add' , SubmitType::class , [
        		'label' => 'Create Card',
				'attr' => ['class' => 'btn-secondary']
			]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Card::class,
        ]);
    }
}
