<?php

namespace picobeauf\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class JoueurType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('nom', 'text')
			->add('sexe', 'choice', array(
			'choices' => array(0 => 'Fille', 1 => 'Garçon')
		));
	}

	public function getName()
	{
		return 'joueur';
	}
}