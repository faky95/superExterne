<?php

namespace Orange\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BuType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('libelle', null, array('label' => 'Libelle :'))
            ->add('structureInDashboard', null, array('label' => "Structure(s) affichées à l'accueil"))
            ->add('niveauValidation', 'choice', array(
        		  'choices' => array(
			        		  		'1' => 'N + 1',
			        		  		'2' => 'N + 2',
			        		  		'3' => 'N + 3',
			        		  		'4' => 'N + 4'
            						),
            		'label' => 'Niveau de validation :',
            		'empty_value' => '--- Choix un niveau de validation ---'
       			 				)
                  )
            ->add('debutRelance', 'choice', array(
                                                'choices' =>array(
                                                                '-1'=>'1 jour avant',
                                                                '-2'=>'2 jours avant ',
                                                                '-3'=>'3 jours avant',
                                                                '-4'=>'4 jours avant',
                                                                '-5'=>'5 jours avant',
                                                                '-6'=>'6 jours avant',
                                                                '-7'=>'7 jours avant'
                                                ),
                                            'label'=>'Debut des Relances : ',
                                            'empty_value'=>'--- Choix debut relance avant la fin de l\'action --- '
                                            ))      
            ->add('frequence','choice', array(
                 'choices'=>array(
                                '1'=>'Tous les jours',
                                '2'=>'Tous les 2 jours',
                                '3'=>'Tous les 3 jours',
                                '4'=>'Tous les 4 jours',
                                '5'=>'Tous 5 jours',
                                '6'=>'Tous les 6 jours',
                                '7'=>'Tous les 7 jours'
                                 ),
                    'label'=>'Frequence des Relances : ',
                    'empty_value'=>'--- Choix frequence relance ---'             
                 )) 
            ->add('add', 'submit', array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-warning')))
            ->add('add_and_new_structure', 'submit', array('label' => 'Enregistrer et ajouter structure', 'attr' => array('class' => 'btn btn-warning')))
            ->add('cancel', 'button', array('label' => 'Annuler', 'attr' => array('class' => 'btn btn-warning cancel', 'data-dismiss'=>'modal')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Orange\MainBundle\Entity\Bu'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'orange_mainbundle_bu';
    }
}
