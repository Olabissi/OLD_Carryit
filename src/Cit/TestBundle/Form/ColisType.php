<?php

namespace Cit\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ColisType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $arraycategories = $this->getCategoriesFr();

        $builder
            //->add('Categorie', 'entity', array(
                //'class' => 'CitTestBundle:Category'))
            ->add('Categorie','choice',array(
                'choices' => $arraycategories,
                'label' => 'Catégorie du colis',
                'empty_value' => 'Sélectionnez une catégorie',
                'attr' => array(
                    'class' => 'catego'),
                ))

            ->add('Poids_kg','integer',array(
                'label' => 'poids du colis (kg)',
                'attr' => array(
                    'class' => 'kilos'),
                ))

            ->add('date_livraison_souhaitee','date',array(
                'label' => 'date de livraison souhaitee',
                'attr' => array(
                    'class' => 'carrydate'),
                ))

            ->add('ville_depart', 'text', array(
                'label' => 'ville de départ',
                'attr' => array(
                    'class' => 'city'),
                ))

            ->add('ville_arrivee', 'text', array(
                'label' => 'ville d\'arrivée',
                'attr' => array(
                    'class' => 'city'),
                ))

            ->add('NomDestinataire','text',array(
                'label' => 'nom du destinataire (facultatif)',
                'required' => false))

            ->add('TelephoneDestinataire','text',array(
                'label' => 'téléphone du destinataire (facultatif)',
                'required' => false))

            ->add('complement_info','text',array(
                'label' => 'complement d\'information (facultatif)',
                'required' => false))
        ;
    }

    public function getName()
    {
        return 'cit_testbundle_colistype';
    }

    protected function getCategoriesFr()
    {
        $arraycategories = array(
            "vetements" => "vetements,accessoires",
            "sacs" => "sac a main,sacoche",
            "chaussures" => "chaussures",
            "bijoux" => "bijoux,montre",
            "art" => "art,antiquites",
            "pieces mecaniques" => "pièces,accessoires auto ou moto",
            "cosmetiques" => "cosmetiques,cremes,parfums",
            "boissons" => "vins et boissons",
            "nourriture" => "nourriture",
            "ceramiques" => "ceramiques,verres,vaisselle",
            "jouets" => "jouets,figurines",
            "livres" => "livres,BD,revues",
            "puericulture" => "bebe,puericulture",
            "electronique" => "equipements electroniques",
            "telephone" => "telephone mobile",
            "tickets" => "cartes,coupons,tickets",
            "outils" => "outils de bricolage",
            "medicaments" => "medicaments",
            "docs" => "documents"
            );

        return $arraycategories;
    }
}
