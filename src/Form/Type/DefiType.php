<?php

namespace picobeauf\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DefiType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('contenu', 'textarea')
			->add('niveau', 'choice', array(
				'choices' => array(1 => 1, 2 => 2, 3 => 3, 4 => 'Beauf ultime', 5 => 5)
			));
	}
	public function getContenu()
	{
		return 'defi';
	}
}